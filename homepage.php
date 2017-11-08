<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags must come first in the head; any other head content must come after these tags -->
    <title>FundMe!!</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/timeline.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
require 'navbar.php';
require 'dbconn.php';


?>

<div class="container">
    <div class="row">
        <blockquote>
            <h5><p>Your Feed</p>
                <h5>
                    <small>Here's are some <cite title="Source Title">Projects from the users you follow!</cite></small>
        </blockquote>


        <?php

            $sql = "SELECT PROJECT.PROJECT_ID,PROJECT_TITLE, PHOTO FROM PROJECT
                    JOIN PROJECT_OWNERSHIP ON PROJECT.PROJECT_ID = PROJECT_OWNERSHIP.PROJECT_ID
                    JOIN FOLLOWER ON PROJECT_OWNERSHIP.EMAIL_ID = FOLLOWER.FOLLOWED_EMAIL_ID
                    WHERE FOLLOWER_EMAIL_ID = '" . $email . "';";

            // $sql = "SELECT rid,rtitle,recipe.desc from recipe where uname = '".$_SESSION['email']."'";


            $result = $conn->query($sql);

            if ($result->num_rows === 0) {
                echo "<br>No Projects to Display!!  ";


            } else {

                while ($row = $result->fetch_assoc()) { ?>

                    <div class ="col-sm-4">
                        <div class="card">

                          <!--Card image-->
                            <?php
                            if (is_null($row['PHOTO'])){?>
                                <img src='img/blank.png' alt='Project Pic' title='Project Pic'
                                     style="width:128px;height:128px">
                                <?php
                            }else{?>
                                <img src='data:image;base64,<?php echo $row['PHOTO']; ?>' alt='Project Pic' title='Project Pic'
                                     style="width:128px;height:128px">
                                <?php
                            }?>

                            <!--/.Card image-->

                        <!--Card content-->
                            <div class="card-block">
                              <!--Title-->
                            <h4 class="card-title"><?php echo $row['PROJECT_TITLE']; ?></h4>
                            <a href="viewProject.php?project_id=<?php echo $row['PROJECT_ID']; ?>" class="btn btn-primary">View Project</a>
                                <BR><br>

                        </div>
                      <!--/.Card content-->
                  </div>
                  </div><?php
                    # code...
                }
            }

        ?>
    </div>
</div>

<div class="container">

    <br>
    <div class="row">
        <blockquote>
            <h5><p>Your Recent Pledges</p>
        </blockquote>


        <?php

        $recent_pledges = "SELECT PROJECT.PROJECT_TITLE, FUNDS.PLEDGED_MONEY 
                            FROM FUNDS 
                              JOIN PROJECT ON FUNDS.PROJECT_ID = PROJECT.PROJECT_ID 
                            WHERE FUNDS.EMAIL_ID = '$email'
                            LIMIT 10;";

        $recent_pledges = $conn->query($recent_pledges);

        if ($recent_pledges->num_rows === 0) {
            echo "<br>No Recent Pledges!";
        } else {
            echo
                "<table class=" . "table table-striped table-hover" . ">
                                <tbody>";
            while ($row = $recent_pledges->fetch_assoc()) {

                echo "<tr>
                                    <td>" ." You pledged $". $row['PLEDGED_MONEY'] ." for project ".$row['PROJECT_TITLE']."</td>
                                  </tr>";
            }
            echo "</tbody></table> ";
            # code...
        }

        ?>
    </div>
</div>

<div class="container">

    <br>
    <div class="row">
        <blockquote>
            <h5><p>Your Recent Likes</p>
        </blockquote>


        <?php

        $recent_likes = "SELECT * FROM (SELECT PROJECT.PROJECT_TITLE AS CONTENT, DATE_TIME_LIKED AS DATETIME,'project' as PROJECT_OR_POST
                FROM PROJECT_LIKES
                  JOIN PROJECT ON PROJECT_LIKES.PROJECT_ID = PROJECT.PROJECT_ID
                WHERE EMAIL_ID = '$email'
                UNION
                SELECT DESCRIPTION AS CONTENT, DATE_TIME_LIKED AS DATETIME, 'post' as PROJECT_OR_POST
                FROM POST_LIKES
                  JOIN POST ON POST_LIKES.POST_ID = POST.POST_ID
                WHERE POST_LIKES.EMAIL_ID = '$email') TEMP
                ORDER BY DATETIME DESC
                LIMIT 10;";

        $recent_likes = $conn->query($recent_likes);

        if ($recent_likes->num_rows === 0) {
            echo "<br>No Recent Likes!";
        } else {
            echo
                "<table class=" . "table table-striped table-hover" . ">
                                <tbody>";
            while ($row = $recent_likes->fetch_assoc()) {

                    echo "<tr>
                                    <td>" ." You liked ". $row['PROJECT_OR_POST'] ." ".$row['CONTENT']. "</td>
                                  </tr>";
                }
                echo "</tbody></table> ";
                # code...
        }

        ?>
    </div>
</div>

<div class="container">

    <br>
    <div class="row">
        <blockquote>
            <h5><p>Your Recent Comments</p>
        </blockquote>


        <?php

        $recent_comments = "SELECT * FROM (SELECT PROJECT.PROJECT_TITLE AS TITLE, PROJECT_COMMENTS.DESCRIPTION AS COMMENT ,DATE_TIME_POSTED AS DATETIME,'project' as PROJECT_OR_POST
                FROM PROJECT_COMMENTS
                  JOIN PROJECT ON PROJECT_COMMENTS.PROJECT_ID = PROJECT.PROJECT_ID
                WHERE PROJECT_COMMENTS.EMAIL_ID = '$email'
                UNION
                SELECT POST.DESCRIPTION AS CONTENT, POST_COMMENTS.DESCRIPTION AS COMMENT, POST_COMMENTS.DATE_TIME_POSTED AS DATETIME, 'post' as PROJECT_OR_POST
                FROM POST_COMMENTS
                  JOIN POST ON POST_COMMENTS.POST_ID = POST.POST_ID
                WHERE POST_COMMENTS.EMAIL_ID = '$email') TEMP
                ORDER BY DATETIME DESC
                LIMIT 10;";

        $recent_comments = $conn->query($recent_comments);

        if ($recent_comments->num_rows === 0) {
            echo "<br>No Recent Comments!";
        } else {
            echo
                "<table class=" . "table table-striped table-hover" . ">
                                <tbody>";
            while ($row = $recent_comments->fetch_assoc()) {

                echo "<tr>
                                    <td>" ." You commented  ' ". $row['COMMENT'] ." ' on ".$row['PROJECT_OR_POST']. " - ". $row['TITLE']."</td>
                                  </tr>";
            }
            echo "</tbody></table> ";
            # code...
        }

        ?>
    </div>
</div>

<div class="container">

    <br>
    <div class="row">
        <blockquote>
            <h5><p>Your Recent Posts</p>
        </blockquote>


        <?php

        $recent_posts = "SELECT p.DESCRIPTION,pr.PROJECT_TITLE FROM fundraise.post p join project pr on p.PROJECT_ID=pr.PROJECT_ID  where email_id= '$email' order by POST_ID desc limit 5";
        
        $recent_posts = $conn->query($recent_posts);

        if ($recent_posts->num_rows === 0) {
            echo "<br>No Recent Posts !";
        } else {
            echo
                "<table class=" . "table table-striped table-hover" . ">
                                <tbody>";
            while ($row = $recent_posts->fetch_assoc()) {

                echo "<tr>
                                    <td>" ." You posted : '". $row['DESCRIPTION'] ." ' on the project - ".$row['PROJECT_TITLE']."</td>
                                  </tr>";
            }
            echo "</tbody></table> ";
            # code...
        }

        ?>
    </div>
</div>


</div><!-- /row -->

</div><!-- /container -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->


</body>

<!-- Include all compiled plugins (below), or include individual files as needed -->

</html>