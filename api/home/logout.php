<?php

require_once "../config.php";
require_once "../functions.php";

session_destroy();
redirect("../index.php");

?>