<?php
require_once "config.php";
require_once "functions.php";

if(logged_in()){
    // redirect("home/");
}
$error="";
$suc="";
$iserror = false;
if(!isset($_GET["code1"]) || !isset($_GET["code2"])){
    $iserror = true;
}else{
    $code1 = $_GET["code1"];
    $code2 = $_GET["code2"];
    $query = "SELECT * FROM `create_users` WHERE `code1`='".mysqli_real_escape_string($con, $code1)."' AND `code2`='".mysqli_real_escape_string($con, $code2)."' AND `time` > ".(time()-(30*60))." ORDER BY `time` DESC LIMIT 1";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    if(mysqli_num_rows($result) > 0){
       
            $data = mysqli_fetch_array($result);
            $role = 1;
            if(is_numeric($data["userid"])){
                $role = 2;
            }
            $query = "INSERT INTO `users` (`id`,`firstname`, `lastname`, `password`, `role`, `firsttime`) VALUES('".mysqli_real_escape_string($con, $data["userid"])."' ,'".mysqli_real_escape_string($con, $data["firstname"])."', '".mysqli_real_escape_string($con, $data["lastname"])."', '".mysqli_real_escape_string($con, $data["password"])."', ".mysqli_real_escape_string($con, $role).", 1)";
            mysqli_query($con, $query) or die(mysqli_error($con));
            $query = "DELETE FROM `create_users` WHERE `code1`='".mysqli_real_escape_string($con, $code1)."' AND `code2`='".mysqli_real_escape_string($con, $code2)."'";
            mysqli_query($con, $query) or die(mysqli_error($con));
            $_SESSION["userid"] = $data["userid"];
            $_SESSION["firsttime"] = 1;
// 			redirect("home/interests.php");	
            
        
    }else{
        $iserror = true;
    }
}


?>

<html>
    <head>
        
    </head>
    <body>
        You have successfully registered.
        <br>
        You can Login from App now.
    </body>
</html>
