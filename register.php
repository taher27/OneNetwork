<?php
require_once "config.php";
require_once "functions.php";
$error = "";
$msg = "";


if(logged_in()){
    redirect("home/");
}
if(isset($_POST["register_btn"])){
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
	if(empty($userid) || empty($firstname) || empty($lastname) || empty($password)){
		$error = "All fields are required";
	}else{
		if(user_already_exists($con, $userid)){ // if user already exists then help him/her to change his password
			$error = "User already registered <a href='forgot_password.php?user=".$userid."'>Click here</a> to change the password";
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
					$query = "INSERT INTO `create_users` (`code1`,`code2`,`userid`,`password`,`firstname`,`lastname`,`time`) VALUES('".mysqli_real_escape_string($con, $code1)."','".mysqli_real_escape_string($con, $code2)."','".mysqli_real_escape_string($con, $userid)."','".mysqli_real_escape_string($con, md5($password))."','".mysqli_real_escape_string($con, ucfirst(strtolower($firstname)))."','".mysqli_real_escape_string($con, ucfirst(strtolower($lastname)))."',".time().")";
					if(mysqli_query($con, $query) or die(mysqli_error($con))){
							$url = "http://".$_SERVER['SERVER_NAME'].chop($_SERVER["PHP_SELF"],"register.php")."add_user.php?code1=".$code1."&code2=".$code2;
							$header = "From: One Network <onenetwork.2019@gmail.com>\r\n";
							$header .= "Content-Type: text/html\r\n";
							$body = "
							<p><a href='$url'>Click here</a> or copy the link below and paste in your browser to verify the email</p>
							<p>$url</p>
							";
							mail($email, "Email verification", $body, $header);
							$msg = "Mail sent to ".htmlentities($email);
							
							$userid = "";
							$password = "";
							$firstname = "";
							$lastname = "";
					}else{
							$error = "Something went wrong. Try again";
					}
					
			}else{
					$userid = "";
					$password = "";
					$firstname = "";
					$lastname = "";
					$msg = "Mail has already sent to ".htmlentities($email);
			}
		}
	}
}



?>

<!DOCTYPE <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>One Network</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- <link rel="stylesheet" type="text/css" media="screen" href="signup.css" />-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<style>
#form{

    background-color: #002a6b;
    min-height: 450px;
    padding: 5px 40px 40px 40px;
    border-radius: 15px;
    
}
::placeholder{
    text-align: center;
}
.text{
    height: 43px;
}

.btn-signup{
    border-radius: 6px;
    padding: 10px;
    width: 40%;
    color: #002a6b !important;
}
.btn-signup:hover{
    color: #ffffff !important;
font-weight: 700 !important;
letter-spacing: 3px;
background: none;
-webkit-box-shadow: 0px 5px 40px -10px #ffffff;
-moz-box-shadow: 0px 5px 40px -10px #002a6b;
transition: all 0.2s ease 0s;
border: none;
}
.btn-reset{
    border-radius: 6px;
    padding: 10px;
    width: 40%;
    color: #002a6b !important;
}
.btn-reset:hover{
    color: #ffffff !important;
font-weight: 700 !important;
letter-spacing: 3px;
background: none;
-webkit-box-shadow: 0px 5px 40px -10px #ffffff;
-moz-box-shadow: 0px 5px 40px -10px #002a6b;
transition: all 0.2s ease 0s;
border: none;
}

a{
      color: white;
    text-decoration: none;
    
    }
a:hover{

       color:orangered;
    }

</style>

</head>
<body>
<div class="container" style="margin-top:1%">
    <center>
        <img src="images/logo.jpg" class="img-responsive" alt="logo"  width="280" height="150" >
       
</center>

    <div class="row" style="justify-content:center">
        <div class="col-md-6 col-md-offset-3" id="form">
            <br>
            <form class="form" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <div class="form-group">
                        <input id="username" name="userid" class="form-control text" autocomplete="off" autofocus="on" required="required" type="text" placeholder="ID" value="<?php if(isset($userid)){ echo addslashes(htmlentities($userid)); } ?>"/>
                        <br/><input id="fname" name="firstname" class="form-control text" autocomplete="off" autofocus="on" required="required" type="text" placeholder="FIRST NAME" value="<?php if(isset($firstname)){ echo addslashes(htmlentities($firstname)); } ?>"/>
                    <br/><input id="lname" name="lastname" class="form-control text" autocomplete="off" autofocus="on" required="required" type="text" placeholder="LAST NAME" value="<?php if(isset($lastname)){ echo addslashes(htmlentities($lastname)); } ?>"/>
                    <br/><input id="password" name="password" class="form-control text" autocomplete="off" autofocus="on" required="required" type="password" placeholder="PASSWORD"/>
                                       
                    </div>
                    <br/>
                    <div class="form-group">
                        <center>
                            <input name="register_btn" type="submit" id="signupbtn" class=" btn-signup btn-light" value="SignUp" /> &nbsp; &nbsp; &nbsp;&nbsp;
                            <input type="reset" id="resetbtn" class=" btn-reset btn-light" value="Reset" />
                            <br />
                            
			<font color="red"><?php echo $error; ?></font>
			<font color="green"><?php echo $msg; ?></font>
                            <br />
                        <a href="index.php">Login!</a>
                        </center>
                        </div>
            </form>

        </div>

    </div>

</div>






</body>
</html>