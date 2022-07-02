<?php
error_reporting(E_ALL);


define("DS", DIRECTORY_SEPARATOR);
define("_LOCAL", $_SERVER['DOCUMENT_ROOT'] . DS);
define("_THEME_DIR", _LOCAL . "core" . DS . "themes" . DS);

define("_VERSION", "0.1.5");
define("_FULLVERSION", "0.1.5-Alpha");
define("_COMMIT", "2022-06-29T20:28:52Z");
define("_DEBUG", true);


$config['title'] = "Endirecto";
$_ADDRESS = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/";
$_INSTALLED = true;


$config['db']['host'] = "127.0.0.1";
$config['db']['user'] = "root";
$config['db']['pass'] = "";
$config['db']['data'] = "icc_endirecto";

$config['misc']['pagination'] = 10;

// GitHub Update Info
$github['owner'] = "striderskynet";
$github['proyect_name'] = "Sistema-integral-de-Clientes";
$github['version'] = _VERSION;
$github['address'] = "https://api.github.com/repos/{$github['owner']}/{$github['proyect_name']}/releases";


// DONT TOUCH BELOW THIS LINE
require_once(_LOCAL . "core" . DS . "misc.php");
require_once(_LOCAL . "core" . DS . "debug.php");
require_once(_LOCAL . "core" . DS . "class" . DS . "mysql.php");
