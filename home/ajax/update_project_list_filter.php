<?php
    require_once '../../config.php';
    require_once '../../functions.php';

    if(!logged_in()){
        redirect("../");
    }
    if(isset($_SESSION["firsttime"])){
        redirect("interests.php");
    }

    $query = "";
    if(isset($_POST["project_uploaded_by"])){
        $project_uploaded_by = $_POST["project_uploaded_by"];
        foreach($project_uploaded_by as $uploaded){
            if($query == ""){
                $query = "SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 AND `users`.`role`=".$uploaded;
            }else{
                $query .= " UNION SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 AND `users`.`role`=".$uploaded;
            }
        }
    }
    if(isset($_POST["interests"])){
        $interests = $_POST["interests"];
        foreach($interests as $interest){
            if($query == ""){
                $query = "SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 AND `projects`.`id` IN (SELECT `project_id` FROM `project_in_interests` WHERE `interest_id` = ".$interest.")";
            }else{
                $query .= " UNION SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0  AND `projects`.`id` IN (SELECT `project_id` FROM `project_in_interests` WHERE `interest_id` = ".$interest.")";
            }
        }
    }
    if(isset($_POST["project_types"])){
        $project_types = $_POST["project_types"];
        foreach($project_types as $project_type){
            if($query == ""){
                $query = "SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 AND `projects`.`project_type`=".$project_type;
            }else{
                $query .= " UNION SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 AND `projects`.`project_type`=".$project_type;
            }
        }
    }


    if($query == ""){
        $query = "SELECT `users`.`role`,`users`.`id` as `user_id` ,`projects`.`mentor` ,`users`.`profile_pic`,`users`.`firstname`,`users`.`lastname`, `projects`.`title`, `projects`.`description`, `project_types`.`name`, `projects`.`id`, `project_status`.`status` FROM `users` JOIN `projects` ON (projects.creator = users.id) JOIN project_types ON (project_types.id = projects.project_type) JOIN `project_status` ON (project_status.id = projects.status) WHERE `projects`.`deleted`=0 ORDER BY `projects`.`id` DESC";
    }
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