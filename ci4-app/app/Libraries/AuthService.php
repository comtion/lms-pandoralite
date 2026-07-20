<?php

namespace App\Libraries;

use App\Models\UserModel;
use CodeIgniter\Session\Session;

class AuthService
{
    private UserModel $users;

    private Session $session;

    public function __construct(?UserModel $users = null, ?Session $session = null)
    {
        $this->users = $users ?? new UserModel();
        $this->session = $session ?? service('session');
    }

    public function attempt(string $username, string $password): bool
    {
        $username = trim($username);

        if ($username === '' || $password === '') {
            return false;
        }

        if ($this->users->isLocked($username)) {
            $this->session->setFlashdata('auth_error', 'Account is locked.');
            return false;
        }

        $user = $this->users->findActiveByCredentials($username, hash('sha256', $password));

        if ($user === null || (string) ($user['login'] ?? '1') !== '1') {
            return false;
        }

        $langLast = ! empty($user['lang_last']) ? $user['lang_last'] : 'english';
        $currentLang = $this->session->get('lang') ?? 'english';

        if ((string) ($user['emp_firsttime'] ?? '0') === '1' || (string) ($user['firsttime'] ?? '0') === '1') {
            $langLast = $currentLang;
        }

        $name = ($user['lang'] ?? '') === 'thai'
            ? ($user['fullname_th'] ?? $username)
            : ($user['fullname_en'] ?? $username);

        $this->session->regenerate(true);
        $this->session->set([
            'user' => $user,
            'name' => $name,
            'lang' => $langLast,
            'username_firsttime' => '',
            'firsttime' => false,
            'passexpire' => false,
        ]);

        $this->users->markOnline($username);

        return true;
    }

    public function logout(): void
    {
        $user = $this->user();

        if (isset($user['useri'])) {
            $this->users->markOffline((string) $user['useri']);
        }

        $this->session->remove([
            'user',
            'name',
            'username_firsttime',
            'firsttime',
            'passexpire',
        ]);
        $this->session->destroy();
    }

    public function check(): bool
    {
        return is_array($this->session->get('user'));
    }

    public function user(): ?array
    {
        $user = $this->session->get('user');

        return is_array($user) ? $user : null;
    }
}
