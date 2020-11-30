<script type="text/javascript">
    function activate(elementId) {
        document.getElementById(elementId).classList.add('active');
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <a class="navbar-brand" href="index.php"><img src="assets/images/programming.png" alt="icon" height="30px" width="30px"> Codedada</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item" id="nav1">
                <a class="nav-link" href="join_contest.php?contestId=<?php echo $contest_id ?>&problems=<?php echo $contest_id ?>">Problems</a>
            </li>
            <li class="nav-item" id="nav2">
                <a class="nav-link" href="contest.php">Contests</a>
            </li>
            <li class="nav-item" id="nav3">
                <a class="nav-link" href="contest_users.php?contestId=<?php echo $contest_id ?>">Users</a>
            </li>
            <li class="nav-item" id="nav4">
                <a class="nav-link" href="contest_submission.php?contestId=<?php echo $contest_id ?>">Submissions</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto" style="margin-right: 7rem;">
            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION["username"]; ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#"><i class="fa fa-cog" aria-hidden="true"></i> Profile</a>
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
</nav>