<?php
require_once "../config.php";
require_once "../functions.php";
$error = "";
if(!logged_in()){
    redirect("../");
}
if(isset($_SESSION["firsttime"])){
    redirect("interests.php");
}

if(isset($_POST["post_project"])){
    if(empty($_POST["project_name"]) || empty($_POST["description"])){ //validate fields if submitted
        $error = "Fill all require fields!";
    }else{ // project is postable
        $project_name = mysqli_real_escape_string($con, $_POST["project_name"]);
        $description = mysqli_real_escape_string($con, $_POST["description"]);
        $project_type = mysqli_real_escape_string($con, $_POST["project_type"]);
        $query = "";
        if($_SESSION["role"] == 2 && $_POST["mentor"]=="yes"){
            $query = "INSERT INTO `projects` (`title`, `description`, `project_type`, `creator`, `time`, `status`) VALUES('".$project_name."', '".$description."', ".$project_type.", '".$_SESSION["userid"]."', ".time().", 1)";
        }else{
            $query = "INSERT INTO `projects` (`title`, `description`, `mentor`, `project_type`, `creator`, `time`, `status`) VALUES('".$project_name."', '".$description."', '".$_SESSION["userid"]."',".$project_type.", '".$_SESSION["userid"]."', ".time().", 1)";
        }
        mysqli_query($con, $query) or die(mysqli_error($con));

        $query = "SELECT `id` FROM `projects` WHERE `creator` = '".$_SESSION["userid"]."' ORDER BY `id` DESC LIMIT 1";
        $result = mysqli_query($con, $query) or die(mysqli_error($con));
        $data = mysqli_fetch_assoc($result);
        $project_id=$data["id"];
        if(isset($_POST["interest"])){
            
            $interests = $_POST["interest"];
            $query = "INSERT INTO `project_in_interests` (`project_id`, `interest_id`) VALUES";
            $first = 1;
            foreach($interests as $interest){
                if($first == 1){
                    $query .= "(".mysqli_real_escape_string($con, $project_id).", ".mysqli_real_escape_string($con, $interest).")";
                    $first = 0;
                }else{
                    $query .= ",(".mysqli_real_escape_string($con, $project_id).", ".mysqli_real_escape_string($con, $interest).")";
                }
            }
            mysqli_query($con, $query) or die(mysqli_error($con));
        }

        redirect("index.php");
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add project | One Network</title>

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../vendors/jqvmap/dist/jqvmap.min.css">

    <link rel="stylesheet" href="../css/index.css">

</head>

<body>

    <div class="wrapper">

        <!-- Page Content Holder -->
        <div id="content">

            <?php require_once "header.php"; ?>

            <div class="container">
                <div class="form_center">
                    
                    <form class="form-horizontal postProject_form" method="post">   
                        <font color="red"><?php echo $error; ?></font>
                        <div class="form-group">
                            <label for="exampleFormControlInput1"><strong>Name of the Project <font color="red">*</font></strong></label>
                            <div class="col-md-20">
                                <input id="exampleFormControlInput1" name="project_name" type="text" placeholder="" class="form-control" value="<?php if(isset($_POST["project_name"])){ echo $_POST["project_name"]; } ?>" />
                            </div>
                        </div>

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

                        <div class="form-group">
                            <label for="sel1"><strong>Project Type: </strong></label>
                            <div class="col-md-20">
                                <select name="project_type" class="form-control" id="sel1">
                                    <?php
                                        $query = "SELECT * FROM `project_types`";
                                        $result = mysqli_query($con, $query);
                                        while($data = mysqli_fetch_assoc($result)){
                                            echo "<option value='".$data["id"]."'>".$data["name"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                            if($_SESSION["role"]==2){
                                ?>
                                <div class="form-group">
                                    <label for="sel1"><strong>Do you want mentor for this project? </strong></label>
                                    <div class="col-md-20">
                                        <select name="mentor" class="form-control" id="sel1">
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>

                        <div class="form-group">
                            <label for="description"><strong>Description of Project <font color="red">*</font></strong></label>
                            <div class="col-md-20">
                                <textarea name="description" class="form-control" rows="5" id="description"><?php if(isset($_POST["description"])){ echo $_POST["description"]; } ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <button name="post_project" type="submit" class="btn btn-primary btn-lg">Submit</button>&nbsp;&nbsp;&nbsp;<a href="index.php">Cancel</a>
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