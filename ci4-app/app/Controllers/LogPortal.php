<?php
namespace App\Controllers;
use App\Models\PermissionModel;
class LogPortal extends BaseController
{
    private array $sources=[
        'activity'=>['path'=>'log/view','table'=>'lms_lg','pk'=>'id','order'=>'log_time'],
        'email'=>['path'=>'log/logEmail','table'=>'lms_lg_email','pk'=>'lgm_id','order'=>'lgm_date'],
        'imports'=>['path'=>'log/logImportUsers','table'=>'lms_lg_import','pk'=>'lgi_id','order'=>'lgi_datetime'],
        'audit'=>['path'=>'auditlog/view','table'=>'lms_audit_logs','pk'=>'audit_id','order'=>'audit_created_at'],
    ];
    public function index(string $type)
    {
        $source=$this->source($type,'ru_view');if(!is_array($source))return $source;
        $builder=db_connect()->table($source['table'])->orderBy($source['order'],'DESC')->limit(500);
        $q=trim((string)$this->request->getGet('q'));
        if($q!==''){ $fields=db_connect()->getFieldNames($source['table']);$builder->groupStart();foreach(array_slice($fields,0,12) as $i=>$field)$i?$builder->orLike($field,$q):$builder->like($field,$q);$builder->groupEnd(); }
        return view('logs/index',['type'=>$type,'source'=>$source,'rows'=>$builder->get()->getResultArray(),'q'=>$q]);
    }
    public function detail(string $type,int $id)
    {
        $source=$this->source($type,'ru_view');if(!is_array($source))return $source;
        $row=db_connect()->table($source['table'])->where($source['pk'],$id)->get()->getRowArray();
        if(!$row)throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return $this->response->setJSON($row);
    }
    public function export(string $type)
    {
        $source=$this->source($type,'ru_print');if(!is_array($source))return $source;
        $rows=db_connect()->table($source['table'])->orderBy($source['order'],'DESC')->limit(10000)->get()->getResultArray();
        $fields=array_keys($rows[0]??[]);$out=fopen('php://temp','w+');fputcsv($out,$fields);foreach($rows as $row)fputcsv($out,\App\Libraries\ExportSanitizer::row(array_map(fn($v)=>is_scalar($v)?$v:json_encode($v),$row)));rewind($out);$csv=stream_get_contents($out);fclose($out);
        return $this->response->setHeader('Content-Type','text/csv')->setHeader('Content-Disposition',"attachment; filename={$type}_log.csv")->setBody("\xEF\xBB\xBF".$csv);
    }
    private function source(string $type,string $permission)
    {
        if(!isset($this->sources[$type]))throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $source=$this->sources[$type];$user=$this->session->get('user');
        if(!(new PermissionModel())->can($user,$source['path'],$permission))return redirect()->to(site_url('dashboard'));
        if(!db_connect()->tableExists($source['table']))throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($source['table']);
        return $source;
    }
}
