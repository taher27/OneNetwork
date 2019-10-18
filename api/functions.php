<?php

function redirect($link){// function to redirect users
    header("Location: $link");
}

function logged_in(){ // check whether user is logged in or not
    if(isset($_SESSION["userid"])){
        return true;
    }else{
        return false;
    }
}

function user_already_exists($con, $userid){
	$query = "SELECT * FROM `users` WHERE `id`='".mysqli_real_escape_string($con, $userid)."'";
	$result = mysqli_query($con, $query) or die(mysqli_error($con));
	if(mysqli_num_rows($result) > 0){
		return true;
	}else{
		return false;
	}
}

?>