<?php

namespace App\Controllers;

use App\Libraries\TotpService;

class Security extends BaseController
{
    public function mfa()
    {
        $user = $this->session->get('user');
        $db = db_connect();
        $existing = $db->tableExists('lms_user_mfa')
            ? $db->table('lms_user_mfa')->where('user_id', $user['u_id'])->get()->getRowArray()
            : null;
        if (! $existing || empty($existing['enabled_at'])) {
            $secret = (new TotpService())->generateSecret();
            $this->session->set('mfa_setup_secret', $secret);
        } else {
            $secret = null;
        }
        return view('security/mfa', [
            'enabled' => ! empty($existing['enabled_at']),
            'uri' => $secret ? (new TotpService())->provisioningUri($secret, (string) $user['useri'], config('P0')->mfaIssuer) : null,
            'secret' => $secret,
            'message' => $this->session->getFlashdata('message'),
            'error' => $this->session->getFlashdata('error'),
        ]);
    }

    public function enableMfa()
    {
        $secret = (string) $this->session->get('mfa_setup_secret');
        $step = $secret ? (new TotpService())->verify($secret, trim((string) $this->request->getPost('code'))) : null;
        if ($step === null) {
            return redirect()->back()->with('error', 'รหัสยืนยันไม่ถูกต้อง');
        }
        $user = $this->session->get('user');
        $now = date('Y-m-d H:i:s');
        db_connect()->table('lms_user_mfa')->replace([
            'user_id' => $user['u_id'],
            'secret' => base64_encode(service('encrypter')->encrypt($secret)),
            'enabled_at' => $now,
            'recovery_codes' => null,
            'last_used_step' => $step,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->session->remove('mfa_setup_secret');
        return redirect()->to(site_url('security/mfa'))->with('message', 'เปิดใช้ MFA แล้ว');
    }

    public function disableMfa()
    {
        $user = $this->session->get('user');
        if (! model(\App\Models\UserModel::class)->verifyMfaCode((int) $user['u_id'], trim((string) $this->request->getPost('code')))) {
            return redirect()->back()->with('error', 'รหัสยืนยันไม่ถูกต้อง');
        }
        db_connect()->table('lms_user_mfa')->where('user_id', $user['u_id'])->delete();
        return redirect()->to(site_url('security/mfa'))->with('message', 'ปิด MFA แล้ว');
    }
}
