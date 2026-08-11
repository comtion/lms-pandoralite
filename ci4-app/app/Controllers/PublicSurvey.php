<?php
namespace App\Controllers;
use App\Models\PermissionModel;
use App\Models\PublicSurveyModel;
class PublicSurvey extends BaseController
{
    public function index(){ $u=$this->session->get('user');$l=$this->session->get('lang')??'english';return view('public_survey/index',['surveys'=>(new PublicSurveyModel())->available($u,$l)]); }
    public function show(int $id){$u=$this->session->get('user');$l=$this->session->get('lang')??'english';$m=new PublicSurveyModel();$s=$m->survey($id,$l);if(!$s||!$m->canAccess($id,$u))throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();return view('public_survey/show',['survey'=>$s,'questions'=>$m->questions($id,$l)]);}
    public function submit(int $id)
    {
        $u=$this->session->get('user');
        try{
            (new PublicSurveyModel())->submit($id,$u,(array)$this->request->getPost('answers'));
        }catch(\InvalidArgumentException|\RuntimeException $e){
            return redirect()->back()->withInput()->with('module_error',$e->getMessage());
        }
        return redirect()->to(site_url('survey'))->with('module_notice','Survey submitted.');
    }
    public function report(int $id){$u=$this->session->get('user');if(!(new PermissionModel())->can($u,'survey/report_survey'))return redirect()->to(site_url('dashboard'));$m=new PublicSurveyModel();return view('public_survey/report',['survey'=>$m->survey($id,$this->session->get('lang')??'english'),'rows'=>$m->report($id,$u),'name'=>$this->session->get('name')]);}
    public function reportIndex(){ $u=$this->session->get('user');if(!(new PermissionModel())->can($u,'survey/report_survey'))return redirect()->to(site_url('dashboard'));return view('public_survey/report_index',['surveys'=>(new PublicSurveyModel())->allSurveys($this->session->get('lang')??'english'),'name'=>$this->session->get('name')]); }
    public function export(int $id){$u=$this->session->get('user');if(!(new PermissionModel())->can($u,'survey/report_survey','ru_print'))return redirect()->to(site_url('dashboard'));$rows=(new PublicSurveyModel())->report($id,$u);$out=fopen('php://temp','w+');fputcsv($out,['Employee','Name TH','Name EN','Company','Started','Finished','Status']);foreach($rows as $r)fputcsv($out,\App\Libraries\ExportSanitizer::row([$r['emp_c'],$r['fullname_th'],$r['fullname_en'],$r['com_code'],$r['svtc_firsttime'],$r['svtc_finishtime'],$r['svtc_status']]));rewind($out);$csv=stream_get_contents($out);fclose($out);return $this->response->setHeader('Content-Type','text/csv')->setHeader('Content-Disposition',"attachment; filename=survey_{$id}.csv")->setBody("\xEF\xBB\xBF".$csv);}
}
