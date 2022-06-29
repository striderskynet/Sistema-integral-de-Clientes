<?php
$position = array('Panel', 'Panel Principal', 'panel');
$theme = file_get_contents(_THEME_DIR . "html" . DS . $position[2] . ".theme.html");

$data['client_count'] = api("clients", "total");
$data['reserv_count'] = api("vouchers", "total");
$data['client_arriving'] = json_decode(api("query", "null", "query=" . urlencode("SELECT count(*) as `amount` FROM `main_clients` WHERE `date_added` BETWEEN DATE_sub(now(),INTERVAL 7 DAY) AND now()")))->amount;


//print_r($data);

$theme = tokenize($data, $theme);

$theme_script = "panel";
//$clients_data = api("clients", "list");


?>
<script defer>
    let pagination = <?php echo $config['misc']['pagination'] ?>;
    let position = [];

    position['sub_title'] = '<?php echo $position[0] ?>';
    position['title'] = '<?php echo $position[1] ?>';
    position['var'] = '<?php echo $position[2] ?>';

    var clients_data_api = null;
</script>

<script type="text/javascript" src="./assets/js/apexcharts.js"></script>
<?php echo $theme; ?>