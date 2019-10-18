<?php
require_once "config.php";

$userid = $_REQUEST["userid"];

$arr =  $_REQUEST["arr"];

foreach ($arr as $a) {
    $sql = "INSERT INTO user_interests (user_id,interest_id) VALUES ($userid,$a)";

    if(mysqli_query($con ,$sql)){
        $msg = array('error' => 'false', 'message' => 'success', 'description' => 'Data inserted successfully' ); 
    }
    else{
        $msg = array('error' => 'true', 'message' => 'Not success', 'description' => 'Data not inserted'); 
    }
}
echo json_encode($msg);

?>
