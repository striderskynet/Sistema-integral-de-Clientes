<?php
header('Content-Type: text/javascript');
require_once("config.php");

$theme_path = "./themes/script/";

if (isset($_GET['js'])) {
    $script = null;

    switch ($_GET['js']) {
        case "main":
            $script .= file_get_contents("../assets/js/countries.js");
            $script .= file_get_contents("../assets/js/main.js");
            break;
        default:
            $script = file_get_contents($theme_path . $_GET['js'] . ".exec.js");
            break;
    }

    if (!_DEBUG) {
        require_once("./minifier.php");
        $script = \JShrink\Minifier::minify($script);
    }
    echo $script;
}
