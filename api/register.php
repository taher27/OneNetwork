<?php
require_once "config.php";
require_once "functions.php";
$error = "";
$msg = "";


if(logged_in()){
    redirect("home/");
}
// if(isset($_POST["register_btn"])){
    if(!empty($_POST["userid"]) && !empty($_POST["firstname"]) && !empty($_POST["lastname"]) && !empty($_POST["password"])){
        $userid = $_POST["userid"];
        $firstname = $_POST["firstname"];
	    $lastname = $_POST["lastname"];
	    $password = $_POST["password"];
	    $email = $userid;
	    if(strpos($userid,'@daiict.ac.in') == false){
		    $email .= '@daiict.ac.in';
	    }else{
		    $userid = chop($userid, '@daiict.ac.in');
	    }
    }
// 	$userid = $_POST["userid"];
// 	$firstname = $_POST["firstname"];
// 	$lastname = $_POST["lastname"];
// 	$password = $_POST["password"];
// 	$email = $userid;
// 	if(strpos($userid,'@daiict.ac.in') == false){
// 		$email .= '@daiict.ac.in';
// 	}else{
// 		$userid = chop($userid, '@daiict.ac.in');
// 	}
	if(empty($userid) || empty($firstname) || empty($lastname) || empty($password)){
		// $error = "All fields are required";
		$response = array("error" => "true", "message" => "not success", "description" => "All the fields are required");
	}else{
		if(user_already_exists($con, $userid)){ // if user already exists then help him/her to change his password
			// $error = "User already registered <a href='forgot_password.php?user=".$userid."'>Click here</a> to change the password";
			$response = array("error"=>"true", "message" => "User already registered", "url" => "forgot_password.php", "userid" => "$userid");
		}else{ // send mail if user doesn't exist and all fields are validated
			$sendmail = false;
			$query = "SELECT * FROM `create_users` WHERE `userid`='".mysqli_real_escape_string($con, $userid)."' ORDER BY `time` DESC LIMIT 1";
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
					$query = "INSERT INTO `create_users` (`code1`,`code2`,`userid`,`password`,`firstname`,`lastname`,`time`) VALUES('".mysqli_real_escape_string($con, $code1)."','".mysqli_real_escape_string($con, $code2)."','".mysqli_real_escape_string($con, $userid)."','".mysqli_real_escape_string($con, md5($password))."','".mysqli_real_escape_string($con, $firstname)."','".mysqli_real_escape_string($con, $lastname)."',".time().")";
					if(mysqli_query($con, $query) or die(mysqli_error($con))){
							$url = "http://".$_SERVER['SERVER_NAME'].chop($_SERVER["PHP_SELF"],"/register.php")."pi/add_user.php?code1=".$code1."&code2=".$code2;
							$header = "From: One Network <onenetwork.2019@gmail.com>\r\n";
							$header .= "Content-Type: text/html\r\n";
							$body = "
							<p><a href='$url'>Click here</a> or copy the link below and paste in your browser to verify the email</p>
							<p>$url</p>
							";
							mail($email, "Email verification", $body, $header);
							$msg = "Mail sent to ".htmlentities($email);
							
							$response = array("error" => "false", "message" => "Success", "description" => "Mail sent to $email");
							
							$userid = "";
							$password = "";
							$firstname = "";
							$lastname = "";
					}else{
				// 			$error = "Something went wrong. Try again";
							$response = array("error" => "true", "message" => "Not Success", "description" => "Something went wrong. Try again");
					}
					
			}else{
					$userid = "";
					$password = "";
					$firstname = "";
					$lastname = "";
					$msg = "Mail has already sent to ".htmlentities($email);
					$response = array("error" => "true", "message" => "Not Success", "description" => "Mail has already sent to $email");
			}
		}
	}
	header('content-type: application/json');
  echo json_encode($response);
// }



?>

