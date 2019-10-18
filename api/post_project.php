<?php
require_once "config.php";
header('content-type: application/json');

$title = $_REQUEST['title'];
$description = $_REQUEST['description'];
$userid = $_REQUEST['userid'];
$project_type = $_REQUEST['project_type'];


$creator = $userid;
$time = time();
$status = 1;

$role = "SELECT role FROM users WHERE id = '".$userid."'";

$result = mysqli_query($con ,$role);

$row = mysqli_fetch_assoc($result);

if($row['role'] == 1){
    $mentor = strval($userid);
    
$insertproject = "INSERT INTO projects (title , description , mentor, project_type , creator , time , status) VALUES
 ('$title','$description',$mentor,$project_type,'$creator',$time,$status)";
}
else{
    
$insertproject = "INSERT INTO projects (title , description , project_type , creator , time , status) VALUES
 ('$title','$description',$project_type,'$creator',$time,$status)";
}

$result2 = mysqli_query($con,$insertproject);

if($result2){
    $msg = array('error' => 'false' ,'message' => 'success', 'description' => 'project added successfully');
}
else
{
    $sqlerror = mysqli_error($con);
    $msg = array('error' => 'true' ,'message' => 'not success', 'description' => 'project not added' ,'sqlerror' => $sqlerror);
}


echo json_encode($msg);

?>