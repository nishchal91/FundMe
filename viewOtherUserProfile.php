<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Profile</title>

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


$guest_email = $_GET['email'];

if($guest_email === $email){?>
    <script>
        window.location.href = 'profile.php';
    </script>
<?php
}

$profile =
    "SELECT EMAIL_ID,FIRST_NAME,LAST_NAME,ADDRESS_LINE_1,ADDRESS_LINE_2,
            CITY,STATE,COUNTRY,ZIPCODE,PHONE,PHOTO 
       FROM USER WHERE EMAIL_ID = '$guest_email'";

$profile = $conn->query($profile);
// Checking for Invalid Option
if ($profile->num_rows === 0) {
    ?>
    <script>
        alert("No such user!");
        window.location.href = 'homepage.php';
    </script>
    <?php
} else {
    $row = $profile->fetch_assoc();
}

?>

<div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div>
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
                </div>
                <br>
                <h3 class="panel-title"><?php echo $row['FIRST_NAME'] . ' ' . $row['LAST_NAME']; ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class=" col-md-9 col-lg-9 ">
                        <table class="table table-user-information" align="center">
                            <tbody>
                            <tr>
                                <td>Email ID:</td>
                                <td><?php echo "<a href=viewOtherUserProfile.php?email=" . $row['EMAIL_ID'] . ">" . $row['EMAIL_ID'] . "</a>"; ?></td>
                            </tr>
                            <tr>
                                <td>First Name:</td>
                                <td><?php echo $row['FIRST_NAME']; ?></td>
                            </tr>
                            <tr>
                                <td>Last Name:</td>
                                <td><?php echo $row['LAST_NAME']; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Some of <?php echo $row['FIRST_NAME']." ".$row['LAST_NAME']."'s" ?> projects</h3>
            </div>
            <div class="panel-body">
                <div class="row">

                    <?php


                    $some_projects = "SELECT * 
                                  FROM PROJECT 
                                  JOIN PROJECT_OWNERSHIP ON PROJECT.PROJECT_ID = PROJECT_OWNERSHIP.PROJECT_ID 
                                  WHERE EMAIL_ID = '$guest_email'";

                    $some_projects = $conn->query($some_projects);

                    if ($some_projects->num_rows === 0) {
                        echo '<div class ="col-sm-4">
                                        <h4>No recent projects!</h4>';

                    } else {
                        while ($row = $some_projects->fetch_assoc()) { ?>
                            <center>
                                <div class="col-sm-4">
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
                                            <h4 class="card-title"><?php echo $row['PROJECT_TITLE'] ;?></h4>
                                            <a href="viewProject.php?project_id=<?php echo $row['PROJECT_ID'] ;?>" class="btn btn-primary">View
                                                Project</a>
                                        </div>
                                        <br><br>
                                        <!--/.Card content-->
                                    </div>
                                </div>
                            </center>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<!--<script src="js/bootstrap.min.js"></script>-->
</body>
</html>
