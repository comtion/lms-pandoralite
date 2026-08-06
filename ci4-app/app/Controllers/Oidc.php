<?php

namespace App\Controllers;

use App\Libraries\AuthService;
use App\Models\UserModel;

class Oidc extends BaseController
{
    public function start()
    {
        $config = config('P0');
        if ($config->oidcDiscoveryUrl === '' || $config->oidcClientId === '') {
            return redirect()->to(site_url('login'))->with('auth_error', 'Enterprise SSO is not configured.');
        }
        $metadata = $this->metadata();
        $state = bin2hex(random_bytes(24));
        $verifier = rtrim(strtr(base64_encode(random_bytes(48)), '+/', '-_'), '=');
        $this->session->set(['oidc_state' => $state, 'oidc_verifier' => $verifier]);
        $query = http_build_query([
            'client_id' => $config->oidcClientId,
            'response_type' => 'code',
            'redirect_uri' => site_url('auth/oidc/callback'),
            'scope' => 'openid profile email',
            'state' => $state,
            'code_challenge' => rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '='),
            'code_challenge_method' => 'S256',
        ]);
        return redirect()->to($metadata['authorization_endpoint'] . '?' . $query);
    }

    public function callback()
    {
        $state = (string) $this->request->getGet('state');
        if ($state === '' || ! hash_equals((string) $this->session->get('oidc_state'), $state)) {
            return redirect()->to(site_url('login'))->with('auth_error', 'Invalid SSO state.');
        }
        $metadata = $this->metadata();
        $config = config('P0');
        $client = service('curlrequest');
        $tokenResponse = $client->post($metadata['token_endpoint'], ['form_params' => [
            'grant_type' => 'authorization_code',
            'code' => (string) $this->request->getGet('code'),
            'redirect_uri' => site_url('auth/oidc/callback'),
            'client_id' => $config->oidcClientId,
            'client_secret' => $config->oidcClientSecret,
            'code_verifier' => (string) $this->session->get('oidc_verifier'),
        ], 'http_errors' => false]);
        $tokens = json_decode($tokenResponse->getBody(), true);
        if ($tokenResponse->getStatusCode() !== 200 || empty($tokens['access_token'])) {
            return redirect()->to(site_url('login'))->with('auth_error', 'SSO token exchange failed.');
        }
        $userInfoResponse = $client->get($metadata['userinfo_endpoint'], [
            'headers' => ['Authorization' => 'Bearer ' . $tokens['access_token']],
            'http_errors' => false,
        ]);
        $claims = json_decode($userInfoResponse->getBody(), true);
        if ($userInfoResponse->getStatusCode() !== 200 || empty($claims['sub'])) {
            return redirect()->to(site_url('login'))->with('auth_error', 'SSO identity lookup failed.');
        }
        $username = trim((string) ($claims[$config->oidcUsernameClaim] ?? ''));
        $user = $username !== '' ? (new UserModel())->findActiveByUsername($username) : null;
        if (! $user) {
            return redirect()->to(site_url('login'))->with('auth_error', 'SSO account is not linked to an active LMS user.');
        }
        $now = date('Y-m-d H:i:s');
        db_connect()->table('lms_oidc_identity')->replace([
            'provider' => $config->oidcProvider, 'subject' => $claims['sub'], 'user_id' => $user['u_id'],
            'email' => $claims['email'] ?? null, 'last_login_at' => $now, 'created_at' => $now,
        ]);
        $this->session->remove(['oidc_state', 'oidc_verifier']);
        (new AuthService())->completeExternal($user);
        return redirect()->to(site_url('dashboard'));
    }

    private function metadata(): array
    {
        $response = service('curlrequest')->get(config('P0')->oidcDiscoveryUrl, ['http_errors' => false]);
        $data = json_decode($response->getBody(), true);
        foreach (['authorization_endpoint', 'token_endpoint', 'userinfo_endpoint'] as $key) {
            if ($response->getStatusCode() !== 200 || empty($data[$key]) || ! str_starts_with($data[$key], 'https://')) {
                throw new \RuntimeException('Invalid OIDC discovery metadata.');
            }
        }
        return $data;
    }
}
