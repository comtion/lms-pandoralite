# CI4 P1 status

P1 is implemented for the localhost acceptance scope.

## Delivered

- Course lifecycle workflow with guarded state transitions, readiness checklist, version increments, rejection reasons, row locking, transaction history, company scope, and owner notifications.
- Enrollment workflow with open/approval/assigned/closed policies, enrollment windows, capacity enforcement, waitlisting, approval/rejection, row locking, current `lms_cos_enroll` activation, and learner notifications.
- Notification center with unread state, mark-one/mark-all actions, digest frequency, email/in-app preferences, and quiet hours.
- Accessible administrator workflow center at `/workflows` for lifecycle transitions, enrollment-policy editing, and enrollment decisions.
- Public load-balancer readiness endpoint at `/health/ready` and authenticated administrative details at `/health/details`.
- Tamper-evident audit-chain verification through `php spark audit:verify`.
- Critical-query performance budgets through `php spark performance:smoke admin_verztec`.
- Unified P1 acceptance gate through `php spark p1:gate admin_verztec`.
- Accessibility baseline on login and notification flows: landmarks, skip links, focus indicators, programmatic required state, status/alert semantics, and escaped retained input.

## Local acceptance criteria

Run from `ci4-app`:

```bash
composer validate --strict
vendor/bin/phpunit
php spark release:gate
php spark migration:parity
php spark migration:smoke admin_verztec
php spark audit:verify
php spark performance:smoke admin_verztec
php spark p1:gate admin_verztec
```

The P1 gate requires:

- all workflow/notification/audit tables to exist;
- explicit routes for health, lifecycle, enrollment, and notification preferences;
- the complete audit hash chain to verify;
- the median of three runs for dashboard, learner-report, and SCORM-report read paths to each finish within 1,500 ms on the local reference stack.

## API/workflow routes

- `GET /health/ready`
- `GET /health/details`
- `GET /notifications`
- `GET /workflows`
- `POST /notifications/{id}/read`
- `POST /notifications/read-all`
- `POST /notifications/preferences`
- `GET /managecourse/courses_all/{courseId}/lifecycle`
- `POST /managecourse/courses_all/{courseId}/lifecycle`
- `POST /enrollment/courses/{courseId}/request`
- `GET /enrollment/requests`
- `POST /enrollment/requests/{requestId}/decision`
- `GET|POST /enrollment/courses/{courseId}/policy`

Every mutation is POST-only, CSRF protected, authenticated, permission checked where administrative, and company scoped.

## Browser acceptance

- `/login` renders one skip link, two programmatically required credential fields, one CSRF field, visible keyboard focus, and no console error.
- `/health/ready` returns HTTP 200 with only readiness state and UTC timestamp. The automated in-app browser blocks direct JSON rendering, so endpoint status is verified by HTTP and the browser acceptance focuses on rendered UI.

## Performance change

Dashboard device analytics now use one conditional-aggregation query instead of three full scans. Company and global course analytics no longer use per-company/per-course N+1 queries. On the current localhost dataset the P1 gate measured the dashboard below the 1,500 ms budget; exact timings vary per run.

## Not part of localhost P1

Production HTTPS, secure cookies, real SSO credentials, mandatory administrator MFA, external mail delivery, load-balancer configuration, and production traffic/load testing remain deployment-environment activities rather than localhost feature gaps.
