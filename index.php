<?php
session_start();

require_once("./core/config.php");

//Building the website
require_once(_THEME_DIR . "header.php");

if (isset($_SESSION['USERID'])) {
  switch (@array_keys($_GET)[0]) {
    case "clients":
      include_once(_THEME_DIR . "clients.php");
      require_once(_THEME_DIR . "modals.php");
      break;
    case "voucher":
      include_once(_THEME_DIR . "voucher.php");
      require_once(_THEME_DIR . "modals.php");
      break;
    case "panel":
    default:
      include_once(_THEME_DIR . "panel.php");
      break;
    case "logs":
    case "prices":
    case "users":
    case "agency":
      include_once(_THEME_DIR . @array_keys($_GET)[0] . ".php");
      break;
  }
} else {
  // Check if this is a new Install and Execute Install.php
  if ($_INSTALLED == true) {
    include_once(_THEME_DIR . "login.php");
  } else {
    if (file_exists(_THEME_DIR . "install.php"))
      include_once(_THEME_DIR . "install.php");
  }
}

require_once(_THEME_DIR . "footer.php");
