<?php
function is_active($id)
{
    if ($id == "main" & !isset(array_keys($_GET)[0])) {
        return "active";
    } elseif (isset($_GET) && @array_keys($_GET)[0] == $id) {
        return "active";
    }
    return "";
}

function api($module, $action = null, $values = null, $decode = false)
{
    global $_ADDRESS;
    $address = $_ADDRESS . "api/?" . $module . "&" . $action . "&" . $values;

    //echo $address;
    if ($decode == true)
        return json_decode(file_get_contents($address));
    else
        return file_get_contents($address);
}

function reload($address = null)
{
    die("<header><script>window.location = '/" . $address . "';</script>");
}

// Voucher function
function tokenize($rep_array, $value)
{

    foreach (array_keys($rep_array) as $k) {
        $value = @str_replace("{" . $k . "}", $rep_array[$k], $value);
    }
    return $value;
}

// Voucher function
function show_companions($companions)
{
    $ret = "";

    foreach ($companions as $c) {
        $ret .= "<span class=\"info-secondary\">" . $c->name . " (" . $c->passport . ")</span>\n";
    }

    return $ret;
}
