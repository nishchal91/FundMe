<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Sign In!</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<?php

require 'dbconn.php';

if (empty($_POST['email'])) {
    echo "Enter Email Address Please";
} else if (!empty($_POST)) {


    if (isset($_POST['email']) and isset($_POST['password'])) {


        $email=mysqli_real_escape_string($conn,$_POST['email']);
        $password=mysqli_real_escape_string($conn,$_POST['password']);

        //$email = $_POST['email'];
        //$password = $_POST['password'];
        //echo $movieName;


        // Query
        $sql =
            "SELECT email_id,first_name,last_name 
                from user 
                where email_id = '" . $email . "' and password ='" . $password . "'";

        $result = $conn->query($sql);


        // Checking for Invalid Movie Option


        if ($result->num_rows === 0) {
            echo "";
            echo '<div class="alert alert-dismissible alert-danger">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Oh snap! Invalid Username or Password</strong> <a href="index.php" class="alert-link">Change a few things up</a> and try submitting again.
				</div>';


        } else {
            session_start();
            $_SESSION['email'] = $_POST['email'];
            while ($row = $result->fetch_assoc()) {

                echo '<div class="alert alert-dismissible alert-success">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Welcome To CookZilla!</strong> You successfully read <a href="#" class="alert-link">this important alert message</a>.
								</div>';
                header('location: homepage.php');
            }
        }
    }
}
?>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
