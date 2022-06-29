<?php

switch (@array_keys($_GET)[1]) {
    case "delete":
        echo voucher_delete();
        break;
    case "add":
        echo voucher_add();
        break;
    case "print":
        echo voucher_print();
        break;
    case "list":
        echo voucher_list();
        break;
    case "total":
        echo voucher_total();
        break;
}

function voucher_print()
{
    global $db;

    $query = 'SELECT *, `main_vouchers`.`id` as voucher_id FROM main_vouchers, main_clients WHERE `main_vouchers`.`id` = ' . $_GET['id'] . ' AND main_clients.`id` = main_vouchers.`main_client` ORDER BY main_vouchers.`id` DESC;';

    //debug(4, $query);
    $data = $db->query($query)->fetchArray();

    $query_companions = "SELECT client_id FROM `voucher_client_array` WHERE `voucher_id` = {$data['voucher_id']}";
    $comp = $db->query($query_companions);
    $comp =  $comp->fetchAll();

    $q = 0;
    foreach ($comp as $c) {
        $query_comp = "SELECT `id`, `passport`, `name`, `lastname`, concat(prefix, ' ', name, ' ',  lastname) AS full_name FROM `main_clients` WHERE `id` = {$c['client_id']}";

        $co = $db->query($query_comp);
        $co =  $co->fetchAll();

        $data['companions'][$q]['id'] = $co[0]['id'];
        $data['companions'][$q]['name'] = $co[0]['full_name'];
        $data['companions'][$q]['passport'] = $co[0]['passport'];
        //$data['companions'][$q]['profile_picture'] = profile_picture ($co[0]['passport'], $co[0]['name'], $co[0]['lastname'], true);
        $q++;
    }

    $data['companions_amount'] = count($comp);

    return json_encode($data);
}

function voucher_list()
{
    global $db, $config;

    $where = "WHERE main_clients.`id` = main_vouchers.`main_client`";
    $limit = null;
    $offset = null;

    if (@isset($_GET['data'])) {
        $where  = "WHERE main_clients.`name` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `lastname` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `passport` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `email` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `phone` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `company` LIKE '%" . $_GET['data'] . "%'";
    }

    $limit = "LIMIT " . $config['misc']['pagination'];

    if (@isset($_GET['offset']))
        $offset = "OFFSET " . (($_GET['offset'] - 1) * $config['misc']['pagination']);


    $query = 'SELECT *, `main_vouchers`.`id` as voucher_id FROM main_vouchers, main_clients ' . $where . ' ORDER BY main_vouchers.`id` DESC ' . $limit . ' ' . $offset . ';';
    $query_no_limit = 'SELECT count(*) as `total` FROM main_vouchers ORDER BY `id` DESC;';

    //echo $query;
    //debug(4, $query);
    $res = $db->query($query);
    $accounts = $res->fetchAll();

    foreach ($accounts as $account) {

        $account['profile_picture'] = profile_picture($account['passport'],  $account['name'],  $account['lastname']);

        $query_companions = "SELECT client_id FROM `voucher_client_array` WHERE `voucher_id` = {$account['voucher_id']}";

        $comp = $db->query($query_companions);
        $comp =  $comp->fetchAll();

        $q = 0;
        foreach ($comp as $c) {
            $query_comp = "SELECT `id`, `passport`, `name`, `lastname`, concat(prefix, ' ', name, ' ',  lastname) AS full_name FROM `main_clients` WHERE `id` = {$c['client_id']}";

            $co = $db->query($query_comp);
            $co =  $co->fetchAll();

            $account['companions'][$q]['id'] = $co[0]['id'];
            $account['companions'][$q]['name'] = $co[0]['full_name'];
            $account['companions'][$q]['profile_picture'] = profile_picture($co[0]['passport'], $co[0]['name'], $co[0]['lastname'], true);
            $q++;
        }

        $data[] = $account;
    }

    //if ( @!isset($_GET['data']) )
    $data['info'] = $db->query($query_no_limit)->fetchAll();

    if (!isset($data))
        return json_encode("");


    //return json_encode($data, JSON_PRETTY_PRINT);
    return json_encode($data);
}

function profile_picture($passport, $name, $lastname, $small =  false)
{

    $profile_picture = md5($passport .  $name .  $lastname);

    if ($small == true)
        $small = array("ps-15", "fa-2x");
    else $small = array("ps-15", "fa-2x");

    if (file_exists('../uploaded/' .  $profile_picture . ".jpg"))
        return "<img class=\"ps-15 rounded-circle\" src='./uploaded/" . $profile_picture . ".jpg' />";
    else
        return "<i class='fas fa-user-alt fa-2x'></i>";
}

function voucher_delete()
{
    global $db;

    $query = "DELETE FROM main_vouchers WHERE `id` = {$_GET['id']}";
    $accounts = $db->query($query);

    debug(4, $query);
    return true;
}

function voucher_add()
{
    global $db;

    //return json_encode($_POST);

    $query = "INSERT INTO `main_vouchers` (`main_client`, `type`, `data`, `in_date`, `out_date`, `information`, `service_partner`, `confirmation_number`) VALUES ('{$_POST['avf_client_id']}', '{$_POST['avf_type']}', '{$_POST['avf_data']}', '{$_POST['avf_inDate']}', '{$_POST['avf_outDate']}', '{$_POST['avf_details']}', '{$_POST['avf_servicePartner']}', '{$_POST['avf_confirmationNumber']}');";

    debug(4, $query);
    $db->query($query);

    foreach ($_POST['avf_companion_id'] as $comp) {
        $query_comp = "INSERT INTO voucher_client_array SELECT id AS `voucher_id`, {$comp} FROM `main_vouchers` ORDER BY `id` DESC LIMIT 1;";
        $db->query($query_comp);
    }

    return true;
}

function voucher_total()
{
    global $db;
    return $db->query('SELECT * FROM main_vouchers')->numRows();
}
