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
    $iserror = true;
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
    min-height: 300px;
    padding: 5px 40px 40px 40px;
    border-radius: 15px;
    
}
.text{
    height: 43px;
    
}

.btn-signup{
    border-radius: 6px;
    padding: 10px;
    width: 40%;
    margin-top: 10%;
    color: #002a6b !important;
}
.btn-signup:hover{
    color: #fff !important;
font-weight: 700 !important;
letter-spacing: 3px;
background: none;
-webkit-box-shadow: 0px 5px 40px -10px #ffffff;
-moz-box-shadow: 0px 5px 40px -10px #002a6b;
transition: all 0.2s ease 0s;
border: none;
}
::placeholder{
  text-align: center;
}

.btn-reset{
    border-radius: 6px;
    padding: 10px;
    width: 40%;
    
    color: #002a6b !important;
}
input{
  margin-top: 2vh;
  margin-bottom: 5vh;
}
.form-group{
  margin-bottom: -5vh;
}
</style>

</head>
<body>

<?php
            if($iserror){
                die("Invalid request");
            }
?>

<div class="container bg" style="margin-top:1%;">
    <center>
        <img src="images/logo.jpg" class="img-responsive" alt="logo"  width="280" height="150" >
       
</center>

    <div class="row" style="justify-content:center">
        <div class="col-md-6 col-md-offset-3" id="form">
            <br>
            <br>
            <form class="form" method="POST" action="<?php echo $_SERVER["PHP_SELF"]."?code1=".$_GET["code1"]."&code2=".$_GET["code2"]; ?>">
                <div class="form-group">
                        <input id="username" name="password" class="form-control text" autocomplete="off" autofocus="on" required="required" type="password" placeholder="Enter the new password"/>
                                                                       
                </div>

                    <div class="form-group">
                        <center>
                            <input name="password_btn" type="submit" id="signupbtn" class=" btn-signup btn-light" value="Change" /> 
                            <br />
            <font color="red"><?php echo $error; ?></font>
            <font color="green"><?php echo $suc; ?></font>
                        </center>
                        </div>




            </form>

        </div>

    </div>

</div>






</body>
</html>