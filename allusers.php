<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

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


if (!isset($_SESSION['email'])) {
    echo "Enter email Please";
} else {

    // Query: Get recipe and title
    $sql =
        "SELECT EMAIL_ID,FIRST_NAME,LAST_NAME,PHOTO from USER where EMAIL_ID <>'" . $_SESSION['email'] . "'";
    $result = $conn->query($sql);


    // Checking for Invalid Option
    if ($result->num_rows === 0) {

        echo "";
        echo '<div class="alert alert-dismissible alert-danger">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Oh snap! There are no other emails on FundMe other than you.</strong>
				</div>';

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
                                            if (is_null($row['PHOTO'])) {
                                                ?>
                                                <img class="img-responsive user-photo" src="img/blank.png">
                                                <?php
                                            } else {
                                                ?>
                                                <img class="img-responsive user-photo"
                                                     src="data:image;base64,<? echo $row['PHOTO']; ?>">
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <a href="viewOtherUserProfile.php?email=<?php echo $row['EMAIL_ID'] ?>">
                                        <?php echo $row['FIRST_NAME'] . " " . $row['LAST_NAME'] ?>
                                    </a>
                                </td>
                                <td>
                                    <?php
                                    $sql_checkfollower =
                                        "SELECT * FROM FOLLOWER WHERE UPPER(FOLLOWER_EMAIL_ID)=UPPER('" . $email . "') AND UPPER(FOLLOWED_EMAIL_ID)=UPPER('" . $row['EMAIL_ID'] . "')";
                                    //echo $sql_checkfollower;
                                    $result_checkfollower = $conn->query($sql_checkfollower);
                                    if ($result_checkfollower->num_rows === 0) {
                                        echo "<a href='addfollow.php?email=" . $row['EMAIL_ID'] . "' class='btn btn-primary btn-sm'>Follow</a>";
                                    } else {
                                        $_SESSION['actual_page'] = basename($_SERVER['PHP_SELF']);
                                        echo "<a href='unfollow.php?email=" . $row['EMAIL_ID'] . "' class='btn btn-primary btn-sm'>Unfollow</a>";
                                    }
                                    ?>
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
