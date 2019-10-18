<?php
require_once "config.php";
require_once "functions.php";

// $error = "";
// if(isset($_POST["forgot_btn"])){
    // redirect("forgot_password.php?user=".$_POST["userid"]);
    if(!empty($_POST['userid'])){
        $userid = $_POST['userid'];
        $response = array("error" => "false", "message" => "success", "url_to_redirect" => "forgot_password.php", 
        "userid" => $userid, "method_to_use" => "GET");
    }
    else{
        $response = array("error" => "true","message" => "success", "description" => "Please enter a userid");
    }
// }

header('content-type: application/json');
echo json_encode($response);

?>

