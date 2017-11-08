<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Search</title>

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

$keyword = $_POST['keyword'];
$flag = 0;


//Query for Projects
$sql_search =
    "SELECT PROJECT.PROJECT_ID,PROJECT_TITLE,PROJECT.DESCRIPTION,GROUP_CONCAT(CATEGORY.NAME) AS CATEGORIES
             FROM PROJECT
              JOIN PROJECT_CATEGORY ON PROJECT.PROJECT_ID = PROJECT_CATEGORY.PROJECT_ID
              JOIN CATEGORY ON PROJECT_CATEGORY.CATEGORY_ID = CATEGORY.CATEGORY_ID
            WHERE UPPER(PROJECT.PROJECT_TITLE) LIKE UPPER('%$keyword%')
                  OR UPPER(PROJECT.DESCRIPTION) LIKE UPPER('%$keyword%')
                  OR UPPER(CATEGORY.NAME) LIKE UPPER('%$keyword')
            GROUP BY  PROJECT.PROJECT_ID,PROJECT_TITLE,PROJECT.DESCRIPTION;";

$sql_search = $conn->query($sql_search);


// Checking for Invalid Option
if ($sql_search->num_rows === 0) {


} else {

    ?>

    <div class="container">
        <div class='panel panel-primary'>
            <div class='panel-heading'>
                <h3 class='panel-title'>Projects</h3>
            </div>
            <div class='panel-body'>
                <table class="table table-striped table-hover">
                    <tbody>
                    <tr>
                        <th>Project Title</th>
                        <th>Project Description</th>
                        <th>Project Categories</th>
                    </tr>
                    <?php
                    while ($search_result = $sql_search->fetch_assoc()) { ?>

                        <tr>
                            <td>
                                <a href="viewProject.php?project_id=<?php echo $search_result['PROJECT_ID']; ?>"><?php echo $search_result['PROJECT_TITLE']; ?></a>
                            </td>
                            <td> <?php echo $search_result['DESCRIPTION'] ?> </td>
                            <td><?php echo $search_result['CATEGORIES'] ?></td>
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


$sql_user_search = "SELECT FIRST_NAME, LAST_NAME, EMAIL_ID, PHOTO
                    FROM USER 
                    WHERE FIRST_NAME LIKE '%$keyword%' 
                          OR LAST_NAME LIKE '%$keyword%' 
                          OR EMAIL_ID LIKE '%$keyword%';";

$sql_user_search = $conn->query($sql_user_search);

if ($sql_user_search->num_rows === 0) {


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
                    while ($user_search_result = $sql_user_search->fetch_assoc()) { ?>

                        <tr>
                            <td>
                                <div class="col-sm-1">
                                    <div class="thumbnail">
                                        <?php
                                        if(is_null($user_search_result['PHOTO'])){?>
                                            <img class="img-responsive user-photo" src="img/blank.png">
                                            <?php
                                        }else{?>
                                            <img class="img-responsive user-photo" src="data:image;base64,<?echo $user_search_result['PHOTO'];?>">
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <a href="viewOtherUserProfile.php?email=<?php echo $user_search_result['EMAIL_ID'] ?>">
                                    <?php echo $user_search_result['FIRST_NAME']." ".$user_search_result['LAST_NAME'] ?>
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



?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<!--<script src="js/bootstrap.min.js"></script>-->
</body>
</html>
