#!/bin/sh
set -eu

APP_ROOT="${LMS_APP_ROOT:-/var/www/html/lms-pandoralite}"
STATE_DIR="${APP_ROOT}/storage/scheduler"
INTERVAL="${LMS_SCHEDULER_INTERVAL:-300}"

mkdir -p "${STATE_DIR}"

run_ci()
{
  php "${APP_ROOT}/index.php" ops "$@"
}

run_once()
{
  job_key="$1"
  period_key="$2"
  shift 2
  stamp="${STATE_DIR}/${job_key}.${period_key}"

  if [ -f "${stamp}" ]; then
    return 0
  fi

  if run_ci "$@"; then
    : > "${stamp}"
    find "${STATE_DIR}" -type f -name "${job_key}.*" ! -name "${job_key}.${period_key}" -delete
  fi
}

while true; do
  now_hour="$(date +%H)"
  now_minute="$(date +%M)"
  today="$(date +%Y%m%d)"
  week="$(date +%G%V)"
  weekday="$(date +%u)"

  run_ci dispatch_notifications 100 || true

  if [ "${now_hour}" = "01" ] && [ "${now_minute}" -ge "10" ]; then
    run_once reminders "${today}" generate_reminders 7 || true
  fi
  if [ "${now_hour}" = "02" ] && [ "${now_minute}" -ge "15" ]; then
    run_once database_backup "${today}" backup database || true
  fi
  if [ "${weekday}" = "7" ] && [ "${now_hour}" = "02" ] && [ "${now_minute}" -ge "30" ]; then
    run_once uploads_backup "${week}" backup uploads || true
  fi
  if [ "${now_hour}" = "03" ] && [ "${now_minute}" -ge "45" ]; then
    run_once cleanup "${today}" cleanup 90 || true
  fi

  sleep "${INTERVAL}"
done
