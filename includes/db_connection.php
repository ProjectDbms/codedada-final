<?php
	date_default_timezone_set('Asia/Kolkata');
	$conn = mysqli_connect('localhost', 'root', '', 'codedada_db');
	if(!$conn) {
		die("Connection to database failed: " . mysqli_connect_error());
	}
?>