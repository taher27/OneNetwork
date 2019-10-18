<?php

require_once "config.php";

header('content-type: application/json');

$project_type = "SELECT * FROM project_types";
$result_ptypes = mysqli_query($con,$project_type);
            
$projects_array = array();


while($row = mysqli_fetch_assoc($result_ptypes)){
    array_push($projects_array , $row);
}
$response = array("error" => "false","message" => "success","Project types" => $projects_array);

echo json_encode($response);
?>