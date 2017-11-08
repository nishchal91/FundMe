<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Create Project</title>

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
    <script src = 'http://code.jquery.com/jquery-1.11.0.min.js';></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <![endif]-->
</head>
<script>

    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '-tab"]').tab('show');
    } //add a suffix

</script>
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
<div class="container">
<ul class="nav nav-tabs">
    <li class="active"><a href="#projectDetails" data-toggle="tab">Add Project Details</a></li>
</ul>
</div>



<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active in" id="projectDetails">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <form action="insertProjectDetails.php" method="post" enctype="multipart/form-data">
                        <br>
                        <label for="inputName" class="control-label">Project Title</label>
                        <input type="text" class="form-control" id="project_title" name="project_title" placeholder="Enter Project Title"
                               required>
                        <br>
                        <label for="inputName" class="control-label">Description</label>
                        <textarea class="form-control" rows="3" id="description" name="description" required></textarea>

                        <br>
                        <label for="inputName" class="control-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date" required>

                        <br>
                        <label for="inputName" class="control-label">Planned Completion Date</label>
                        <input type="date" class="form-control" id="planned_completion_date" name="planned_completion_date" placeholder="Planned Completion Date" required>

                        <br>
                        <label for="inputName" class="control-label">Campaign Start Date</label>
                        <input type="date" class="form-control" id="campaign_start_date" name="campaign_start_date" placeholder="Campaign Start Date" required>

                        <br>
                        <label for="inputName" class="control-label">Campaign End Date</label>
                        <input type="date" class="form-control" id="campaign_end_date" name="campaign_end_date" placeholder="Campaign End Date" required>

                        <br>
                        <label for="inputName" class="control-label">Minimum Funds Required</label><br>
                        <input type="number" step="0.1" class="form-control" id="min_fund_req" name="min_fund_req" placeholder="Minimum Funds Required" required>

                        <br>
                        <label for="inputName" class="control-label">Maximum Funds Required</label><br>
                        <input type="number" step="0.1" class="form-control" id="max_fund_req" name="max_fund_req" placeholder="Maximum Funds Required" required>

                        <br>
                        <label for="inputName" class="control-label">Project Picture</label><br>
                        <span class="btn btn-primary btn-sm">
					                    <input type="file" color="#FFFFFF" name="photo"/>
					                </span>
                        <br>
                        <br>
                        <label for="inputName" class="control-label">Project Video</label><br>
                        <span class="btn btn-primary btn-sm">
					                    <input type="file" color="#FFFFFF" name="video"/>
					                </span>
                        <br>
                        <br>
                            <input  type="submit" value="Submit" name="up" class="btn btn-primary btn-sm"/>
                        </form>

                        <br>

                    </form>
                    <br>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Include all compiled plugins (below), or include individual files as needed -->
<!--<script src="js/bootstrap.min.js"></script>-->
</body>
<script type="text/javascript">
    var project_title;
    var description;
    var start_date;
    var planned_completion_date;

    var campaign_start_date;
    var campaign_end_date;
    var min_fund_req;
    var max_fund_req;

    var category_name;

    function addProjectDetails() {

        project_title = $('#project_title').val();
        description = $('#description').val();
        start_date = document.getElementById("start_date").value;
        planned_completion_date = $('#planned_completion_date').val();

        var jsonData = {
            'project_title': project_title,
            'description': description,
            'start_date': start_date,
            'planned_completion_date':planned_completion_date
        };

        alert(JSON.stringify(jsonData));

        $.ajax({
            type: "POST",
            url: "insertProjectDetails.php",
            data: jsonData,
            dataType: 'json',
            cache: false,
            success: function (result) {
                alert("Awesome");
            }
        }).done(function (jsonData) {
            alert(JSON.stringify(jsonData));
        });

    }

    function addFundingDetails(){
        campaign_start_date = $('#campaign_start_date').val();
        campaign_end_date = $('#campaign_end_date').val();
        min_fund_req = $('#min_fund_req').val();
        max_fund_req = $('#max_fund_req').val();

        alert(campaign_start_date + campaign_end_date + min_fund_req + max_fund_req);
        var jsonData = {
            'campaign_start_date': campaign_start_date,
            'campaign_end_date': campaign_end_date,
            'min_fund_req': min_fund_req,
            'max_fund_req':max_fund_req
        };

        $.ajax({
            type: "POST",
            url: "insertProjectCampaignDetails.php",
            data: jsonData,
            dataType: 'json',
            cache: false,
            success: function (result) {
                alert("Awesome");
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                $('#post').html(msg);
            },
        });
    }

    function addCategory() {

        var category_name = $('#category_name').val();

        var jsonData2 = {
            'category_name': category_name
        };

        $.ajax({
            type: "POST",
            url: "insertCategory.php",
            data: jsonData2,
            dataType: 'json',
            cache: false,
            success: function (result) {
            }

        });
        $('#category_name').prop('selectedIndex', 0);
    }
</script>
</html>
