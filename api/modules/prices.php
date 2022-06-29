<?php
switch (array_keys($_GET)[1]) {
    case "add":
        echo price_add();
        break;
    case "list":
        echo prices_list();
        break;
    case "list_min":
        echo prices_list_min();
        break;
    case "duplicate":
        echo prices_duplicate();
        break;
    case "delete":
        echo prices_delete();
        break;
}


function prices_list_min()
{
    global $db;
    $where = null;

    if (@isset($_GET['q'])) {
        $where  = "WHERE `code` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `name` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `type` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `place` LIKE '%" . $_GET['q'] . "%'";
    }

    //echo $where;
    //$query = 'SELECT id as `value`, CONCAT(`code`, "<br>", `name`, "<br>", `type`, "\t\t\t", `place`) as `text` FROM price_list ' . $where . ' ORDER BY `id` DESC;';
    $query = 'SELECT id as `value`, CONCAT("<main_type>",`code`, "<br>", `name`, "<br>", `type`, "\t\t\t", `place`) as `text` FROM price_list ' . $where . ' ORDER BY `id` DESC';

    $res = $db->query($query);
    $accounts = $res->fetchAll();

    foreach ($accounts as $account) {
        $data[] = $account;
    }

    if (@isset($_GET['q'])) {
        $where  = "WHERE `code` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `from_place` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `to_place` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `vehicle_type` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `agency` LIKE '%" . $_GET['q'] . "%'";
    }

    $query = 'SELECT id as `value`, CONCAT("<transport_type>", `code`, " - " , `agency`, "<br>", `vehicle_type`, " \(" , `vehicle_max_passenger`, "\) " , `vehicle_price` , "$ <br>", `from_place`, "\t - \t ", `to_place`) as `text` FROM price_transport ' . $where . ' ORDER BY `id` DESC';

    $res = $db->query($query);
    $accounts = $res->fetchAll();

    foreach ($accounts as $account) {
        $data[] = $account;
    }

    if (!isset($data))
        $data = "";

    echo json_encode($data, JSON_PRETTY_PRINT);
}

function price_add()
{
    global $db;

    $type = "default";
    if (@isset($_GET['type'])) $type =  $_GET['type'];

    switch ($type) {
        default:
            $query = prices_add_default();
            break;
        case "transport":
            $query = prices_add_transport();
            break;
        case "agency":
            $query = prices_add_agency();
            break;
    }
    //print_r ( $_POST );

    $query = str_replace("\n", "", $query);
    debug(4, $query);

    if ($db->query($query)) return true;
    else return false;
}

function prices_add_transport()
{
    $query = "INSERT INTO `price_transport` (
        `code`,
        `from_place`,
        `to_place`,
        `vehicle_type`,
        `agency`,
        `vehicle_max_passenger`,
        `vehicle_price`) VALUES (
        '{$_POST['apf_code']}',
        '{$_POST['apf_from_place']}',
        '{$_POST['apf_to_place']}',
        '{$_POST['apf_vehicle_type']}',
        '{$_POST['apf_agency']}',
        '{$_POST['apf_vehicle_max_passenger']}',
        '{$_POST['apf_vehicle_price']}');";

    return $query;
}

function prices_add_agency()
{
    $query = "INSERT INTO `price_agency` (
        `name`,
        `type`,
        `commission`,
        `contact_phone`,
        `contact_email`) VALUES (
        '{$_POST['apf_name']}',
        '{$_POST['apf_type']}',
        '{$_POST['apf_commission']}',
        '{$_POST['apf_contact_phone']}',
        '{$_POST['apf_contact_email']}');";

    return $query;
}
function prices_add_default()
{
    $query = "INSERT INTO `price_list` (
        `code`,
        `name`,
        `type`,
        `place`,
        `from_date`,
        `to_date`,
        `season`,
        `plan`,
        `price_pax_double`,
        `price_simple`,
        `price_tripled`,
        `price_dinner`,
        `hab_doble`,
        `hab_simple`,
        `hab_tripled`,
        `offert`,
        `offert_validity`,
        `offert_from`,
        `offert_to`,
        `provider`,
        `kids_policy`,
        `room_vacancy`) VALUES (
        '{$_POST['apf_code']}',
        '{$_POST['apf_name']}',
        '{$_POST['apf_type']}',
        '{$_POST['apf_place']}',
        '{$_POST['apf_from_date']}',
        '{$_POST['apf_to_date']}',
        '{$_POST['apf_season']}',
        '{$_POST['apf_plan']}',
        '{$_POST['apf_price_pax_double']}',
        '{$_POST['apf_price_simple']}',
        '{$_POST['apf_price_tripled']}',
        '{$_POST['apf_price_dinner']}',
        '{$_POST['apf_hab_doble']}',
        '{$_POST['apf_hab_simple']}',
        '{$_POST['apf_hab_tripled']}',
        '{$_POST['apf_offert']}',
        '{$_POST['apf_offert_validity']}',
        '{$_POST['apf_offert_from']}',
        '{$_POST['apf_offert_to']}',
        '{$_POST['apf_provider']}',
        '{$_POST['apf_kids_policy']}',
        '{$_POST['apf_room_vacancy']}');";

    return $query;
}

function prices_delete()
{
    global $db;

    $where = "WHERE `id` IN (";
    for ($q = 0; $q < count($_POST['info']); $q++) {
        //foreach ( $_POST['info'] as $i ){
        if ($q == count($_POST['info']) - 1)
            $where .= $_POST['info'][$q];
        else
            $where .= $_POST['info'][$q] . ", ";
    }
    $where .= ")";

    $query = "DELETE FROM price_{$_GET['table']} {$where};";
    debug(4, $query);

    try {
        $db->query($query);
    } catch (Exception $e) {
        return $e->getMessage();
    }
    return "Se ha eliminado el listado con ID: " . $where;
}

function prices_duplicate()
{
    global $db;

    $query = null;

    foreach ($_POST['info'] as $id) {
        $query = "INSERT INTO `price_list` (`code`, `name`, `type`, `place`, `from_date`, `to_date`, `season`, `plan`, `price_pax_double`, `price_simple`, `price_tripled`, `price_dinner`, `hab_doble`, `hab_simple`, `hab_tripled`, `offert`, `offert_validity`, `offert_from`, `offert_to`, `provider`, `kids_policy`, `room_vacancy`) 
        SELECT `code`, `name`, `type`, `place`, `from_date`, `to_date`, `season`, `plan`, `price_pax_double`, `price_simple`, `price_tripled`, `price_dinner`, `hab_doble`, `hab_simple`, `hab_tripled`, `offert`, `offert_validity`, `offert_from`, `offert_to`, `provider`, `kids_policy`, `room_vacancy` FROM `price_list` WHERE `id`={$id};";
        $db->query($query);
    }

    return $query;
}

function prices_list()
{
    global $db, $config;

    $table = "`price_list`";
    $where = null;
    $order = "ORDER BY `id`";
    $dir = "DESC";
    $limit = null;
    $offset = null;

    if (@isset($_GET['data'])) {
        $where  = "WHERE `code` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `name` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `type` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `place` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `provider` LIKE '%" . $_GET['data'] . "%'";
    }

    if (@isset($_GET['table']))
        $table = "`" . $_GET['table'] . "`";

    if (@isset($_GET['wh']))
        $where = $_GET['wh'];


    if (@isset($_GET['orderBy']))
        $order = "ORDER by `" . $_GET['orderBy'] . "`";

    if (@isset($_GET['dir']))
        $dir = $_GET['dir'];

    $limit = "LIMIT 50";

    if (@isset($_GET['offset']))
        $offset = "OFFSET " . (($_GET['offset'] - 1) * $config['misc']['pagination']);


    $query = "SELECT * FROM $table {$where} {$order} {$dir} {$limit} {$offset};";
    //$query = 'SELECT * FROM main_clients ' . $where . ' ' . $order . ' DESC ' . $limit .' ' . $offset .';';
    $query_no_limit = 'SELECT count(*) as `total` FROM ' . $table . ' ' . $where . ' ORDER BY `id` DESC;';

    //print_r ( $query );
    //debug(4, $query);
    $res = $db->query($query);
    $accounts = $res->fetchAll();

    foreach ($accounts as $account) {
        $data[] = $account;
    }

    $data['info'] = $db->query($query_no_limit)->fetchAll();

    if (!isset($data))
        return json_encode("");

    //return json_encode($data, JSON_PRETTY_PRINT);
    return json_encode($data);
}
