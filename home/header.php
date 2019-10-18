<?php
require_once "../config.php";
require_once "../functions.php";

if(!logged_in()){
    redirect("../");
}
if(isset($_SESSION["firsttime"])){
    redirect("interests.php");
}

$query = "SELECT `id`,`profile_pic` FROM `users` WHERE `id`='".$_SESSION["userid"]."'";
$result = mysqli_query($con, $query);
$data = mysqli_fetch_assoc($result);


?>

<script src="../vendors/jquery/dist/jquery.min.js"></script>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    
                    <button type="button" id="sidebarCollapse" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                        <!--<i class="fa fa-filter fa-2x"></i>-->
                    </button>

                    <a href="index.php" class="btn btn-info btn-small">
                        <span class="fa fa-home fa-2x"></span>
                    </a>

                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-align-justify"></i>
                    </button>

                    <form style="margin-left:20px;" action="search.php?search=<?php if(isset($_POST["search"])){ echo $_POST["search"]; } ?>">
                        <input class="form-control" type="text" name="search" placeholder="Search" aria-label="Search" value="<?php if(isset($_GET["search"])){echo $_GET["search"];} ?>" autocomplete="off">
                    </form>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto temp2">
                            <li class="nav-item">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle temp" type="button" id="notification" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-bell fa-2x"></i>
                                    </button>
                                    <div id="notification_container" class="dropdown-menu" aria-labelledby="notification">
                                    <!-- THIS IS FOR DEMO
                                    <a class="dropdown-menu-items" href="#">
                                        Server #1 overloaded.<br>
                                    </a>
                                    <a class="dropdown-menu-items" href="#">
                                        Server #2 overloaded.<br>
                                    </a>
                                    <a class="dropdown-menu-items" href="#">
                                        Server #3 overloaded.<br>
                                    </a>
                                    -->
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item logout_container">
                                    <a class="logout" href="logout.php"><img src="../images/logout.png" height="25px" width="25px" ><br>Logout</a>
                            </li>
                            <li class="nav-item">
                                    <a href="profile.php?id=<?php echo $data["id"]; ?>"><img class="user-avatar rounded-circle" src="../images/user_pics/<?php echo $data["profile_pic"] ?>" alt="User Avatar"></a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <script>
                $(document).ready(function(){
                    
                    get_notifications();

                    function get_notifications(){
                        $.ajax({
                            type: 'GET',
                            url: 'ajax/notifications.php',
                            success: function(msg){
                                $('#notification_container').html(msg);
                            }
                        });
                    }

                    setInterval(get_notifications, 10000);

                    $('#notification').click(function(){
                        $.ajax({
                            type: 'GET',
                            url: 'ajax/seen_all.php',
                            success: function(msg){
                                get_notifications();
                            }
                        });
                    });

                });
            </script>