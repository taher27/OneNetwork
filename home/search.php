<?php
require_once "../config.php";
require_once "../functions.php";

if(!logged_in()){
    redirect("../");
}
if(isset($_SESSION["firsttime"])){
    redirect("interests.php");
}
$search;
if(!isset($_GET["search"])){
    redirect("index.php");
}else{
    $search = htmlentities($_GET["search"]);
    $search = mysqli_real_escape_string($con, $search);
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Search | One Network</title>

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

               <?php require_once 'header.php'; ?>

            <div class="MainPannel Sections" style="height: auto;">
                    Search result for : <?php echo $search; ?><br />
                    <a href="search.php?search=<?php echo $search; ?>"><input class="btn rounded" type="submit" value="Search by people" style="float:right;"></a>
                    <a href="search.php?search=<?php echo $search; ?>&projects"><input class="btn rounded" type="submit" value="Search by projects" style="float:left;"></a>
            </div><br><br>
            
            <?php
if(isset($_GET["projects"])){


    $query = "SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 AND (`projects`.`title` LIKE '%".$search."%')";
    $query .= " UNION SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 AND (`projects`.`description` LIKE '%".$search."%')";
    $result = mysqli_query($con, $query);
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
}else{


    $query = "SELECT * FROM `users` WHERE CONCAT(firstname, ' ', lastname) LIKE '%".$search."%'";
    $query .= "UNION SELECT * FROM `users` WHERE CONCAT(lastname, ' ', firstname) LIKE '%".$search."%'";
    $query .= "UNION SELECT * FROM `users` WHERE `users`.`id` = '".$search."'";
    
    
    $result = mysqli_query($con, $query);
    while($data = mysqli_fetch_assoc($result)){
    ?>
<div class="Boxy" style="height: 90px;">
                                    
                                    <a href="<?php echo "profile.php?id=".$data["id"]; ?>">
                                        <img class="user-avatar rounded-circle" src="../images/user_pics/<?php echo $data["profile_pic"] ?>" alt="User Avatar" width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                                    
                                    <p style="display: inline;" class="TitleName"><?php echo $data["firstname"]." ".$data["lastname"]." </a>"; if($data["role"]==1){ echo "<font color='green'>[Faculty]</font>"; }else{ echo "(".$data["id"].")"; }?>
                                    
                                    </p>
</div>
    <?php
    }
}
?>

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
        });
    </script>
</body>

</html>