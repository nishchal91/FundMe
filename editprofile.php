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
require 'navbar.php';
?>
<?php


// Query: Get recipe and title
$sql =
    "SELECT * from user where email_id = '$email'";
$result = $conn->query($sql);


// Checking for Invalid Option
if ($result->num_rows === 0) {

    echo "";


} else {
    //$tableclass=;
    $row = $result->fetch_assoc();

}
?>

<div class="container">
    <div class="row">
        <!--<div class="col-md-5  toppad  pull-right col-md-offset-3 ">
             <A href="edit.html" >Edit Profile</A>

          <A href="edit.html" >Logout</A>
         <br>

        </div>-->
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad">


            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $row['FIRST_NAME'] . ' ' . $row['LAST_NAME']; ?></h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 " align="center">
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


                        <div class=" col-md-9 col-lg-9 ">
                            <form class="form-signin" method="POST" action="editprofiledata.php">
                                <table class="table table-user-information">
                                    <tbody>
                                    <tr>
                                        <td>Email ID:</td>
                                        <td><input type="text" class="form-control" name="email_id"
                                                   value="<?php echo $row['EMAIL_ID']; ?>" required
                                            readonly="readonly"></td>
                                    </tr>
                                    <tr>
                                        <td>First Name:</td>
                                        <td><input type="text" class="form-control" name="first_name"
                                                   value="<?php echo $row['FIRST_NAME']; ?>" required readonly="readonly"></td>
                                    </tr>
                                    <tr>
                                        <td>Last Name:</td>
                                        <td><input type="text" class="form-control" name="last_name"
                                                   value="<?php echo $row['LAST_NAME']; ?>" required readonly="readonly"></td>
                                    </tr>
                                    <tr>

                                        <td>Address Line 1:</td>
                                        <td><input type="text" class="form-control" name="address_line_1"
                                                   value="<?php echo $row['ADDRESS_LINE_1']; ?>" required></td>
                                    </tr>
                                    <tr>

                                        <td>Address Line 2:</td>
                                        <td><input type="text" class="form-control" name="address_line_2"
                                                   value="<?php echo $row['ADDRESS_LINE_2']; ?>" required></td>
                                    </tr>
                                    <tr>
                                        <td>City:</td>
                                        <td><input type="text" class="form-control" name="city"
                                                   value="<?php echo $row['CITY']; ?>" required></td>
                                    </tr>
                                    <tr>
                                        <td>State:</td>
                                        <td><input type="text" class="form-control" name="state"
                                                   value="<?php echo $row['STATE']; ?>" required></td>
                                    </tr>
                                    <tr>
                                        <td>Zip Code:</td>
                                        <td><input type="text" class="form-control" name="zip"
                                                   value="<?php echo $row['ZIPCODE']; ?>" required></td>

                                    </tr>
                                    <tr>
                                        <td>Country:</td>
                                        <td><input type="text" class="form-control" name="country"
                                                   value="<?php echo $row['COUNTRY']; ?>" required></td>

                                    </tr>

                                    <tr>
                                        <td>Phone:</td>
                                        <td><input type="text" class="form-control" id="phone" name="phone"
                                                   placeholder="Enter Phone" onkeypress="phoneno()" minlength="10" maxlength="10"
                                                   value="<?php echo $row['PHONE']; ?>" required></td>

                                    </tr>

                                    </tbody>
                                </table>

                                <button class="btn btn-primary" type="Submit">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function phoneno() {
        $('#phone').keypress(function (e) {
            var a = [];
            var k = e.which;

            for (i = 48; i < 58; i++)
                a.push(i);

            if (!(a.indexOf(k) >= 0))
                e.preventDefault();
        });
    }
</script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<!--<script src="js/bootstrap.min.js"></script>-->
</body>
</html>
