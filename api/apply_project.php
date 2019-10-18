<?php

require_once "config.php";

$userid = $_REQUEST['userid'];
$projectid = $_REQUEST['projectid'];
$isresume = $_REQUEST['resume'];
$message = "applied_student";

//INSERT INTO applied_users
$insert = "INSERT INTO applied_users (user_id,project_id,resume,message) VALUES ('$userid',$projectid,$isresume,'$message')";
$result = mysqli_query($con,$insert);

//INSERT INTO notifications

//OBTAIN REQUIRED FILEDS

$sql = "SELECT creator, project_type FROM projects WHERE id=$projectid";
$resultsql = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($resultsql);
$creator = $row['creator'];
$project_type = $row['project_type'];

$insert_into_notifications =  "INSERT INTO notifications (owner_id,project_id,user_id,type) VALUES ('$creator',$projectid,'$userid','$project_type')";
$result2 = mysqli_query($con,$insert_into_notifications);

$noti = "SELECT u.firstname,p.title from users u inner join notifications n on u.id = n.user_id join projects p on p.id = $projectid"; 

$r = mysqli_query($con,$noti);

$arr = array();

if($r)
{
        $rows3 = mysqli_fetch_assoc($r);
        $str = $rows3['firstname']." has applied for ".$rows3['title'];
        $response = array('error' => 'false' , 'message' => 'success' , 'description' => "successfully sent", 'data' => $str);        
}else{
   $response = array('error' => 'true' , 'message' => 'not success' , 'description' => "Could not apply for project","erro2"=>mysqli_error($con));  
}

header('content-type: application/json');
echo json_encode($response);
?>