<?php 
require('require.php');
$id         = (int)($_GET['id']);
$id         = $mysqli->real_escape_string($id);
$data       = '1';
$sql        = "UPDATE `user` SET deleted_at = '" . $data . "' WHERE id = '" . $id . "'";

$result     = $mysqli->query($sql);
$url        = $base_url . "index.php?msg=delete";
header("refresh:0; url=$url");
exit();
?>