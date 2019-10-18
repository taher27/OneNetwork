<?php
require_once "../config.php";
require_once "../functions.php";

if(!logged_in()){
    redirect("../");
}
if(isset($_SESSION["firsttime"])){
    redirect("interests.php");
}
$data1 = 1;
if(!isset($_GET["id"])){
    die("invalid link");
}else{
    $project_id = mysqli_real_escape_string($con, $_GET["id"]);
    $query = "SELECT `users`.`role` ,`users`.`id` as `user_id` , `projects`.`creator` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 AND `projects`.`id`=".$project_id." ORDER BY `projects`.`id` DESC";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    if(mysqli_num_rows($result)>0){
        $data1 = mysqli_fetch_assoc($result);
        $query2 = "SELECT * FROM `applied_users` WHERE `project_id`=".$data1["id"]." AND `user_id`='".$_SESSION["userid"]."'";
        $result2 = mysqli_query($con, $query2);
        $apply_button = true;
        if(mysqli_num_rows($result2)>0){
            $apply_button = false;
        }
        if(isset($_POST["apply_to_project"])){// mentor clicked on mentor this button
            if(isset($_POST["project_id"])){
                if($_SESSION["role"] == 1){
                    $query = "SELECT `creator`,`mentor` FROM `projects` WHERE `id`=".$_POST["project_id"];
                    $result = mysqli_query($con, $query) or die(mysqli_error($con));
                    
                    if(mysqli_num_rows($result)==1){
                         $data = mysqli_fetch_assoc($result);
                         if($data["mentor"] == null){
                             $query = "UPDATE `projects` SET `mentor`='".$_SESSION["userid"]."' WHERE `projects`.`id`=".$_POST["project_id"];
                             mysqli_query($con, $query);
                             $project_id = $_POST["project_id"];
                             $owner_id = $data["creator"];
                             $query = "INSERT INTO `notifications` (`owner_id`, `project_id`, `user_id`, `type`, `seen`, `time`) VALUES('".$owner_id."', ".$project_id.", '".$_SESSION["userid"]."', 'applied_mentor', 0, ".time().")";
                             mysqli_query($con, $query);
                             echo '<script>alert("You are assigned to this project as a mentor");</script>';
                             redirect("project_details.php?id=".$project_id);
                         }
                    }
                }
            }
        }

        if(isset($_POST["delete_button"])){// if user pressed delete button
            if(isset($_POST["project_id"])){
                $query = "SELECT `id` FROM `projects` WHERE `id`=".$_POST["project_id"]." AND `creator`='".$_SESSION["userid"]."'";
                $result = mysqli_query($con, $query) or die(mysqli_error($con));
                if(mysqli_num_rows($result)==1){
                    $data= mysqli_fetch_assoc($result);
                    $query = "UPDATE `projects` SET `deleted`=1 WHERE `id`=".$data["id"];
                    mysqli_query($con, $query) or die(mysqli_error($con));
                    echo '<script>alert("project deleted.");</script>';
                }
           }
           redirect("index.php");
        }

    }else{//project doesnt exist
        die("Project doesn't exists");
    }
}



?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>One Network</title>

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../vendors/jqvmap/dist/jqvmap.min.css">

    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/project_details.css">
    

</head>

<body>

    <div class="wrapper">
        

        <!-- Page Content Holder -->
        <div id="content">

            <?php require_once 'header.php'; ?>
            
            <div id="main_panel" class="MainPannel Sections">
                <div class="ListOfProjects">
                    <table border="0">
                        
                        <tr>
                            <td>
                            <div class="Boxy">

                                    <a href="<?php echo "profile.php?id=".$data1["user_id"]; ?>">
                                        <img class="user-avatar rounded-circle" src="../images/user_pics/<?php echo $data1["profile_pic"] ?>" alt="User Avatar" width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                                    
                                    <p style="display: inline;" class="TitleName"><?php echo $data1["firstname"]." ".$data1["lastname"]." </a>"; if($data1["role"]==1){ echo "<font color='green'>[Faculty]</font>"; } if($data1["user_id"]==$_SESSION["userid"]){ echo '<form method="POST" action="project_details.php?id='.$project_id.'" id="delete_form" style="display: inline;"><input type="hidden" name="project_id" value="'.$data1["id"].'"><input name="delete_button" style="ouline: none; border: none;" type="submit" class="closeicon" value="Delete"></form>'; } ?>
                                    </p>
                                    <hr class="closehr">
                                    <p class="NameDescription">
                                        <b>Title : </b><?php echo htmlentities($data1["title"]); ?><br />
                                       <b> Description : </b><?php echo htmlentities($data1["description"]); ?><br />
                                       <b>Project type :</b> <?php echo htmlentities($data1["name"]); ?><br />
                                       <b>Project status : </b><?php echo htmlentities($data1["status"]); ?><br />
                                    </p>
                                    <p>
                                        
                                        <?php 
                                        if($data1["status"]=="open" && $_SESSION["role"]==2 && $data1["user_id"] != $_SESSION["userid"] && $apply_button)
                                        { 
                                            echo '<a href="apply_project.php?id='.$data1["id"].'" target="_blank"><button type="submit" id="apply_student?id=" name="apply_to_project" formmethod="" formaction="" class="apply_btn style="display: inline;">Apply</button></a>'; 
                                        } elseif($data1["status"]=="open" && $_SESSION["role"]==1 && $data1["mentor"] == null){
                                            
                                            echo '<form method="POST" action="project_details.php?id='.$project_id.'" id="apply_button_form" style="display: inline;"><button type="submit" id="apply_mentor" name="apply_to_project" class="apply_btn" style="display: inline;">Mentor this</button><input type="hidden" name="project_id" value="'.$data1["id"].'" /></form>'; 
                                        }
                                        
                                        ?>
                                        
                                    <?php
                                        $query1 = "select `interests`.`name` from `project_in_interests` JOIN `interests` ON (interests.id = project_in_interests.interest_id) WHERE project_id = ".$data1["id"];
                                        $result1 = mysqli_query($con, $query1);
                                        if(mysqli_num_rows($result1)>0){
                                            echo '<i class="tags">';
                                                $first = 1;
                                                while($data11 = mysqli_fetch_assoc($result1)){
                                                    if($first == 1){
                                                        echo $data11["name"];
                                                        $first = 0;
                                                    }else{
                                                        echo ", ".$data11["name"];
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
                                
                            </td>
                        </tr> 
                    </table>
                </div>
            <?php
            
            $query = "SELECT `creator`,`mentor` FROM `projects` WHERE `projects`.`id`=".$project_id;
            $result = mysqli_query($con, $query) or die(mysqli_query($con));
            $data = mysqli_fetch_assoc($result);
            if($data["mentor"] != null && $data["mentor"] != $data["creator"]){
                echo "Mentor:";
                $query = "SELECT * FROM `users` WHERE `users`.`id`='".$data["mentor"]."'";
                $result = mysqli_query($con, $query) or die(mysqli_query($con));
                $data = mysqli_fetch_assoc($result);
                ?>
<div class="Boxy1">
                 <div class="bDiv">                   
                    <a href="profile.php?id=<?php echo $data["id"]; ?>">
                        <img class="user-avatar rounded-circle" src="../images/user_pics/<?php echo $data["profile_pic"]; ?>" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                    
                    <p class="TitleName"><?php echo $data["firstname"]." ".$data["lastname"]; ?></p></a>
                    
                </div>
			</div>
                <?php
            }
            
            ?>
            Project Members:
            <?php
            
                $query = "SELECT `users`.`id`,`users`.`firstname`, `users`.`lastname`, `users`.`profile_pic` FROM `users` JOIN `project_members` ON (`users`.`id` = `project_members`.`user_id` AND `project_members`.`project_id`=".$project_id.")";
                $result = mysqli_query($con, $query) or die(mysqli_query($con));
                if(mysqli_num_rows($result)>0){
                    while($data = mysqli_fetch_assoc($result)){

                    
                    ?>

			<div class="Boxy1">
                 <div class="bDiv">                   
                    <a href="profile.php?id=<?php echo $data["id"]; ?>">
                        <img class="user-avatar rounded-circle" src="../images/user_pics/<?php echo $data["profile_pic"]; ?>" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                    
                    <p class="TitleName"><?php echo $data["firstname"]." ".$data["lastname"]; ?></p></a>
                    
                </div>
			</div>


                    <?php
                    }
                }else{
                    echo "No current member(s)";
                }
            
            ?>

            <?php
            if($data1["creator"] == $_SESSION["userid"]){

            
                $query = "SELECT `users`.`profile_pic`,`applied_users`.`user_id`, `users`.`firstname`, `users`.`lastname`, `applied_users`.`message` FROM `applied_users` JOIN `users` ON (users.id = applied_users.user_id AND applied_users.approved=0 AND applied_users.project_id = ".$project_id.")";
                $result = mysqli_query($con, $query) or die(mysqli_query($con));
                if(mysqli_num_rows($result)>0){
                    while($data = mysqli_fetch_assoc($result)){

                    
                    ?>

			<div class="Boxy1">
                 <div class="bDiv">                   
                    <a href="profile.php?id=<?php echo $data["user_id"]; ?>">
                        <img class="user-avatar rounded-circle" src="../images/user_pics/<?php echo $data["profile_pic"]; ?>" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                    
                    <p class="TitleName"><?php echo $data["firstname"]." ".$data["lastname"]; ?></p></a>
                    <hr class="closehr">
                    <p class="NameDescription">
                        <?php echo $data["message"]; ?>
                    </p>
                    <p id="acceptreject">
                        <form name="accept_reject_form" id="accept_reject_form">
                            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>"/>
                            <input type="hidden" name="user_id" value="<?php echo $data["user_id"]; ?>"/>
                            
                            <button type="submit" id="accept_button" name="accept" formmethod="" formaction="" class="apply_btn">Accept</button>
                            <button type="submit" id="reject_button" name="reject" formmethod="" formaction="" class="apply_btn">Reject</button>
                        </form>
                    </p>
                    <p>
                        
                    </p>
                </div>
			</div>


                    <?php
                    }
                }else{
                    echo "No pending request";
                }
            }
            ?>
            <!--
			<div class="Boxy1">
                 <div class="bDiv">                   
                    <a href="#">
                        <img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                    </a>
                    <p class="TitleName">Name Of Applicant</p>
                    <hr class="closehr">
                    <p class="NameDescription">
                        Details about project
                    </p>
                    <p id="acceptreject">
                        
                        <button name="accept" formmethod="" formaction="" class="apply_btn">Accept</button>
                        <button name="reject" formmethod="" formaction="" class="apply_btn">Reject</button>
                        
                    </p>
                    <p>
                        
                    </p>
                </div>
			</div>
			<div class="Boxy1">
                <div class="bDiv"> 
				<a href="#">
					<img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
				</a>
				<p class="TitleName">Name Of Applicant</p>
				<hr class="closehr">
				<p class="NameDescription">
					Details about project
				</p>
				<p>
					
					<button name="accept" formmethod="" formaction="" class="apply_btn">Accept</button>
					<button name="reject" formmethod="" formaction="" class="apply_btn">Reject</button>
					
				</p>
				<p>
					
                </p>
                </div>
            </div>
            <div class="Boxy1">
                <div class="bDiv">              
				<a href="#">
					<img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
				</a>
				<p class="TitleName">Name Of Applicant</p>
				<hr class="closehr">
				<p class="NameDescription">
					Details about project
				</p>
				<p>
					
					<button name="accept" formmethod="" formaction="" class="apply_btn">Accept</button>
					<button name="reject" formmethod="" formaction="" class="apply_btn">Reject</button>
					
				</p>
				<p>
					
                </p>
                </div>
            </div>
            <div class="Boxy1">
                <div class="bDiv">              
				<a href="#">
					<img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
				</a>
				<p class="TitleName">Name Of Applicant</p>
				<hr class="closehr">
				<p class="NameDescription">
					Details about project
				</p>
				<p>
					
					<button name="accept" formmethod="" formaction="" class="apply_btn">Accept</button>
					<button name="reject" formmethod="" formaction="" class="apply_btn">Reject</button>
					
				</p>
				<p>
					
                </p>
                </div>
            </div>
            -->
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
            var button_pressed;

            
            $('#main_panel').on('click','.apply_btn', function(){
                button_pressed = $(this).attr('name');
            });

            $('#main_panel').on('submit','#accept_reject_form', function(){
                if(button_pressed == 'accept'){
                    var temp = $(this);
                     $.ajax({
                        type: 'POST',
                        url: 'ajax/accept_student.php',
                        data: $(this).serialize(),
                        success: function(msg){
                            $(temp).html(msg);
                        }
                    });
                     

                }else{
                    var temp = $(this);
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/reject_student.php',
                        data: $(this).serialize(),
                        success: function(msg){
                            $(temp).html(msg);
                        }
                    });
                }
                
                button_pressed = '';
                return false;
            });


        });
        var slider = document.getElementById("teamlength");
        var output = document.getElementById("length");
        output.innerHTML = slider.value;

        slider.oninput = function() {
            if(this.value==10)
            {
                output.innerHTML = this.value + " or more";
            }
            else
            {
                output.innerHTML=this.value;
            }
        }
        var slider1 = document.getElementById("projectduration");
        var output1 = document.getElementById("duration");
        output1.innerHTML = slider1.value;

        slider1.oninput = function() {
            if(this.value==12)
            {
                output1.innerHTML = this.value + " or more";
            }
            else
            {
                output1.innerHTML=this.value;
            }
        }
    </script>
</body>

</html>