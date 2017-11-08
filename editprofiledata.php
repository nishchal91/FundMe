<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Edit Profile</title>

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


if (isset($_POST['first_name']) and isset($_POST['last_name']) and
    isset($_POST['address_line_1']) and isset($_POST['address_line_2']) and
    isset($_POST['city']) and isset($_POST['state']) and
    isset($_POST['zip']) and isset($_POST['country']) and
    isset($_POST['email_id']) and isset($_POST['phone'])) {


    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address_line_1 = $_POST['address_line_1'];
    $address_line_2 = $_POST['address_line_2'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $country = $_POST['country'];
    $new_email = $_POST['email_id'];


    $sql_proupdate =
        "update user 
          set 
            EMAIL_ID = '$new_email',
            FIRST_NAME = '$first_name',
            LAST_NAME = '$last_name',
            ADDRESS_LINE_1 = '$address_line_1',
            ADDRESS_LINE_2 = '$address_line_2',
            PHONE = '$phone',
            CITY = '$city',
            STATE = '$state',
            COUNTRY = '$country',
            ZIPCODE = '$zip'
          where email_id = '$email'";

    if ($conn->query($sql_proupdate)) {
        $_SESSION['email'] = $new_email;
        ?>
        <script>
            window.location.href = 'profile.php';
        </script>
        <?php
    }
}
?>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<!-- Include all compiled plugins (below), or include individual files as needed -->

</body>
</html>
