<?php
namespace App\Models;
use CodeIgniter\Model;
class PublicSurveyModel extends Model
{
    public function available(array $user,string $lang): array
    {
        $now=date('Y-m-d H:i:s');
        $rows=$this->db->table('lms_sv')->where(['sv_public'=>'1','sv_approve'=>'1','sv_status'=>'1','sv_isDelete'=>'0'])
            ->groupStart()->groupStart()->where("CAST(sv_open AS CHAR) = '0000-00-00 00:00:00'",null,false)->where("CAST(sv_end AS CHAR) = '0000-00-00 00:00:00'",null,false)->groupEnd()
            ->orGroupStart()->where('sv_open <=',$now)->where('sv_end >=',$now)->groupEnd()->groupEnd()->orderBy('sv_id','DESC')->get()->getResultArray();
        $result=[];
        foreach($rows as $row){
            $restricted=$this->db->table('lms_sv_pm')->where('sv_id',$row['sv_id'])->countAllResults()>0;
            if($restricted && $this->db->table('lms_sv_pm')->where(['sv_id'=>$row['sv_id'],'posi_id'=>$user['posi_id']??0])->countAllResults()===0) continue;
            $row['title']=$this->localized($row,$lang,'sv_title');
            $row['response']=$this->db->table('lms_sv_tc')->where(['sv_id'=>$row['sv_id'],'emp_id'=>$user['emp_id'],'svtc_isDelete'=>'0'])->get()->getRowArray();
            $result[]=$row;
        }
        return $result;
    }
    public function survey(int $id,string $lang): ?array
    {
        $row=$this->db->table('lms_sv')->where(['sv_id'=>$id,'sv_status'=>'1','sv_isDelete'=>'0'])->get()->getRowArray();
        if(!$row)return null;
        $row['title']=$this->localized($row,$lang,'sv_title'); $row['explanation']=$this->localized($row,$lang,'sv_explanation');
        return $row;
    }
    public function canAccess(int $id,array $user): bool
    {
        if($this->db->table('lms_sv')->where(['sv_id'=>$id,'sv_public'=>'1','sv_approve'=>'1','sv_status'=>'1','sv_isDelete'=>'0'])->countAllResults()!==1)return false;
        $restricted=$this->db->table('lms_sv_pm')->where('sv_id',$id)->countAllResults()>0;
        return !$restricted||$this->db->table('lms_sv_pm')->where(['sv_id'=>$id,'posi_id'=>$user['posi_id']??0])->countAllResults()===1;
    }
    public function questions(int $id,string $lang): array
    {
        $rows=$this->db->table('lms_svde')->where(['sv_id'=>$id,'svde_status'=>'1','svde_isDelete'=>'0'])->orderBy('svde_id','ASC')->get()->getResultArray();
        foreach($rows as &$row){
            $row['question']=$this->localized($row,$lang,'svde_name') ?: $this->localized($row,$lang,'svde_info');
            $mul=$this->db->table('lms_svde_mul')->where(['svde_id'=>$row['svde_id'],'mul_status'=>'1','mul_isDelete'=>'0'])->get()->getRowArray();
            $row['choices']=[];
            for($i=1;$i<=15;$i++){ $value=$mul[$lang==='thai'?"mul_c{$i}_th":($lang==='japan'?"mul_c{$i}_jp":"mul_c{$i}_eng")]??''; if(trim($value)!=='')$row['choices'][]=$value; }
        }
        return $rows;
    }
    public function submit(int $id,array $user,array $answers): void
    {
        if(!$this->canAccess($id,$user))throw new \RuntimeException('Survey is not available for this user.');
        $questions=$this->questions($id,'english');
        if($questions===[])throw new \RuntimeException('Survey has no active questions.');
        $byId=[];foreach($questions as $question)$byId[(int)$question['svde_id']]=$question;
        foreach($byId as $questionId=>$question){
            $answer=trim((string)($answers[$questionId]??''));
            if($answer==='')throw new \InvalidArgumentException('Every survey question requires an answer.');
            if($question['choices']!==[]&&!in_array($answer,$question['choices'],true))throw new \InvalidArgumentException('An answer is not one of the allowed choices.');
        }
        $this->db->transBegin();
        try{
            $main=$this->db->query('SELECT * FROM lms_sv_tc WHERE sv_id=? AND emp_id=? AND svtc_isDelete=0 FOR UPDATE',[$id,$user['emp_id']])->getRowArray();
            if($main&&(string)$main['svtc_status']==='1')throw new \RuntimeException('This survey has already been completed.');
            $now=date('Y-m-d H:i:s');
            if(!$main){$this->db->table('lms_sv_tc')->insert(['sv_id'=>$id,'emp_id'=>$user['emp_id'],'svtc_firsttime'=>$now,'svtc_status'=>'0','svtc_isDelete'=>'0','svtc_createby'=>$user['u_id'],'svtc_createdate'=>$now]);}
            foreach($answers as $qid=>$answer){
                $qid=(int)$qid;if(!isset($byId[$qid]))continue;$answer=mb_substr(trim((string)$answer),0,4000);
                $existing=$this->db->table('lms_svde_tc')->where(['sv_id'=>$id,'svde_id'=>$qid,'emp_id'=>$user['emp_id'],'tc_isDelete'=>'0'])->get()->getRowArray();
                $data=['sv_id'=>$id,'svde_id'=>$qid,'emp_id'=>$user['emp_id'],'tc_answer'=>$answer,'tc_finish'=>'1','tc_save'=>'1','tc_modifieddate'=>$now];
                $existing?$this->db->table('lms_svde_tc')->where('tc_id',$existing['tc_id'])->update($data):$this->db->table('lms_svde_tc')->insert($data);
            }
            $this->db->table('lms_sv_tc')->where(['sv_id'=>$id,'emp_id'=>$user['emp_id'],'svtc_isDelete'=>'0'])->update(['svtc_status'=>'1','svtc_finishtime'=>$now,'svtc_modifiedby'=>$user['u_id'],'svtc_modifieddate'=>$now]);
            $this->db->transCommit();
        }catch(\Throwable $e){$this->db->transRollback();throw $e;}
    }
    public function report(int $id,?array $user=null): array
    {
        $builder=$this->db->table('lms_sv_tc')->select('lms_sv_tc.*,lms_emp.emp_c,lms_emp.fullname_th,lms_emp.fullname_en,lms_company.com_code')
            ->join('lms_emp','lms_emp.emp_id=lms_sv_tc.emp_id','left')->join('lms_company','lms_company.com_id=lms_emp.com_id','left')
            ->where(['lms_sv_tc.sv_id'=>$id,'lms_sv_tc.svtc_isDelete'=>'0'])->orderBy('svtc_id','DESC');
        if($user!==null&&(string)($user['ug_viewdata']??'')!=='1'&&(string)($user['u_id']??'')!=='1')$builder->where('lms_emp.com_id',$user['com_id']??0);
        return $builder->get()->getResultArray();
    }
    public function allSurveys(string $lang): array
    {
        $rows=$this->db->table('lms_sv')->where('sv_isDelete','0')->orderBy('sv_id','DESC')->get()->getResultArray();
        foreach($rows as &$row){$row['title']=$this->localized($row,$lang,'sv_title');$row['responses']=$this->db->table('lms_sv_tc')->where(['sv_id'=>$row['sv_id'],'svtc_isDelete'=>'0'])->countAllResults();}
        return $rows;
    }
    private function localized(array $row,string $lang,string $prefix): string
    {
        foreach($lang==='thai'?['th','eng','jp']:($lang==='japan'?['jp','eng','th']:['eng','th','jp']) as $suffix){$v=trim(strip_tags((string)($row[$prefix.'_'.$suffix]??'')));if($v!=='')return $v;}return '';
    }
}
