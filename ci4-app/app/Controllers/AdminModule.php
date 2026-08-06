<?php
namespace App\Controllers;
use App\Models\AdminModuleModel;
use App\Models\PermissionModel;
class AdminModule extends BaseController
{
    public function show(string $key)
    {
        $model=new AdminModuleModel(); $config=$model->definition($key);
        if(!$config) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($key);
        $context=$this->authorize($config['path'],'ru_view');
        if(!is_array($context)) return $context;
        $id=(int)$this->request->getGet('edit');
        return view('admin_module/index',['config'=>$config,'key'=>$key,'rows'=>$model->rows($config,$context['user']),'editing'=>$id?$model->row($config,$id,$context['user']):null,'canAdd'=>$context['permissions']->can($context['user'],$config['path'],'ru_add'),'canEdit'=>$context['permissions']->can($context['user'],$config['path'],'ru_edit')]);
    }
    public function save(string $key)
    {
        $model=new AdminModuleModel(); $config=$model->definition($key);
        if(!$config) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($key);
        $id=(int)$this->request->getPost($config['pk']);
        $context=$this->authorize($config['path'],$id?'ru_edit':'ru_add');
        if(!is_array($context)) return $context;
        $input=$this->request->getPost();
        try{
            foreach($config['uploads']??[] as $field){
                $file=$this->request->getFile('upload__'.$field);
                if($file&&$file->isValid()&&!$file->hasMoved()){
                    $allowed=['image/jpeg','image/png','image/webp','application/pdf'];
                    if(!in_array($file->getMimeType(),$allowed,true)||$file->getSize()>20*1024*1024)throw new \RuntimeException("Invalid upload for {$field}.");
                    if(str_starts_with($file->getMimeType(),'image/')&&@getimagesize($file->getTempName())===false)throw new \RuntimeException("Invalid image content for {$field}.");
                    if($file->getMimeType()==='application/pdf'&&file_get_contents($file->getTempName(),false,null,0,5)!=='%PDF-')throw new \RuntimeException("Invalid PDF content for {$field}.");
                    $dir=ROOTPATH.'../uploads/admin/'.$key;if(!is_dir($dir)&&!mkdir($dir,0750,true)&&!is_dir($dir))throw new \RuntimeException('Cannot create upload directory.');
                    $name=$file->getRandomName();$file->move($dir,$name);$input[$field]=base_url('uploads/admin/'.$key.'/'.$name);
                }
            }
            $model->save($config,$id?:null,$input,$context['user']);
        }
        catch(\Throwable $e){return redirect()->back()->withInput()->with('module_error',$e->getMessage());}
        return redirect()->to(site_url($config['path']))->with('module_notice','Saved successfully.');
    }
    public function questionnaireDetails(int $id)
    {
        $context=$this->authorize('questionnaire/create','ru_view');if(!is_array($context))return $context;$model=new AdminModuleModel();
        $questionnaire=$model->row($model->definition('questionnaire'),$id,$context['user']);if(!$questionnaire)throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('admin_module/questionnaire_details',['questionnaire'=>$questionnaire,'rows'=>$model->questionnaireDetails($id)]);
    }
    public function saveQuestionnaireDetail(int $id)
    {
        $rowId=(int)$this->request->getPost('qnde_id');$context=$this->authorize('questionnaire/create',$rowId?'ru_edit':'ru_add');if(!is_array($context))return $context;
        $model=new AdminModuleModel();if(!$model->row($model->definition('questionnaire'),$id,$context['user']))throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $model->saveQuestionnaireDetail($id,$rowId?:null,$this->request->getPost());
        return redirect()->to(site_url('questionnaire/'.$id.'/questions'))->with('module_notice','Question saved.');
    }
    public function publicSurveyDetails(int $id)
    {
        $context=$this->authorize('survey/list_survey','ru_view');if(!is_array($context))return $context;$model=new AdminModuleModel();
        $survey=$model->row($model->definition('public-survey'),$id,$context['user']);if(!$survey)throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('admin_module/public_survey_details',['survey'=>$survey,'rows'=>$model->publicSurveyDetails($id)]);
    }
    public function savePublicSurveyDetail(int $id)
    {
        $rowId=(int)$this->request->getPost('svde_id');$context=$this->authorize('survey/list_survey',$rowId?'ru_edit':'ru_add');if(!is_array($context))return $context;
        $model=new AdminModuleModel();if(!$model->row($model->definition('public-survey'),$id,$context['user']))throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $model->savePublicSurveyDetail($id,$rowId?:null,$this->request->getPost());
        return redirect()->to(site_url('public-survey/'.$id.'/questions'))->with('module_notice','Survey question saved.');
    }
    private function authorize(string $path,string $field)
    {
        $user=$this->session->get('user'); $permissions=new PermissionModel();
        if(!is_array($user)||!$permissions->can($user,$path,$field)) return redirect()->to(site_url('dashboard'));
        return ['user'=>$user,'permissions'=>$permissions];
    }
}
