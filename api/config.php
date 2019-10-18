<?php
/*
This is a configuration file for entire web app
it provides information about database connectivity
Some functions are also defined in this config file
*/
session_start();

$host = "localhost";
$user = "onenetwork";
$password = "OneNetwork@2019";
$database = "onenetwork";




$con = mysqli_connect($host, $user, $password, $database);

// Check connection
if (mysqli_connect_errno())
  {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
  }

?>