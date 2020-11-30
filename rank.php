<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        session_start();
        include_once("includes/db_connection.php");
        if(!(isset($_SESSION["username"]))) {
            header("location: login.php");
        }
        include("includes/header.php");
        if(isset($_GET['contestId'])) {
            $contest_id = $_GET['contestId'];
            $sql = "SELECT * FROM contest WHERE contest_id=$contest_id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $contest_name = $row['contest_name'];
            $limit = 20;  
            if (isset($_GET["page"])) {
                $page  = $_GET["page"]; 
            } 
            else{ 
                $page=1;
            }
            $start_from = ($page-1) * $limit; 
            $sql = "SELECT * FROM rank WHERE contest_id=$contest_id ORDER BY rank ASC LIMIT $start_from, $limit";
            $result = mysqli_query($conn, $sql);
            $ranks = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            header('location: contest.php');
        }
    ?>
    <title>Ranks</title>
</head>
<body>
    <?php include("includes/navbar.php"); ?>
    <br><br>
    <div class="container-fluid">
        <table class="table table-bordered mt-5">
            <h3><?php echo $contest_name ?> ranks</h3>
            <thead class="thead-dark">
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ranks as $rank) { ?>
                    <?php
                        $username = $rank['username'];
                        $usr_rank = $rank['rank'];
                        $points = $rank['points'];
                    ?>
                    <tr>
                        <td><?php echo $usr_rank ?></td>
                        <td><?php echo $username ?></td>
                        <td><?php echo $points ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php  
            $result_db = mysqli_query($conn,"SELECT COUNT(contest_id) FROM RANK WHERE contest_id=$contest_id"); 
            $row_db = mysqli_fetch_row($result_db);  
            $total_records = $row_db[0];  
            $total_pages = ceil($total_records / $limit); 
            // echo  $total_pages;
            $pagLink = "<ul class='pagination'>";  
            for ($i=1; $i<=$total_pages; $i++) {
                $pagLink .= "<li class='page-item'><a class='page-link' href='rank.php?contestId=$contest_id&page=".$i."'>".$i."</a></li>";	
            }
            echo $pagLink . "</ul>";  
        ?>
    </div>
</body>
</html>
