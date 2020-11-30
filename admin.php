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
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM accounts WHERE username='$username'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        // print_r($row);
        if($row['admin'] == false) {
            header('location: index_profile.php');
        }
    ?>
    <title>Admin</title>
</head>
<body>
    <div class="container-fluid">
        <?php include("includes/navbar.php"); ?>
    </div>
    <br><br><br><br><br>
    <div class="contaner-fluid">
        <?php 
            $sql = "SELECT * FROM accounts WHERE organizer=false OR organizer=true AND admin=false";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
            // print_r($row);
        ?>
        <div class="container">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th style="width:10%">#</th>
                        <th style="width:60%">Username</th>
                        <th style="width:30%">Organizer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach($row as $user) { ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $user['username'] ?></td>
                            <td>
                                <?php
                                    // echo $user['organizer']
                                    $id = $user['account_id'];
                                    if($user['organizer'] == false) {             
                                        echo "<a href='makeOrg.php?id=$id'>False</a>";
                                    } else {
                                        echo "<a href='makeOrg.php?id=$id'>True</a>";
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php $i+=1; } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
