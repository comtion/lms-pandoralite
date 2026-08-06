<?php
namespace App\Controllers;
use App\Libraries\QueueService;
use App\Models\PermissionModel;
class Operations extends BaseController
{
    public function index()
    {
        if (! (new PermissionModel())->can($this->session->get('user'), 'manage/userdata')) {
            return redirect()->to(site_url('dashboard'));
        }
        $db=db_connect();
        return view('operations/index',[
            'jobs'=>$db->table('lms_jobs')->orderBy('id','DESC')->limit(100)->get()->getResultArray(),
            'backups'=>$db->table('lms_backup_runs')->orderBy('backup_id','DESC')->limit(50)->get()->getResultArray(),
        ]);
    }
    public function retryJob(int $id)
    {
        if (! (new PermissionModel())->can($this->session->get('user'), 'manage/userdata', 'ru_edit')) {
            return redirect()->to(site_url('dashboard'));
        }
        (new QueueService())->retry($id);
        return redirect()->to(site_url('operations'))->with('message','Job queued for retry.');
    }
}
