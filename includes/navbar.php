<script type="text/javascript">
    function activate(elementId) {
        document.getElementById(elementId).classList.add('active');
    }
</script>
<link href="./assets/css/index_profile.css" rel="stylesheet" />
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
            <div class="container">
                <?php if(isset($_SESSION['username'])) { ?>
                    <a class="navbar-brand js-scroll-trigger" href="index_profile.php"><h2>code_Dada</h2></a>
                <?php } else { ?>
                <a class="navbar-brand js-scroll-trigger" href="#page-top"><h2>code_Dada</h2></a>
                <?php } ?>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="./contest.php">Contests</a></li>
                        <!-- <li class="nav-item"><a class="nav-link " href="logout.php">Logout</a></li> -->
                        <li class="nav-item" id="nav1">
                <a class="nav-link" href="problems.php">Problems</a>
            </li>
            
            <li class="nav-item" id="nav3">
                <a class="nav-link" href="users.php">Users</a>
            </li>
            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION["username"]; ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="index_profile.php"><i class="fa fa-cog" aria-hidden="true"></i> Profile</a>
                    <?php
                        $username = $_SESSION['username'];
                        $sql = "SELECT organizer, admin FROM accounts WHERE username='$username'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        if($row['organizer']) {
                            echo '<a class="dropdown-item" href="organizer/manage.php"><i class="fa fa-plus" aria-hidden="true"></i> Manage Contests</a>';
                            echo '<a class="dropdown-item" href="organizer/create_contest.php"><i class="fa fa-plus" aria-hidden="true"></i> Create Contests</a>';
                            // echo '<a class="dropdown-item" href="#"><i class="fa fa-trophy" aria-hidden="true"></i> My Contests</a>';
                        }
                        if($row['admin']) {
                            echo '<a class="dropdown-item" href="admin.php"><i class="fa fa-lock" aria-hidden="true"></i> Admin</a>';
                        }
                    ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                </div>
            </li>
                    </ul>
                </div>
            </div>
        </nav>




