<?php
require_once "../config.php";
require_once "../functions.php";

if(!logged_in()){
    redirect("../");
}
if(!isset($_SESSION["firsttime"])){
    redirect("index.php");
}
if(isset($_GET["skip"])){
    unset($_SESSION["firsttime"]);
    $query = "UPDATE `users` SET `firsttime`=0 WHERE `id`='".$_SESSION["userid"]."'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    redirect("index.php");
}
if(isset($_GET["next"])){
    unset($_SESSION["firsttime"]);
    redirect("index.php");
}
if(isset($_POST["interest_btn"])){
    $query = "DELETE FROM `user_interests` WHERE `user_id`='".$_SESSION["userid"]."'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    $interests = $_POST["interest"];
    $query = "INSERT INTO `user_interests` (`user_id`, `interest_id`) VALUES";
    $first = 1;
    foreach($interests as $interest){
        if($first == 1){
            $query .= "('".$_SESSION["userid"]."', ".mysqli_real_escape_string($con, $interest).")";
            $first = 0;
        }else{
            $query .= ",('".$_SESSION["userid"]."', ".mysqli_real_escape_string($con, $interest).")";
        }
    }
    mysqli_query($con, $query) or die(mysqli_error($con));

    unset($_SESSION["firsttime"]);
    $query = "UPDATE `users` SET `firsttime`=0 WHERE `id`='".$_SESSION["userid"]."'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    redirect("index.php");
}
?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Calm breeze login screen</title>
  
  <link rel="stylesheet" href="../css/interests.css" />
  

</head>

<body>
    <div id="container">
        <div id="title">
            Select your interests
        </div>
        <form method="POST">
        <div id="list">
            <?php
                $query = "SELECT * FROM `interest_categories`";
                $result = mysqli_query($con, $query) or die(mysqli_error($con));
                while($data = mysqli_fetch_array($result)){
                    ?>
                        <div class="list_category">
                        <div class="category_name"><?php echo htmlentities($data["name"]); ?></div>
                        <div class="list_items">
                    <?php
                    $query1 = "SELECT * FROM `interests` WHERE `category`=".$data["id"];
                    $result1 = mysqli_query($con, $query1) or die(mysqli_error($con));
                    while($data1 = mysqli_fetch_array($result1)){
                        ?>
                            <div class="item">
                                <input type="checkbox" name="interest[]" value="<?php echo $data1['id'] ?>" /><?php echo htmlentities($data1["name"]); ?>
                            </div>
                        <?php
                    }
                    ?>
                      
                </div>
            </div>
                    <?php
                }
            ?>
           

        </div>
        <a href="interests.php?skip">Skip</a><a href="interests.php?next">Ask me next time</a><input type="submit" name="interest_btn" value="Submit" />
        </form>
    </div>
    
</body>
</html>