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

        $min_fund_goal = $_POST['min_fund_goal'];
        $max_fund_goal = $_POST['max_fund_goal'];

        $project_fund_update = "UPDATE project_campaign 
                            SET min_fund_goal = '".$min_fund_goal."', 
                                max_fund_goal = '".$max_fund_goal."'              
                            where project_campaign.project_id =".$_SESSION['project_id'];

        echo $project_fund_update."\r\n";

        //echo 'location : http://localhost/fundme/viewProject.php?project_id='.$project_id;

        if ($conn->query($project_fund_update)=== TRUE){
            header('location:viewProject.php?project_id='.$project_id);
            echo"Hello";
        }
    }
    else{
        echo "Session not SET";
}
?>