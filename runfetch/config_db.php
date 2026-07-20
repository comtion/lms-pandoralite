<?php

if (!function_exists('runfetch_load_env_file')) {
	function runfetch_load_env_file($filePath)
	{
		if (!is_file($filePath) || !is_readable($filePath)) {
			return;
		}

		$lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if ($lines === false) {
			return;
		}

		foreach ($lines as $line) {
			$line = trim($line);
			if ($line === '' || $line[0] === '#') {
				continue;
			}

			$parts = explode('=', $line, 2);
			if (count($parts) !== 2) {
				continue;
			}

			$name = trim($parts[0]);
			$value = trim($parts[1]);

			if ($name === '') {
				continue;
			}

			if (
				(strlen($value) >= 2) &&
				(
					($value[0] === '"' && substr($value, -1) === '"') ||
					($value[0] === "'" && substr($value, -1) === "'")
				)
			) {
				$value = substr($value, 1, -1);
			}

			if (getenv($name) === false) {
				putenv($name . '=' . $value);
				$_ENV[$name] = $value;
				$_SERVER[$name] = $value;
			}
		}
	}
}

runfetch_load_env_file(__DIR__ . DIRECTORY_SEPARATOR . '.env');

$hostname_condb = getenv('RUNFETCH_DB_HOST') ?: "localhost";
$username_condb = getenv('RUNFETCH_DB_USER') ?: "hqfaznbdej";
$password_conndb = getenv('RUNFETCH_DB_PASSWORD');
if ($password_conndb === false) {
	$password_conndb = "w48Scha2UU";
}
$db_name = getenv('RUNFETCH_DB_NAME') ?: "hqfaznbdej";
$db_port = getenv('RUNFETCH_DB_PORT');
if ($db_port === false || $db_port === '') {
	$db_port = null;
}

$conndb = mysqli_connect($hostname_condb, $username_condb, $password_conndb, $db_name, $db_port ?: null);
//$conndb=mysqli_connect($hostname_condb2,$username_condb2,$password_conndb2,$db_name2);
//mysqli_set_charset($conndb, 'utf8'); 
if (mysqli_connect_errno())
{
	echo "Error Connect".mysqli_connect_error();
	exit();
}


include("MysqliDb.php");
$strHost = getenv('RUNFETCH_MYSQLIDB_HOST') ?: (($db_port && $db_port !== '3306') ? $hostname_condb . ':' . $db_port : $hostname_condb);
$strDB = getenv('RUNFETCH_MYSQLIDB_NAME') ?: $db_name;
$strUser = getenv('RUNFETCH_MYSQLIDB_USER') ?: $username_condb;
$strPassword = getenv('RUNFETCH_MYSQLIDB_PASSWORD');
if ($strPassword === false) {
	$strPassword = $password_conndb;
}
$db = new MysqliDb($strHost, $strUser, $strPassword, $strDB);

$env_url="https://elearning.isuzu.co.th";

?>
