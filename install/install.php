<?php
if (count($_POST) == 0) die("Cant access directly! <a href='../'>Go to the main site</a>");

$config_file_path = "../core/config.php";
$config_file = file_get_contents($config_file_path);
$sql_file = file_get_contents("database.sql");
$sql_file = str_replace("{data}", $_POST['data'], $sql_file);

$error = null;

try {
    $conn = new mysqli($_POST['host'], $_POST['user'], $_POST['pass']);
    $conn->multi_query($sql_file);
} catch (Exception $e) {
    $error = $e->getMessage();
    die($error);
}

// HOST
preg_match("\$config\[\'db\'\]\[\'host\'\] \= \"(.*)\"\;\$", $config_file, $matches);
$host_replace = str_replace($matches[1], $_POST['host'], $matches[0]);
$config_file = str_replace($matches[0], $host_replace, $config_file);

// USER
preg_match("\$config\[\'db\'\]\[\'user\'\] \= \"(.*)\"\;\$", $config_file, $matches);
$user_replace = str_replace($matches[1], $_POST['user'], $matches[0]);
$config_file = str_replace($matches[0], $user_replace, $config_file);

// PASS
preg_match("\$config\[\'db\'\]\[\'pass\'\] \= \"(.*)\"\;\$", $config_file, $matches);
$pass_replace = str_replace($matches[1], $_POST['pass'], $matches[0]);
$config_file = str_replace($matches[0], $pass_replace, $config_file);

// DATA
preg_match("\$config\[\'db\'\]\[\'data\'\] \= \"(.*)\"\;\$", $config_file, $matches);
$data_replace = str_replace($matches[1], $_POST['data'], $matches[0]);
$config_file = str_replace($matches[0], $data_replace, $config_file);

$config_file = str_replace('$_INSTALLED = false;', '$_INSTALLED = true;', $config_file);

try {
    file_put_contents($config_file_path, $config_file);
} catch (Exception $e) {
    $error = $e->getMessage();
    die($error);
}
