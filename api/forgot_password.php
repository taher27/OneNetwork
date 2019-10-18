<?php
require_once "config.php";
require_once "functions.php";

// if(logged_in()){
//     redirect("home/");
// }

$userid = mysqli_real_escape_string($con,$_GET['user']);


$msg = "";
if(!isset($_GET["user"])){
    $msg = "Invalid link";
    redirect("forgot.php");
}else{ // if user exists
    $userid = $_GET["user"];
    $sendmail = false;
    $email = $userid;
	if(strpos($userid,'@daiict.ac.in') == false){
		$email .= '@daiict.ac.in';
	}else{
		$userid = chop($userid, '@daiict.ac.in');
    }
    if(!user_already_exists($con ,$userid)){
        // $msg = "User doesn't exists <br /><a href='register.php'>Click here</a> to register";
        $response = array("error"=>"true", "message" => "not success", "description" => "User doesn't exist");
    }else{
        $query = "SELECT * FROM `forgot_passwords` WHERE `userid`='".mysqli_real_escape_string($con, $userid)."' AND `used`=0 ORDER BY `time` DESC LIMIT 1";
        $result = mysqli_query($con ,$query) or die(mysqli_error($con));
        if(mysqli_num_rows($result) > 0){ // check for request made previously to avoid mail spamming (If system has made same request withing 30 minutes then discard mail request)
            $data = mysqli_fetch_array($result);
            if($data["time"] < time()-(30*60)){ //previous request has made before 30 minutes from now
                $sendmail = true;
            }
        }else{ // no request made previously
            $sendmail = true;
        }
        if($sendmail){
            $code1 = rand(1, 1000000000);
            $code2 = rand(1, 1000000000);
            $code1 = md5($code1);
            $code2 = md5($code2);
            $query = "INSERT INTO `forgot_passwords` (`code1`,`code2`,`userid`,`time`,`used`) VALUES('".mysqli_real_escape_string($con, $code1)."','".mysqli_real_escape_string($con, $code2)."','".mysqli_real_escape_string($con, $userid)."',".time().",0)";
            if(mysqli_query($con, $query)){
                $url = "http://".$_SERVER['SERVER_NAME'].chop($_SERVER["PHP_SELF"],"forgot_password.php")."change_password.php?code1=".$code1."&code2=".$code2;
                $header = "From: One Network <onenetwork.2019@gmail.com>\r\n";
                $header .= "Content-Type: text/html\r\n";
                $body = "
                <p><a href='$url'>Click here</a> or copy the link below and paste in your browser to change the password</p>
                <p>$url</p>
                ";
                
                mail($email, "Password change request", $body, $header);
                $msg = "mail sent to ".$email;
                $response = array("error"=>"false", "message" => "success", "description" => "Mail sent to $email");
                
            }else{
                // redirect("forgot_password.php?user=".$userid);
                $response = array("error" => "false", "message" => "success", "url_to_redirect" => "forgot_password.php", 
        "userid" => $userid, "method_to_use" => "GET");
                
            }
            
        }else{
            $msg = "mail has already sent to ".$email;
            $response = array("error" => "true", "message" => "not success", "description" => "mail has already sent to $email");
        }
    }
    
}

?>

