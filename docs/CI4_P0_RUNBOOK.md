# CI4 P0 production runbook

## One-time setup

Run `php spark migrate`. Configure the encryption key and P0 values through environment variables:

```ini
encryption.key = base64:<random 32-byte key>
p0.mfaIssuer = PandoraLite LMS
p0.mfaRequiredForAdmins = true
p0.oidcDiscoveryUrl = https://identity.example/.well-known/openid-configuration
p0.oidcClientId = <client id>
p0.oidcClientSecret = <secret>
p0.oidcUsernameClaim = preferred_username
p0.backupDirectory = /var/www/html/lms-pandoralite/ci4-app/writable/backups
p0.backupRetentionDays = 30
p0.queueRetryBaseSeconds = 60
```

Register the exact OIDC redirect URI `/auth/oidc/callback` with the identity provider. SSO only links to an existing active LMS username; it never provisions privileged users.

## Scheduler

Run these from one scheduler instance only:

```cron
* * * * * cd /var/www/html/lms-pandoralite/ci4-app && php spark queue:work default 100
15 2 * * * cd /var/www/html/lms-pandoralite/ci4-app && php spark backup:run
30 3 * * 0 cd /var/www/html/lms-pandoralite/ci4-app && php spark backup:restore-rehearsal "$(ls -1t writable/backups/database_*.sql | head -1)" --confirm
```

Use `/operations` for failed jobs and backup verification history. Use `/migration/status` or `/migration/inventory` for migration coverage.

## Release gate

Before deployment run:

```bash
composer validate --strict
vendor/bin/phpunit
php spark routes
php spark migration:smoke admin_verztec
php spark migration:parity
php spark migrate:status
php spark release:gate --production
```

`release:gate --production` must be green. It deliberately fails when the runtime is not in production, the base URL is not HTTPS, cookies are not secure, administrator MFA is disabled, OIDC credentials are absent, a P0 table is missing, route parity is incomplete, or the upload execution guard is missing.

Before turning on mandatory MFA, enroll and verify at least two break-glass administrators. Enable OIDC only after discovery, callback, PKCE/state validation, and account-link tests pass in the target environment.

## Cutover and rollback

1. Freeze CI3 writes and record the start time.
2. Run and verify a database backup plus uploads backup; retain their checksums outside the deployment directory.
3. Run migrations, warm the CI4 application, and execute the complete release gate above.
4. Complete acceptance for login/SSO/MFA, course enrollment and completion, survey/quiz submission, certificate generation, reports, uploads, queue processing, and administrator company scoping.
5. Switch the reverse proxy/document root to `ci4-app/public`, then monitor `/operations`, application logs, queue failures, HTTP 5xx, and database latency.

Rollback if any acceptance-critical flow fails or the agreed error/latency threshold is exceeded: stop CI4 writes, restore the pre-cutover database/uploads only when CI4 has written incompatible data, return traffic to CI3, resume the prior scheduler, and record the incident window. Never run CI3 and CI4 schedulers concurrently.
