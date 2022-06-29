<?php
switch (array_keys($_GET)[1]) {
    case "total":
        echo clients_total();
        break;
    case "show":
        echo clients_show();
        break;
    case "add":
        echo clients_add();
        break;
    case "gen":
        echo clients_generate();
        break;
    case "list":
        echo clients_list();
        break;
    case "list_min":
        echo clients_list_min();
        break;
    case "delete":
        echo clients_delete();
        break;
    case "upload":
        echo clients_upload();
        break;
}

function clients_total()
{
    global $db;
    return $db->query('SELECT * FROM main_clients')->numRows();
}

function clients_generate()
{
    global $db;

    $file_name = md5($_POST['passport'] . $_POST['name'] . $_POST['lastname']);
    $face = "https://thispersondoesnotexist.com/image";

    if (@getimagesize($face))
        file_put_contents('../uploaded/' .  $file_name . ".jpg", file_get_contents($face));

    $query = "INSERT INTO `main_clients` (`prefix`, `name`, `lastname`, `passport`, `phone`, `email`, `address`, `country`, `date_added`, `company`, `status`, `observations`, `last_touch`) VALUES ('{$_POST['prefix']}', '{$_POST['name']}', '{$_POST['lastname']}', '{$_POST['passport']}', '{$_POST['phone']}', '{$_POST['email']}', '{$_POST['address']}','{$_POST['country']}', '{$_POST['date_added']}', '{$_POST['company']}', '{$_POST['status']}', '{$_POST['observations']}', '{$_POST['last_touch']}');";

    debug(4, $query);

    if ($db->query($query)) return true;
    else return false;
}

function clients_add()
{
    global $db;

    $query = "INSERT INTO `main_clients` (`prefix`, `name`, `lastname`, `passport`, `phone`, `email`, `address`, `country`, `date_added`, `company`, `status`, `observations`, `last_touch`, `agency`) VALUES ('{$_POST['prefix']}', '{$_POST['name']}', '{$_POST['lastname']}', '{$_POST['passport']}', '{$_POST['phone']}', '{$_POST['email']}', '{$_POST['address']}', '{$_POST['country']}', now(), '{$_POST['company']}', '{$_POST['status']}', '{$_POST['observations']}', '{$_POST['last_touch']}', '{$_POST['agency']}');";

    debug(4, $query);

    if ($db->query($query)) return true;
    else return false;
}

function clients_delete()
{
    global $db;

    $query = "DELETE FROM main_vouchers WHERE `main_client` = '{$_GET['id']}';";
    $query1 = "DELETE FROM main_clients WHERE `id` = '{$_GET['id']}';";


    debug(4, $query . " " . $query1);

    try {
        $db->query($query);
        $db->query($query1);
    } catch (Exception $e) {
        return $e->getMessage();
    }

    return "Se ha eliminado el cliente con ID: " . $_GET['id'];
}

function clients_list_min()
{
    global $db;
    $where = null;

    if (@isset($_GET['q'])) {
        $where  = "WHERE `name` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `lastname` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `passport` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `email` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `phone` LIKE '%" . $_GET['q'] . "%'";
        $where .= " OR `company` LIKE '%" . $_GET['q'] . "%'";
    }

    $query = 'SELECT id as `value`, CONCAT(name, " ", lastname) as `text` FROM main_clients ' . $where . ' ORDER BY `id` DESC;';

    $res = $db->query($query);
    $accounts = $res->fetchAll();

    foreach ($accounts as $account) {
        $data[] = $account;
    }

    if (!isset($data))
        $data = "";

    echo json_encode($data, JSON_PRETTY_PRINT);
}


function clients_list()
{
    global $db, $config;

    $where = null;
    $order = "ORDER BY `id`";
    $dir = "DESC";
    $limit = null;
    $offset = null;

    if (@isset($_GET['data'])) {
        $where  = "WHERE `name` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `lastname` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `passport` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `email` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `phone` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `company` LIKE '%" . $_GET['data'] . "%'";
    }

    if (@isset($_GET['orderBy'])) {
        $order = "ORDER by `" . $_GET['orderBy'] . "`";
    }

    if (@isset($_GET['dir'])) {
        $dir = $_GET['dir'];
    }


    $limit = "LIMIT " . $config['misc']['pagination'];

    if (@isset($_GET['offset']))
        $offset = "OFFSET " . (($_GET['offset'] - 1) * $config['misc']['pagination']);


    $query = "SELECT * FROM `main_clients` {$where} {$order} {$dir} {$limit} {$offset};";
    //$query = 'SELECT * FROM main_clients ' . $where . ' ' . $order . ' DESC ' . $limit .' ' . $offset .';';
    $query_no_limit = 'SELECT count(*) as `total` FROM main_clients ' . $where . ' ORDER BY `id` DESC;';

    //print_r ( $query );
    //debug(4, $query);
    $res = $db->query($query);
    $accounts = $res->fetchAll();

    foreach ($accounts as $account) {

        $profile_picture = md5($account['passport'] .  $account['name'] .  $account['lastname']);

        if (file_exists('../uploaded/' .  $profile_picture . ".jpg"))
            //$account['profile_picture'] = "<img style='width: 45px;' class='rounded-circle' src='./uploaded/" . $profile_picture . ".jpg' />";
            $account['profile_picture'] = "<img class='client-avatar rounded-circle' src='./uploaded/" . $profile_picture . ".jpg' />";
        else
            $account['profile_picture'] = "<i class='text-dark fas fa-user-alt fa-3x'></i>";

        $data[] = $account;
    }

    $data['info'] = $db->query($query_no_limit)->fetchAll();

    if (!isset($data))
        return json_encode("");

    //return json_encode($data, JSON_PRETTY_PRINT);
    return json_encode($data);
}

function clients_show()
{
    global $db;

    $query = 'SELECT * FROM `main_clients` WHERE `id` = ' . $_GET['id'] . ' LIMIT 1';



    $data = $db->query($query)->fetchArray();
    // debug(4, $query);

    $profile_picture = md5($data['passport'] .  $data['name'] .  $data['lastname']);

    if (file_exists('../uploaded/' .  $profile_picture . ".jpg"))
        $data['profile_picture'] = "<img class='ps-45 rounded-circle my-5' src='./uploaded/" . $profile_picture . ".jpg' />";
    else
        $data['profile_picture'] = "<i class='fas fa-user-alt fa-6x my-5'></i>";


    return json_encode($data);
}

function clients_upload()
{
    global $db;

    $file_name = md5($_POST['passport'] . $_POST['name'] . $_POST['lastname']);
    if ($_FILES['file']['size'] > 0)
        move_uploaded_file($_FILES['file']['tmp_name'], '../uploaded/' .  $file_name . ".jpg");
}
