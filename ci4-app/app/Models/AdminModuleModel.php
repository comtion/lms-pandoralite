<?php
namespace App\Models;
use CodeIgniter\Model;
class AdminModuleModel extends Model
{
    private array $map = [
        'ect'=>['path'=>'setting/ManageECT','table'=>'lms_about','pk'=>'da_id','fields'=>['da_title_th','da_title_en','da_company_th','da_company_en','da_address_th','da_address_en','da_contact_main','da_contact_fax','da_website','da_email_a','da_email_b','da_copyright']],
        'banner'=>['path'=>'setting/ManageBanner','table'=>'lms_ban','pk'=>'id','fields'=>['banner','hidden','emp_c','com_id'],'uploads'=>['banner']],
        'banner-course'=>['path'=>'setting/ManageBannerCourse','table'=>'lms_ban_cos','pk'=>'bc_id','fields'=>['bc_name_th','bc_name_eng','bc_type','bc_status','bc_image'],'uploads'=>['bc_image']],
        'mail'=>['path'=>'setting/setting_email','table'=>'lms_setting_mail','pk'=>'sm_id','fields'=>['sm_host','sm_port','sm_sender','sm_emailsender','sm_secure']],
        'mail-template'=>['path'=>'setting/format_email','table'=>'lms_sendmail_form','pk'=>'smf_id','fields'=>['smf_type','smf_subject_th','smf_subject_en','smf_message_th','smf_message_en','smf_show']],
        'faq'=>['path'=>'setting/ManageFAQ','table'=>'lms_faq','pk'=>'id','fields'=>['title','lang','hidden','emp_c']],
        'menu'=>['path'=>'setting/ManageMenu','table'=>'lms_menu','pk'=>'mu_id','fields'=>['mu_name_th','mu_name_en','mu_name_jp','mu_path','mu_parent','mu_num','mu_icon','mu_status']],
        'group'=>['path'=>'manage/groupuserdata','table'=>'lms_usp_gp','pk'=>'ug_id','fields'=>['ug_name_th','ug_name_en','ug_code','ug_for','Is_admin','ug_approve','ug_viewdata','ug_status']],
        'course-type'=>['path'=>'coursetype/loadCourseType','table'=>'lms_typecos','pk'=>'tc_id','fields'=>['tc_name_th','tc_name_en','tc_lesson','tc_pretest','tc_status']],
        'manual'=>['path'=>'setting/usermanual','table'=>'lms_about','pk'=>'da_id','fields'=>['da_manual_sa_th','da_manual_sa_eng','da_manual_gr_th','da_manual_gr_eng','da_manual_is_th','da_manual_is_eng','da_manual_ln_th','da_manual_ln_eng'],'uploads'=>['da_manual_sa_th','da_manual_sa_eng','da_manual_gr_th','da_manual_gr_eng','da_manual_is_th','da_manual_is_eng','da_manual_ln_th','da_manual_ln_eng']],
        'qrcode'=>['path'=>'qrcode/create','table'=>'lms_qrcode','pk'=>'qr_id','fields'=>['com_id','qr_name','qr_type','qr_path','qr_detail','qr_status'],'uploads'=>['qr_path']],
        'questionnaire'=>['path'=>'questionnaire/create','table'=>'lms_questionnaire','pk'=>'qn_id','fields'=>['com_id','qn_lang','qn_title_th','qn_explanation_th','qn_title_eng','qn_explanation_eng','qn_title_jp','qn_explanation_jp','qn_suggestion_status','qn_filename','qn_status']],
        'public-survey'=>['path'=>'survey/list_survey','table'=>'lms_sv','pk'=>'sv_id','fields'=>['com_id','sv_type','sv_lang','sv_title_th','sv_explanation_th','sv_detail_th','sv_title_eng','sv_explanation_eng','sv_detail_eng','sv_title_jp','sv_explanation_jp','sv_detail_jp','sv_suggestion_status','sv_open','sv_end','sv_public','sv_approve','sv_status']],
    ];
    public function definition(string $key): ?array
    {
        $config=$this->map[$key]??null;
        if(!$config) return null;
        $actual=array_flip($this->db->getFieldNames($config['table']));
        $config['fields']=array_values(array_filter($config['fields'],fn($f)=>isset($actual[$f])));
        $config['uploads']=array_values(array_filter($config['uploads']??[],fn($f)=>isset($actual[$f])));
        return $config;
    }
    public function rows(array $config,array $user): array
    {
        $builder=$this->db->table($config['table'])->orderBy($config['pk'],'DESC')->limit(250);
        if(in_array('com_id',$this->db->getFieldNames($config['table']),true)&&!$this->canViewAll($user))$builder->where('com_id',$user['com_id']??0);
        return $builder->get()->getResultArray();
    }
    public function row(array $config,int $id,array $user): ?array
    {
        $builder=$this->db->table($config['table'])->where($config['pk'],$id);
        if(in_array('com_id',$this->db->getFieldNames($config['table']),true)&&!$this->canViewAll($user))$builder->where('com_id',$user['com_id']??0);
        $row=$builder->get()->getRowArray();
        return $row?:null;
    }
    public function save(array $config,?int $id,array $input,array $user): int
    {
        $data=[];
        foreach($config['fields'] as $field) if(array_key_exists($field,$input)) $data[$field]=is_string($input[$field])?trim($input[$field]):$input[$field];
        if($data===[]) throw new \InvalidArgumentException('No supported fields supplied.');
        if(in_array('com_id',$this->db->getFieldNames($config['table']),true)&&!$this->canViewAll($user))$data['com_id']=$user['com_id']??0;
        if($id){
            $builder=$this->db->table($config['table'])->where($config['pk'],$id);
            if(in_array('com_id',$this->db->getFieldNames($config['table']),true)&&!$this->canViewAll($user))$builder->where('com_id',$user['com_id']??0);
            $builder->update($data);
            if($this->db->affectedRows()===0&&!$this->row($config,$id,$user))throw new \RuntimeException('Record is outside your company scope.');
            return $id;
        }
        $actual=array_flip($this->db->getFieldNames($config['table']));$user=service('session')->get('user')??[];$now=date('Y-m-d H:i:s');
        foreach(array_keys($actual) as $field){
            if(array_key_exists($field,$data))continue;
            if($field==='time_created'||str_ends_with($field,'_createdate'))$data[$field]=$now;
            elseif(str_ends_with($field,'_createby'))$data[$field]=$user['u_id']??0;
            elseif(str_ends_with($field,'_isDelete'))$data[$field]='0';
        }
        if(isset($actual['emp_c'])&&!isset($data['emp_c']))$data['emp_c']=$user['emp_c']??'';
        if(isset($actual['com_id'])&&!isset($data['com_id']))$data['com_id']=$user['com_id']??0;
        $this->db->table($config['table'])->insert($data);
        return (int)$this->db->insertID();
    }
    public function questionnaireDetails(int $id): array{return $this->db->table('lms_questionnaire_de')->where(['qn_id'=>$id,'qnde_isDelete'=>'0'])->orderBy('qnde_id')->get()->getResultArray();}
    public function saveQuestionnaireDetail(int $questionnaireId,?int $id,array $input): int
    {
        $fields=['qnde_heading_th','qnde_detail_th','qnde_heading_eng','qnde_detail_eng','qnde_heading_jp','qnde_detail_jp','qnde_status'];$data=['qn_id'=>$questionnaireId,'qnde_modifieddate'=>date('Y-m-d H:i:s')];
        foreach($fields as $field)$data[$field]=trim((string)($input[$field]??''));
        if($id){$this->db->table('lms_questionnaire_de')->where(['qnde_id'=>$id,'qn_id'=>$questionnaireId])->update($data);return $id;}
        $data+=['qnde_isDelete'=>'0','qnde_createdate'=>date('Y-m-d H:i:s')];$this->db->table('lms_questionnaire_de')->insert($data);return (int)$this->db->insertID();
    }
    public function publicSurveyDetails(int $id): array
    {
        $rows=$this->db->table('lms_svde')->where(['sv_id'=>$id,'svde_isDelete'=>'0'])->orderBy('svde_id')->get()->getResultArray();
        foreach($rows as &$row)$row['choices']=$this->db->table('lms_svde_mul')->where(['svde_id'=>$row['svde_id'],'mul_isDelete'=>'0'])->get()->getRowArray()??[];
        return $rows;
    }
    public function savePublicSurveyDetail(int $surveyId,?int $id,array $input): int
    {
        $user=service('session')->get('user')??[];$now=date('Y-m-d H:i:s');$fields=['svde_type','svde_header_th','svde_header_eng','svde_header_jp','svde_name_th','svde_info_th','svde_name_eng','svde_info_eng','svde_name_jp','svde_info_jp','svde_isMultichoice','svde_isSpecify','svde_specify_name_th','svde_specify_name_eng','svde_specify_name_jp','svde_status'];
        $data=['sv_id'=>$surveyId,'svde_modifiedby'=>$user['u_id']??0,'svde_modifieddate'=>$now];foreach($fields as $f)$data[$f]=trim((string)($input[$f]??''));
        if($id)$this->db->table('lms_svde')->where(['svde_id'=>$id,'sv_id'=>$surveyId])->update($data);else{$data+=['svde_isDelete'=>'0','svde_createby'=>$user['u_id']??0,'svde_createdate'=>$now];$this->db->table('lms_svde')->insert($data);$id=(int)$this->db->insertID();}
        $choices=['svde_id'=>$id,'mul_isDelete'=>'0','mul_status'=>'1','mul_modifiedby'=>$user['u_id']??0,'mul_modifieddate'=>$now];foreach(['th','eng','jp'] as $lang)for($i=1;$i<=15;$i++)$choices["mul_c{$i}_{$lang}"]=trim((string)($input["mul_c{$i}_{$lang}"]??''));
        $existing=$this->db->table('lms_svde_mul')->where(['svde_id'=>$id,'mul_isDelete'=>'0'])->get()->getRowArray();
        if($existing)$this->db->table('lms_svde_mul')->where('mul_id',$existing['mul_id'])->update($choices);else{$choices+=['mul_createby'=>$user['u_id']??0,'mul_createdate'=>$now];$this->db->table('lms_svde_mul')->insert($choices);}
        return $id;
    }
    private function canViewAll(array $user): bool{return (string)($user['ug_viewdata']??'')==='1'||(string)($user['u_id']??'')==='1';}
}
