<?php
   require_once '../../config.php';
   require_once '../../functions.php';

   if(!logged_in()){
       redirect("../");
   }
   if(isset($_SESSION["firsttime"])){
       redirect("interests.php");
   }

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
                }
           }
       }
   }
?>