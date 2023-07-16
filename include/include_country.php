<?php
$db_countryName = [];
$sql = "SELECT id, name FROM `country_list`";
$result = $mysqli->query($sql);
if ($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $id             = (int) ($row['id']);
        $name        = htmlspecialchars($row['name']);
        $data['id']     = $id;
        $data['name']   = $name;
        array_push($db_countryName, $data);
    }
}

?>