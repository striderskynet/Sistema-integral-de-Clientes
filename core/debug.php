<?php
function debug($level, $info)
{
    global $_DEBUG;
    $user = $_SESSION['USERID'];

    $lvl[0] = "Info";
    $lvl[1] = "Warn";
    $lvl[2] = "Erro";
    $lvl[3] = "Debug";
    $lvl[4] = "Data";
    $lvl[5] = "Logi";

    $log = date('Y-m-d H:i:s') . "\t| " . $lvl[$level] . "\t|\t" . $user . "\t|\t" . $info . "\n";
    $today = date('Y-m-d');

    //if ( $_DEBUG )
    file_put_contents(_LOCAL . "logs/{$today}_main.log", $log, FILE_APPEND);
}
