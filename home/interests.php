<?php
require_once "../config.php";
require_once "../functions.php";
if(!logged_in()){
    redirect("../");
}
if(!isset($_SESSION["firsttime"])){
    redirect("index.php");
}
if(isset($_GET["skip"])){
    unset($_SESSION["firsttime"]);
    $query = "UPDATE `users` SET `firsttime`=0 WHERE `id`='".$_SESSION["userid"]."'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    redirect("index.php");
}
if(isset($_GET["next"])){
    unset($_SESSION["firsttime"]);
    redirect("index.php");
}
if(isset($_POST["interest_btn"])){
    $query = "DELETE FROM `user_interests` WHERE `user_id`='".$_SESSION["userid"]."'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    $interests = $_POST["interest"];
    $query = "INSERT INTO `user_interests` (`user_id`, `interest_id`) VALUES";
    $first = 1;
    foreach($interests as $interest){
        if($first == 1){
            $query .= "('".$_SESSION["userid"]."', ".mysqli_real_escape_string($con, $interest).")";
            $first = 0;
        }else{
            $query .= ",('".$_SESSION["userid"]."', ".mysqli_real_escape_string($con, $interest).")";
        }
    }
    mysqli_query($con, $query) or die(mysqli_error($con));

    unset($_SESSION["firsttime"]);
    $query = "UPDATE `users` SET `firsttime`=0 WHERE `id`='".$_SESSION["userid"]."'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    redirect("index.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Collapsible sidebar using Bootstrap 4</title>

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../vendors/jqvmap/dist/jqvmap.min.css">

    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/interests.css">

</head>

<body>

    <div class="wrapper">

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <div class="container-fluid">
                        
                        <div class="sidebar-header">
                            <img src="../images/logo.jpg" class="logo">
                        </div>
    
                        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-align-justify"></i>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto temp2">
                                <li class="nav-item">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle temp" type="button" id="notification" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-bell fa-2x"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="notification">
                                        <p class="red">You have 3 Notification</p>
                                        <a class="dropdown-menu-items" href="#">
                                            Server #1 overloaded.<br>
                                        </a>
                                        <a class="dropdown-menu-items" href="#">
                                            Server #2 overloaded.<br>
                                        </a>
                                        <a class="dropdown-menu-items" href="#">
                                            Server #3 overloaded.<br>
                                        </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item logout_container">
                                        <a class="logout" href="logout.php"><img src="../images/logout.png" height="25px" width="25px" ><br>Logout</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#">
                                        <img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

            <div class="container">
                <div class="form_center">
                    <form class="form-horizontal postProject_form mainclass" method="POST">   
                    <?php
                $query = "SELECT * FROM `interest_categories`";
                $result = mysqli_query($con, $query) or die(mysqli_error($con));
                while($data = mysqli_fetch_array($result)){
                    ?>
                        <div class="form-group">
                            <label for="exampleFormControlInput2"><strong><?php echo htmlentities($data["name"]); ?></strong></label>
                            <div class="col-md-20" id="exampleFormControlInput2">

                            <?php
                    $query1 = "SELECT * FROM `interests` WHERE `category`=".$data["id"];
                    $result1 = mysqli_query($con, $query1) or die(mysqli_error($con));
                    while($data1 = mysqli_fetch_array($result1)){
                        ?>
                             <label class="checkbox-inline">
                                        <input type="checkbox" name="interest[]" value="<?php echo $data1['id'] ?>" /><?php echo htmlentities($data1["name"]); ?>
                                    </label>
                        <?php
                    }
                    ?>
                     </div>
                        </div>
                        <?php

                }
                ?>


                    <!-- THESE ARE DESIGN PROTOTYPE
                        <div class="form-group">
                            <label for="exampleFormControlInput2"><strong>Technologies</strong></label>
                            <div class="col-md-20" id="exampleFormControlInput2">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="">Ambient intelligence
                                    </label>

                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="">Artificial brain
                                    </label>

                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="">Blockchain
                                    </label>

                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="">Internet Of Things
                                    </label>
                                    <label class="checkbox-inline">
                                            <input type="checkbox" value="">Ambient intelligence
                                        </label>
    
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">Artificial brain
                                        </label>
    
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">Blockchain
                                        </label>
    
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">Internet Of Things
                                        </label>
                            </div>
                        </div>
                        <div class="form-group">
                                <label for="exampleFormControlInput2"><strong>Languages</strong></label>
                                <div class="col-md-20" id="exampleFormControlInput2">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">JAVA
                                        </label>
    
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">PHP
                                        </label>
    
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">MYSQL
                                        </label>
    
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">HTML
                                        </label>
                                        <label class="checkbox-inline">
                                                <input type="checkbox" value="">JAVA
                                            </label>
        
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="">PHP
                                            </label>
        
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="">MYSQL
                                            </label>
        
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="">HTML
                                            </label>
                                </div>
                            </div>
                        -->
                        <div class="links"> 
                            <button type="button" class="btn btn-link">
                                <a href="interests.php?skip">
                                    Skip
                                </a>
                            </button>
                            <button type="button" class="btn btn-link">
                                <a href="interests.php?next">
                                    Ask Me Next Time
                                </a>
                            </button>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <button name="interest_btn" type="submit" class="btn btn-primary btn-lg">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
                  
        </div>


    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="../vendors/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
</body>

</html>