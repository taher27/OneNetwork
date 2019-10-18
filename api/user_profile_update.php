<?php
    include "config.php";
    include "functions.php";

    if(!empty($_REQUEST['userid'])){
        $userid = $_REQUEST['userid'];
        $userdetails = "SELECT * FROM users WHERE id = $userid";
        $result_userdetails = mysqli_query($con,$userdetails);
        $row = mysqli_fetch_assoc($result_userdetails);
    
        if($row<1){
            $response = array("error" => "true", "message" => "not success", "description" => "could not fetch details");
        }
        else{
            $profile_pic_url = chop($_SERVER['PHP_SELF'],"user_profile_update.php")."profile_images/".$userid;
            
            $response = array("error" => "false", "message" => "success", "userdetails" => $row, "url" => $profile_pic_url);
        }    
    }
    else{
        $response = array("error" => "true", "message" => "not success", "description" => "userid cannot be empty");
    }
    header('content-type: application/json');
    echo json_encode($response);
?>