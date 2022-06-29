<?php
$position = array('Usuarios', 'Listado de Usuarios', 'users');
$theme = file_get_contents(_THEME_DIR . "html" . DS . $position[2] . ".theme.html");

$theme_script = $position[2];
//$clients_data = api("clients", "list");
?>

<script defer>
    let pagination = <?php echo $config['misc']['pagination'] ?>;
    let position = [];

    position['sub_title'] = '<?php echo $position[0] ?>';
    position['title'] = '<?php echo $position[1] ?>';
    position['var'] = '<?php echo $position[2] ?>';

    var clients_data_api = null;
    let session_id = <?php echo $_SESSION['ID'] ?>;
</script>

<?php echo $theme; ?>