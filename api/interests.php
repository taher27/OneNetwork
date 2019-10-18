<?php

include "config.php";
include "functions.php";

header('content-type: application/json');

$interest_categories = "SELECT * FROM interest_categories";
$result_int_categories = mysqli_query($con,$interest_categories);
            
$interest_categories_array = array();
$interests_array = array();
$final = array();

while($row = mysqli_fetch_assoc($result_int_categories)){
   // array_push($interest_categories_array,$row);
    $q = "SELECT * FROM interests WHERE category = ".$row['id']."";
    $result_interests = mysqli_query($con,$q);
    $count=mysqli_num_rows($result_interests);
    while($row1 = mysqli_fetch_assoc($result_interests)){
       array_push($interests_array,$row1);
    }
    $response_interest_names = array("category_id" => $row["id"], "category_name" => $row["name"], "error" => "false", "count" => $count , "message" => "success", "interests_array" => $interests_array);
    array_push($final,$response_interest_names);
    $interests_array = array();
}
$response = array("error" => "false","message" => "success","interests" => $final);


echo json_encode($response);
?>