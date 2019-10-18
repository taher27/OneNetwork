<?php
require_once "../config.php";
require_once "../functions.php";

if(!logged_in()){
    redirect("../");
}
if(isset($_SESSION["firsttime"])){
    redirect("interests.php");
}

if(!isset($_GET["id"])){
    die("Invalid link");
}else{
    $user_id = mysqli_real_escape_string($con, $_GET["id"]);
    if(!user_already_exists($con, $user_id)){
        die("User doesn't exist");
    }else{ //  if user exists

        if(isset($_FILES['image'])){
            $errors= array();
            $file_name = $_FILES['image']['name'];
            $file_size =$_FILES['image']['size'];
            $file_tmp =$_FILES['image']['tmp_name'];
            $file_type=$_FILES['image']['type'];
            $tmp = explode('.', $file_name);
            $file_ext = end($tmp);
            
            $extensions= array("jpeg","jpg","png");
            
            if(in_array($file_ext,$extensions)=== false){
               $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }
            
            if($file_size > 2097152){
               $errors[]='File size must be excately 2 MB';
            }
            
            if(empty($errors)==true){
                $file_name = md5(rand(1,10000000));
                $file_name .= ".".$file_ext;
               if(move_uploaded_file($file_tmp,"../images/user_pics/".$file_name)){
                    $query = "UPDATE `users` SET `profile_pic`='".$file_name."' WHERE `id`='".$_SESSION["userid"]."'";
                    mysqli_query($con, $query) or die(mysqli_error($con));
               }else{
                    die("Some error occured");
               }
               
            }else{
               print_r($errors);
            }
         }

         //RESUME UPLOAD
         if(isset($_FILES['image1'])){
            $errors= array();
            $file_name = $_FILES['image1']['name'];
            $file_size =$_FILES['image1']['size'];
            $file_tmp =$_FILES['image1']['tmp_name'];
            $file_type=$_FILES['image1']['type'];
            $tmp = explode('.', $file_name);
            $file_ext = end($tmp);
            
            $extensions= array("pdf");
            
            if(in_array($file_ext,$extensions)=== false){
               $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }
            
            if($file_size > 2097152){
               $errors[]='File size must be excately 2 MB';
            }
            
            if(empty($errors)==true){
                $file_name = md5(rand(1,10000000));
                $file_name .= ".".$file_ext;
               if(move_uploaded_file($file_tmp,"../user_resumes/".$file_name)){
                    $query = "UPDATE `users` SET `resume`='".$file_name."' WHERE `id`='".$_SESSION["userid"]."'";
                    mysqli_query($con, $query) or die(mysqli_error($con));
               }else{
                    die("Some error occured");
               }
               
            }else{
               print_r($errors);
            }
         }


        $query = "SELECT `id`, `firstname`, `lastname`, `role`, `profile_pic`, `resume` FROM `users` WHERE `id`='".$user_id."'";
        $result = mysqli_query($con, $query) or die(mysqli_error($con));
        $data = mysqli_fetch_assoc($result);
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile | <?php echo $data["firstname"]." ".$data["lastname"]; ?></title>

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
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="../images/user_pics/<?php echo $data["profile_pic"]; ?>" width="170px" height="170px" class="logout" style="margin-left:20px;">
            </div>

            <div class="list-unstyled components">
                <center><p><?php echo $data["firstname"]." ".$data["lastname"]; ?></p>
                <?php
                    if($_SESSION["userid"] == $user_id){
                        ?>

<form action="<?php echo $_SERVER["PHP_SELF"]."?id=".$user_id; ?>" method="POST" enctype="multipart/form-data">
                    Select image to upload:
                    <input type="file" name="image" id="fileToUpload">
                    <input type="submit" value="Upload Image" name="file_upload">
                </form>

                <form action="<?php echo $_SERVER["PHP_SELF"]."?id=".$user_id; ?>" method="POST" enctype="multipart/form-data">
                    Select resume to upload:
                    <input type="file" name="image1" id="fileToUpload">
                    <input type="submit" value="Upload Resume" name="file_upload">
                </form>
                        <?php
                    }
                ?>
                <?php
                    if($data["role"] == 1){
                        echo '<font style="font-weight: bold; font-size: 20px;" color="green">[Faculty]</font>';
                    }
                ?>
                <?php
                    if((($_SESSION["role"] == 1) || ($_SESSION["userid"] == $data["id"])) && ($data["resume"] != null)){
                        echo '<font color="blue"><a target="_blank" href="../user_resumes/'.$data["resume"].'">Resume</a></font>';
                    }
                ?>
                
                </center>
                <li>
                    <div>
                        <p>Username :
                            <kbd style="background: transparent;"><?php echo $data["id"]; ?></kbd>   
                        </p>
                    </div>
                </li>
                <?php
                    if($user_id==$_SESSION["userid"]){
?>
        <form id="user_interest_update">


        <?php
                $query = "SELECT * FROM `interest_categories`";
                $result = mysqli_query($con, $query) or die(mysqli_error($con));
                while($data = mysqli_fetch_array($result)){
                    ?>


                <li>
                    <div class="form-group">
                        <label for="exampleFormControlInput2"><strong><?php echo htmlentities($data["name"]); ?></strong></label>
                        <div class="col-md-20" id="exampleFormControlInput2">
                        <?php
                    $query1 = "SELECT * FROM `interests` WHERE `category`=".$data["id"];
                    $result1 = mysqli_query($con, $query1) or die(mysqli_error($con));
                    while($data1 = mysqli_fetch_array($result1)){
                        $query3 = "SELECT * FROM `user_interests` WHERE `user_id`='".$user_id."' AND `interest_id`=".$data1["id"];
                        $checked = false;
                        $result3 = mysqli_query($con, $query3) or die(mysqli_error($con));
                        if(mysqli_num_rows($result3)>0){
                            $checked = true;
                        }
                        ?>
                                <label class="checkbox-inline">
                                    <input class="check_box" type="checkbox" name="interests[]" value="<?php echo $data1['id'] ?>" <?php if($checked){ echo "checked"; } ?>><?php echo htmlentities($data1["name"]); ?>
                                </label>

                               

                                <?php
                    }
?>
                        </div>
                    </div>
                </li>
                <?php
                    }
                    ?>
               
            </form>

<?php       
                    }else{
                        $query1 = "SELECT `interest_categories`.`name` , GROUP_CONCAT(`interests`.`name`) AS `interest` FROM `interests` JOIN `interest_categories` ON (interest_categories.id = interests.category) JOIN `user_interests` ON (interests.id = user_interests.interest_id AND user_interests.user_id = '".$user_id."') GROUP BY(`interest_categories`.`name`)";
                        $result1 = mysqli_query($con, $query1) or die(mysqli_error($con));
                        while($data1 = mysqli_fetch_assoc($result1)){
                            echo '
                            <li>
                            <div>
                                <p>'.$data1["name"].' :
                                    <kbd style="background: transparent;">'.$data1["interest"].'</kbd>   
                                </p>
                            </div>
                        </li>
                            ';
                        }
                    }
                ?>
                
            </div>
        </nav>
        <!-- Page Content Holder -->
        <div id="content">

           <?php require_once 'header.php'; ?>
            
            <div class="MainPannel Sections">
                <div class="container">
                    <form class="form-inline">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input class="form-control form-control-sm ml-3 w-75" type="text" placeholder="Search Projects" aria-label="Search">
                    </form>
                        

                    <div id="content">

<?php require_once 'header.php'; ?>
 
 <div class="MainPannel Sections">
     <div class="ListOfProjects">
         <table border="0">
             <tr>
                 <td>
                     <a href="post_project.php"><button type="submit" name="post_projects" formmethod="" formaction="" class="postProject_btn"><img src="../images/plus.png" style="padding-right:20px;" height="35px" width="55px">Post Project</button></a>
                     <a href="post_project.php"><button type="submit" name="post_projects" formmethod="" formaction="" class="postProject_btni"><img src="../images/plus.png" style="padding-right:20px;" height="35px" width="55px"></button></a>
                 </td>
             </tr>
             <tr>
                 <td id="post_container">
                     <?php

$query = "SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 AND `projects`.`creator`='".$user_id."' OR `projects`.`id` IN (SELECT `project_id` FROM `project_members` WHERE `user_id`='".$user_id."') ORDER BY `projects`.`id` DESC";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
while($data = mysqli_fetch_assoc($result)){
    $query2 = "SELECT * FROM `applied_users` WHERE `project_id`=".$data["id"]." AND `user_id`='".$_SESSION["userid"]."'";
    $result2 = mysqli_query($con, $query2);
    $apply_button = true;
    if(mysqli_num_rows($result2)>0){
        $apply_button = false;
    }
?>
<div class="Boxy">
                                
                                <a href="<?php echo "profile.php?id=".$data["user_id"]; ?>">
                                    <img class="user-avatar rounded-circle" src="../images/user_pics/<?php echo $data["profile_pic"] ?>" alt="User Avatar" width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                                
                                <p style="display: inline;" class="TitleName"><?php echo $data["firstname"]." ".$data["lastname"]." </a>"; if($data["role"]==1){ echo "<font color='green'>[Faculty]</font>"; }  if($data["user_id"]==$_SESSION["userid"]){ echo '<form id="delete_form" style="display: inline;"><input type="hidden" name="project_id" value="'.$data["id"].'"><input style="ouline: none; border: none;" type="submit" class="closeicon" value="Delete"></form>'; } ?>
                                </p>
                                <hr class="closehr">
                                <p class="NameDescription">
                                    <b>Title : </b><?php echo htmlentities($data["title"]); ?><br />
                                   <b> Description : </b><?php echo htmlentities($data["description"]); ?><br />
                                   <b>Project type :</b> <?php echo htmlentities($data["name"]); ?><br />
                                   <b>Project status : </b><?php echo htmlentities($data["status"]); ?><br />
                                </p>
                                <p>
                                    <a target="_blank" href="project_details.php?id=<?php echo $data['id']; ?>"><button name="details_about_project" formmethod="" formaction="" class="details_btn" style="display: inline;">Details</button></a>
                                    
                                    
                                    <?php 
                                    if($data["status"]=="open" && $_SESSION["role"]==2 && $data["user_id"] != $_SESSION["userid"] && $apply_button)
                                    { 
                                        echo '<a href="apply_project.php?id='.$data["id"].'" target="_blank"><button type="submit" id="apply_student?id=" name="apply_to_project" formmethod="" formaction="" class="apply_btn style="display: inline;">Apply</button></a>'; 
                                    } elseif($data["status"]=="open" && $_SESSION["role"]==1 && $data["mentor"] == null){
                                        echo '<form id="apply_button_form" style="display: inline;"><button type="submit" id="apply_mentor" name="apply_to_project" formmethod="" formaction="" class="apply_btn" style="display: inline;">Mentor this</button><input type="hidden" name="project_id" value="'.$data["id"].'" /></form>'; 
                                    }
                                    
                                    ?>
                                    
                                <?php
                                    $query1 = "select `interests`.`name` from `project_in_interests` JOIN `interests` ON (interests.id = project_in_interests.interest_id) WHERE project_id = ".$data["id"];
                                    $result1 = mysqli_query($con, $query1);
                                    if(mysqli_num_rows($result1)>0){
                                        echo '<i class="tags">';
                                            $first = 1;
                                            while($data1 = mysqli_fetch_assoc($result1)){
                                                if($first == 1){
                                                    echo $data1["name"];
                                                    $first = 0;
                                                }else{
                                                    echo ", ".$data1["name"];
                                                }
                                            }
                                        echo '</i>';
                                    }

                                ?>
                                <br />
                                    </p>
                                <p>
                                    
                                </p>
                            </div>
<?php
}

?>
                 </td>
             </tr> 
         </table>
     </div>
 </div>
</div>


                        </div>
                    </div>
                </div>
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

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });

        $('.check_box').change(function(){
            $.ajax({
                        type: 'POST',
                        url: 'ajax/update_user_interests.php',
                        data: $('#user_interest_update').serialize()
                      
                    });
        });

        });
    </script>
</body>

</html>