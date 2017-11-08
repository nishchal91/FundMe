<script src = 'http://code.jquery.com/jquery-1.11.0.min.js';></script>
<?php
session_start(); //starts the session
if ($_SESSION['email']) { //checks if name is logged in
} else {
    header("location:index.php"); // redirects if name is not logged in
}
$email = $_SESSION['email'];
require 'dbconn.php';
require 'navbar.php';

if (isset($_POST['project_title']) and
    isset($_POST['description']) and
    isset($_POST['start_date']) and
    isset($_POST['planned_completion_date']) and
    isset($_POST['campaign_start_date']) and
    isset($_POST['campaign_end_date']) and
    isset($_POST['min_fund_req']) and
    isset($_POST['max_fund_req'])
) {
    $project_title = $_POST['project_title'];
	$description = $_POST['description'];
	$start_date = $_POST['start_date'];
	$planned_completion_date = $_POST['planned_completion_date'];

    if(trim($_FILES['photo']['tmp_name'])!=''){
        if (getimagesize($_FILES['photo']['tmp_name']) == TRUE) {
            $photo = addslashes($_FILES['photo']['tmp_name']);
            $photo = file_get_contents($photo);
            $photo = base64_encode($photo);
            echo $photo;
        }
    }else{
        $photo = '';
    }

    $video_name= $_FILES['video']['name'];
    $video_tmp_name= $_FILES['video']['tmp_name'];

    $position= strpos($video_name, ".");

    $file_extension= substr($video_name, $position + 1);

    $file_extension= strtolower($file_extension);

    $success= -1;

    if (isset($video_name)) {
        $path= 'videos/';

        if (!empty($video_name)){
            if (($file_extension !== "mp4") and ($file_extension !== "ogg") and ($file_extension !== "webm"))
            {
                $success=0;
                ?>
                <script>
                    alert("The video should be mp4, ogg, or webm format");
                </script>
                <?php
            }else if (($file_extension == "mp4") or ($file_extension == "ogg") or ($file_extension == "webm")) {
                if (move_uploaded_file($video_tmp_name, $path.$video_name)) {
                    $success=1;
                }
            }
        }
    }


    $campaign_start_date = $_POST['campaign_start_date'];
	$campaign_end_date = $_POST['campaign_end_date'];
	$min_fund_req = $_POST['min_fund_req'];
	$max_fund_req = $_POST['max_fund_req'];

    if(trim($photo)!=''){
        if(trim($video_name)!=''){
            $sql_insert_project = "insert into PROJECT(PROJECT_TITLE, DESCRIPTION, DATE_TIME_CREATED, START_DATE, PLANNED_COMPLETION_DATE, PHOTO, VIDEO) 
                    values('$project_title','$description',NOW(),'$start_date','$planned_completion_date','$photo','$video_name')";
        }else{
            $sql_insert_project = "insert into PROJECT(PROJECT_TITLE, DESCRIPTION, DATE_TIME_CREATED, START_DATE, PLANNED_COMPLETION_DATE, PHOTO, VIDEO) 
                    values('$project_title','$description',NOW(),'$start_date','$planned_completion_date','$photo',NULL)";
        }
    }else{
        if(trim($video_name)!=''){
            $sql_insert_project = "insert into PROJECT(PROJECT_TITLE, DESCRIPTION, DATE_TIME_CREATED, START_DATE, PLANNED_COMPLETION_DATE, PHOTO, VIDEO) 
                    values('$project_title','$description',NOW(),'$start_date','$planned_completion_date',NULL,'$video_name')";
        }else{
            $sql_insert_project = "insert into PROJECT(PROJECT_TITLE, DESCRIPTION, DATE_TIME_CREATED, START_DATE, PLANNED_COMPLETION_DATE, PHOTO, VIDEO) 
                    values('$project_title','$description',NOW(),'$start_date','$planned_completion_date',NULL,NULL)";
        }
    }

	echo $sql_insert_project;

    if( ($min_fund_req < $max_fund_req) and ($campaign_start_date < $campaign_end_date) and ($campaign_end_date <= $start_date) and  ($start_date < $planned_completion_date)){
        if ($conn->query($sql_insert_project)=== TRUE){
            $sql_rid = "select max(PROJECT_ID) as Project_id from PROJECT;";
            $res = $conn->query($sql_rid);
            $res = $res->fetch_assoc();
            $new_project_id = $res['Project_id'];
            $_SESSION['project_id'] = $new_project_id;

            $sql_insert_project_campaign = "insert into PROJECT_CAMPAIGN(PROJECT_ID, FUND_START_DATE, FUND_END_DATE, MIN_FUND_GOAL, MAX_FUND_GOAL) 
                                        VALUES ('$new_project_id','$campaign_start_date','$campaign_end_date','$min_fund_req','$max_fund_req')";

            echo $sql_insert_project_campaign;

            if ($conn->query($sql_insert_project_campaign)=== TRUE){

                $sql_insert_project_owner = "insert into project_ownership VALUES ('$new_project_id','$email')";
                if($conn -> query($sql_insert_project_owner)){
                    ?>
                    <script>
                        alert("Project Created!");
                        window.location.href = 'viewProject.php?project_id=<?php echo $new_project_id ?>';
                    </script>
                    <?php
                }else{
                    $sql_delete_project_campaign = "delete from project_campaign where project_id = $new_project_id";
                    $conn->query($sql_delete_project_campaign);
                    $sql_delete_project = "delete from project where project_id = $new_project_id";
                    $conn->query($sql_delete_project);
                    ?>
                    <script>
                        alert("There was some problem!");
                        window.location.href = 'createProject.php';
                    </script>
                    <?php
                }
            }else{
                $sql_delete_project = "delete from project where project_id = $new_project_id";
                $conn->query($sql_delete_project);
                ?>
                <script>
                    alert("There was some problem!");
                    window.location.href = 'createProject.php';
                </script>
                <?php
            }
        }else{
            ?>
            <script>
                alert("There was some problem!");
                window.location.href = 'createProject.php';
            </script>
            <?php
        }
    }else{
        ?>
        <script>
            alert("There was some problem!");
            window.location.href = 'createProject.php';
        </script>
        <?php
    }
}
?>