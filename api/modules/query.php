<?php
$query = (string)$_GET['query'];

//echo $query;
try {
    echo json_encode($db->query($query)->fetchArray());
    //$db->fetchArray();
} catch (Exception $e) {
    return $e->getMessage();
}
