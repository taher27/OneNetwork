<?php

require_once '../../config.php';
require_once '../../functions.php';

if(!logged_in()){
    redirect("../");
}
if(isset($_SESSION["firsttime"])){
    redirect("interests.php");
}

$query = "SELECT `notifications`.`seen` ,`projects`.`id`, `projects`.`title`, `users`.`firstname`, `users`.`lastname` , `notifications`.`type` FROM `users` JOIN `notifications` ON (notifications.user_id = users.id AND notifications.owner_id = '".$_SESSION["userid"]."') JOIN `projects` ON (projects.id = notifications.project_id) ORDER BY `notifications`.`time` DESC";
$result = mysqli_query($con, $query);

if(mysqli_num_rows($result)>0){
    while($data = mysqli_fetch_assoc($result)){
        if($data["type"]=="applied_mentor"){
            echo '<div style="border-bottom: 1px solid black;"><a class="dropdown-menu-items" href="project_details.php?id='.$data["id"].'">'.$data["firstname"].' '.$data["lastname"].' is the mentor of your project "'.$data["title"].'"<br></a></div>';    
        }elseif($data["type"]=="applied_student"){
            echo '<div style="border-bottom: 1px solid black;"><a class="dropdown-menu-items" href="project_details.php?id='.$data["id"].'">'.$data["firstname"].' '.$data["lastname"].' wants to work on your project "'.$data["title"].'"<br></a></div>';    
        }elseif($data["type"]=="approved_student"){
            echo '<div style="border-bottom: 1px solid black;"><a class="dropdown-menu-items" href="project_details.php?id='.$data["id"].'">'.$data["firstname"].' '.$data["lastname"].' accepted your request for the project "'.$data["title"].'"<br></a></div>';
        }elseif($data["type"]=="student_rejected"){
            echo '<div style="border-bottom: 1px solid black;"><a class="dropdown-menu-items" href="project_details.php?id='.$data["id"].'">'.$data["firstname"].' '.$data["lastname"].' rejected your request for the project "'.$data["title"].'"<br></a></div>';
        }
        
        
    }
    $query = "SELECT * FROM `notifications` WHERE `owner_id` = '".$_SESSION["userid"]."' AND `seen`=0";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    if(mysqli_num_rows($result)){
        echo '<style>
        #notification {
            background-color: yellow;
        }
        </style>';
    }
}else{
    echo "No notifications";
}

?>