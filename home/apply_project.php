<?php
require_once "../config.php";
require_once "../functions.php";
$error= "";
if(!logged_in()){
    redirect("../");
}
if(isset($_SESSION["firsttime"])){
    redirect("interests.php");
}

if(!isset($_GET["id"])){
    die("Invalid link");
}else{
    if(isset($_POST["apply_project"])){
        $project_id = mysqli_real_escape_string($con, $_GET["id"]);
        $message = mysqli_real_escape_string($con, $_POST["msg"]);
        $query = "SELECT `projects`.`id`, `creator`  from `projects` JOIN `project_status` ON (projects.status = project_status.id AND `project_status`.status='open')  WHERE `projects`.`id` = ".$project_id." AND `deleted` = 0";
        $result = mysqli_query($con ,$query) or die(mysqli_error($con));
        $data = mysqli_fetch_assoc($result);
        $owner = $data["creator"];
        if(mysqli_num_rows($result) > 0){// if project is appliable (e.g not deleted or not closed)
            $query = "SELECT * FROM `applied_users` WHERE `project_id` = ".$project_id." AND `user_id`='".$_SESSION["userid"]."'";
            $result = mysqli_query($con, $query) or die(mysqli_error($con));
            if(mysqli_num_rows($result) > 0){ // user as already applied on this project
                $error = "You have already applied on this project";
            }else{//
                $query = "INSERT INTO `applied_users` (`user_id`, `project_id`, `approved`, `resume`, `message`) VALUES('".$_SESSION["userid"]."', ".$project_id.", 0, 0, '".$message."')";
                mysqli_query($con, $query) or die(mysqli_error($con));
                $query = "INSERT INTO `notifications` (`owner_id`, `project_id`, `user_id`, `type`, `seen`, `time`) VALUES('".$owner."', ".$project_id.", '".$_SESSION["userid"]."', 'applied_student', 0, ".time().")";
                mysqli_query($con, $query) or die(mysqli_error($con));
                echo '<script>window.close();</script>';
            }
        }else{
            $error = "Project is not in state of applying";
        }
    }
}
    



?>

<!DOCTYPE <!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>One Network | DAIICT</title>

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../vendors/jqvmap/dist/jqvmap.min.css">

    <link rel="stylesheet" href="../css/index.css">


<style>
    
#form{

    background-color: #fff;
    min-height: 300px;
    padding: 5px 40px 40px 40px;
    border-radius: 15px;
    
}


.btn-signup{
    border-radius: 6px;
    padding: 10px;
    width: 40%;
    margin-top: 10%;
    color: #002a6b !important;
}
.btn-signup:hover{
    color: #002a6b !important;
font-weight: 700 !important;
letter-spacing: 3px;
background: none;
-webkit-box-shadow: 0px 5px 40px -10px orangered;
-moz-box-shadow: 0px 5px 40px -10px orangered;
transition: all 0.2s ease 0s;
border: none;
}
::placeholder{
  text-align: center;
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
<div class="wrapper">

        <!-- Page Content Holder -->
        <div id="content">

        <?php require_once "header.php"; ?>

<div class="container bg" style="margin-top:1%;">

    <div class="row justify-content-center">
        <div class="col-md-6 col-md-offset-3" id="form">
            
            <form action="<?php echo $_SERVER["PHP_SELF"].'?id='.$_GET["id"]; ?>" method="POST">
                <div class="form-group">
                    <label>
                        <font color="red"><?php echo $error; ?><br /></font>
                        Why do you want to apply for this project?
                    </label>
                    <textarea id="textbox" name="msg" class="form-control" autofocus rows="10"></textarea>
                    <div class="row justify-content-center">
                    <input name="apply_project" type="submit" id="signupbtn" class="btn btn-signup btn-light" value="submit" style="align-items: center" /> 
                    </div>
                </div>
            </form>

        </div>

    </div>

</div>
</div>
</div>
</body>
</html>