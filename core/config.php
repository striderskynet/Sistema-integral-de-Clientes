<?php
error_reporting(E_ALL);


define("DS", DIRECTORY_SEPARATOR);
define("_LOCAL", $_SERVER['DOCUMENT_ROOT'] . DS);
define("_THEME_DIR", _LOCAL . "core" . DS . "themes" . DS);

define("_VERSION", "0.1.3-alpha");
define("_COMMIT", "2022-06-20T19:14:50Z");
define("_DEBUG", true);


$config['title'] = "Endirecto";
$_ADDRESS = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/";
$_INSTALLED = true;


$config['db']['host'] = "127.0.0.1";
$config['db']['user'] = "root";
$config['db']['pass'] = "";
$config['db']['data'] = "icc_endirecto";

$config['misc']['pagination'] = 10;


// DONT TOUCH BELOW THIS LINE
require_once(_LOCAL . "core" . DS . "misc.php");
require_once(_LOCAL . "core" . DS . "debug.php");
require_once(_LOCAL . "core" . DS . "class" . DS . "mysql.php");
