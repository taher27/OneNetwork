<?php 

header('content-type: application/json');

include "../config.php";
include "../functions.php";


$userid = mysqli_real_escape_string($con,$_POST['userid']);
$password = md5(mysqli_real_escape_string($con,$_POST['password']));

$passcheck = "SELECT * FROM users WHERE id = '$userid' and password = '$password' ";

$result2 = mysqli_query($con, $passcheck);

$row2 = mysqli_fetch_row($result2);

if($row2>0){
    if($row2[5] == 1){
        $msg = array('error' => 'false', 'message' => 'success', 'description' => 'Login Successful', 'visited' => 'false'); 
    }
    else{
        $msg = array('error' => 'false', 'message' => 'success', 'description' => 'Login Successful', 'visited' => 'true');
    }
}
else{
    $msg = array('error' => 'true', 'message' => 'not success', 'description' => "user doesn't exists");
}

echo json_encode($msg);

mysqli_close($con);

?>