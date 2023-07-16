<?php
$server_name = "localhost";
$user_name  = "root";
$password   = "";
$db_name    = "intern_test";
$mysqli      = new mysqli($server_name, $user_name, $password, $db_name);
if ($mysqli->connect_error) {
    echo "Connect Error ->".$mysqli->connect_error;
} 