<?php
   require_once '../../config.php';
   require_once '../../functions.php';

   if(!logged_in()){
       redirect("../");
   }
   if(isset($_SESSION["firsttime"])){
       redirect("interests.php");
 }

 $query = "DELETE FROM `user_interests` WHERE `user_id`='".$_SESSION["userid"]."'";
 mysqli_query($con, $query) or die(mysqli_error($con));
 if(isset($_POST["interests"])){
     $interests = $_POST["interests"];
     foreach($interests as $interest){
        $query = "INSERT INTO `user_interests` (`user_id`, `interest_id`) VALUES('".$_SESSION["userid"]."', ".$interest.")";
        mysqli_query($con, $query) or die(mysqli_error($con));
     }
 }

?>