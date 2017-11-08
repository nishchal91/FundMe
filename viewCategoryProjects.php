<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>My Projects</title>

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
<?php require 'dbconn.php';
require 'navbar.php';

$category_name = $_GET['category_name'];

$category_projects_query = "SELECT * FROM PROJECT WHERE PROJECT_ID IN (
  SELECT PROJECT_ID FROM CATEGORY
  JOIN PROJECT_CATEGORY ON CATEGORY.CATEGORY_ID = PROJECT_CATEGORY.CATEGORY_ID
  WHERE UPPER(NAME) = UPPER('$category_name')
)";


$category_projects_result = $conn->query($category_projects_query);

if ($category_projects_result->num_rows === 0) {
    echo "";
    echo '<div class="alert alert-dismissible alert-danger">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Oh snap! You have not posted any recipe yet.</strong>
				</div>';


} else {

    while ($row = $category_projects_result->fetch_assoc()) { ?>
        <center>
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
                        <a href="viewProject.php?project_id=<?echo $row['PROJECT_ID']?>" class="btn btn-primary">View Project</a>
                    </div>
                    <br><br>
                    <!--/.Card content-->
                </div>
            </div>
        </center>
        <?php
        # code...
    }
}
?>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<!--<script src="js/bootstrap.min.js"></script>-->
</body>
</html>
