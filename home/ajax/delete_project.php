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
        $query = "SELECT `id` FROM `projects` WHERE `id`=".$_POST["project_id"]." AND `creator`='".$_SESSION["userid"]."'";
        $result = mysqli_query($con, $query) or die(mysqli_error($con));
        if(mysqli_num_rows($result)==1){
            $data= mysqli_fetch_assoc($result);
            $query = "UPDATE `projects` SET `deleted`=1 WHERE `id`=".$data["id"];
            mysqli_query($con, $query) or die(mysqli_error($con));
            echo '<script>alert("project deleted.");</script>';
        }
   }

?>