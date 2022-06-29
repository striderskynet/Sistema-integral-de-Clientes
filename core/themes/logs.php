<?php
$position = array('Logs', 'Panel de Logs', 'logs');

$theme_script = "logs";
//$clients_data = api("clients", "list");
$log_dir = $_SERVER['DOCUMENT_ROOT'] . "/logs/";

if (@isset($_GET['log'])) {
    die(read_logs($log_dir . $_GET['log']));
}

function dir_logs($log_dir)
{
    $files =  array_reverse(array_diff(scandir($log_dir), array('..', '.')));

    $result = null;
    $q = 0;

    foreach ($files as $f) {
        if ($q == 0)
            $selected = "selected";
        else $selected = "";

        $result .= "\t\t\t<option value=\"{$f}\" {$selected}>{$f}</option>\n";
        $q++;
    }

    return  $result;
}

function read_logs($log_file)
{
    $logs = file_get_contents($log_file);
    $logs = explode("\n", $logs);

    foreach ($logs as $l) {
        $r = explode("|", $l);
        parse_logs($r);
    }
}
//echo str_replace("\n", "<br>\n", htmlentities(htmlspecialchars($logs)));

function parse_logs($log)
{;

    if (array_key_exists(2, $log))
        $hide = "";
    else $hide = "hide";

    echo "<li class=\"list-group-item $hide\"><div class=\"row align-items-center no-gutters\">";
    if (array_key_exists(2, $log)) {
        $type = trim($log[1]);

        if (isset($log[3])) {
            $user = trim($log[2]);
            $value = @trim($log[3]);
        } else {
            $user = "{NULL}";
            $value = @trim($log[2]);
        }


        if (strtolower($type) != "data") {
            $value = str_replace("(", "(<span class='important'>", $value);
            $value = str_replace(")", "</span>)", $value);
        }
        echo "<div class='log log_" . strtolower($type) . "'><span class='date'>" . trim($log[0]) . "</span>\t\t|\t\t <span class='type'>" . $type . "</span>\t\t|\t\t <span class='user'>" . $user . "</span></span>\t\t|\t\t <span class='value'>" . $value . "</span></div>\n";
    } else {
        echo "<div class='log log_default'>" . $log[0] . "</div>\n";
    }
    echo "</div></li>";
}

$data['logs_directory'] = dir_logs($log_dir);

$theme = file_get_contents(_LOCAL . "/core/themes/html/" . $position[2] . ".theme.html");

$theme = tokenize($data, $theme);

?>
<script>
    let pagination = <?php echo $config['misc']['pagination'] ?>;
    let position = [];

    position['sub_title'] = '<?php echo $position[0] ?>';
    position['title'] = '<?php echo $position[1] ?>';
    position['var'] = '<?php echo $position[2] ?>';

    var clients_data_api = null;
</script>
<?php echo $theme; ?>