<?php
require 'dbconn.php';

session_start(); //starts the session
if ($_SESSION['email']) { //checks if name is logged in
} else {
    header("location:index.php"); // redirects if name is not logged in
}
$email = $_SESSION['email']; //assigns name value


if (isset($_SESSION['project_id'])){

    $completion_date = $_POST['completion_date'];
    $status = $_POST['status'];

    $project_campaign_status = "SELECT * FROM PROJECT_CAMPAIGN WHERE PROJECT_ID = ".$_SESSION['project_id'];
    $project_campaign_status = $conn -> query($project_campaign_status);
    $project_campaign_status_result = $project_campaign_status->fetch_assoc();


    if(($project_campaign_status_result['STATUS'] === 'IN_PROGRESS') or ($project_campaign_status_result['STATUS'] === 'COMPLETED')){
        if(!isset($completion_date) and (($status ==='FAILED'))){
            $project_update = "UPDATE project 
                        SET status = '".$status."'                           
                        where project.project_id =".$_SESSION['project_id'];
        }else if (trim($completion_date)!=='' and ($status ==='COMPLETED')){
            $project_update = "UPDATE project 
                        SET COMPLETION_DATE = '$completion_date',
                            status = '".$status."'                           
                        where project.project_id =".$_SESSION['project_id'];
        }else{
            ?>
            <script>
                alert("Error in updating. Please check the details and try again.");
                window.location.href = 'viewProject.php?project_id=<?php echo $_SESSION['project_id'];?>';
            </script>
            <?php
        }

        echo $project_update."\r\n";

        $project_id = $_SESSION['project_id'];
        //echo 'location : http://localhost/fundme/viewProject.php?project_id='.$project_id;

        if ($conn->query($project_update)=== TRUE){
            header('location:viewProject.php?project_id='.$project_id);
            echo"Hello";
        }

    }else{
        ?>
        <script>
            alert("Error in updating. Please check the details and try again.");
            window.location.href = 'viewProject.php?project_id=<?php echo $_SESSION['project_id'];?>';
        </script>
        <?php
    }

}
else{
	echo "Session not SET";
}

?>