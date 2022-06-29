<?php
$position = array('Loguearse', 'Loguearse', 'login');
$theme = file_get_contents(_THEME_DIR . "html" . DS . $position[2] . ".theme.html");

$theme_script = "login";
$login = true;
//$clients_data = api("clients", "list");
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