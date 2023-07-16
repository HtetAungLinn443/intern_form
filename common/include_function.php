<?php
function changeYmdFormat($dateString){
    $date_obj       = DateTime::createFromFormat('m/d/Y', $dateString);
    if ($date_obj !== false) {
        $formattedDate  = $date_obj->format('Y-m-d');
        return $formattedDate;
    } else {
        return 'Invalid date format';
    }
}

function changemdYFormat($dateString){
    $date_obj       = DateTime::createFromFormat('Y-m-d', $dateString);
    $formattedDate  = $date_obj->format("m/d/Y");
    return $formattedDate;
}

?>