<?php
   require_once '../../config.php';
   require_once '../../functions.php';

   if(!logged_in()){
       redirect("../");
   }
   if(isset($_SESSION["firsttime"])){
       redirect("interests.php");
   }

   $query = "UPDATE `notifications` SET `seen`=1 WHERE `owner_id`='".$_SESSION["userid"]."'";
   mysqli_query($con, $query) or die(mysqli_error($con));

?>