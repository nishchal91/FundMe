<?php

session_start(); //starts the session
if ($_SESSION['email']) { //checks if name is logged in
} else {
    header("location:index.php"); // redirects if name is not logged in
}
$email = $_SESSION['email']; //assigns name value

require 'dbconn.php';

echo "Hello";

if(isset($_POST['rating']) && isset($_POST['project_id']) && isset($_POST['email_id'])){
	echo "Success";

	$project_id = $_POST['project_id'];
	$rating = $_POST['rating'];
	$email_id = $_POST['email_id'];
	
	$add_rating_query = "INSERT into ratings values(".$rating.", NOW(),'".$project_id."','".$email_id."'";
    echo $add_rating_query;
	if ($conn->query($add_rating_query) === TRUE ){
		echo " Records Inserted";
	}
}

?>