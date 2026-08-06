<?php
function label($label)
{
  $ci = &get_instance();
  if (isset($ci->lang->language[$label]) && $ci->lang->language[$label] !== '') {
    return $ci->lang->language[$label];
  }

  return $label;
}

function lms_has_period_date($value)
{
  return !empty($value) && $value !== '0000-00-00 00:00:00';
}

function lms_format_period_date($value, $lang)
{
  if (!lms_has_period_date($value)) {
    return '';
  }
  if ($lang === 'thai') {
    return date('d/m/', strtotime($value)) . (date('Y', strtotime($value)) + 543) . ' ' . date('H:i', strtotime($value));
  }
  return date('d/m/Y H:i', strtotime($value));
}

function lms_format_period_range($dateStart, $dateEnd, $lang, $separator = ' - ')
{
  $periodStart = lms_format_period_date($dateStart, $lang);
  $periodEnd = lms_format_period_date($dateEnd, $lang);
  if ($periodStart !== '' && $periodEnd !== '') {
    return $periodStart . $separator . $periodEnd;
  }
  if ($periodStart !== '') {
    return $periodStart . $separator . label('course_no_end_date');
  }
  if ($periodEnd !== '') {
    return $periodEnd;
  }
  return label('UnlimitedTime');
}

function media_url($relativePath, $defaultPath = '')
{
  $relativePath = ltrim((string) $relativePath, '/\\');
  $defaultPath = ltrim((string) $defaultPath, '/\\');

  $query = http_build_query(array_filter(array(
    'path' => $relativePath,
    'default' => $defaultPath
  ), function ($value) {
    return $value !== '';
  }));

  return site_url('media/file') . ($query !== '' ? '?' . $query : '');
}

function audit_file_action($action, $path, $oldValues = null, $newValues = null, $extra = array())
{
  $ci = &get_instance();
  if (!isset($ci->db)) {
    $ci->load->database();
  }

  $user = $ci->session->userdata('user');
  $user = is_array($user) ? $user : array();
  $router = isset($ci->router) ? $ci->router : null;

  $changed = array(
    'before' => $oldValues,
    'after' => $newValues
  );

  $data = array(
    'audit_action' => 'file_' . $action,
    'audit_table' => 'filesystem',
    'audit_row_key' => (string) $path,
    'audit_old_values' => $oldValues === null ? null : json_encode($oldValues, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    'audit_new_values' => $newValues === null ? null : json_encode($newValues, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    'audit_changed_values' => json_encode($changed + array('extra' => $extra), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    'audit_sql' => null,
    'audit_controller' => $router ? $router->fetch_class() : null,
    'audit_method' => $router ? $router->fetch_method() : null,
    'audit_uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null,
    'audit_http_method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null,
    'audit_ip' => audit_client_ip(),
    'audit_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
    'audit_user_id' => isset($user['u_id']) ? $user['u_id'] : null,
    'audit_emp_id' => isset($user['emp_id']) ? $user['emp_id'] : null,
    'audit_com_id' => isset($user['com_id']) ? $user['com_id'] : null,
    'audit_username' => isset($user['useri']) ? $user['useri'] : (isset($user['emp_c']) ? $user['emp_c'] : null),
    'audit_user_display' => isset($user['fullname_th']) ? $user['fullname_th'] : (isset($user['fullname_en']) ? $user['fullname_en'] : null),
    'audit_created_at' => date('Y-m-d H:i:s')
  );

  audit_insert_file_row($ci, $data);
}

function audit_insert_file_row($ci, $data)
{
  $mysqli = audit_new_mysqli($ci);
  if ($mysqli === null) {
    return;
  }

  $prevHash = null;
  $result = $mysqli->query('SELECT audit_hash FROM `lms_audit_logs` ORDER BY audit_id DESC LIMIT 1');
  if ($result && ($row = $result->fetch_assoc()) && isset($row['audit_hash'])) {
    $prevHash = $row['audit_hash'];
  }

  $payload = $data;
  ksort($payload);
  $data['audit_prev_hash'] = $prevHash;
  $data['audit_hash'] = hash('sha256', (string) $prevHash . '|' . json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

  $fields = array();
  $values = array();
  foreach ($data as $field => $value) {
    $fields[] = '`' . str_replace('`', '``', $field) . '`';
    $values[] = $value === null ? 'NULL' : "'" . $mysqli->real_escape_string((string) $value) . "'";
  }

  $mysqli->query('INSERT INTO `lms_audit_logs` (' . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')');
  $mysqli->close();
}

function audit_new_mysqli($ci)
{
  if (!isset($ci->db)) {
    return null;
  }

  $hostname = $ci->db->hostname;
  $port = isset($ci->db->port) && $ci->db->port ? $ci->db->port : ini_get('mysqli.default_port');
  if (strpos($hostname, ':') !== false) {
    $parts = explode(':', $hostname, 2);
    $hostname = $parts[0];
    $port = $parts[1];
  }

  $mysqli = @new mysqli(
    $hostname,
    $ci->db->username,
    $ci->db->password,
    $ci->db->database,
    (int) $port
  );

  if ($mysqli->connect_errno) {
    return null;
  }

  $mysqli->set_charset(isset($ci->db->char_set) ? $ci->db->char_set : 'utf8');
  return $mysqli;
}

function audit_move_uploaded_file($source, $target)
{
  $extension = strtolower(pathinfo((string) $target, PATHINFO_EXTENSION));
  $blockedExtensions = array('php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'pht', 'phar', 'cgi', 'pl', 'sh', 'exe', 'dll', 'com', 'bat', 'cmd', 'htaccess');
  $maxBytes = (int) (getenv('LMS_UPLOAD_MAX_BYTES') ?: 536870912);
  $uploadRoot = defined('ROOT_DIR') ? realpath(ROOT_DIR . 'uploads') : false;
  $imageRoot = defined('ROOT_DIR') ? realpath(ROOT_DIR . 'images') : false;
  $targetDirectory = realpath(dirname((string) $target));
  $isAllowedRoot = $targetDirectory && (
    ($uploadRoot && strpos($targetDirectory, $uploadRoot) === 0) ||
    ($imageRoot && strpos($targetDirectory, $imageRoot) === 0 && in_array($extension, array('jpg', 'jpeg', 'png', 'gif', 'webp'), true))
  );

  if (strpos((string) $target, "\0") !== false ||
      in_array($extension, $blockedExtensions, true) ||
      !is_uploaded_file($source) ||
      !is_file($source) ||
      filesize($source) > $maxBytes ||
      !$isAllowedRoot) {
    log_message('error', 'Blocked unsafe upload target: ' . basename((string) $target));
    return false;
  }

  $result = move_uploaded_file($source, $target);
  if ($result) {
    audit_file_action('upload', $target, null, audit_file_snapshot($target), array('source' => basename((string) $source)));
  }

  return $result;
}

function audit_unlink($path)
{
  $old = audit_file_snapshot($path);
  $result = unlink($path);
  if ($result) {
    audit_file_action('delete', $path, $old, null);
  }

  return $result;
}

function audit_copy($source, $target)
{
  $result = copy($source, $target);
  if ($result) {
    audit_file_action('copy', $target, audit_file_snapshot($source), audit_file_snapshot($target), array('source' => $source));
  }

  return $result;
}

function audit_file_snapshot($path)
{
  if (!is_file($path)) {
    return array('path' => (string) $path, 'exists' => false);
  }

  return array(
    'path' => (string) $path,
    'exists' => true,
    'size' => filesize($path),
    'sha256' => hash_file('sha256', $path),
    'modified_at' => date('Y-m-d H:i:s', filemtime($path))
  );
}

function audit_client_ip()
{
  foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
    if (!empty($_SERVER[$key])) {
      return $_SERVER[$key];
    }
  }

  return null;
}

function textCenter($strValue) {
  return "<center>".$strValue."</center>";
}

function textRight($strValue) {
  return "<span style='float:right;'>".$strValue."</span>";
}

function textRed($strValue) {
  return "<span class='text-danger'><b>".$strValue."</b></span>";
}

function textWarning($strValue) {
  return "<span class='text-warning'>".$strValue."</span>";
}

function textSuccess($strValue) {
  return "<span class='text-success'>".$strValue."</span>";
}

function checkValueIsNullTypeString($value) {
  if ($value != "" && $value != null && $value != NULL) {
    return false;
  } else {
    return true;
  }
}

function checkValueIsNullTypeNumber($value) {
  if ($value != "" && $value != null && $value != NULL && intval($value) != 0) {
    return false;
  } else {
    return true;
  }
}

function getValueAfterCheckIsNullTypeNumber($value) {
  return !checkValueIsNullTypeNumber($value) ? intval($value) : 0;
}

function checkDatetimeIsNull($strDate) {
  $isNull = true;
  if ($strDate != "0000-00-00 00:00:00" && $strDate != "1900-01-01 00:00:00" && $strDate != "1900-01-01" && $strDate != "0000-00-00" && $strDate != "") {
    $isNull = false;
  }
  return $isNull;
}

function checkDateIsNull($strDate) {
  $isNull = true;
  if ($strDate != "1900-01-01" && $strDate != "0000-00-00" && $strDate != "") {
    $isNull = false;
  }
  return $isNull;
}

function changeDateNews($date)
{

  $date =  str_replace(" ", "-", $date);
  $date_ar = explode("-", $date);
  $month["english"] = array("mdi ", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
  //print_r( $date_ar );
  $day = $date_ar[2];
  /*if( intval($date_ar[1]) < 10 ){
      $month_use = "0".$date_ar[1];
    }else{
      $month_use = $date_ar[1];
    }*/
  //$month_use = $date_ar[1];
  $month_use = $month["english"][intval($date_ar[1])];
  $date = $day . " " . $month_use . "";

  return $date;
}
function changeDate($date, $lang)
{

  $month = array();
  $month["thailand"] = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
  $month["english"] = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $date =  str_replace(" ", "-", $date);
  $date_ar = explode("-", $date);
  //print_r( $date_ar );
  $day = $date_ar[2];
  $month_use = $month[$lang][intval($date_ar[1])];
  $year = $lang == "thailand" ? intval($date_ar[0]) + 543 : $date_ar[0];

  $date = $day . " " . $month_use . " " . $year;

  return $date;
}
function changeDateNotime($date, $lang)
{

  $month = array();
  $month["thailand"] = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
  $month["english"] = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  //$date =  str_replace(" ","-",$date );
  $date_ar = explode("-", $date);
  //print_r( $date_ar );
  $day = $date_ar[2];
  $month_use = $month[$lang][intval($date_ar[1])];
  $year = $lang == "thailand" ? intval($date_ar[0]) + 543 : $date_ar[0];

  $date = $day . " " . $month_use . " " . $year;

  return $date;
}

function checkStrLength($str, $limit)
{
  if (strlen($str) > intval($limit)) {
    $str = iconv_substr($str, 0, $limit, "UTF-8") . "...";
  }
  return $str;
}

function countArray($input)
{
  return isset($input) && is_array($input) ? count($input) : 0;
}

function certificate_text_layout($langCode = '')
{
  $langCode = strtoupper((string)$langCode);

  if (in_array($langCode, array('EN', 'JP'))) {
    return array(
      'name_y' => 85,
      'company_y' => 98,
      'course_y' => 130,
      'issued_y' => 160,
      'name_size' => 22,
      'company_size' => 18,
      'course_size' => 18,
      'issued_size' => 18,
      'name_height' => 8,
      'company_height' => 6,
      'issued_height' => 6,
    );
  }

  return array(
    'name_y' => 85,
    'company_y' => 100,
    'course_y' => 130,
    'issued_y' => 175,
    'name_size' => 30,
    'company_size' => 22,
    'course_size' => 28,
    'issued_size' => 20,
    'name_height' => 12,
    'company_height' => 10,
    'issued_height' => 10,
  );
}

function icon_material()
{
  $arr_icon = array("mdi mdi-access-point", "mdi mdi-access-point-network", "mdi mdi-account", "mdi mdi-account-alert", "mdi mdi-account-box", "mdi mdi-account-box-outline", "mdi mdi-account-card-details", "mdi mdi-account-check", "mdi mdi-account-circle", "mdi mdi-account-convert", "mdi mdi-account-edit", "mdi mdi-account-key", "mdi mdi-account-location", "mdi mdi-account-minus", "mdi mdi-account-multiple", "mdi mdi-account-multiple-minus", "mdi mdi-account-multiple-outline", "mdi mdi-account-multiple-plus", "mdi mdi-account-network", "mdi mdi-account-off", "mdi mdi-account-outline", "mdi mdi-account-plus", "mdi mdi-account-remove", "mdi mdi-account-search", "mdi mdi-account-settings", "mdi mdi-account-settings-variant", "mdi mdi-account-star", "mdi mdi-account-star-variant", "mdi mdi-account-switch", "mdi mdi-adjust", "mdi mdi-air-conditioner", "mdi mdi-airballoon", "mdi mdi-airplane", "mdi mdi-airplane-landing", "mdi mdi-airplane-off", "mdi mdi-airplane-takeoff", "mdi mdi-airplay", "mdi mdi-alarm", "mdi mdi-alarm-check", "mdi mdi-alarm-multiple", "mdi mdi-alarm-off", "mdi mdi-alarm-plus", "mdi mdi-alarm-snooze", "mdi mdi-album", "mdi mdi-alert", "mdi mdi-alert-box", "mdi mdi-alert-circle", "mdi mdi-alert-circle-outline", "mdi mdi-alert-octagon", "mdi mdi-alert-octagram", "mdi mdi-alert-outline", "mdi mdi-all-inclusive", "mdi mdi-alpha", "mdi mdi-alphabetical", "mdi mdi-altimeter", "mdi mdi-amazon", "mdi mdi-amazon-clouddrive", "mdi mdi-ambulance", "mdi mdi-amplifier", "mdi mdi-anchor", "mdi mdi-android", "mdi mdi-android-debug-bridge", "mdi mdi-android-studio", "mdi mdi-angular", "mdi mdi-angularjs", "mdi mdi-animation", "mdi mdi-apple", "mdi mdi-apple-finder", "mdi mdi-apple-ios", "mdi mdi-apple-keyboard-caps", "mdi mdi-apple-keyboard-command", "mdi mdi-apple-keyboard-control", "mdi mdi-apple-keyboard-option", "mdi mdi-apple-keyboard-shift", "mdi mdi-apple-mobileme", "mdi mdi-apple-safari", "mdi mdi-application", "mdi mdi-apps", "mdi mdi-archive", "mdi mdi-arrange-bring-forward", "mdi mdi-arrange-bring-to-front", "mdi mdi-arrange-send-backward", "mdi mdi-arrange-send-to-back", "mdi mdi-arrow-all", "mdi mdi-arrow-bottom-left", "mdi mdi-arrow-bottom-right", "mdi mdi-arrow-compress", "mdi mdi-arrow-compress-all", "mdi mdi-arrow-down", "mdi mdi-arrow-down-bold", "mdi mdi-arrow-down-bold-circle", "mdi mdi-arrow-down-bold-circle-outline", "mdi mdi-arrow-down-bold-hexagon-outline", "mdi mdi-arrow-down-box", "mdi mdi-arrow-down-drop-circle", "mdi mdi-arrow-down-drop-circle-outline", "mdi mdi-arrow-expand", "mdi mdi-arrow-expand-all", "mdi mdi-arrow-left", "mdi mdi-arrow-left-bold", "mdi mdi-arrow-left-bold-circle", "mdi mdi-arrow-left-bold-circle-outline", "mdi mdi-arrow-left-bold-hexagon-outline", "mdi mdi-arrow-left-box", "mdi mdi-arrow-left-drop-circle", "mdi mdi-arrow-left-drop-circle-outline", "mdi mdi-arrow-right", "mdi mdi-arrow-right-bold", "mdi mdi-arrow-right-bold-circle", "mdi mdi-arrow-right-bold-circle-outline", "mdi mdi-arrow-right-bold-hexagon-outline", "mdi mdi-arrow-right-box", "mdi mdi-arrow-right-drop-circle", "mdi mdi-arrow-right-drop-circle-outline", "mdi mdi-arrow-top-left", "mdi mdi-arrow-top-right", "mdi mdi-arrow-up", "mdi mdi-arrow-up-bold", "mdi mdi-arrow-up-bold-circle", "mdi mdi-arrow-up-bold-circle-outline", "mdi mdi-arrow-up-bold-hexagon-outline", "mdi mdi-arrow-up-box", "mdi mdi-arrow-up-drop-circle", "mdi mdi-arrow-up-drop-circle-outline", "mdi mdi-assistant", "mdi mdi-asterisk", "mdi mdi-at", "mdi mdi-attachment", "mdi mdi-audiobook", "mdi mdi-auto-fix", "mdi mdi-auto-upload", "mdi mdi-autorenew", "mdi mdi-av-timer", "mdi mdi-baby", "mdi mdi-baby-buggy", "mdi mdi-backburger", "mdi mdi-backspace", "mdi mdi-backup-restore", "mdi mdi-bandcamp", "mdi mdi-bank", "mdi mdi-barcode", "mdi mdi-barcode-scan", "mdi mdi-barley", "mdi mdi-barrel", "mdi mdi-basecamp", "mdi mdi-basket", "mdi mdi-basket-fill", "mdi mdi-basket-unfill", "mdi mdi-battery", "mdi mdi-battery-10", "mdi mdi-battery-20", "mdi mdi-battery-30", "mdi mdi-battery-40", "mdi mdi-battery-50", "mdi mdi-battery-60", "mdi mdi-battery-70", "mdi mdi-battery-80", "mdi mdi-battery-90", "mdi mdi-battery-alert", "mdi mdi-battery-charging", "mdi mdi-battery-charging-100", "mdi mdi-battery-charging-20", "mdi mdi-battery-charging-30", "mdi mdi-battery-charging-40", "mdi mdi-battery-charging-60", "mdi mdi-battery-charging-80", "mdi mdi-battery-charging-90", "mdi mdi-battery-minus", "mdi mdi-battery-negative", "mdi mdi-battery-outline", "mdi mdi-battery-plus", "mdi mdi-battery-positive", "mdi mdi-battery-unknown", "mdi mdi-beach", "mdi mdi-beaker", "mdi mdi-beats", "mdi mdi-beer", "mdi mdi-behance", "mdi mdi-bell", "mdi mdi-bell-off", "mdi mdi-bell-outline", "mdi mdi-bell-plus", "mdi mdi-bell-ring", "mdi mdi-bell-ring-outline", "mdi mdi-bell-sleep", "mdi mdi-beta", "mdi mdi-bible", "mdi mdi-bike", "mdi mdi-bing", "mdi mdi-binoculars", "mdi mdi-bio", "mdi mdi-biohazard", "mdi mdi-bitbucket", "mdi mdi-black-mesa", "mdi mdi-blackberry", "mdi mdi-blender", "mdi mdi-blinds", "mdi mdi-block-helper", "mdi mdi-blogger", "mdi mdi-bluetooth", "mdi mdi-bluetooth-audio", "mdi mdi-bluetooth-connect", "mdi mdi-bluetooth-off", "mdi mdi-bluetooth-settings", "mdi mdi-bluetooth-transfer", "mdi mdi-blur", "mdi mdi-blur-linear", "mdi mdi-blur-off", "mdi mdi-blur-radial", "mdi mdi-bomb", "mdi mdi-bomb-off", "mdi mdi-bone", "mdi mdi-book", "mdi mdi-book-minus", "mdi mdi-book-multiple", "mdi mdi-book-multiple-variant", "mdi mdi-book-open", "mdi mdi-book-open-page-variant", "mdi mdi-book-open-variant", "mdi mdi-book-plus", "mdi mdi-book-variant", "mdi mdi-bookmark", "mdi mdi-bookmark-check", "mdi mdi-bookmark-music", "mdi mdi-bookmark-outline", "mdi mdi-bookmark-plus", "mdi mdi-bookmark-plus-outline", "mdi mdi-bookmark-remove", "mdi mdi-boombox", "mdi mdi-bootstrap", "mdi mdi-border-all", "mdi mdi-border-bottom", "mdi mdi-border-color", "mdi mdi-border-horizontal", "mdi mdi-border-inside", "mdi mdi-border-left", "mdi mdi-border-none", "mdi mdi-border-outside", "mdi mdi-border-right", "mdi mdi-border-style", "mdi mdi-border-top", "mdi mdi-border-vertical", "mdi mdi-bow-tie", "mdi mdi-bowl", "mdi mdi-bowling", "mdi mdi-box", "mdi mdi-box-cutter", "mdi mdi-box-shadow", "mdi mdi-bridge", "mdi mdi-briefcase", "mdi mdi-briefcase-check", "mdi mdi-briefcase-download", "mdi mdi-briefcase-upload", "mdi mdi-brightness-1", "mdi mdi-brightness-2", "mdi mdi-brightness-3", "mdi mdi-brightness-4", "mdi mdi-brightness-5", "mdi mdi-brightness-6", "mdi mdi-brightness-7", "mdi mdi-brightness-auto", "mdi mdi-broom", "mdi mdi-brush", "mdi mdi-buffer", "mdi mdi-bug", "mdi mdi-bulletin-board", "mdi mdi-bullhorn", "mdi mdi-bullseye", "mdi mdi-burst-mode", "mdi mdi-bus", "mdi mdi-cached", "mdi mdi-cake", "mdi mdi-cake-layered", "mdi mdi-cake-variant", "mdi mdi-calculator", "mdi mdi-calendar", "mdi mdi-calendar-blank", "mdi mdi-calendar-check", "mdi mdi-calendar-clock", "mdi mdi-calendar-multiple", "mdi mdi-calendar-multiple-check", "mdi mdi-calendar-plus", "mdi mdi-calendar-question", "mdi mdi-calendar-range", "mdi mdi-calendar-remove", "mdi mdi-calendar-text", "mdi mdi-calendar-today", "mdi mdi-call-made", "mdi mdi-call-merge", "mdi mdi-call-missed", "mdi mdi-call-received", "mdi mdi-call-split", "mdi mdi-camcorder", "mdi mdi-camcorder-box", "mdi mdi-camcorder-box-off", "mdi mdi-camcorder-off", "mdi mdi-camera", "mdi mdi-camera-burst", "mdi mdi-camera-enhance", "mdi mdi-camera-front", "mdi mdi-camera-front-variant", "mdi mdi-camera-iris", "mdi mdi-camera-off", "mdi mdi-camera-party-mode", "mdi mdi-camera-rear", "mdi mdi-camera-rear-variant", "mdi mdi-camera-switch", "mdi mdi-camera-timer", "mdi mdi-candle", "mdi mdi-candycane", "mdi mdi-car", "mdi mdi-car-battery", "mdi mdi-car-connected", "mdi mdi-car-wash", "mdi mdi-cards", "mdi mdi-cards-outline", "mdi mdi-cards-playing-outline", "mdi mdi-cards-variant", "mdi mdi-carrot", "mdi mdi-cart", "mdi mdi-cart-off", "mdi mdi-cart-outline", "mdi mdi-cart-plus", "mdi mdi-case-sensitive-alt", "mdi mdi-cash", "mdi mdi-cash-100", "mdi mdi-cash-multiple", "mdi mdi-cash-usd", "mdi mdi-cast", "mdi mdi-cast-connected", "mdi mdi-castle", "mdi mdi-cat", "mdi mdi-cellphone", "mdi mdi-cellphone-android", "mdi mdi-cellphone-basic", "mdi mdi-cellphone-dock", "mdi mdi-cellphone-iphone", "mdi mdi-cellphone-link", "mdi mdi-cellphone-link-off", "mdi mdi-cellphone-settings", "mdi mdi-certificate", "mdi mdi-chair-school", "mdi mdi-chart-arc", "mdi mdi-chart-areaspline", "mdi mdi-chart-bar", "mdi mdi-chart-bubble", "mdi mdi-chart-gantt", "mdi mdi-chart-histogram", "mdi mdi-chart-line", "mdi mdi-chart-pie", "mdi mdi-chart-scatterplot-hexbin", "mdi mdi-chart-timeline", "mdi mdi-check", "mdi mdi-check-all", "mdi mdi-check-circle", "mdi mdi-check-circle-outline", "mdi mdi-checkbox-blank", "mdi mdi-checkbox-blank-circle", "mdi mdi-checkbox-blank-circle-outline", "mdi mdi-checkbox-blank-outline", "mdi mdi-checkbox-marked", "mdi mdi-checkbox-marked-circle", "mdi mdi-checkbox-marked-circle-outline", "mdi mdi-checkbox-marked-outline", "mdi mdi-checkbox-multiple-blank", "mdi mdi-checkbox-multiple-blank-circle", "mdi mdi-checkbox-multiple-blank-circle-outline", "mdi mdi-checkbox-multiple-blank-outline", "mdi mdi-checkbox-multiple-marked", "mdi mdi-checkbox-multiple-marked-circle", "mdi mdi-checkbox-multiple-marked-circle-outline", "mdi mdi-checkbox-multiple-marked-outline", "mdi mdi-checkerboard", "mdi mdi-chemical-weapon", "mdi mdi-chevron-double-down", "mdi mdi-chevron-double-left", "mdi mdi-chevron-double-right", "mdi mdi-chevron-double-up", "mdi mdi-chevron-down", "mdi mdi-chevron-left", "mdi mdi-chevron-right", "mdi mdi-chevron-up", "mdi mdi-chip", "mdi mdi-church", "mdi mdi-cisco-webex", "mdi mdi-city", "mdi mdi-clipboard", "mdi mdi-clipboard-account", "mdi mdi-clipboard-alert", "mdi mdi-clipboard-arrow-down", "mdi mdi-clipboard-arrow-left", "mdi mdi-clipboard-check", "mdi mdi-clipboard-flow", "mdi mdi-clipboard-outline", "mdi mdi-clipboard-text", "mdi mdi-clippy", "mdi mdi-clock", "mdi mdi-clock-alert", "mdi mdi-clock-end", "mdi mdi-clock-fast", "mdi mdi-clock-in", "mdi mdi-clock-out", "mdi mdi-clock-start", "mdi mdi-close", "mdi mdi-close-box", "mdi mdi-close-box-outline", "mdi mdi-close-circle", "mdi mdi-close-circle-outline", "mdi mdi-close-network", "mdi mdi-close-octagon", "mdi mdi-close-octagon-outline", "mdi mdi-close-outline", "mdi mdi-closed-caption", "mdi mdi-cloud", "mdi mdi-cloud-check", "mdi mdi-cloud-circle", "mdi mdi-cloud-download", "mdi mdi-cloud-outline", "mdi mdi-cloud-outline-off", "mdi mdi-cloud-print", "mdi mdi-cloud-print-outline", "mdi mdi-cloud-sync", "mdi mdi-cloud-upload", "mdi mdi-code-array", "mdi mdi-code-braces", "mdi mdi-code-brackets", "mdi mdi-code-equal", "mdi mdi-code-greater-than", "mdi mdi-code-greater-than-or-equal", "mdi mdi-code-less-than", "mdi mdi-code-less-than-or-equal", "mdi mdi-code-not-equal", "mdi mdi-code-not-equal-variant", "mdi mdi-code-parentheses", "mdi mdi-code-string", "mdi mdi-code-tags", "mdi mdi-code-tags-check", "mdi mdi-codepen", "mdi mdi-coffee", "mdi mdi-coffee-outline", "mdi mdi-coffee-to-go", "mdi mdi-coin", "mdi mdi-coins", "mdi mdi-collage", "mdi mdi-color-helper", "mdi mdi-comment", "mdi mdi-comment-account", "mdi mdi-comment-account-outline", "mdi mdi-comment-alert", "mdi mdi-comment-alert-outline", "mdi mdi-comment-check", "mdi mdi-comment-check-outline", "mdi mdi-comment-multiple-outline", "mdi mdi-comment-outline", "mdi mdi-comment-plus-outline", "mdi mdi-comment-processing", "mdi mdi-comment-processing-outline", "mdi mdi-comment-question-outline", "mdi mdi-comment-remove-outline", "mdi mdi-comment-text", "mdi mdi-comment-text-outline", "mdi mdi-compare", "mdi mdi-compass", "mdi mdi-compass-outline", "mdi mdi-console", "mdi mdi-contact-mail", "mdi mdi-contacts", "mdi mdi-content-copy", "mdi mdi-content-cut", "mdi mdi-content-duplicate", "mdi mdi-content-paste", "mdi mdi-content-save", "mdi mdi-content-save-all", "mdi mdi-content-save-settings", "mdi mdi-contrast", "mdi mdi-contrast-box", "mdi mdi-contrast-circle", "mdi mdi-cookie", "mdi mdi-copyright", "mdi mdi-counter", "mdi mdi-cow", "mdi mdi-creation", "mdi mdi-credit-card", "mdi mdi-credit-card-multiple", "mdi mdi-credit-card-off", "mdi mdi-credit-card-plus", "mdi mdi-credit-card-scan", "mdi mdi-crop", "mdi mdi-crop-free", "mdi mdi-crop-landscape", "mdi mdi-crop-portrait", "mdi mdi-crop-rotate", "mdi mdi-crop-square", "mdi mdi-crosshairs", "mdi mdi-crosshairs-gps", "mdi mdi-crown", "mdi mdi-cube", "mdi mdi-cube-outline", "mdi mdi-cube-send", "mdi mdi-cube-unfolded", "mdi mdi-cup", "mdi mdi-cup-off", "mdi mdi-cup-water", "mdi mdi-currency-btc", "mdi mdi-currency-eur", "mdi mdi-currency-gbp", "mdi mdi-currency-inr", "mdi mdi-currency-ngn", "mdi mdi-currency-rub", "mdi mdi-currency-try", "mdi mdi-currency-usd", "mdi mdi-currency-usd-off", "mdi mdi-cursor-default", "mdi mdi-cursor-default-outline", "mdi mdi-cursor-move", "mdi mdi-cursor-pointer", "mdi mdi-cursor-text", "mdi mdi-database", "mdi mdi-database-minus", "mdi mdi-database-plus", "mdi mdi-debug-step-into", "mdi mdi-debug-step-out", "mdi mdi-debug-step-over", "mdi mdi-decimal-decrease", "mdi mdi-decimal-increase", "mdi mdi-delete", "mdi mdi-delete-circle", "mdi mdi-delete-empty", "mdi mdi-delete-forever", "mdi mdi-delete-sweep", "mdi mdi-delete-variant", "mdi mdi-delta", "mdi mdi-deskphone", "mdi mdi-desktop-mac", "mdi mdi-desktop-tower", "mdi mdi-details", "mdi mdi-developer-board", "mdi mdi-deviantart", "mdi mdi-dialpad", "mdi mdi-diamond", "mdi mdi-dice-1", "mdi mdi-dice-2", "mdi mdi-dice-3", "mdi mdi-dice-4", "mdi mdi-dice-5", "mdi mdi-dice-6", "mdi mdi-dice-d20", "mdi mdi-dice-d4", "mdi mdi-dice-d6", "mdi mdi-dice-d8", "mdi mdi-dictionary", "mdi mdi-directions", "mdi mdi-directions-fork", "mdi mdi-discord", "mdi mdi-disk", "mdi mdi-disk-alert", "mdi mdi-disqus", "mdi mdi-disqus-outline", "mdi mdi-division", "mdi mdi-division-box", "mdi mdi-dna", "mdi mdi-dns", "mdi mdi-do-not-disturb", "mdi mdi-do-not-disturb-off", "mdi mdi-dolby", "mdi mdi-domain", "mdi mdi-dots-horizontal", "mdi mdi-dots-vertical", "mdi mdi-douban", "mdi mdi-download", "mdi mdi-drag", "mdi mdi-drag-horizontal", "mdi mdi-drag-vertical", "mdi mdi-drawing", "mdi mdi-drawing-box", "mdi mdi-dribbble", "mdi mdi-dribbble-box", "mdi mdi-drone", "mdi mdi-dropbox", "mdi mdi-drupal", "mdi mdi-duck", "mdi mdi-dumbbell", "mdi mdi-earth", "mdi mdi-earth-box", "mdi mdi-earth-box-off", "mdi mdi-earth-off", "mdi mdi-edge", "mdi mdi-eject", "mdi mdi-elevation-decline", "mdi mdi-elevation-rise", "mdi mdi-elevator", "mdi mdi-email", "mdi mdi-email-alert", "mdi mdi-email-open", "mdi mdi-email-open-outline", "mdi mdi-email-outline", "mdi mdi-email-secure", "mdi mdi-email-variant", "mdi mdi-emby", "mdi mdi-emoticon", "mdi mdi-emoticon-cool", "mdi mdi-emoticon-dead", "mdi mdi-emoticon-devil", "mdi mdi-emoticon-excited", "mdi mdi-emoticon-happy", "mdi mdi-emoticon-neutral", "mdi mdi-emoticon-poop", "mdi mdi-emoticon-sad", "mdi mdi-emoticon-tongue", "mdi mdi-engine", "mdi mdi-engine-outline", "mdi mdi-equal", "mdi mdi-equal-box", "mdi mdi-eraser", "mdi mdi-eraser-variant", "mdi mdi-escalator", "mdi mdi-ethernet", "mdi mdi-ethernet-cable", "mdi mdi-ethernet-cable-off", "mdi mdi-etsy", "mdi mdi-ev-station", "mdi mdi-evernote", "mdi mdi-exclamation", "mdi mdi-exit-to-app", "mdi mdi-export", "mdi mdi-eye", "mdi mdi-eye-off", "mdi mdi-eye-outline", "mdi mdi-eye-outline-off", "mdi mdi-eyedropper", "mdi mdi-eyedropper-variant", "mdi mdi-face", "mdi mdi-face-profile", "mdi mdi-facebook", "mdi mdi-facebook-box", "mdi mdi-facebook-messenger", "mdi mdi-factory", "mdi mdi-fan", "mdi mdi-fast-forward", "mdi mdi-fast-forward-outline", "mdi mdi-fax", "mdi mdi-feather", "mdi mdi-ferry", "mdi mdi-file", "mdi mdi-file-chart", "mdi mdi-file-check", "mdi mdi-file-cloud", "mdi mdi-file-delimited", "mdi mdi-file-document", "mdi mdi-file-document-box", "mdi mdi-file-excel", "mdi mdi-file-excel-box", "mdi mdi-file-export", "mdi mdi-file-find", "mdi mdi-file-hidden", "mdi mdi-file-image", "mdi mdi-file-import", "mdi mdi-file-lock", "mdi mdi-file-multiple", "mdi mdi-file-music", "mdi mdi-file-outline", "mdi mdi-file-pdf", "mdi mdi-file-pdf-box", "mdi mdi-file-powerpoint", "mdi mdi-file-powerpoint-box", "mdi mdi-file-presentation-box", "mdi mdi-file-restore", "mdi mdi-file-send", "mdi mdi-file-tree", "mdi mdi-file-video", "mdi mdi-file-word", "mdi mdi-file-word-box", "mdi mdi-file-xml", "mdi mdi-film", "mdi mdi-filmstrip", "mdi mdi-filmstrip-off", "mdi mdi-filter", "mdi mdi-filter-outline", "mdi mdi-filter-remove", "mdi mdi-filter-remove-outline", "mdi mdi-filter-variant", "mdi mdi-find-replace", "mdi mdi-fingerprint", "mdi mdi-fire", "mdi mdi-firefox", "mdi mdi-fish", "mdi mdi-flag", "mdi mdi-flag-checkered", "mdi mdi-flag-outline", "mdi mdi-flag-outline-variant", "mdi mdi-flag-triangle", "mdi mdi-flag-variant", "mdi mdi-flash", "mdi mdi-flash-auto", "mdi mdi-flash-off", "mdi mdi-flash-outline", "mdi mdi-flash-red-eye", "mdi mdi-flashlight", "mdi mdi-flashlight-off", "mdi mdi-flask", "mdi mdi-flask-empty", "mdi mdi-flask-empty-outline", "mdi mdi-flask-outline", "mdi mdi-flattr", "mdi mdi-flip-to-back", "mdi mdi-flip-to-front", "mdi mdi-floppy", "mdi mdi-flower", "mdi mdi-folder", "mdi mdi-folder-account", "mdi mdi-folder-download", "mdi mdi-folder-google-drive", "mdi mdi-folder-image", "mdi mdi-folder-lock", "mdi mdi-folder-lock-open", "mdi mdi-folder-move", "mdi mdi-folder-multiple", "mdi mdi-folder-multiple-image", "mdi mdi-folder-multiple-outline", "mdi mdi-folder-outline", "mdi mdi-folder-plus", "mdi mdi-folder-remove", "mdi mdi-folder-star", "mdi mdi-folder-upload", "mdi mdi-font-awesome", "mdi mdi-food", "mdi mdi-food-apple", "mdi mdi-food-fork-drink", "mdi mdi-food-off", "mdi mdi-food-variant", "mdi mdi-football", "mdi mdi-football-australian", "mdi mdi-football-helmet", "mdi mdi-format-align-center", "mdi mdi-format-align-justify", "mdi mdi-format-align-left", "mdi mdi-format-align-right", "mdi mdi-format-annotation-plus", "mdi mdi-format-bold", "mdi mdi-format-clear", "mdi mdi-format-color-fill", "mdi mdi-format-color-text", "mdi mdi-format-float-center", "mdi mdi-format-float-left", "mdi mdi-format-float-none", "mdi mdi-format-float-right", "mdi mdi-format-font", "mdi mdi-format-header-1", "mdi mdi-format-header-2", "mdi mdi-format-header-3", "mdi mdi-format-header-4", "mdi mdi-format-header-5", "mdi mdi-format-header-6", "mdi mdi-format-header-decrease", "mdi mdi-format-header-equal", "mdi mdi-format-header-increase", "mdi mdi-format-header-pound", "mdi mdi-format-horizontal-align-center", "mdi mdi-format-horizontal-align-left", "mdi mdi-format-horizontal-align-right", "mdi mdi-format-indent-decrease", "mdi mdi-format-indent-increase", "mdi mdi-format-italic", "mdi mdi-format-line-spacing", "mdi mdi-format-line-style", "mdi mdi-format-line-weight", "mdi mdi-format-list-bulleted", "mdi mdi-format-list-bulleted-type", "mdi mdi-format-list-numbers", "mdi mdi-format-page-break", "mdi mdi-format-paint", "mdi mdi-format-paragraph", "mdi mdi-format-pilcrow", "mdi mdi-format-quote", "mdi mdi-format-rotate-90", "mdi mdi-format-section", "mdi mdi-format-size", "mdi mdi-format-strikethrough", "mdi mdi-format-strikethrough-variant", "mdi mdi-format-subscript", "mdi mdi-format-superscript", "mdi mdi-format-text", "mdi mdi-format-textdirection-l-to-r", "mdi mdi-format-textdirection-r-to-l", "mdi mdi-format-title", "mdi mdi-format-underline", "mdi mdi-format-vertical-align-bottom", "mdi mdi-format-vertical-align-center", "mdi mdi-format-vertical-align-top", "mdi mdi-format-wrap-inline", "mdi mdi-format-wrap-square", "mdi mdi-format-wrap-tight", "mdi mdi-format-wrap-top-bottom", "mdi mdi-forum", "mdi mdi-forward", "mdi mdi-foursquare", "mdi mdi-fridge", "mdi mdi-fridge-filled", "mdi mdi-fridge-filled-bottom", "mdi mdi-fridge-filled-top", "mdi mdi-fullscreen", "mdi mdi-fullscreen-exit", "mdi mdi-function", "mdi mdi-gamepad", "mdi mdi-gamepad-variant", "mdi mdi-garage", "mdi mdi-garage-open", "mdi mdi-gas-cylinder", "mdi mdi-gas-station", "mdi mdi-gate", "mdi mdi-gauge", "mdi mdi-gavel", "mdi mdi-gender-female", "mdi mdi-gender-male", "mdi mdi-gender-male-female", "mdi mdi-gender-transgender", "mdi mdi-ghost", "mdi mdi-gift", "mdi mdi-git", "mdi mdi-github-box", "mdi mdi-github-circle", "mdi mdi-github-face", "mdi mdi-glass-flute", "mdi mdi-glass-mug", "mdi mdi-glass-stange", "mdi mdi-glass-tulip", "mdi mdi-glassdoor", "mdi mdi-glasses", "mdi mdi-gmail", "mdi mdi-gnome", "mdi mdi-gondola", "mdi mdi-google", "mdi mdi-google-cardboard", "mdi mdi-google-chrome", "mdi mdi-google-circles", "mdi mdi-google-circles-communities", "mdi mdi-google-circles-extended", "mdi mdi-google-circles-group", "mdi mdi-google-controller", "mdi mdi-google-controller-off", "mdi mdi-google-drive", "mdi mdi-google-earth", "mdi mdi-google-glass", "mdi mdi-google-keep", "mdi mdi-google-maps", "mdi mdi-google-nearby", "mdi mdi-google-pages", "mdi mdi-google-photos", "mdi mdi-google-physical-web", "mdi mdi-google-play", "mdi mdi-google-plus", "mdi mdi-google-plus-box", "mdi mdi-google-translate", "mdi mdi-google-wallet", "mdi mdi-gradient", "mdi mdi-grease-pencil", "mdi mdi-grid", "mdi mdi-grid-off", "mdi mdi-group", "mdi mdi-guitar-electric", "mdi mdi-guitar-pick", "mdi mdi-guitar-pick-outline", "mdi mdi-hackernews", "mdi mdi-hamburger", "mdi mdi-hand-pointing-right", "mdi mdi-hanger", "mdi mdi-hangouts", "mdi mdi-harddisk", "mdi mdi-headphones", "mdi mdi-headphones-box", "mdi mdi-headphones-settings", "mdi mdi-headset", "mdi mdi-headset-dock", "mdi mdi-headset-off", "mdi mdi-heart", "mdi mdi-heart-box", "mdi mdi-heart-box-outline", "mdi mdi-heart-broken", "mdi mdi-heart-half-outline", "mdi mdi-heart-half-part", "mdi mdi-heart-half-part-outline", "mdi mdi-heart-outline", "mdi mdi-heart-pulse", "mdi mdi-help", "mdi mdi-help-circle", "mdi mdi-help-circle-outline", "mdi mdi-hexagon", "mdi mdi-hexagon-multiple", "mdi mdi-hexagon-outline", "mdi mdi-highway", "mdi mdi-history", "mdi mdi-hololens", "mdi mdi-home", "mdi mdi-home-map-marker", "mdi mdi-home-modern", "mdi mdi-home-outline", "mdi mdi-home-variant", "mdi mdi-hook", "mdi mdi-hook-off", "mdi mdi-hops", "mdi mdi-hospital", "mdi mdi-hospital-building", "mdi mdi-hospital-marker", "mdi mdi-hotel", "mdi mdi-houzz", "mdi mdi-houzz-box", "mdi mdi-human", "mdi mdi-human-child", "mdi mdi-human-female", "mdi mdi-human-greeting", "mdi mdi-human-handsdown", "mdi mdi-human-handsup", "mdi mdi-human-male", "mdi mdi-human-male-female", "mdi mdi-human-pregnant", "mdi mdi-image", "mdi mdi-image-album", "mdi mdi-image-area", "mdi mdi-image-area-close", "mdi mdi-image-broken", "mdi mdi-image-broken-variant", "mdi mdi-image-filter", "mdi mdi-image-filter-black-white", "mdi mdi-image-filter-center-focus", "mdi mdi-image-filter-center-focus-weak", "mdi mdi-image-filter-drama", "mdi mdi-image-filter-frames", "mdi mdi-image-filter-hdr", "mdi mdi-image-filter-none", "mdi mdi-image-filter-tilt-shift", "mdi mdi-image-filter-vintage", "mdi mdi-image-multiple", "mdi mdi-import", "mdi mdi-inbox", "mdi mdi-inbox-arrow-down", "mdi mdi-inbox-arrow-up", "mdi mdi-incognito", "mdi mdi-infinity", "mdi mdi-information", "mdi mdi-information-outline", "mdi mdi-information-variant", "mdi mdi-instagram", "mdi mdi-instapaper", "mdi mdi-internet-explorer", "mdi mdi-invert-colors", "mdi mdi-itunes", "mdi mdi-jeepney", "mdi mdi-jira", "mdi mdi-jsfiddle", "mdi mdi-json", "mdi mdi-keg", "mdi mdi-kettle", "mdi mdi-key", "mdi mdi-key-change", "mdi mdi-key-minus", "mdi mdi-key-plus", "mdi mdi-key-remove", "mdi mdi-key-variant", "mdi mdi-keyboard", "mdi mdi-keyboard-backspace", "mdi mdi-keyboard-caps", "mdi mdi-keyboard-close", "mdi mdi-keyboard-off", "mdi mdi-keyboard-return", "mdi mdi-keyboard-tab", "mdi mdi-keyboard-variant", "mdi mdi-kodi", "mdi mdi-label", "mdi mdi-label-outline", "mdi mdi-lambda", "mdi mdi-lamp", "mdi mdi-lan", "mdi mdi-lan-connect", "mdi mdi-lan-disconnect", "mdi mdi-lan-pending", "mdi mdi-language-c", "mdi mdi-language-cpp", "mdi mdi-language-csharp", "mdi mdi-language-css3", "mdi mdi-language-html5", "mdi mdi-language-javascript", "mdi mdi-language-php", "mdi mdi-language-python", "mdi mdi-language-python-text", "mdi mdi-language-swift", "mdi mdi-language-typescript", "mdi mdi-laptop", "mdi mdi-laptop-chromebook", "mdi mdi-laptop-mac", "mdi mdi-laptop-off", "mdi mdi-laptop-windows", "mdi mdi-lastfm", "mdi mdi-launch", "mdi mdi-layers", "mdi mdi-layers-off", "mdi mdi-lead-pencil", "mdi mdi-leaf", "mdi mdi-led-off", "mdi mdi-led-on", "mdi mdi-led-outline", "mdi mdi-led-variant-off", "mdi mdi-led-variant-on", "mdi mdi-led-variant-outline", "mdi mdi-library", "mdi mdi-library-books", "mdi mdi-library-music", "mdi mdi-library-plus", "mdi mdi-lightbulb", "mdi mdi-lightbulb-on", "mdi mdi-lightbulb-on-outline", "mdi mdi-lightbulb-outline", "mdi mdi-link", "mdi mdi-link-off", "mdi mdi-link-variant", "mdi mdi-link-variant-off", "mdi mdi-linkedin", "mdi mdi-linkedin-box", "mdi mdi-linux", "mdi mdi-lock", "mdi mdi-lock-open", "mdi mdi-lock-open-outline", "mdi mdi-lock-outline", "mdi mdi-lock-pattern", "mdi mdi-lock-plus", "mdi mdi-login", "mdi mdi-login-variant", "mdi mdi-logout", "mdi mdi-logout-variant", "mdi mdi-looks", "mdi mdi-loop", "mdi mdi-loupe", "mdi mdi-lumx", "mdi mdi-magnet", "mdi mdi-magnet-on", "mdi mdi-magnify", "mdi mdi-magnify-minus", "mdi mdi-magnify-minus-outline", "mdi mdi-magnify-plus", "mdi mdi-magnify-plus-outline", "mdi mdi-mail-ru", "mdi mdi-mailbox", "mdi mdi-map", "mdi mdi-map-marker", "mdi mdi-map-marker-circle", "mdi mdi-map-marker-minus", "mdi mdi-map-marker-multiple", "mdi mdi-map-marker-off", "mdi mdi-map-marker-plus", "mdi mdi-map-marker-radius", "mdi mdi-margin", "mdi mdi-markdown", "mdi mdi-marker", "mdi mdi-marker-check", "mdi mdi-martini", "mdi mdi-material-ui", "mdi mdi-math-compass", "mdi mdi-matrix", "mdi mdi-maxcdn", "mdi mdi-medical-bag", "mdi mdi-medium", "mdi mdi-memory", "mdi mdi-menu", "mdi mdi-menu-down", "mdi mdi-menu-down-outline", "mdi mdi-menu-left", "mdi mdi-menu-right", "mdi mdi-menu-up", "mdi mdi-menu-up-outline", "mdi mdi-message", "mdi mdi-message-alert", "mdi mdi-message-bulleted", "mdi mdi-message-bulleted-off", "mdi mdi-message-draw", "mdi mdi-message-image", "mdi mdi-message-outline", "mdi mdi-message-plus", "mdi mdi-message-processing", "mdi mdi-message-reply", "mdi mdi-message-reply-text", "mdi mdi-message-settings", "mdi mdi-message-settings-variant", "mdi mdi-message-text", "mdi mdi-message-text-outline", "mdi mdi-message-video", "mdi mdi-meteor", "mdi mdi-microphone", "mdi mdi-microphone-off", "mdi mdi-microphone-outline", "mdi mdi-microphone-settings", "mdi mdi-microphone-variant", "mdi mdi-microphone-variant-off", "mdi mdi-microscope", "mdi mdi-microsoft", "mdi mdi-minecraft", "mdi mdi-minus", "mdi mdi-minus-box", "mdi mdi-minus-box-outline", "mdi mdi-minus-circle", "mdi mdi-minus-circle-outline", "mdi mdi-minus-network", "mdi mdi-mixcloud", "mdi mdi-monitor", "mdi mdi-monitor-multiple", "mdi mdi-more", "mdi mdi-motorbike", "mdi mdi-mouse", "mdi mdi-mouse-off", "mdi mdi-mouse-variant", "mdi mdi-mouse-variant-off", "mdi mdi-move-resize", "mdi mdi-move-resize-variant", "mdi mdi-movie", "mdi mdi-multiplication", "mdi mdi-multiplication-box", "mdi mdi-music-box", "mdi mdi-music-box-outline", "mdi mdi-music-circle", "mdi mdi-music-note", "mdi mdi-music-note-bluetooth", "mdi mdi-music-note-bluetooth-off", "mdi mdi-music-note-eighth", "mdi mdi-music-note-half", "mdi mdi-music-note-off", "mdi mdi-music-note-quarter", "mdi mdi-music-note-sixteenth", "mdi mdi-music-note-whole", "mdi mdi-nature", "mdi mdi-nature-people", "mdi mdi-navigation", "mdi mdi-near-me", "mdi mdi-needle", "mdi mdi-nest-protect", "mdi mdi-nest-thermostat", "mdi mdi-network", "mdi mdi-network-download", "mdi mdi-network-question", "mdi mdi-network-upload", "mdi mdi-new-box", "mdi mdi-newspaper", "mdi mdi-nfc", "mdi mdi-nfc-tap", "mdi mdi-nfc-variant", "mdi mdi-nodejs", "mdi mdi-note", "mdi mdi-note-multiple", "mdi mdi-note-multiple-outline", "mdi mdi-note-outline", "mdi mdi-note-plus", "mdi mdi-note-plus-outline", "mdi mdi-note-text", "mdi mdi-notification-clear-all", "mdi mdi-npm", "mdi mdi-nuke", "mdi mdi-numeric", "mdi mdi-numeric-0-box", "mdi mdi-numeric-0-box-multiple-outline", "mdi mdi-numeric-0-box-outline", "mdi mdi-numeric-1-box", "mdi mdi-numeric-1-box-multiple-outline", "mdi mdi-numeric-1-box-outline", "mdi mdi-numeric-2-box", "mdi mdi-numeric-2-box-multiple-outline", "mdi mdi-numeric-2-box-outline", "mdi mdi-numeric-3-box", "mdi mdi-numeric-3-box-multiple-outline", "mdi mdi-numeric-3-box-outline", "mdi mdi-numeric-4-box", "mdi mdi-numeric-4-box-multiple-outline", "mdi mdi-numeric-4-box-outline", "mdi mdi-numeric-5-box", "mdi mdi-numeric-5-box-multiple-outline", "mdi mdi-numeric-5-box-outline", "mdi mdi-numeric-6-box", "mdi mdi-numeric-6-box-multiple-outline", "mdi mdi-numeric-6-box-outline", "mdi mdi-numeric-7-box", "mdi mdi-numeric-7-box-multiple-outline", "mdi mdi-numeric-7-box-outline", "mdi mdi-numeric-8-box", "mdi mdi-numeric-8-box-multiple-outline", "mdi mdi-numeric-8-box-outline", "mdi mdi-numeric-9-box", "mdi mdi-numeric-9-box-multiple-outline", "mdi mdi-numeric-9-box-outline", "mdi mdi-numeric-9-plus-box", "mdi mdi-numeric-9-plus-box-multiple-outline", "mdi mdi-numeric-9-plus-box-outline", "mdi mdi-nut", "mdi mdi-nutrition", "mdi mdi-oar", "mdi mdi-octagon", "mdi mdi-octagon-outline", "mdi mdi-octagram", "mdi mdi-odnoklassniki", "mdi mdi-office", "mdi mdi-oil", "mdi mdi-oil-temperature", "mdi mdi-omega", "mdi mdi-onedrive", "mdi mdi-opacity", "mdi mdi-open-in-app", "mdi mdi-open-in-new", "mdi mdi-openid", "mdi mdi-opera", "mdi mdi-ornament", "mdi mdi-ornament-variant", "mdi mdi-owl", "mdi mdi-package", "mdi mdi-package-down", "mdi mdi-package-up", "mdi mdi-package-variant", "mdi mdi-package-variant-closed", "mdi mdi-page-first", "mdi mdi-page-last", "mdi mdi-page-layout-body", "mdi mdi-page-layout-footer", "mdi mdi-page-layout-header", "mdi mdi-page-layout-sidebar-left", "mdi mdi-page-layout-sidebar-right", "mdi mdi-palette", "mdi mdi-palette-advanced", "mdi mdi-panda", "mdi mdi-pandora", "mdi mdi-panorama", "mdi mdi-panorama-fisheye", "mdi mdi-panorama-horizontal", "mdi mdi-panorama-vertical", "mdi mdi-panorama-wide-angle", "mdi mdi-paper-cut-vertical", "mdi mdi-paperclip", "mdi mdi-parking", "mdi mdi-pause", "mdi mdi-pause-circle", "mdi mdi-pause-circle-outline", "mdi mdi-pause-octagon", "mdi mdi-pause-octagon-outline", "mdi mdi-paw", "mdi mdi-paw-off", "mdi mdi-pen", "mdi mdi-pencil", "mdi mdi-pencil-box", "mdi mdi-pencil-box-outline", "mdi mdi-pencil-circle", "mdi mdi-pencil-lock", "mdi mdi-pencil-off", "mdi mdi-pentagon", "mdi mdi-pentagon-outline", "mdi mdi-percent", "mdi mdi-pharmacy", "mdi mdi-phone", "mdi mdi-phone-bluetooth", "mdi mdi-phone-classic", "mdi mdi-phone-forward", "mdi mdi-phone-hangup", "mdi mdi-phone-in-talk", "mdi mdi-phone-incoming", "mdi mdi-phone-locked", "mdi mdi-phone-log", "mdi mdi-phone-minus", "mdi mdi-phone-missed", "mdi mdi-phone-outgoing", "mdi mdi-phone-paused", "mdi mdi-phone-plus", "mdi mdi-phone-settings", "mdi mdi-phone-voip", "mdi mdi-pi", "mdi mdi-pi-box", "mdi mdi-piano", "mdi mdi-pig", "mdi mdi-pill", "mdi mdi-pillar", "mdi mdi-pin", "mdi mdi-pin-off", "mdi mdi-pine-tree", "mdi mdi-pine-tree-box", "mdi mdi-pinterest", "mdi mdi-pinterest-box", "mdi mdi-pistol", "mdi mdi-pizza", "mdi mdi-plane-shield", "mdi mdi-play", "mdi mdi-play-box-outline", "mdi mdi-play-circle", "mdi mdi-play-circle-outline", "mdi mdi-play-pause", "mdi mdi-play-protected-content", "mdi mdi-playlist-check", "mdi mdi-playlist-minus", "mdi mdi-playlist-play", "mdi mdi-playlist-plus", "mdi mdi-playlist-remove", "mdi mdi-playstation", "mdi mdi-plex", "mdi mdi-plus", "mdi mdi-plus-box", "mdi mdi-plus-box-outline", "mdi mdi-plus-circle", "mdi mdi-plus-circle-multiple-outline", "mdi mdi-plus-circle-outline", "mdi mdi-plus-network", "mdi mdi-plus-one", "mdi mdi-plus-outline", "mdi mdi-pocket", "mdi mdi-pokeball", "mdi mdi-polaroid", "mdi mdi-poll", "mdi mdi-poll-box", "mdi mdi-polymer", "mdi mdi-pool", "mdi mdi-popcorn", "mdi mdi-pot", "mdi mdi-pot-mix", "mdi mdi-pound", "mdi mdi-pound-box", "mdi mdi-power", "mdi mdi-power-plug", "mdi mdi-power-plug-off", "mdi mdi-power-settings", "mdi mdi-power-socket", "mdi mdi-prescription", "mdi mdi-presentation", "mdi mdi-presentation-play", "mdi mdi-printer", "mdi mdi-printer-3d", "mdi mdi-printer-alert", "mdi mdi-printer-settings", "mdi mdi-priority-high", "mdi mdi-priority-low", "mdi mdi-professional-hexagon", "mdi mdi-projector", "mdi mdi-projector-screen", "mdi mdi-publish", "mdi mdi-pulse", "mdi mdi-puzzle", "mdi mdi-qqchat", "mdi mdi-qrcode", "mdi mdi-qrcode-scan", "mdi mdi-quadcopter", "mdi mdi-quality-high", "mdi mdi-quicktime", "mdi mdi-radar", "mdi mdi-radiator", "mdi mdi-radio", "mdi mdi-radio-handheld", "mdi mdi-radio-tower", "mdi mdi-radioactive", "mdi mdi-radiobox-blank", "mdi mdi-radiobox-marked", "mdi mdi-raspberrypi", "mdi mdi-ray-end", "mdi mdi-ray-end-arrow", "mdi mdi-ray-start", "mdi mdi-ray-start-arrow", "mdi mdi-ray-start-end", "mdi mdi-ray-vertex", "mdi mdi-rdio", "mdi mdi-react", "mdi mdi-read", "mdi mdi-readability", "mdi mdi-receipt", "mdi mdi-record", "mdi mdi-record-rec", "mdi mdi-recycle", "mdi mdi-reddit", "mdi mdi-redo", "mdi mdi-redo-variant", "mdi mdi-refresh", "mdi mdi-regex", "mdi mdi-relative-scale", "mdi mdi-reload", "mdi mdi-remote", "mdi mdi-rename-box", "mdi mdi-reorder-horizontal", "mdi mdi-reorder-vertical", "mdi mdi-repeat", "mdi mdi-repeat-off", "mdi mdi-repeat-once", "mdi mdi-replay", "mdi mdi-reply", "mdi mdi-reply-all", "mdi mdi-reproduction", "mdi mdi-resize-bottom-right", "mdi mdi-responsive", "mdi mdi-restart", "mdi mdi-restore", "mdi mdi-rewind", "mdi mdi-rewind-outline", "mdi mdi-rhombus", "mdi mdi-rhombus-outline", "mdi mdi-ribbon", "mdi mdi-road", "mdi mdi-road-variant", "mdi mdi-robot", "mdi mdi-rocket", "mdi mdi-roomba", "mdi mdi-rotate-3d", "mdi mdi-rotate-left", "mdi mdi-rotate-left-variant", "mdi mdi-rotate-right", "mdi mdi-rotate-right-variant", "mdi mdi-rounded-corner", "mdi mdi-router-wireless", "mdi mdi-routes", "mdi mdi-rowing", "mdi mdi-rss", "mdi mdi-rss-box", "mdi mdi-ruler", "mdi mdi-run", "mdi mdi-run-fast", "mdi mdi-sale", "mdi mdi-satellite", "mdi mdi-satellite-variant", "mdi mdi-saxophone", "mdi mdi-scale", "mdi mdi-scale-balance", "mdi mdi-scale-bathroom", "mdi mdi-scanner", "mdi mdi-school", "mdi mdi-screen-rotation", "mdi mdi-screen-rotation-lock", "mdi mdi-screwdriver", "mdi mdi-script", "mdi mdi-sd", "mdi mdi-seal", "mdi mdi-search-web", "mdi mdi-seat-flat", "mdi mdi-seat-flat-angled", "mdi mdi-seat-individual-suite", "mdi mdi-seat-legroom-extra", "mdi mdi-seat-legroom-normal", "mdi mdi-seat-legroom-reduced", "mdi mdi-seat-recline-extra", "mdi mdi-seat-recline-normal", "mdi mdi-security", "mdi mdi-security-home", "mdi mdi-security-network", "mdi mdi-select", "mdi mdi-select-all", "mdi mdi-select-inverse", "mdi mdi-select-off", "mdi mdi-selection", "mdi mdi-send", "mdi mdi-serial-port", "mdi mdi-server", "mdi mdi-server-minus", "mdi mdi-server-network", "mdi mdi-server-network-off", "mdi mdi-server-off", "mdi mdi-server-plus", "mdi mdi-server-remove", "mdi mdi-server-security", "mdi mdi-settings", "mdi mdi-settings-box", "mdi mdi-shape-circle-plus", "mdi mdi-shape-plus", "mdi mdi-shape-polygon-plus", "mdi mdi-shape-rectangle-plus", "mdi mdi-shape-square-plus", "mdi mdi-share", "mdi mdi-share-variant", "mdi mdi-shield", "mdi mdi-shield-outline", "mdi mdi-shopping", "mdi mdi-shopping-music", "mdi mdi-shovel", "mdi mdi-shovel-off", "mdi mdi-shredder", "mdi mdi-shuffle", "mdi mdi-shuffle-disabled", "mdi mdi-shuffle-variant", "mdi mdi-sigma", "mdi mdi-sigma-lower", "mdi mdi-sign-caution", "mdi mdi-signal", "mdi mdi-signal-2g", "mdi mdi-signal-3g", "mdi mdi-signal-4g", "mdi mdi-signal-hspa", "mdi mdi-signal-hspa-plus", "mdi mdi-signal-variant", "mdi mdi-silverware", "mdi mdi-silverware-fork", "mdi mdi-silverware-spoon", "mdi mdi-silverware-variant", "mdi mdi-sim", "mdi mdi-sim-alert", "mdi mdi-sim-off", "mdi mdi-sitemap", "mdi mdi-skip-backward", "mdi mdi-skip-forward", "mdi mdi-skip-next", "mdi mdi-skip-next-circle", "mdi mdi-skip-next-circle-outline", "mdi mdi-skip-previous", "mdi mdi-skip-previous-circle", "mdi mdi-skip-previous-circle-outline", "mdi mdi-skull", "mdi mdi-skype", "mdi mdi-skype-business", "mdi mdi-slack", "mdi mdi-sleep", "mdi mdi-sleep-off", "mdi mdi-smoking", "mdi mdi-smoking-off", "mdi mdi-snapchat", "mdi mdi-snowflake", "mdi mdi-snowman", "mdi mdi-soccer", "mdi mdi-sofa", "mdi mdi-solid", "mdi mdi-sort", "mdi mdi-sort-alphabetical", "mdi mdi-sort-ascending", "mdi mdi-sort-descending", "mdi mdi-sort-numeric", "mdi mdi-sort-variant", "mdi mdi-soundcloud", "mdi mdi-source-branch", "mdi mdi-source-commit", "mdi mdi-source-commit-end", "mdi mdi-source-commit-end-local", "mdi mdi-source-commit-local", "mdi mdi-source-commit-next-local", "mdi mdi-source-commit-start", "mdi mdi-source-commit-start-next-local", "mdi mdi-source-fork", "mdi mdi-source-merge", "mdi mdi-source-pull", "mdi mdi-speaker", "mdi mdi-speaker-off", "mdi mdi-speaker-wireless", "mdi mdi-speedometer", "mdi mdi-spellcheck", "mdi mdi-spotify", "mdi mdi-spotlight", "mdi mdi-spotlight-beam", "mdi mdi-spray", "mdi mdi-square-inc", "mdi mdi-square-inc-cash", "mdi mdi-stackexchange", "mdi mdi-stackoverflow", "mdi mdi-stadium", "mdi mdi-stairs", "mdi mdi-star", "mdi mdi-star-circle", "mdi mdi-star-half", "mdi mdi-star-off", "mdi mdi-star-outline", "mdi mdi-steam", "mdi mdi-steering", "mdi mdi-step-backward", "mdi mdi-step-backward-2", "mdi mdi-step-forward", "mdi mdi-step-forward-2", "mdi mdi-stethoscope", "mdi mdi-sticker", "mdi mdi-stocking", "mdi mdi-stop", "mdi mdi-stop-circle", "mdi mdi-stop-circle-outline", "mdi mdi-store", "mdi mdi-store-24-hour", "mdi mdi-stove", "mdi mdi-subdirectory-arrow-left", "mdi mdi-subdirectory-arrow-right", "mdi mdi-subway", "mdi mdi-subway-variant", "mdi mdi-sunglasses", "mdi mdi-surround-sound", "mdi mdi-svg", "mdi mdi-swap-horizontal", "mdi mdi-swap-vertical", "mdi mdi-swim", "mdi mdi-switch", "mdi mdi-sword", "mdi mdi-sync", "mdi mdi-sync-alert", "mdi mdi-sync-off", "mdi mdi-tab", "mdi mdi-tab-unselected", "mdi mdi-table", "mdi mdi-table-column-plus-after", "mdi mdi-table-column-plus-before", "mdi mdi-table-column-remove", "mdi mdi-table-column-width", "mdi mdi-table-edit", "mdi mdi-table-large", "mdi mdi-table-row-height", "mdi mdi-table-row-plus-after", "mdi mdi-table-row-plus-before", "mdi mdi-table-row-remove", "mdi mdi-tablet", "mdi mdi-tablet-android", "mdi mdi-tablet-ipad", "mdi mdi-tag", "mdi mdi-tag-faces", "mdi mdi-tag-heart", "mdi mdi-tag-multiple", "mdi mdi-tag-outline", "mdi mdi-tag-plus", "mdi mdi-tag-remove", "mdi mdi-tag-text-outline", "mdi mdi-target", "mdi mdi-taxi", "mdi mdi-teamviewer", "mdi mdi-telegram", "mdi mdi-television", "mdi mdi-television-guide", "mdi mdi-temperature-celsius", "mdi mdi-temperature-fahrenheit", "mdi mdi-temperature-kelvin", "mdi mdi-tennis", "mdi mdi-tent", "mdi mdi-terrain", "mdi mdi-test-tube", "mdi mdi-text-shadow", "mdi mdi-text-to-speech", "mdi mdi-text-to-speech-off", "mdi mdi-textbox", "mdi mdi-texture", "mdi mdi-theater", "mdi mdi-theme-light-dark", "mdi mdi-thermometer", "mdi mdi-thermometer-lines", "mdi mdi-thumb-down", "mdi mdi-thumb-down-outline", "mdi mdi-thumb-up", "mdi mdi-thumb-up-outline", "mdi mdi-thumbs-up-down", "mdi mdi-ticket", "mdi mdi-ticket-account", "mdi mdi-ticket-confirmation", "mdi mdi-ticket-percent", "mdi mdi-tie", "mdi mdi-tilde", "mdi mdi-timelapse", "mdi mdi-timer", "mdi mdi-timer-10", "mdi mdi-timer-3", "mdi mdi-timer-off", "mdi mdi-timer-sand", "mdi mdi-timer-sand-empty", "mdi mdi-timetable", "mdi mdi-toggle-switch", "mdi mdi-toggle-switch-off", "mdi mdi-tooltip", "mdi mdi-tooltip-edit", "mdi mdi-tooltip-image", "mdi mdi-tooltip-outline", "mdi mdi-tooltip-outline-plus", "mdi mdi-tooltip-text", "mdi mdi-tooth", "mdi mdi-tor", "mdi mdi-tower-beach", "mdi mdi-tower-fire", "mdi mdi-traffic-light", "mdi mdi-train", "mdi mdi-tram", "mdi mdi-transcribe", "mdi mdi-transcribe-close", "mdi mdi-transfer", "mdi mdi-transit-transfer", "mdi mdi-translate", "mdi mdi-treasure-chest", "mdi mdi-tree", "mdi mdi-trello", "mdi mdi-trending-down", "mdi mdi-trending-neutral", "mdi mdi-trending-up", "mdi mdi-triangle", "mdi mdi-triangle-outline", "mdi mdi-trophy", "mdi mdi-trophy-award", "mdi mdi-trophy-outline", "mdi mdi-trophy-variant", "mdi mdi-trophy-variant-outline", "mdi mdi-truck", "mdi mdi-truck-delivery", "mdi mdi-truck-trailer", "mdi mdi-tshirt-crew", "mdi mdi-tshirt-v", "mdi mdi-tumblr", "mdi mdi-tumblr-reblog", "mdi mdi-tune", "mdi mdi-tune-vertical", "mdi mdi-twitch", "mdi mdi-twitter", "mdi mdi-twitter-box", "mdi mdi-twitter-circle", "mdi mdi-twitter-retweet", "mdi mdi-ubuntu", "mdi mdi-umbraco", "mdi mdi-umbrella", "mdi mdi-umbrella-outline", "mdi mdi-undo", "mdi mdi-undo-variant", "mdi mdi-unfold-less", "mdi mdi-unfold-more", "mdi mdi-ungroup", "mdi mdi-unity", "mdi mdi-untappd", "mdi mdi-update", "mdi mdi-upload", "mdi mdi-usb", "mdi mdi-vector-arrange-above", "mdi mdi-vector-arrange-below", "mdi mdi-vector-circle", "mdi mdi-vector-circle-variant", "mdi mdi-vector-combine", "mdi mdi-vector-curve", "mdi mdi-vector-difference", "mdi mdi-vector-difference-ab", "mdi mdi-vector-difference-ba", "mdi mdi-vector-intersection", "mdi mdi-vector-line", "mdi mdi-vector-point", "mdi mdi-vector-polygon", "mdi mdi-vector-polyline", "mdi mdi-vector-rectangle", "mdi mdi-vector-selection", "mdi mdi-vector-square", "mdi mdi-vector-triangle", "mdi mdi-vector-union", "mdi mdi-verified", "mdi mdi-vibrate", "mdi mdi-video", "mdi mdi-video-off", "mdi mdi-video-switch", "mdi mdi-view-agenda", "mdi mdi-view-array", "mdi mdi-view-carousel", "mdi mdi-view-column", "mdi mdi-view-dashboard", "mdi mdi-view-day", "mdi mdi-view-grid", "mdi mdi-view-headline", "mdi mdi-view-list", "mdi mdi-view-module", "mdi mdi-view-parallel", "mdi mdi-view-quilt", "mdi mdi-view-sequential", "mdi mdi-view-stream", "mdi mdi-view-week", "mdi mdi-vimeo", "mdi mdi-vine", "mdi mdi-violin", "mdi mdi-visualstudio", "mdi mdi-vk", "mdi mdi-vk-box", "mdi mdi-vk-circle", "mdi mdi-vlc", "mdi mdi-voice", "mdi mdi-voicemail", "mdi mdi-volume-high", "mdi mdi-volume-low", "mdi mdi-volume-medium", "mdi mdi-volume-off", "mdi mdi-vpn", "mdi mdi-walk", "mdi mdi-wallet", "mdi mdi-wallet-giftcard", "mdi mdi-wallet-membership", "mdi mdi-wallet-travel", "mdi mdi-wan", "mdi mdi-washing-machine", "mdi mdi-watch", "mdi mdi-watch-export", "mdi mdi-watch-import", "mdi mdi-watch-vibrate", "mdi mdi-water", "mdi mdi-water-off", "mdi mdi-water-percent", "mdi mdi-water-pump", "mdi mdi-watermark", "mdi mdi-weather-cloudy", "mdi mdi-weather-fog", "mdi mdi-weather-hail", "mdi mdi-weather-lightning", "mdi mdi-weather-lightning-rainy", "mdi mdi-weather-night", "mdi mdi-weather-partlycloudy", "mdi mdi-weather-pouring", "mdi mdi-weather-rainy", "mdi mdi-weather-snowy", "mdi mdi-weather-snowy-rainy", "mdi mdi-weather-sunny", "mdi mdi-weather-sunset", "mdi mdi-weather-sunset-down", "mdi mdi-weather-sunset-up", "mdi mdi-weather-windy", "mdi mdi-weather-windy-variant", "mdi mdi-web", "mdi mdi-webcam", "mdi mdi-webhook", "mdi mdi-webpack", "mdi mdi-wechat", "mdi mdi-weight", "mdi mdi-weight-kilogram", "mdi mdi-whatsapp", "mdi mdi-wheelchair-accessibility", "mdi mdi-white-balance-auto", "mdi mdi-white-balance-incandescent", "mdi mdi-white-balance-iridescent", "mdi mdi-white-balance-sunny", "mdi mdi-widgets", "mdi mdi-wifi", "mdi mdi-wifi-off", "mdi mdi-wii", "mdi mdi-wiiu", "mdi mdi-wikipedia", "mdi mdi-window-close", "mdi mdi-window-closed", "mdi mdi-window-maximize", "mdi mdi-window-minimize", "mdi mdi-window-open", "mdi mdi-window-restore", "mdi mdi-windows", "mdi mdi-wordpress", "mdi mdi-worker", "mdi mdi-wrap", "mdi mdi-wrench", "mdi mdi-wunderlist", "mdi mdi-xaml", "mdi mdi-xbox", "mdi mdi-xbox-controller", "mdi mdi-xbox-controller-off", "mdi mdi-xda", "mdi mdi-xing", "mdi mdi-xing-box", "mdi mdi-xing-circle", "mdi mdi-xml", "mdi mdi-yeast", "mdi mdi-yelp", "mdi mdi-yin-yang", "mdi mdi-youtube-play", "mdi mdi-zip-box");
  return $arr_icon;
}
function icon_material_code()
{
  $arr_icon = array("f002", "f003", "f004", "f005", "f006", "f007", "f5d2", "f008", "f009", "f00a", "f6bb", "f00b", "f00c", "f00d", "f00e", "f5d3", "f00f", "f010", "f011", "f012", "f013", "f014", "f015", "f016", "f630", "f631", "f017", "f018", "f019", "f01a", "f01b", "f01c", "f01d", "f5d4", "f01e", "f5d5", "f01f", "f020", "f021", "f022", "f023", "f024", "f68d", "f025", "f026", "f027", "f028", "f5d6", "f029", "f6bc", "f02a", "f6bd", "f02b", "f02c", "f5d7", "f02d", "f02e", "f02f", "f030", "f031", "f032", "f033", "f034", "f6b1", "f6be", "f5d8", "f035", "f036", "f037", "f632", "f633", "f634", "f635", "f636", "f038", "f039", "f614", "f03b", "f03c", "f03d", "f03e", "f03f", "f040", "f041", "f042", "f043", "f615", "f044", "f045", "f046", "f047", "f048", "f049", "f6bf", "f04a", "f04b", "f616", "f04c", "f04d", "f04e", "f04f", "f050", "f051", "f6c0", "f052", "f053", "f054", "f055", "f056", "f057", "f058", "f6c1", "f059", "f05a", "f05b", "f05c", "f05d", "f05e", "f05f", "f060", "f061", "f6c2", "f062", "f063", "f064", "f6c3", "f065", "f066", "f067", "f068", "f069", "f06a", "f06b", "f06c", "f68e", "f06d", "f06e", "f06f", "f674", "f070", "f071", "f072", "f073", "f074", "f075", "f076", "f077", "f078", "f079", "f07a", "f07b", "f07c", "f07d", "f07e", "f07f", "f080", "f081", "f082", "f083", "f084", "f085", "f086", "f087", "f088", "f089", "f08a", "f08b", "f08c", "f08d", "f08e", "f08f", "f090", "f091", "f092", "f68f", "f097", "f098", "f099", "f09a", "f09b", "f09c", "f09d", "f09e", "f09f", "f0a0", "f0a1", "f0a2", "f0a3", "f0a4", "f0a5", "f0a6", "f0a7", "f0a8", "f0a9", "f0aa", "f0ab", "f0ac", "f0ad", "f0ae", "f0af", "f0b0", "f0b1", "f0b2", "f0b3", "f0b4", "f0b5", "f0b6", "f0b7", "f0b8", "f690", "f6c4", "f0b9", "f0ba", "f5d9", "f0bb", "f0bc", "f0bd", "f5da", "f0be", "f5db", "f0bf", "f0c0", "f0c1", "f0c2", "f0c3", "f0c5", "f0c4", "f0c6", "f5dc", "f6c5", "f0c7", "f0c8", "f0c9", "f0ca", "f0cb", "f0cc", "f0cd", "f0ce", "f0cf", "f0d0", "f0d1", "f0d2", "f677", "f617", "f0d3", "f0d4", "f0d5", "f637", "f618", "f0d6", "f0d7", "f0d8", "f0d9", "f0da", "f0db", "f0dc", "f0dd", "f0de", "f0df", "f0e0", "f0e1", "f0e2", "f0e3", "f619", "f0e4", "f0e5", "f0e6", "f5dd", "f5de", "f0e7", "f0e8", "f0e9", "f0ea", "f0eb", "f0ec", "f0ed", "f0ee", "f0ef", "f0f0", "f0f1", "f0f2", "f0f3", "f691", "f678", "f0f4", "f0f5", "f0f6", "f0f7", "f0f8", "f0f9", "f0fa", "f0fb", "f0fc", "f0fd", "f0fe", "f0ff", "f100", "f692", "f101", "f102", "f103", "f104", "f5df", "f105", "f106", "f107", "f108", "f109", "f5e2", "f10a", "f10b", "f10c", "f10d", "f10e", "f638", "f639", "f63a", "f6c6", "f10f", "f110", "f66b", "f111", "f112", "f113", "f114", "f115", "f116", "f117", "f118", "f119", "f11a", "f11b", "f11c", "f11d", "f11e", "f11f", "f120", "f121", "f122", "f123", "f124", "f125", "f126", "f127", "f128", "f5e3", "f66c", "f129", "f12a", "f12b", "f66d", "f66e", "f12c", "f12d", "f5e0", "f5e1", "f12e", "f12f", "f130", "f131", "f132", "f133", "f134", "f135", "f136", "f63b", "f63c", "f137", "f138", "f63d", "f63e", "f139", "f13a", "f13b", "f13c", "f13d", "f13e", "f13f", "f140", "f141", "f142", "f143", "f61a", "f144", "f145", "f146", "f147", "f148", "f149", "f14a", "f14b", "f14c", "f6c7", "f14d", "f14e", "f14f", "f150", "f5ce", "f151", "f152", "f153", "f154", "f155", "f156", "f157", "f158", "f159", "f15a", "f15b", "f15c", "f15d", "f6c8", "f15e", "f15f", "f160", "f161", "f162", "f163", "f164", "f165", "f166", "f63f", "f167", "f168", "f169", "f16a", "f16b", "f16c", "f16d", "f16e", "f16f", "f170", "f171", "f172", "f173", "f174", "f693", "f175", "f176", "f6c9", "f177", "f178", "f694", "f640", "f179", "f17a", "f17b", "f17c", "f17d", "f17e", "f17f", "f180", "f181", "f182", "f183", "f184", "f185", "f186", "f187", "f188", "f189", "f18a", "f18b", "f18c", "f18d", "f18e", "f6ca", "f18f", "f190", "f191", "f192", "f193", "f194", "f61b", "f195", "f196", "f197", "f198", "f5e6", "f199", "f19a", "f1c9", "f19b", "f19c", "f5e4", "f675", "f19d", "f19e", "f19f", "f1a0", "f1a1", "f695", "f1a2", "f1a3", "f1a4", "f1a5", "f1a6", "f1a7", "f1a8", "f1a9", "f1aa", "f5e5", "f1ab", "f1ac", "f1ad", "f1ae", "f1af", "f1b0", "f1b1", "f1b2", "f1b3", "f679", "f1b4", "f1b5", "f1b6", "f1b7", "f5e7", "f1b8", "f1b9", "f1ba", "f1bb", "f1bc", "f1bd", "f1be", "f1bf", "f1c0", "f682", "f6cb", "f5e8", "f5e9", "f1c1", "f1c2", "f1c3", "f1c4", "f1c5", "f1c6", "f696", "f1c7", "f61c", "f1c8", "f1ca", "f1cb", "f1cc", "f1cd", "f1ce", "f1cf", "f5ea", "f5eb", "f5ec", "f5ed", "f61d", "f1d0", "f641", "f66f", "f5ee", "f1d1", "f1d2", "f1d3", "f1d4", "f1d5", "f683", "f1d6", "f697", "f698", "f6b2", "f1d7", "f1d8", "f1d9", "f699", "f1da", "f1db", "f1dc", "f1dd", "f1de", "f1df", "f1e0", "f1e1", "f1e2", "f1e3", "f1e4", "f1e5", "f1e6", "f1e7", "f6cc", "f6cd", "f1e8", "f1e9", "f1ea", "f1eb", "f1ec", "f1ed", "f1ee", "f6ce", "f1ef", "f5ef", "f1f0", "f1f1", "f5f0", "f6b3", "f1f2", "f1f3", "f69a", "f1f4", "f69b", "f1f5", "f1f6", "f1f7", "f1f8", "f1f9", "f1fa", "f1fb", "f1fc", "f1fd", "f1fe", "f642", "f1ff", "f200", "f201", "f202", "f203", "f5f1", "f204", "f205", "f206", "f207", "f208", "f209", "f6cf", "f6d0", "f20a", "f20b", "f643", "f644", "f20c", "f20d", "f20e", "f20f", "f210", "f211", "f6d1", "f212", "f6d2", "f213", "f214", "f215", "f216", "f217", "f218", "f219", "f21a", "f21b", "f21c", "f21d", "f21e", "f613", "f21f", "f220", "f221", "f222", "f223", "f224", "f225", "f226", "f227", "f228", "f229", "f670", "f22a", "f645", "f22b", "f22c", "f22d", "f22e", "f22f", "f230", "f231", "f232", "f233", "f234", "f235", "f236", "f6d3", "f237", "f238", "f239", "f23a", "f23b", "f23c", "f23d", "f23e", "f23f", "f240", "f241", "f242", "f243", "f6d4", "f67a", "f244", "f245", "f093", "f094", "f095", "f096", "f246", "f247", "f248", "f249", "f24a", "f24b", "f24c", "f24d", "f24e", "f24f", "f250", "f251", "f252", "f253", "f254", "f255", "f256", "f257", "f258", "f69c", "f259", "f03a", "f25a", "f25b", "f5f2", "f5f3", "f25c", "f25d", "f25e", "f25f", "f260", "f261", "f262", "f263", "f646", "f264", "f265", "f266", "f69d", "f267", "f268", "f269", "f26a", "f6d5", "f26b", "f26c", "f26d", "f26e", "f26f", "f270", "f271", "f272", "f273", "f274", "f61e", "f61f", "f620", "f275", "f276", "f277", "f278", "f5c8", "f5c9", "f279", "f27a", "f27b", "f6d6", "f27c", "f27d", "f6d7", "f27e", "f6a9", "f69e", "f27f", "f280", "f281", "f282", "f283", "f284", "f285", "f286", "f5f4", "f287", "f621", "f622", "f623", "f288", "f289", "f28a", "f28b", "f28c", "f28d", "f28e", "f28f", "f290", "f291", "f292", "f293", "f294", "f295", "f296", "f297", "f6d8", "f6d9", "f647", "f298", "f299", "f29a", "f29b", "f29c", "f29d", "f29e", "f29f", "f2a0", "f2a1", "f2a2", "f2a3", "f2a4", "f6da", "f2a5", "f2a6", "f2a7", "f2a8", "f2a9", "f2aa", "f2ab", "f2ac", "f685", "f2ad", "f2ae", "f2af", "f2b0", "f2b1", "f2b2", "f2b3", "f2b4", "f2b5", "f2b6", "f2b7", "f2b8", "f6db", "f5f5", "f2b9", "f2ba", "f6dc", "f2bb", "f2bc", "f2bd", "f2be", "f2bf", "f2c0", "f69f", "f648", "f2c1", "f2c2", "f2c3", "f2c4", "f2c5", "f2c6", "f624", "f684", "f2c7", "f2c8", "f2c9", "f2ca", "f2cb", "f2cc", "f2cd", "f2ce", "f2cf", "f2d0", "f2d1", "f2d2", "f2d3", "f2d4", "f6dd", "f6de", "f6df", "f2d5", "f5f6", "f2d6", "f2d7", "f625", "f2d8", "f6e0", "f2d9", "f5f7", "f2da", "f2db", "f2dc", "f5f8", "f2dd", "f6a0", "f2de", "f6e1", "f6e2", "f2df", "f2e0", "f2e1", "f2e2", "f2e3", "f2e4", "f2e5", "f2e6", "f2e7", "f649", "f64a", "f64b", "f64c", "f64d", "f2e8", "f5cf", "f2e9", "f2ea", "f2eb", "f2ec", "f2ed", "f2ee", "f2ef", "f2f0", "f2f1", "f2f2", "f2f3", "f2f4", "f2f5", "f2f6", "f2f7", "f2f8", "f2f9", "f2fa", "f686", "f2fb", "f3d1", "f5f9", "f6e3", "f2fc", "f2fd", "f64e", "f2fe", "f2ff", "f300", "f301", "f676", "f302", "f303", "f304", "f626", "f305", "f5fa", "f306", "f307", "f308", "f309", "f30a", "f30b", "f30c", "f30d", "f30e", "f30f", "f310", "f311", "f312", "f313", "f314", "f315", "f316", "f627", "f6b4", "f317", "f318", "f319", "f31a", "f671", "f672", "f31b", "f31c", "f31d", "f31e", "f31f", "f320", "f321", "f6e4", "f6e5", "f322", "f323", "f324", "f6e6", "f325", "f326", "f327", "f328", "f329", "f64f", "f32a", "f32b", "f32c", "f32d", "f32e", "f32f", "f330", "f331", "f332", "f333", "f334", "f335", "f6e7", "f6e8", "f336", "f337", "f338", "f339", "f33a", "f33b", "f33c", "f33d", "f33e", "f33f", "f340", "f341", "f6e9", "f5fb", "f342", "f5fc", "f343", "f5fd", "f344", "f6ea", "f345", "f346", "f347", "f348", "f349", "f34a", "f6eb", "f34b", "f6ec", "f34c", "f6ed", "f34d", "f34e", "f34f", "f650", "f350", "f351", "f651", "f352", "f353", "f354", "f652", "f355", "f356", "f357", "f358", "f628", "f359", "f6ee", "f35a", "f35b", "f35c", "f35d", "f6b5", "f35e", "f35f", "f360", "f6b6", "f361", "f362", "f6a1", "f6a2", "f363", "f364", "f365", "f653", "f366", "f367", "f368", "f6ef", "f6f0", "f369", "f36a", "f36b", "f629", "f36c", "f36d", "f36e", "f36f", "f370", "f371", "f654", "f372", "f373", "f374", "f375", "f6f1", "f376", "f377", "f378", "f62a", "f379", "f37a", "f37b", "f37c", "f37d", "f37e", "f37f", "f380", "f655", "f656", "f381", "f382", "f383", "f384", "f385", "f386", "f387", "f5fe", "f5ff", "f388", "f389", "f38a", "f38b", "f38c", "f38d", "f38e", "f38f", "f390", "f5cd", "f391", "f392", "f393", "f6f2", "f6f3", "f6f4", "f6f5", "f394", "f395", "f396", "f397", "f398", "f399", "f39a", "f6b7", "f6b8", "f39b", "f39c", "f39d", "f39e", "f39f", "f6f6", "f6a3", "f3a0", "f3a1", "f3a2", "f3a3", "f3a4", "f3a5", "f3a6", "f3a7", "f3a8", "f3a9", "f3aa", "f3ab", "f3ac", "f3ad", "f3ae", "f3af", "f3b0", "f3b1", "f3b2", "f3b3", "f3b4", "f3b5", "f3b6", "f3b7", "f3b8", "f3b9", "f3ba", "f3bb", "f3bc", "f3bd", "f3be", "f3bf", "f3c0", "f3c1", "f6f7", "f3c2", "f67b", "f3c3", "f3c4", "f6f8", "f3c5", "f3c6", "f3c7", "f3c8", "f3c9", "f3ca", "f5cc", "f3cb", "f3cc", "f3cd", "f3ce", "f3cf", "f3d0", "f3d2", "f3d3", "f3d4", "f3d5", "f3d6", "f3d7", "f600", "f601", "f6f9", "f6fa", "f6fb", "f6fc", "f6fd", "f3d8", "f3d9", "f3da", "f3db", "f3dc", "f3dd", "f3de", "f3df", "f3e0", "f3e1", "f3e2", "f3e3", "f3e4", "f3e5", "f3e6", "f3e7", "f3e8", "f3e9", "f657", "f3ea", "f3eb", "f3ec", "f3ed", "f6fe", "f3ee", "f3ef", "f6ff", "f700", "f3f0", "f3f1", "f3f2", "f3f3", "f602", "f3f4", "f3f5", "f3f6", "f3f7", "f3f8", "f3f9", "f658", "f3fa", "f3fb", "f3fc", "f659", "f3fd", "f3fe", "f3ff", "f400", "f67c", "f401", "f402", "f701", "f403", "f404", "f405", "f406", "f407", "f408", "f702", "f409", "f6ba", "f40a", "f40b", "f40c", "f40d", "f40e", "f40f", "f5c7", "f410", "f411", "f412", "f413", "f414", "f6b9", "f415", "f416", "f703", "f417", "f418", "f419", "f41a", "f41b", "f704", "f41c", "f41d", "f41e", "f41f", "f420", "f421", "f606", "f422", "f65a", "f65b", "f423", "f424", "f425", "f6a4", "f6a5", "f426", "f427", "f705", "f428", "f429", "f42a", "f42b", "f42c", "f706", "f603", "f604", "f42d", "f42e", "f42f", "f6a6", "f430", "f431", "f605", "f432", "f433", "f434", "f435", "f436", "f437", "f438", "f439", "f43a", "f43b", "f43c", "f43d", "f43e", "f43f", "f440", "f441", "f442", "f443", "f444", "f445", "f446", "f707", "f447", "f448", "f449", "f44a", "f44b", "f44c", "f44d", "f44e", "f44f", "f450", "f451", "f452", "f453", "f454", "f455", "f687", "f688", "f456", "f457", "f458", "f459", "f45a", "f45b", "f45c", "f45d", "f45e", "f708", "f6a7", "f45f", "f709", "f70a", "f70b", "f460", "f461", "f462", "f6a8", "f463", "f70c", "f464", "f465", "f466", "f467", "f468", "f607", "f469", "f46a", "f608", "f46b", "f46c", "f46d", "f70d", "f46e", "f46f", "f470", "f471", "f609", "f472", "f5d1", "f473", "f6aa", "f474", "f475", "f476", "f477", "f478", "f479", "f47a", "f70e", "f47b", "f47c", "f47d", "f47e", "f47f", "f480", "f481", "f482", "f483", "f689", "f484", "f485", "f486", "f487", "f488", "f489", "f48a", "f65c", "f48b", "f48c", "f48d", "f48e", "f48f", "f490", "f491", "f492", "f493", "f494", "f65d", "f495", "f65e", "f65f", "f660", "f496", "f497", "f498", "f499", "f49a", "f49b", "f70f", "f710", "f49c", "f49d", "f49e", "f49f", "f4a0", "f62b", "f4a1", "f4a2", "f711", "f712", "f713", "f714", "f715", "f60a", "f4a3", "f4a4", "f4a5", "f4a6", "f4a7", "f4a8", "f4a9", "f4aa", "f4ab", "f4ac", "f4ad", "f661", "f662", "f4ae", "f663", "f664", "f68b", "f4af", "f4b0", "f4b1", "f4b2", "f4b3", "f4b4", "f4b5", "f4b6", "f716", "f4b7", "f4b8", "f4b9", "f68c", "f4ba", "f4bb", "f4bc", "f4bd", "f4be", "f4bf", "f4c0", "f62c", "f717", "f718", "f719", "f71a", "f71b", "f71c", "f71d", "f4c1", "f62d", "f4c2", "f4c3", "f4c4", "f71e", "f4c5", "f4c6", "f4c7", "f4c8", "f4c9", "f665", "f4ca", "f4cb", "f60b", "f4cc", "f71f", "f4cd", "f4ce", "f4cf", "f4d0", "f4d1", "f4d2", "f4d3", "f4d4", "f4d5", "f4d6", "f4d7", "f4d8", "f4d9", "f5d0", "f4da", "f4db", "f666", "f667", "f4dc", "f4dd", "f4de", "f60c", "f60d", "f6ab", "f4df", "f4e0", "f5c5", "f720", "f4e1", "f4e2", "f4e3", "f4e4", "f4e5", "f4e6", "f4e7", "f4e8", "f4e9", "f4ea", "f4eb", "f4ec", "f4ed", "f4ee", "f4ef", "f4f0", "f4f1", "f4f2", "f4f3", "f4f4", "f4f5", "f4f6", "f4f7", "f4f8", "f4f9", "f4fa", "f68a", "f4fb", "f4fc", "f721", "f722", "f4fd", "f4fe", "f4ff", "f500", "f501", "f502", "f503", "f504", "f505", "f506", "f507", "f508", "f509", "f668", "f669", "f50a", "f50b", "f60e", "f50c", "f50d", "f50e", "f50f", "f510", "f511", "f512", "f513", "f514", "f515", "f516", "f517", "f518", "f723", "f519", "f724", "f51a", "f51b", "f51c", "f51d", "f51e", "f51f", "f6ac", "f520", "f521", "f522", "f523", "f524", "f525", "f526", "f527", "f528", "f529", "f52a", "f680", "f681", "f52b", "f52c", "f52d", "f52e", "f52f", "f530", "f6ad", "f5ca", "f725", "f531", "f532", "f533", "f534", "f535", "f536", "f537", "f538", "f539", "f53a", "f53b", "f53c", "f53d", "f53e", "f726", "f53f", "f540", "f541", "f542", "f62e", "f66a", "f543", "f544", "f545", "f546", "f547", "f548", "f549", "f54a", "f54b", "f54c", "f54d", "f54e", "f54f", "f550", "f6ae", "f551", "f6af", "f552", "f553", "f554", "f555", "f556", "f557", "f558", "f559", "f55a", "f55b", "f55c", "f55d", "f55e", "f55f", "f560", "f561", "f5c6", "f562", "f001", "f563", "f564", "f565", "f566", "f567", "f568", "f569", "f56a", "f56b", "f56c", "f56d", "f56e", "f56f", "f570", "f571", "f572", "f573", "f727", "f574", "f728", "f575", "f576", "f577", "f578", "f60f", "f610", "f579", "f57a", "f57b", "f57c", "f5cb", "f57d", "f57e", "f57f", "f580", "f581", "f582", "f583", "f584", "f585", "f586", "f587", "f588", "f729", "f589", "f58a", "f58b", "f6b0", "f58c", "f58d", "f58e", "f58f", "f612", "f590", "f591", "f592", "f593", "f67d", "f594", "f595", "f596", "f597", "f598", "f67e", "f599", "f59a", "f59b", "f59c", "f59d", "f59e", "f59f", "f5a0", "f62f", "f72a", "f611", "f5a1", "f5a2", "f5a3", "f5a4", "f5a5", "f5a6", "f5a7", "f5a8", "f72b", "f5a9", "f5aa", "f5ab", "f72c", "f5ac", "f5ad", "f5ae", "f5af", "f5b0", "f5b1", "f5b2", "f5b3", "f5b4", "f5b5", "f5b6", "f5b7", "f5b8", "f673", "f5b9", "f5ba", "f5bb", "f5bc", "f5bd", "f5be", "f5bf", "f5c0", "f5c1", "f5c2", "f67f", "f5c3", "f5c4",);
  return $arr_icon;
}
