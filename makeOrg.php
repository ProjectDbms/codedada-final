<?php
    session_start();
    include_once("includes/db_connection.php");
    $account_id = $_GET['id'];
    $sql = "UPDATE accounts SET organizer=NOT organizer WHERE account_id=$account_id";
    mysqli_query($conn, $sql);
    header('location: admin.php');
?>