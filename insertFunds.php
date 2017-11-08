<?php
require 'dbconn.php';

session_start(); //starts the session
if ($_SESSION['email']) { //checks if name is logged in
} else {
    header("location:index.php"); // redirects if name is not logged in
}
$email = $_SESSION['email']; //assigns name value

if (isset($_SESSION['project_id'])) {

    $pledged_money = $_POST['pledged_money'];
    $project_id = $_SESSION['project_id'];

    $insert_funds = "insert into FUNDS(PROJECT_ID, EMAIL_ID, PLEDGED_MONEY, DATETIME_PLEDGED) 
                        VALUES ('" . $project_id . "','" . $email . "','" . $pledged_money . "',NOW());";

    if ($conn->query($insert_funds) === TRUE) { ?>
        <script>
            alert("successfully pledged.");
            window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("You have already pledged");
            window.location.href = 'viewProject.php?project_id=<?php echo $project_id;?>';
        </script>
        <?php

    }
} else {
    echo "Session not SET";
}

?>