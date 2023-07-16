<?php 
$db_job_types = [];
$sql = "SELECT id, name FROM `job_type`";
$result = $mysqli->query($sql);
if ($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $id             = (int) ($row['id']);
        $name        = htmlspecialchars($row['name']);
        $data['id']     = $id;
        $data['name']   = $name;
        array_push($db_job_types, $data);
    }
}

?>