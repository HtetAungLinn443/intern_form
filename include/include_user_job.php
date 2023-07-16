<?php

$sql = "SELECT T01.job_type_id, T02.name as job_type_name 
        FROM user_job_type T01 
        LEFT JOIN job_type T02 ON T01.job_type_id = T02.id 
        WHERE T01.user_id = '$id'";
$job_res = $mysqli->query($sql);

?>