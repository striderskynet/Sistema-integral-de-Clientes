<?php
switch (array_keys($_GET)[1]) {
    case "total":
        echo users_total();
        break;
    case "add":
        echo users_add();
        break;
    case "verify":
        echo users_verify();
        break;
    case "list":
        echo users_list();
        break;
    case "delete":
        echo users_delete();
        break;
    case "logout":
        echo users_logout();
        break;
    case "password":
        echo users_password();
        break;
    case "install":
        echo users_install();
        break;
}

function users_install()
{
    global $db;
    $pass = md5($_POST['password']);
    return $db->query("INSERT INTO general_users ( `username`, `password`, `role` ) VALUES ('{$_POST['username']}', '{$pass}', 'root');");
}

function users_total()
{
    global $db;
    return $db->query('SELECT * FROM general_users')->numRows();
}

function users_add()
{
    global $db;

    print_r($_POST);
    $val['user']    = $_POST['username'];
    $val['pass1']   = $_POST['password1'];
    $val['pass2']   = $_POST['password2'];
    $val['fullname']   = $_POST['apf_full_name'];
    $val['position']   = $_POST['apf_position'];
    $val['role']   = $_POST['apf_role'];
    $val['gender']   = $_POST['apf_gender'];
    $val['avatar']   = $_POST['avatar-radio'];
    $val['pass'] = md5($val['pass1']);

    $query = "INSERT INTO general_users( `username`, `password`, `role`, `fullname`, `position`, `gender`, `avatar`) VALUES ('{$val['user']}','{$val['pass']}','{$val['role']}','{$val['fullname']}','{$val['position']}', '{$val['gender']}', '{$val['avatar']}');";

    echo $query;
    debug(4, $query);

    if ($db->query($query)) return true;
    else return false;
}

function users_logout()
{
    session_destroy();
}

function users_password()
{
    global $db;

    $pass = md5($_GET['pass']);
    $query = "UPDATE general_users SET `password` = \"$pass\" WHERE `id`='{$_GET['id']}'";

    debug(1, $query);

    $db->query($query);
}
function users_verify()
{
    global $db;

    $log_user = $_POST['username'];
    $log_pass = md5($_POST['password']);

    $query = "SELECT * FROM general_users WHERE `username`='{$log_user}' AND `password`='{$log_pass}'";

    debug(5, $query);
    $result = $db->query($query)->fetchArray();

    if (count($result) > 0) {
        $_SESSION['USERID'] = $result['username'];
        $_SESSION['SSID'] = $result['username'] . date("dd/mm/yy/");
        $_SESSION['USER_ROLE'] = $result['role'];
        $_SESSION['AVATAR'] = $result['avatar'];
        $_SESSION['ID'] = $result['id'];

        return "{\"login\":\"true\"}\n";
    } else {
        return "{\"login\":\"false\"}\n";
    }

    //return json_encode($result);
}

function users_list()
{
    global $db;

    $query = "SELECT *, \"hidden\" as `password` FROM general_users";
    //debug(4, $query);

    $result = $db->query($query)->fetchAll();

    return json_encode($result);
}

function users_delete()
{
    global $db;

    $query = "DELETE FROM general_users WHERE `id`='{$_GET['id']}'";
    debug(1, $query);

    $db->query($query);
}
