<?php
   require_once '../../config.php';
   require_once '../../functions.php';

   if(!logged_in()){
       redirect("../");
   }
   if(isset($_SESSION["firsttime"])){
       redirect("interests.php");
   }

   if(isset($_POST["project_id"]) && isset($_POST["user_id"])){
    $query = "SELECT * FROM `applied_users` JOIN `projects` ON (projects.id = applied_users.project_id AND applied_users.user_id='".$_POST["user_id"]."' AND applied_users.approved=0 AND projects.creator='".$_SESSION["userid"]."' AND projects.id=".$_POST["project_id"].")";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    
    if(mysqli_num_rows($result)>0){
        $data = mysqli_fetch_assoc($result);
        $project_owner = $data["creator"];
        $query = "DELETE FROM `applied_users` WHERE `user_id`='".$_POST["user_id"]."' AND `project_id`='".$_POST["project_id"]."'";
        mysqli_query($con, $query) or die(mysqli_error($con));
        $query = "INSERT INTO `notifications` (`owner_id`, `project_id`, `user_id`, `type`, `seen`, `time`) VALUES('".$_POST["user_id"]."', ".$_POST["project_id"].", '".$project_owner."', 'student_rejected', 0, ".time().")";
        mysqli_query($con, $query) or die(mysqli_error($con));
        echo "<font color='red'>Rejected</font>";
    }else{
        echo "<script>alert('something went wrong');</script>";     
    }
}else{
   echo "<script>alert('something went wrong');</script>";
}

?>