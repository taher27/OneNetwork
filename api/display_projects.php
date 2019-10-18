<?php

require_once "config.php";
header('content-type: application/json');

$query = "SELECT * FROM projects ORDER BY time DESC";
$res = mysqli_query($con,$query);

$projects = array();
if(mysqli_num_rows($res)<1){
    $msg = array('error' => 'true' , 'message' => 'not success' , 'description' => "NO projects available");
}
else{
    while($row = mysqli_fetch_assoc($res))
    {
        array_push($projects , $row);
    }
$msg = array('error' => 'false' , 'message' => 'success' , 'projects' => $projects);    
}


echo json_encode($msg);

?>