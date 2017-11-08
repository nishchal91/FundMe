<?php
require 'dbconn.php';

session_start(); //starts the session
if ($_SESSION['email']) { //checks if name is logged in
} else {
    header("location:index.php"); // redirects if name is not logged in
}
$email = $_SESSION['email']; //assigns name value
$project_id = $_SESSION['project_id'];

if (isset($_SESSION['project_id'])){

    if(!empty($_POST['category'])) {
        foreach($_POST['category'] as $check) {

            $update_project_category = "insert into project_category(PROJECT_ID, CATEGORY_ID)
                                        (SELECT $project_id,category_id from category where name='$check')";

            if ($conn->query($update_project_category)=== TRUE){
                header('location:viewProject.php?project_id='.$project_id);
                echo"Hello";
            }

            echo $check; //echoes the value set in the HTML form for each checked checkbox.
            //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
            //in your case, it would echo whatever $row['Report ID'] is equivalent to.
        }
    }
}
else{
    echo "Session not SET";
}
?>