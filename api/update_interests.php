<?php
require_once "config.php";

$userid = $_REQUEST["userid"];
$arr =  $_REQUEST["arr"];

// foreach($arr as $a)
// {
//     echo $a;
// }

$query = "DELETE FROM user_interests WHERE user_id = $userid";

if(mysqli_query($con ,$query)){
        $msg = array('error' => 'false', 'message' => 'success', 'description' => 'Data deleted successfully' ); 
}
foreach ($arr as $a) {
    $sql = "INSERT INTO user_interests (user_id,interest_id) VALUES ($userid,$a)";

    if(mysqli_query($con ,$sql)){
        $msg = array('error' => 'false', 'message' => 'success', 'description' => 'Data updated successfully' ); 
    }
    else{
        $msg = array('error' => 'true', 'message' => 'Not success', 'description' => 'Data not updated'); 
    }
}
 echo json_encode($msg);

?>
