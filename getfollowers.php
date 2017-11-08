<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>My Followers</title>

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
?>

<?php

$us = $_GET['email'];


if (!isset($_SESSION['email'])) {
    echo "Enter Username Please";
} else {

    // Query: Get recipe and title
    /*if($us===$_SESSION['user'])
      {
        $flag=TRUE;
              $sql =
              "SELECT email,fname,lname,street,city,country,state,zip,propic from user join follows on user.email=follows.email where femail = '".$_SESSION['user']."'";
          }
      else
      {*/
    $sql =
        "SELECT FOLLOWER_EMAIL_ID,FIRST_NAME,LAST_NAME,PHOTO FROM fundraise.follower f  join user u on f.FOLLOWER_EMAIL_ID=u.EMAIL_ID where 
		        f.FOLLOWED_EMAIL_ID = '" . $us . "'";
    //}


    $result = $conn->query($sql);


    // Checking for Invalid Option
    if ($result->num_rows === 0) {

        ?>

        <script>
            alert("There are no followers!")
            window.location.href = 'homepage.php';
        </script>

    <?php

    } else {

        ?>

        <div class="container">
            <div class='panel panel-primary'>
                <div class='panel-heading'>
                    <h3 class='panel-title'>Users</h3>
                </div>
                <div class='panel-body'>
                    <table class="table table-striped table-hover">
                        <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) { ?>

                            <tr>
                                <td>
                                    <div class="col-sm-1">
                                        <div class="thumbnail">
                                            <?php
                                            if(is_null($row['PHOTO'])){?>
                                                <img class="img-responsive user-photo" src="img/blank.png">
                                                <?php
                                            }else{?>
                                                <img class="img-responsive user-photo" src="data:image;base64,<?echo $row['PHOTO'];?>">
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <a href="viewOtherUserProfile.php?email=<?php echo $row['FOLLOWER_EMAIL_ID'] ?>">
                                        <?php echo $row['FIRST_NAME']." ".$row['LAST_NAME'] ?>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php


    }
}
?>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<!--<script src="js/bootstrap.min.js"></script>-->
</body>
</html>
