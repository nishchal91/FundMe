<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Project Details</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--<link href="css/star-rating.css" media="all" rel="stylesheet" type="text/css" />-->
    <script src="js/star-rating.js" type="text/javascript"></script>
    <style>
        .glyphicon-star {
            color: #FFDF00;
        }

    </style>


</head>
<?php

session_start(); //starts the session

if ($_SESSION['email']) { //checks if name is logged in
} else {
    header("location:index.php"); // redirects if name is not logged in
}
$email = $_SESSION['email']; //assigns name value
?>
<body>
<?php
require 'dbconn.php';
require 'navbar.php';

$project_id = $_GET['project_id'];

$_SESSION['project_id'] = $project_id;
// Query: Get recipe and title
$sql =
    "SELECT PROJECT.PROJECT_ID,PROJECT.PROJECT_TITLE,PROJECT.DESCRIPTION,PROJECT.PHOTO,PROJECT.VIDEO from PROJECT where PROJECT.PROJECT_ID=$project_id";
$result = $conn->query($sql);


$row = $result->fetch_assoc();

?>
<div class='container'>
    <div class='row'>
        <div class='col-sm-20 col-md-16 user-details'>
            <table>
                    <tr>
                        <td style="padding:0 30px 0 0px;">
                            <?php
                            if (is_null($row['PHOTO'])) {

                                ?>
                                <img src='img/blank.png' alt='Project Pic' title='Project Pic'
                                     style="width:128px;height:128px">
                                <?php
                            } else {
                                ?>
                                <img src='data:image;base64,<?php echo $row['PHOTO']; ?>' alt='Project Pic' title='Project Pic'
                                     style="width:128px;height:128px">
                                <?php
                            } ?>
                        </td>

                        <td style="padding:0 0px 0 30px;">

                            <?php
                            $video= $row['VIDEO'];
                            $video_show= "videos/$video";

                            $position= strpos($video, ".");

                            $file_extension= substr($video, $position + 1);

                            $file_extension= strtolower($file_extension);

                            echo "<div align=center><video width='320' controls><source src='$video_show' type='video/$file_extension'>Your browser does
                            not support the video tag.</video></div>";

                            ?>
                        </td>
                    </tr>
                </table>

            </div>
            <div>
                <div>
                    <h3><?php
                        echo $row['PROJECT_TITLE'];
                        ?>
                    </h3>
                </div>


                <?php
                $row_geteditrec = "select * from project_ownership where project_id=$project_id and email_id = '" . $_SESSION['email'] . "'";
                $result_geteditrec = $conn->query($row_geteditrec);
                if ($result_geteditrec->num_rows > 0) {
                    echo '  <div class="fileinput fileinput-new" data-provides="fileinput">
						        <form enctype="multipart/form-data" method="post">
					                <span class="btn btn-primary btn-sm">
					                    <input type="file" color="#FFFFFF" name="pics"/>
					                </span>
                                    <input  type="submit" value="Upload" name="up" class="btn btn-primary btn-sm"/>
                                </form>
							</div>';
                }

                function saveimg($photo, $project_id)
                {
                    require 'dbconn.php';
                    $sqluploadimg = "UPDATE project SET photo='$photo' WHERE project_id=$project_id";
                    $resultup = $conn->query($sqluploadimg);
                    if ($resultup) {
                        echo "Image uploaded";

                        ?>
                        <script>
                            window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
                        </script>
                        <?php

                    } else {
                        echo "Image not uploaded";
                    }


                }


                if (isset($_POST['up'])) {

                    if (getimagesize($_FILES['pics']['tmp_name']) == TRUE) {
                        $photo = addslashes($_FILES['pics']['tmp_name']);
                        $photo = file_get_contents($photo);
                        $photo = base64_encode($photo);
                        saveimg($photo, $project_id);
                    } else {
                        echo "Please select an image";
                    }
                }

                if (isset($_POST['like'])) {

                    $sql_like = "Insert into project_likes (project_id,email_id,date_time_liked) values ($project_id, '" . $_SESSION['email'] . "',NOW())";
                    if ($conn->query($sql_like)) {
                        ?>
                        <script>
                            window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
                        </script>
                        <?php

                    }
                }

                if (isset($_POST['dislike'])) {

                    $sql_like = "delete from project_likes where email_id = '" . $_SESSION['email'] . "' and project_id = $project_id";
                    if ($conn->query($sql_like)) {
                        ?>
                        <script>
                            window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
                        </script>
                        <?php

                    }
                }

                ?>
            </div>

            <div>
                <?php $sqllike =
                    "SELECT * from PROJECT join PROJECT_LIKES on PROJECT.PROJECT_ID=PROJECT_LIKES.PROJECT_ID
                      where PROJECT_LIKES.EMAIL_ID='" . $_SESSION['email'] . "' and PROJECT.PROJECT_ID = $project_id";
                $resultlike = $conn->query($sqllike);
                if ($resultlike->num_rows === 0) {
                    echo "<form enctype='multipart/form-data' method='post'><input  type='submit' value='Like!' name='like' class='btn btn-primary btn-sm'/></form>";
                } else {
                    echo "<form enctype='multipart/form-data' method='post'><input  type='submit' value='Dislike!' name='dislike' class='btn btn-primary btn-sm'/></form>";
                }
                ?>
                <div>
                    <?php
                    $get_project_campaign_status_for_pledge = "select status from project_campaign where project_id =" . $project_id;
                    $get_project_campaign_status_for_pledge = $conn->query($get_project_campaign_status_for_pledge);
                    $get_project_campaign_status_for_pledge_result = $get_project_campaign_status_for_pledge->fetch_assoc();



                    $row_geteditrec = "select * from project_ownership where project_id=$project_id and email_id='" . $_SESSION['email'] . "'";

                    $result_geteditrec = $conn->query($row_geteditrec);




                    if ($get_project_campaign_status_for_pledge_result['status'] === 'IN_PROGRESS' and $result_geteditrec->num_rows === 0) {
                        ?>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#myModal5">Pledge!
                        </button>
                        <?php
                    }
                    ?>

                </div>


            </div>
            <br>
            <div>
                <ul class='nav nav-tabs' id="myTab">
                    <li class='active'><a href='#information' data-toggle='tab'>Project Information</a></li>
                    <li><a href='#funding_details' data-toggle='tab'>Funding Details</a></li>
                    <li><a href='#category' data-toggle='tab'>Category</a></li>
                    <li><a href='#ratings' data-toggle='tab'>Ratings</a></li>
                    <li><a href='#likes' data-toggle='tab'>Likes</a></li>
                    <li><a href='#comments' data-toggle='tab'>Comments</a></li>
                    <li><a href='#posts' data-toggle='tab'>Posts</a></li>
                </ul>
            </div>

            <div id='myTabContent' class='tab-content'>

                <div class='tab-pane fade active in' id='information'>

                    <table class='table table-user-information'>

                        <tbody>
                        <tr>
                            <td>Project Title:</td>
                            <td><?php echo $row['PROJECT_TITLE']; ?></td>
                        </tr>
                        <tr>
                            <td>Description:</td>
                            <td><?php echo $row['DESCRIPTION']; ?></td>
                        </tr>
                        <tr>
                            <?php $sql_owner =
                                "   SELECT PROJECT_OWNERSHIP.EMAIL_ID, FIRST_NAME, LAST_NAME, DATE_FORMAT(START_DATE, '%M %d %Y') AS START_DATE, 
                                    DATE_FORMAT(COMPLETION_DATE, '%M %d %Y') AS COMPLETION_DATE, 
                                    DATE_FORMAT(PLANNED_COMPLETION_DATE,'%M %d %Y') AS PLANNED_COMPLETION_DATE, STATUS
                                    FROM PROJECT
                                      JOIN PROJECT_OWNERSHIP ON PROJECT.PROJECT_ID = PROJECT_OWNERSHIP.PROJECT_ID
                                      JOIN USER ON PROJECT_OWNERSHIP.EMAIL_ID = USER.EMAIL_ID
                                    WHERE PROJECT.PROJECT_ID=$project_id;";
                            $result = $conn->query($sql_owner);
                            $result_owner = $result->fetch_assoc();

                            ?>
                            <td>Owner:</td>
                            <td>
                                <?php
                                echo $result_owner['FIRST_NAME'] . " " . $result_owner['LAST_NAME'] . " (" ?>
                                <a href="viewOtherUserProfile.php?email=<?php echo $result_owner['EMAIL_ID']; ?>"> <?php echo $result_owner['EMAIL_ID']; ?> </a> <?php echo ")"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Start Date:</td>
                            <td><?php echo $result_owner['START_DATE']; ?></td>
                        </tr>
                        <tr>
                            <td>End Date:</td>
                            <td><?php
                                if (is_null($result_owner['COMPLETION_DATE']) && $result_owner['STATUS'] === "CAMPAIGNING") {
                                    echo "The Project is still campaigning.";
                                } else if (is_null($result_owner['COMPLETION_DATE']) && $result_owner['STATUS'] === "FAILED") {
                                    echo "The Project has failed.";
                                } else if (is_null($result_owner['COMPLETION_DATE']) && $result_owner['STATUS'] === "STARTED") {
                                    echo "The Project has started with successful campaigning.";
                                } else {
                                    echo $result_owner['COMPLETION_DATE'];
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td>Planned End Date:</td>
                            <td><?php echo $result_owner['PLANNED_COMPLETION_DATE']; ?></td>
                        </tr>
                        <tr>
                            <td>Project Status:</td>
                            <td><?php echo $result_owner['STATUS']; ?></td>
                        </tr>

                        </tbody>
                    </table>

                    <?php
                    $row_geteditrec = "select * from project_ownership where project_id=$project_id and email_id='" . $_SESSION['email'] . "'";
                    $row_get_end_Date = "select completion_date from project where project_id =" . $project_id;
                    $result_geteditrec = $conn->query($row_geteditrec);
                    $row_get_end_Date = $conn->query($row_get_end_Date);
                    $row_get_end_Date_result = $row_get_end_Date->fetch_assoc();

                    if ($result_geteditrec->num_rows > 0 and (($result_owner['STATUS']==='STARTED') or ($result_owner['STATUS']==='CAMPAIGNING'))) {
                        echo '<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary" >Edit Details</button>';
                    } else if ($result_geteditrec->num_rows > 0){
                        echo '<button type="button" data-toggle="modal" data-target="#" class="btn btn-primary" >The Project cannot be edited!</button>';
                    }

                    $row_get_end_Date_result['completion_date'];
                    ?>
                    


                </div>

                <div class='tab-pane fade' id='funding_details'>

                    <?php

                    $funding_query =
                        "   SELECT DATE_FORMAT(FUND_START_DATE, '%M %d %Y') AS FUND_START_DATE,
                            DATE_FORMAT(FUND_END_DATE, '%M %d %Y') AS FUND_END_DATE, MIN_FUND_GOAL, MAX_FUND_GOAL, STATUS
                            FROM PROJECT_CAMPAIGN
                            WHERE PROJECT_CAMPAIGN.PROJECT_ID = $project_id;";
                    $result = $conn->query($funding_query);
                    $funding_result = $result->fetch_assoc();

                    $total_funds_query = "SELECT SUM(PLEDGED_MONEY) AS TOTAL_MONEY_PLEDGED FROM FUNDS WHERE PROJECT_ID = $project_id GROUP BY PROJECT_ID;";
                    $result = $conn->query($total_funds_query);
                    $total_funds_result = $result->fetch_assoc();
                    ?>


                    <table class='table table-user-information'>

                        <tbody>
                        <tr>
                            <td>Campaign Start Date:</td>
                            <td><?php echo $funding_result['FUND_START_DATE']; ?></td>
                        </tr>
                        <tr>
                            <td>Campaign End Date:</td>
                            <td><?php echo $funding_result['FUND_END_DATE']; ?></td>
                        </tr>
                        <tr>
                            <td>Minimum Funds Required:</td>
                            <td><?php echo "$" . $funding_result['MIN_FUND_GOAL']; ?></td>
                        </tr>
                        <tr>
                            <td>Maximum Funds Required:</td>
                            <td><?php echo "$" . $funding_result['MAX_FUND_GOAL']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Funds Acquired Till Now:</td>
                            <td><?php
                                if(trim($total_funds_result['TOTAL_MONEY_PLEDGED'])!='')
                                    echo "$" . $total_funds_result['TOTAL_MONEY_PLEDGED'];
                                else
                                    echo "$0.00";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Campaign Status:</td>
                            <td><?php echo $funding_result['STATUS']; ?></td>
                        </tr>
                        </tbody>
                    </table>


                    <?php
                    $row_geteditrec = "select * from project_ownership where project_id=$project_id and email_id='" . $_SESSION['email'] . "'";
                    $get_campaign_status = "select status from project_campaign where project_id =" . $project_id;
                    $result_geteditrec = $conn->query($row_geteditrec);
                    $get_campaign_status = $conn->query($get_campaign_status);
                    $row_campgaign_status_result = $get_campaign_status->fetch_assoc();
                    if ($result_geteditrec->num_rows > 0 and $row_campgaign_status_result['status'] === 'IN_PROGRESS') {
                        echo '<button type="button" data-toggle="modal" data-target="#myModal2" class="btn btn-primary" >Edit Details</button>';
                    } else if ($result_geteditrec->num_rows > 0 and $row_campgaign_status_result['status'] === 'COMPLETED') {
                        echo '<button type="button" data-toggle="modal" data-target="#" class="btn btn-primary" >Funding Details cannot be edited!</button>';
                    } else if ($result_geteditrec->num_rows > 0 and $row_campgaign_status_result['status'] === 'FAILED') {
                        echo '<button type="button" data-toggle="modal" data-target="#" class="btn btn-primary" >Funding Details cannot be edited!</button>';
                    }
                    ?>



                </div>

                <div class='tab-pane fade' id='category'>

                    <?php

                    $category_query =
                        " SELECT NAME FROM PROJECT_CATEGORY
                          JOIN CATEGORY ON PROJECT_CATEGORY.CATEGORY_ID = CATEGORY.CATEGORY_ID
                          WHERE PROJECT_ID = $project_id;";

                    $result = $conn->query($category_query);
                    if ($result->num_rows === 0) {
                        echo "No categories for this project.<br>";
                    } else {
                        echo
                            "<table class=" . "table table-striped table-hover" . ">
                                <tbody>";

                        while ($category_result = $result->fetch_assoc()) { ?>

                            <tr>
                                <td>
                                    <a href="viewCategoryProjects.php?category_name=<?php echo $category_result['NAME']; ?>"> <?php echo $category_result['NAME']; ?> </a>
                                </td>
                            </tr>
                            <?php
                        }
                        echo "</tbody></table> ";
                    }

                    ?>

                    <!--By Drumil Button to edit ingredients-->
                    <?php
                    $row_geteditrec = "select * from project_ownership where project_id=$project_id and email_id='" . $_SESSION['email'] . "'";
                    $result_geteditrec = $conn->query($row_geteditrec);
                    if ($result_geteditrec->num_rows > 0) {
                        echo '<button type="button" data-toggle="modal" data-target="#myModal3 " class="btn btn-primary">Add Categories</button>';
                    }
                    ?>

                    
                </div>

                <div class='tab-pane fade' id='ratings'>

                    <?php

                    //checking if the user has pledged and the project is complete
                    $check_project_status_and_user_pledge = "
                            select STATUS,COMPLETION_DATE from PROJECT join funds on PROJECT.PROJECT_ID = FUNDS.PROJECT_ID 
                            where EMAIL_ID = '$email' and PROJECT.project_id = $project_id;";

                    $check_project_status_and_user_pledge = $conn->query($check_project_status_and_user_pledge);

                    $ratings_query =
                        "   select FIRST_NAME,LAST_NAME,USER.EMAIL_ID,NUMBER_OF_STARS,DATE_FORMAT(DATETIME_POSTED, '%M %d %Y, %h:%i:%s %p') AS DATETIME_POSTED from RATINGS
                            JOIN USER on RATINGS.EMAIL_ID = USER.EMAIL_ID
                            where PROJECT_ID = $project_id
                            ORDER BY DATETIME_POSTED DESC";
                    $result = $conn->query($ratings_query);


                    if ($result->num_rows === 0) {
                        echo "No ratings for this project.";
                    } else {
                        echo
                            "<table class=" . "table table-striped table-hover" . ">
                                    <tbody>
                                        <tr>
                                            <th>User</th>
                                            <th>Ratings</th>
                                            <th>Date Time Posted</th>
                                        </tr>";
                        while ($ratings_result = $result->fetch_assoc()) {

                            echo "<tr>"; ?>
                            <td>
                                <strong> <?php echo $ratings_result['FIRST_NAME'] . " " . $ratings_result['LAST_NAME'] . " (" ?>
                                    <a href="viewOtherUserProfile.php?email=<?php echo $ratings_result['EMAIL_ID']; ?>"
                                       style="color: #FFFFFF;"><?php echo $ratings_result['EMAIL_ID']; ?></a><?php echo ")"; ?>
                                </strong></td>
                            <?php echo "<td>" . $ratings_result['NUMBER_OF_STARS'] . "</td>
                                            <td>" . $ratings_result['DATETIME_POSTED'] . "</td>
                                        </tr>";

                        }
                        echo "</tbody></table> ";

                    }


                    if ($check_project_status_and_user_pledge->num_rows !== 0) {
                        $check_project_status_and_user_pledge = $check_project_status_and_user_pledge->fetch_assoc();
                        if ($check_project_status_and_user_pledge['STATUS'] === 'COMPLETED' and !is_null($check_project_status_and_user_pledge['COMPLETION_DATE'])) {
                            $check_user_has_rated = "select * from ratings where email_id = '$email' and project_id = '$project_id'";
                            $check_user_has_rated = $conn->query($check_user_has_rated);
                            if ($check_user_has_rated->num_rows == 0) {
                                ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <form enctype="multipart/form-data" method="post">
                                        <table class="table table-striped table-hover">
                                            <tbody>
                                            <tr>
                                                <td>Stars:</td>
                                                <td><input class="form-control" type="number" name="stars" id="stars"
                                                           min="0"
                                                           max="5" step="0.1" required></td>
                                                <td><input type="submit" value="Submit" name="rate" id="rate"
                                                           class="btn btn-primary btn-sm"/></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <?php
                            }
                        }
                    }

                    if (isset($_POST['rate'])) {
                        $add_rating_query = "INSERT into ratings values(" . $_POST['stars'] . ", NOW()," . $project_id . ",'" . $email . "');";
                        if ($conn->query($add_rating_query) === TRUE) {
                            unset($_POST['stars']);
                            unset($_POST['rate']); ?>
                            <script>
                                window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
                            </script>
                            <?php
                        }
                    }

                    ?>
                </div>

                <div class='tab-pane fade' id='likes'>
                    <?php

                    $like_query =
                        "   SELECT USER.FIRST_NAME, USER.LAST_NAME, USER.EMAIL_ID, DATE_FORMAT(PROJECT_LIKES.DATE_TIME_LIKED, '%M %d %Y, %h:%i:%s %p') AS DATE_TIME_LIKED
                            FROM PROJECT_LIKES
                            JOIN USER ON PROJECT_LIKES.EMAIL_ID = USER.EMAIL_ID
                            WHERE PROJECT_ID = $project_id
                            ORDER BY DATE_TIME_LIKED DESC;";

                    $like_query = $conn->query($like_query);
                    if ($like_query->num_rows === 0) {
                        echo "No Likes for this project. Be the first one to LIKE!";
                    } else {
                        echo
                            "<table class=" . "table table-striped table-hover" . ">
                                <tbody>";

                        while ($like_result = $like_query->fetch_assoc()) { ?>

                            <tr>
                                <td> <?php echo $like_result['FIRST_NAME'] . " " . $like_result['LAST_NAME'] ?> </td>
                                <td>
                                    <a href="viewOtherUserProfile.php?email=<?php echo $like_result['EMAIL_ID']; ?>"><?php echo $like_result['EMAIL_ID']; ?></a>
                                </td>
                                <td><?php echo $like_result['DATE_TIME_LIKED'] ?></td>
                            </tr>
                            <?php
                        }
                        echo "</tbody></table> ";
                    }


                    ?>
                </div>

                <div class='tab-pane fade' id='comments'>
                    <?php


                    $comment_query =
                        "SELECT FIRST_NAME,LAST_NAME,DESCRIPTION,USER.EMAIL_ID, DATE_FORMAT(DATE_TIME_POSTED, '%M %d %Y, %h:%i:%s %p') AS DATE_TIME_POSTED
                            FROM PROJECT_COMMENTS 
                            JOIN USER ON PROJECT_COMMENTS.EMAIL_ID = USER.EMAIL_ID WHERE PROJECT_ID = $project_id
                            ORDER BY DATE_TIME_POSTED DESC ;";

                    $result = $conn->query($comment_query);

                    ?>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <form enctype="multipart/form-data" method="post">
                            <table class="table table-striped table-hover">
                                <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" placeholder="Type your comment here..."
                                               name="comment" id="comment"></td>
                                    <td><input type="submit" value="Submit" name="comment_button" id="comment_button"
                                               class="btn btn-primary btn-sm"/></td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <?php

                    if ($result->num_rows === 0) {
                        echo "No comments yet. Be the first one to comment!";
                    } else {

                        while ($comment_result = $result->fetch_assoc()) { ?>


                            <div class="container">
                                <div class="col-sm-8">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">


                                            <strong> <?php echo $comment_result['FIRST_NAME'] . " " . $comment_result['LAST_NAME'] . " (" ?>
                                                <a href="viewOtherUserProfile.php?email=<?php echo $comment_result['EMAIL_ID']; ?>"
                                                   style="color: #FFFFFF;"><?php echo $comment_result['EMAIL_ID']; ?></a><?php echo ")"; ?>
                                            </strong>
                                            <span> posted on </span>
                                            <span><?php echo $comment_result['DATE_TIME_POSTED']; ?> </span>
                                        </div>
                                        <div class="panel-body"><?php echo $comment_result['DESCRIPTION']; ?> </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }

                    if (isset($_POST['comment_button'])) {
                        $add_project_comment = "INSERT into project_comments values(" . $project_id . ",'" . $email . "','" . $_POST['comment'] . "', NOW());";
                        if ($conn->query($add_project_comment) === TRUE) {
                            ?>
                            <script>
                                window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
                            </script>
                            <?php
                        }
                    } else {

                    }

                    ?>

                </div>

                <div class='tab-pane fade' id='posts'>
                    <?php


                    $post_query =
                        "
                        SELECT USER.FIRST_NAME, USER.LAST_NAME,POST.POST_ID, POST.EMAIL_ID, POST.DATE_TIME_POSTED, POST.DESCRIPTION,POST.MULTIMEDIA FROM POST
                        JOIN USER ON POST.EMAIL_ID = USER.EMAIL_ID
                        WHERE PROJECT_ID = '$project_id' ORDER BY DATE_TIME_POSTED DESC;";

                    $post_query = $conn->query($post_query);

                    ?>

                    <?php
                    $row_geteditrec = "select * from project_ownership where project_id=$project_id and email_id='" . $_SESSION['email'] . "'";
                    $result_geteditrec = $conn->query($row_geteditrec);
                    if ($result_geteditrec->num_rows > 0) {?>
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <form enctype="multipart/form-data" method="post">
                                <table class="table table-striped table-hover">
                                    <tbody>
                                    <tr>
                                        <br>
                                        <label for="inputName" class="control-label">Post</label>
                                        <textarea class="form-control" rows="3" id="post" name="post" required></textarea>
                                        <div style="margin-top:1%">
                        <span class="btn btn-primary btn-sm">
                                       <input type="file" color="#FFFFFF" id="photo" name="photo"/>
                                    </span>
                                            <input type="submit" value="Post" name="post_button" id="post_button"
                                                   style="margin-left: 1%;" class="btn btn-primary btn-sm"/>
                                        </div>

                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    <?php
                    }
                    ?>

                    <br>
                    <?php

                    if ($post_query->num_rows === 0) {
                        echo "No posts yet. Be the first one to post !";
                    } else {

                    while ($post_result = $post_query->fetch_assoc()) { ?>


                        <div class="container">
                            <div class="col-sm-8">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <strong> <?php echo $post_result['FIRST_NAME'] . " " . $post_result['LAST_NAME'] . " (" ?>
                                            <a href="viewOtherUserProfile.php?email=<?php echo $post_result['EMAIL_ID']; ?>"
                                               style="color: #FFFFFF;"><?php echo $post_result['EMAIL_ID']; ?></a><?php echo ")"; ?>
                                        </strong>
                                        <span> posted on </span>
                                        <span><?php echo $post_result['DATE_TIME_POSTED']; ?> </span>
                                    </div>

                                    <div class="panel-body">
                                    <div style="color:#FFFFFF">
                                       <?php
                                        echo $post_result['DESCRIPTION']; ?>



                                    </div>
                                    <br>

                                    <?php
                                    if (!is_null($post_result['MULTIMEDIA'])) {
                                        ?>
                                        <div>
                
                                      <img src='data:image;base64,<?php echo $post_result['MULTIMEDIA']; ?>' alt='Project Pic' title='Post_pic'">
                                      </div>"
                                                           
                                            <?php }?>


                                            
                                
                                    </div>

                                    <div class="container">
                                            <?php

                                            $post_id = $post_result['POST_ID'];

                                            $post_like_query =
                                                "SELECT * FROM POST JOIN POST_LIKES ON POST.POST_ID=POST_LIKES.POST_ID
                                              WHERE UPPER(POST_LIKES.EMAIL_ID)=UPPER('$email')
                                                    AND POST.POST_ID = '$post_id';";

                                            $post_like_query = $conn->query($post_like_query);

                                            if ($post_like_query->num_rows === 0) {
                                                echo "<form enctype='multipart/form-data' method='post'>
                                                        <input  type='submit' value='Like!' name='like_post' class='btn btn-primary btn-sm'/>
                                                        <input type='number' name='post_id' value='".$post_id."' hidden/>
                                                      </form>";
                                            } else {
                                                echo "<form enctype='multipart/form-data' method='post'>
                                                        <input  type='submit' value='Dislike!' name='dislike_post' class='btn btn-primary btn-sm'/>
                                                        <input type='number' name='post_id' value='".$post_id."' hidden/>
                                                      </form>";
                                            }

                                            ?>
                                        
                                    </div>


                                  <?php  
                                $post_id= $post_result['POST_ID'];
                                  $post_comment_query =
                                 
                        " SELECT u.FIRST_NAME,u.LAST_NAME,pc.DESCRIPTION,u.EMAIL_ID, DATE_FORMAT(pc.DATE_TIME_POSTED, '%M %d %Y, %h:%i:%s %p') AS DATE_TIME_POSTED
                            FROM POST_COMMENTS  pc                          
                            JOIN USER u ON pc.EMAIL_ID = u.EMAIL_ID join post p on p.POST_ID=pc.POST_ID WHERE p.PROJECT_ID = $project_id and 
                            p.POST_ID = $post_id
                            ORDER BY DATE_TIME_POSTED ;";
                            

                        $result = $conn->query($post_comment_query);
                        if ($result->num_rows === 0) {
                            ?>
                            <div class="panel-body">
                            <?php
                            echo "No comments yet. Be the first one to comment!";
                            ?>
                            </div>
                            <?php
                        } else {

                        while ($post_comment_result = $result->fetch_assoc()) { ?>


                            <div class="container">
                                <div class="col-sm-8">
                                        

                                            <strong> <?php echo $post_comment_result['FIRST_NAME'] . " " . $post_comment_result['LAST_NAME'] . " (" ?>
                                                <a href="viewOtherUserProfile.php?email=<?php echo $post_comment_result['EMAIL_ID']; ?>"
                                                   style="color: #FFFFFF;"><?php echo $post_comment_result['EMAIL_ID']; ?></a><?php echo ")"; ?>
                                            </strong>
                                            <span> commented on </span>
                                            <span><?php echo $post_comment_result['DATE_TIME_POSTED']; ?> </span>
                                        
                                        <div class="panel-body" style="color: #FFFFFF;"><?php echo $post_comment_result['DESCRIPTION']; ?> </div>
                                   
                                </div>
                            </div>
                            <?php
                        }
                    }


                    ?>
                    

                                    <div class="container">
                                        <div class="col-sm-7">
                                           <form enctype="multipart/form-data" method="post">
                            <table class="table table-striped table-hover">
                                <tbody>
                                <tr>
                                <input type="hidden" name="post_id" value="<?php echo $post_id;?>" />

                                    <td><input type="text" class="form-control" placeholder="Type your comment here..."
                                               name="post_comment" id="post_comment"></td>
                                    <td><input type="submit" value="Comment" name="post_comment_button" id="post_comment_button"
                                               class="btn btn-primary btn-sm"/></td>
                                </tr>
                                </tbody>
                            </table>
                        </form>


                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>

                    <?php
                    }

                    if (isset($_POST['post_comment_button'])) {
                        $add_post_comment = "INSERT into post_comments values(" . $_POST['post_id'] . ",'" . $email . "',NOW(),'" . $_POST['post_comment'] . "' );";
                        echo $add_post_comment;
                        if ($conn->query($add_post_comment) === TRUE) {
                            ?>
                            <script>
                                window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
                            </script>
                            <?php
                        }
                    } else {

                    }



                    if (isset($_POST['like_post'])) {
                    $sql_like_post = "Insert into post_likes (post_id,email_id,date_time_liked) values ('".$_POST['post_id']."','$email',NOW());";
                    if ($conn->query($sql_like_post)) {
                    ?>
                        <script>
                            window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
                        </script>
                    <?php

                    }
                    }

                    if (isset($_POST['dislike_post'])) {

                    $sql_like = "delete from post_likes where email_id = '$email' and post_id = '".$_POST['post_id']."'";
                    if ($conn->query($sql_like)) {
                    ?>
                        <script>
                            window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
                        </script>
                        <?php

                    }
                    }


                    }


                    if (isset($_POST['post_button'])) {
                            if (getimagesize($_FILES['photo']['tmp_name']) == TRUE) {
                                $photo = addslashes($_FILES['photo']['tmp_name']);
                                $photo = file_get_contents($photo);
                                $photo = base64_encode($photo);
                                echo $photo;
    

                                $add_post = "INSERT into post(PROJECT_ID,EMAIL_ID,DESCRIPTION,DATE_TIME_POSTED,MULTIMEDIA) values(" . $project_id . ",'" . $email . "','" . $_POST['post'] . "', NOW(),'" . $photo . "');";
                    }
                    else{
                   $add_post = "INSERT into post (PROJECT_ID,EMAIL_ID,DESCRIPTION,DATE_TIME_POSTED,MULTIMEDIA) values(" . $project_id . ",'" . $email . "','" . $_POST['post'] . "', NOW(),null);";
                        
                    }
                    echo $add_post;

                        if ($conn->query($add_post) === TRUE) {
                            ?>
                            <script>
                                window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
                            </script>
                            <?php
                        }
                    } else {

                    }

                    ?>

                </div>

            </div>
        </div>

    </div>
</div>

<!--By Drumil Modal Code-->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 ">
                    <h3 class="text-center login-title">Edit Project Information</h3>
                    <div class="account-wall">
                        <form class="form-signin" method="POST" action="updateProject.php">

                            <table class='table table-user-information'>

                                <tbody>

                                <tr>
                                    <td>End Date:</td>
                                    <td><input type="date" class="form-control"
                                               placeholder="example: March 12 2017" name="completion_date"
                                               autofocus value="<?php echo $result_owner['COMPLETION_DATE']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td><input list="statuses" class="form-control" placeholder="Status" name="status"
                                               required
                                               autofocus value="<?php echo $result_owner['STATUS']; ?>" required>
                                        <datalist id="statuses">
                                            <option value="COMPLETED">
                                            <option value="STARTED">
                                            <option value="CAMPAIGNING">
                                            <option value="FAILED">
                                        </datalist>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 ">
                    <h1 class="text-center login-title">Edit Fund Details</h1>
                    <div class="account-wall">
                        <form class="form-signin" method="POST" action="updateFundingDetails.php">

                            <?php

                            $fund_edit_query =
                                "select MIN_FUND_GOAL, MAX_FUND_GOAL from PROJECT_CAMPAIGN where PROJECT_ID = $project_id";

                            $fund_edit_query = $conn->query($fund_edit_query);

                            $fund_edit_result = $fund_edit_query->fetch_assoc();


                            ?>

                            <table class='table table-user-information'>

                                <tbody>
                                <tr>
                                    <td>Minimum Funds($):</td>
                                    <td><input type="number" step="0.1" class="form-control" placeholder="MIN_FUND_GOAL"
                                               name="min_fund_goal"
                                               required autofocus
                                               value="<?php echo $fund_edit_result['MIN_FUND_GOAL']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Maximum Funds($):</td>
                                    <td><input type="number" step="0.1" class="form-control" placeholder="Description"
                                               name="max_fund_goal"
                                               required autofocus
                                               value="<?php echo $fund_edit_result['MAX_FUND_GOAL']; ?>">
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal3" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 ">
                    <h1 class="text-center login-title">Edit Categories</h1>
                    <div class="account-wall">

                        <!--<form class="form-signin" method="POST" action="updateFundingDetails.php">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Add new Category</button>
                            </div>
                        </form>-->

                        <form class="form-signin" method="POST" action="updateCategory.php">
                            <?php

                            $category_update_query =
                                "select NAME from CATEGORY";

                            $category_update_query = $conn->query($category_update_query);

                            $current_category = "select NAME 
                                                  from PROJECT_CATEGORY 
                                                  join CATEGORY on PROJECT_CATEGORY.CATEGORY_ID = CATEGORY.CATEGORY_ID 
                                                  where PROJECT_ID = $project_id";

                            $current_category = $conn->query($current_category);

                            $current_category_array = array();
                            while ($row = $current_category->fetch_assoc()) {
                                $current_category_array[] = $row['NAME'];
                            }


                            if ($category_update_query->num_rows === 0) {
                                echo "No categories to choose from";
                            } else { ?>

                                <table class='table table-user-information'>
                                    <?php
                                    while ($category_update_result = $category_update_query->fetch_assoc()) {
                                        $flag = 0;
                                        for ($x = 0; $x < count($current_category_array); $x++) {
                                            if ($category_update_result['NAME'] === $current_category_array[$x]) {
                                                $flag = 1;
                                            }
                                        }
                                        if ($flag === 0) {
                                            echo '
                                                    <tr>
                                                    <td><input type="checkbox" name="category[]"
                                                               value="' . $category_update_result['NAME'] . '"></td>
                                                    <td>' . $category_update_result['NAME'] . '</td> </tr>';
                                        }

                                        ?>
                                        <?php
                                    }
                                    ?>
                                </table>
                                <?php
                            }
                            ?>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal5" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 ">
                    <h4 class="text-center login-title">Pledge Funds</h4>
                    <h6 class="text-center login-title">Help create the world a better place.</h6>
                    <div class="account-wall">
                        <form class="form-signin" method="POST" action="insertFunds.php">

                            <table class='table table-user-information'>

                                <tbody>
                                <tr>
                                    <td>Enter Amount:</td>
                                    <td><input type="number" step="0.1" class="form-control" placeholder="Pledged Money"
                                               name="pledged_money"
                                               required autofocus>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script>

    $('#myTab a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
        var id = $(e.target).attr("href").substr(1);
        <?php $_SESSION['hash']?> = id;
        window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    hash = <?php echo $_SESSION['hash'];?>
    $('#myTab a[href="' + hash + '"]').tab('show');


    var val = '<?php echo $_GET['tab'] ?>';
    if(val != ''){
        //alert(val);
        jQuery(function () {
            jQuery('a[href="#stock"]').tab('show');
        });
    }

</script>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<!--<script src="js/bootstrap.min.js"></script>-->
</body>
</html>
