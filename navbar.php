<?php
require 'dbconn.php';
?>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="homepage.php">FundMe!!</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                <!-- Recipes Nav Bar -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Projects
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">

                        <li class="divider"></li>
                        <li><a href="getUserProjects.php">View My Projects</a></li>
                        <li class="divider"></li>
                        <li><a href="getAllProjects.php">View All Projects</a></li>
                        <li class="divider"></li>
                        <li><a href="recommendProjects.php">Recommended Projects</a></li>
                        <li class="divider"></li>
                        <li><a href="createProject.php">Create New Project</a></li>
                    </ul>
                </li>

                <!-- Follows Nav Bar -->


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Companions
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">

                        <li class="divider"></li>
                        <li><a href="getfollowers.php?email=<?php echo $_SESSION['email'] ?>">My Followers</a></li>
                        <li class="divider"></li>
                        <li><a href="getfollowing.php?email=<?php echo $_SESSION['email'] ?>">Users I Follow</a></li>
                        <li class="divider"></li>
                        <li><a href="allusers.php">All Users</a></li>
                    </ul>
                </li>

                <li>
                    <form class="navbar-form navbar-left" role="search" method="POST" action="search.php">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search" name="keyword">
                        </div>
                        <button type="submit" class="btn btn-default">Search</button>
                    </form>
                </li>
                <!--<ul class="nav navbar-nav navbar-right">-->


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">

                        <?php
                        if (isset($_SESSION['email'])) {
                            $email = $_SESSION['email'];
                            $sql_getname = "select first_name,last_name from user where email_id='" . $_SESSION['email'] . "'";
                            $result_getname = $conn->query($sql_getname);
                            $row_getname = $result_getname->fetch_assoc();
                            echo $row_getname['first_name'] . " " . $row_getname['last_name'] . "!";
                        } else {
                            #header("location:index.php");
                        }
                        ?>

                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">

                        <li class="divider"></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php">Logout</a> </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>