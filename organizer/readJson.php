<?php
	include("../includes/db_connection.php");
	if($_POST['to'] == 'delete') {
		$contest_id = $_POST['id'];
		$sql = "DELETE FROM contest WHERE contest_id=$contest_id";
		if(mysqli_query($conn, $sql)) {
			echo true;
		} else {
			echo false;
		}
	}
?>