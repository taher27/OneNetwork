<?php
require_once "config.php";
require_once "functions.php";

if(logged_in()){
    redirect("home/");
}
$error="";
$suc="";
$iserror = false;
if(!isset($_GET["code1"]) || !isset($_GET["code2"])){
    $error = true;
}else{
    $code1 = $_GET["code1"];
    $code2 = $_GET["code2"];
    $query = "SELECT * FROM `forgot_passwords` WHERE `code1`='".mysqli_real_escape_string($con, $code1)."' AND `code2`='".mysqli_real_escape_string($con, $code2)."' AND `used`=0 AND `time` > ".(time()-(30*60))." ORDER BY `time` DESC LIMIT 1";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    if(mysqli_num_rows($result) > 0){
        if(isset($_POST["password_btn"])){
            if(empty($_POST["password"])){
                $error = "Password can't be empty";
            }else{
                $data = mysqli_fetch_array($result);
                $query = "UPDATE `users` SET `password`='".mysqli_real_escape_string($con, md5($_POST["password"]))."' WHERE `id`='".mysqli_real_escape_string($con, $data["userid"])."'";
                mysqli_query($con, $query) or die(mysqli_error($con));
                $query = "UPDATE `forgot_passwords` SET `used`=1 WHERE `code1`='".mysqli_real_escape_string($con, $code1)."' AND `code2`='".mysqli_real_escape_string($con, $code2)."'";
                mysqli_query($con, $query) or die(mysqli_error($con));
                $suc = "Password changed successfully<br /><a href='index.php'>Click here</a> to login";
            }
        }
    }else{
        $iserror = true;
    }
}


?>

<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Calm breeze login screen</title>
  
  
  
      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>

  <div class="wrapper">
	<div class="container">
		
        <?php
            if($iserror){
                ?>
                    <h1>Invalid request</h1>
                <?php
            }else{
        ?>
        <h1>Change the password</h1>
		<form class="form" method="POST" action="<?php echo $_SERVER["PHP_SELF"]."?code1=".$_GET["code1"]."&code2=".$_GET["code2"]; ?>">
			<input type="password" name="password" placeholder="Enter the new password">
			<button type="submit" name="password_btn" id="login-button">Login</button><br />
            <font color="red"><?php echo $error; ?></font>
            <font color="green"><?php echo $suc; ?></font>
		</form>
        <?php
            }
        ?>
	</div>
	
	<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  

 



</body>

</html>
