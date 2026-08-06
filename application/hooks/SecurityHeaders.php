<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SecurityHeaders
{
	public function apply()
	{
		$CI =& get_instance();
		if (headers_sent()) {
			return;
		}

		$CI->output
			->set_header('X-Content-Type-Options: nosniff')
			->set_header('X-Frame-Options: SAMEORIGIN')
			->set_header('Referrer-Policy: strict-origin-when-cross-origin')
			->set_header('Permissions-Policy: camera=(), microphone=(), geolocation=()')
			->set_header('Cross-Origin-Opener-Policy: same-origin');

		$isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
			|| (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
			|| (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
		if ($isHttps) {
			$CI->output->set_header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
		}

		if ($CI->config->item('csrf_protection')) {
			$CI->output
				->set_header('X-CSRF-Name: '.$CI->security->get_csrf_token_name())
				->set_header('X-CSRF-Token: '.$CI->security->get_csrf_hash());
		}
	}
}
