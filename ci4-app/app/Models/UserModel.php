<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'lms_usp';
    protected $primaryKey = 'u_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'last_act',
        'st_on',
        'login',
        'u_lockdate',
        'firsttime',
        'expiredate',
        'userp',
        'lang_last',
    ];

    public function findActiveByCredentials(string $username, string $passwordHash): ?array
    {
        $row = $this->db->table('lms_usp')
            ->select('lms_usp.*, lms_emp.*, lms_company.*, lms_usp_gp.*')
            ->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id')
            ->join('lms_company', 'lms_emp.com_id = lms_company.com_id')
            ->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id')
            ->where('lms_usp.useri', $username)
            ->where('lms_usp.userp', $passwordHash)
            ->where('lms_emp.status', '1')
            ->where('lms_emp.emp_isDelete', '0')
            ->where('lms_usp.u_isDelete', '0')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function isLocked(string $username): bool
    {
        return $this->db->table('lms_usp')
            ->where('useri', $username)
            ->where('login', '0')
            ->where('u_isDelete', '0')
            ->countAllResults() > 0;
    }

    public function markOnline(string $username): void
    {
        $this->db->table('lms_usp')
            ->set('last_act', 'NOW()', false)
            ->set('st_on', 'online')
            ->where('useri', $username)
            ->update();
    }

    public function markOffline(string $username): void
    {
        $this->db->table('lms_usp')
            ->set('last_act', 'NOW()', false)
            ->set('st_on', 'offline')
            ->where('useri', $username)
            ->update();
    }
}
