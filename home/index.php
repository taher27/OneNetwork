<?php
require_once "../config.php";
require_once "../functions.php";

if(!logged_in()){
    redirect("../");
}
if(isset($_SESSION["firsttime"])){
    redirect("interests.php");
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>One Network | DAIICT</title>

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../vendors/jqvmap/dist/jqvmap.min.css">

    <link rel="stylesheet" href="../css/index.css">

</head>

<body>

    <div class="wrapper">
        <!-- Sidebar Holder -->
        <form id="filter_form">
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="../images/logo.jpg" class="logo">
            </div>

            <ul class="list-unstyled components">
                <p class="filters">Filters</p>
                <hr class="simplehr">
                <li>
                    <p>Project By</p>
                    <?php
                        $query = "SELECT * FROM `roles`";
                        $result = mysqli_query($con, $query) or die(mysqli_error($con));

                        while($data =  mysqli_fetch_assoc($result)){
                            ?>
                    <div class="custom-control custom-checkbox">
                        <input name="project_uploaded_by[]" type="checkbox" class="custom-control-input" value="<?php echo $data["id"]; ?>" id="<?php echo $data["id"]; ?>">
                        <label class="custom-control-label" for="<?php echo $data["id"]; ?>"><?php echo $data["name"]; ?></label>
                    </div>
                            <?php
                        }
                    
                    ?>

                </li>
                <li>
                    <p>Interested in</p>
                    
                    <?php
                        $query = "SELECT * FROM `interests`";
                        $result = mysqli_query($con, $query) or die(mysqli_error($con));

                        while($data =  mysqli_fetch_assoc($result)){
                            ?>
                    <div class="custom-control custom-checkbox">
                        <input name="interests[]" type="checkbox" class="custom-control-input" value="<?php echo $data["id"]; ?>" id="<?php echo $data["id"]; ?>">
                        <label class="custom-control-label" for="<?php echo $data["id"]; ?>"><?php echo $data["name"]; ?></label>                                        
                    </div>
                            <?php
                        }
                    
                    ?>

                </li>
                <li>
                    <p>Type of Project</p>

                    <?php
                        $query = "SELECT * FROM `project_types`";
                        $result = mysqli_query($con, $query) or die(mysqli_error($con));

                        while($data =  mysqli_fetch_assoc($result)){
                            ?>
                    
                    <div class="custom-control custom-checkbox">
                        <input name="project_types[]" type="checkbox" class="custom-control-input" value="<?php echo $data["id"]; ?>" id="<?php echo $data["id"]; ?>">
                        <label class="custom-control-label" for="<?php echo $data["id"]; ?>"><?php echo $data["name"]; ?></label>                                        
                    </div>
                            <?php
                        }
                    
                    ?>
                </li>
            </ul>
            <hr class="simplehr">
            <!-- <div>
                <button class="applybtn" type="submit" name="submit" value="Apply">
                    Apply
                </button>
            </div> -->
        </nav>
        </form>

        <!-- Page Content Holder -->
        <div id="content">

           <?php require_once 'header.php'; ?>
            
            <div class="MainPannel Sections">
                <div class="ListOfProjects">
                    <table border="0">
                        <tr>
                            <td>
                                <a href="post_project.php"><button type="submit" name="post_projects" formmethod="" formaction="" class="postProject_btn"><img src="../images/plus.png" style="padding-right:20px;" height="35px" width="55px">Post Project</button></a>
                                <a href="post_project.php"><button type="submit" name="post_projects" formmethod="" formaction="" class="postProject_btni"><img src="../images/plus.png" style="padding-right:20px;" height="35px" width="55px"></button></a>
                            </td>
                        </tr>
                        <tr>
                            <td id="post_container">
                                <!-- this content is coming from ajax dynamically
                                <div class="Boxy">
                                    
                                    <a href="#">
                                        <img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                                    </a>
                                    <p class="TitleName">Lorem Ipsum<img class="closeicon" src="../images/close.png"></p>
                                    <hr class="closehr">
                                    <p class="NameDescription">
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                    </p>
                                    <p>
                                        <button name="details_about_project" formmethod="" formaction="" class="details_btn">Details</button>
                                        <button name="apply_to_project" formmethod="" formaction="" class="apply_btn">Apply</button>
                                        <i class="tags">Andriod Artificial Intelligence Machine Learning</i>
                                    </p>
                                    <p>
                                        
                                    </p>
                                </div>
                                <div class="Boxy">
                                        <a href="#">
                                                <img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                                            </a>
                                    <p class="TitleName">Lorem Ipsum<img class="closeicon" src="../images/close.png"></p>
                                    <hr class="closehr">
                                    <p class="NameDescription">
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                    </p>
                                    <p>
                                        <button name="details_about_project" formmethod="" formaction="" class="details_btn">Details</button>
                                        <button name="apply_to_project" formmethod="" formaction="" class="apply_btn">Apply</button>
                                        <i class="tags">Andriod Artificial Intelligence Machine Learning</i>
                                    </p>
                                </div>
                                <div class="Boxy">
                                    <a href="#">
                                        <img class="user-avatar rounded-circle" src="../images/admin.jpg" alt="User Avatar"width: 50px height: 50px style="margin: 0%;margin-left: 5px;display: block; float: left">
                                    </a>
                                    <p class="TitleName">Lorem Ipsum<img class="closeicon" src="../images/close.png"></p>
                                    <hr class="closehr">
                                    <p class="NameDescription">
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                    </p>
                                    <p>
                                        <button name="details_about_project" formmethod="" formaction="" class="details_btn">Details</button>
                                        <button name="apply_to_project" formmethod="" formaction="" class="apply_btn">Apply</button>
                                        <i class="tags">Andriod Artificial Intelligence Machine Learning</i>
                                    </p>
                                    <p>
                                        
                                    </p>
                                </div>
                                -->
                            </td>
                        </tr> 
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="display_msg">
    </div>

    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="../vendors/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            update_section();

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });

            //Logic to update page every 10 seconds without refreshing the whole page
            function update_section(){
                $.ajax({
                    type: 'GET',
                    url: 'ajax/update_project_list.php',
                    success: function(msg){
                        $('#post_container').html(msg);
                    }
                });
            }

            function update_with_filter(){
                $.ajax({
                    type: 'POST',
                    url: 'ajax/update_project_list_filter.php',
                    data: $('#filter_form').serialize(),
                    success: function(msg){
                        $('#post_container').html(msg);
                    }
                });
            }

            var interval_id = setInterval(update_section, 10000);

            $('#post_container').on('click','#apply_student', function(){
                
            });


            $('#post_container').on('submit','#apply_button_form', function(){
                var con = confirm("Are you sure you want to mentor this project?");
                if(con == true){
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/apply_mentor.php',
                        data: $(this).serialize(),
                        success: function(msg){
                            $('#display_msg').html(msg);
                        }
                    });
                    update_section();
                }
                return false;
            });

            $('#post_container').on('submit','#delete_form', function(){
                var con = confirm("Are you sure you want to delete this project?");
                if(con == true){
                        $.ajax({
                        type: 'POST',
                        url: 'ajax/delete_project.php',
                        data: $(this).serialize(),
                        success: function(msg){
                            $('#display_msg').html(msg);
                        }
                    });
                    update_section();
                }
                
                return false;
            });

            //Checkbox filter
            $('input[type="checkbox"]').change(function(){
                clearInterval(interval_id);
                update_with_filter();
                interval_id = setInterval(update_with_filter, 10000);
            });

        });
        var slider = document.getElementById("teamlength");
        var output = document.getElementById("length");
        output.innerHTML = slider.value;

        slider.oninput = function() {
            if(this.value==10)
            {
                output.innerHTML = this.value + " or more";
            }
            else
            {
                output.innerHTML=this.value;
            }
        }
        var slider1 = document.getElementById("projectduration");
        var output1 = document.getElementById("duration");
        output1.innerHTML = slider1.value;

        slider1.oninput = function() {
            if(this.value==12)
            {
                output1.innerHTML = this.value + " or more";
            }
            else
            {
                output1.innerHTML=this.value;
            }
        }
    </script>
</body>

</html>