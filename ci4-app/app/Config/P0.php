<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class P0 extends BaseConfig
{
    public bool $mfaRequiredForAdmins = false;
    public string $mfaIssuer = 'PandoraLite LMS';
    public string $oidcDiscoveryUrl = '';
    public string $oidcClientId = '';
    public string $oidcClientSecret = '';
    public string $oidcProvider = 'enterprise';
    public string $oidcUsernameClaim = 'preferred_username';
    public bool $oidcAutoLinkByEmail = false;
    public string $backupDirectory = WRITEPATH . 'backups';
    public int $backupRetentionDays = 30;
    public int $queueRetryBaseSeconds = 60;

    public function __construct()
    {
        parent::__construct();
        $this->mfaRequiredForAdmins = filter_var(env('p0.mfaRequiredForAdmins', false), FILTER_VALIDATE_BOOL);
        $this->mfaIssuer = (string) env('p0.mfaIssuer', $this->mfaIssuer);
        $this->oidcDiscoveryUrl = (string) env('p0.oidcDiscoveryUrl', '');
        $this->oidcClientId = (string) env('p0.oidcClientId', '');
        $this->oidcClientSecret = (string) env('p0.oidcClientSecret', '');
        $this->oidcProvider = (string) env('p0.oidcProvider', $this->oidcProvider);
        $this->oidcUsernameClaim = (string) env('p0.oidcUsernameClaim', $this->oidcUsernameClaim);
        $this->oidcAutoLinkByEmail = filter_var(env('p0.oidcAutoLinkByEmail', false), FILTER_VALIDATE_BOOL);
        $this->backupDirectory = (string) env('p0.backupDirectory', $this->backupDirectory);
        $this->backupRetentionDays = max(1, (int) env('p0.backupRetentionDays', 30));
        $this->queueRetryBaseSeconds = max(5, (int) env('p0.queueRetryBaseSeconds', 60));
    }
}
