<!DOCTYPE html>
<html>
<head>
	<title>Codedada - Logout</title>
	<?php
		include("includes/header.php");
		if(!isset($_SESSION["username"])) {
			header("location:index.php");
		}
		session_start();
		session_destroy();
		include("includes/db_connection.php");
		mysqli_close($conn);
	?>
</head>
<body>
	<div class="text-center">
		<h1 class="alert alert-success">Logout Successful</h1>
	</div>
</body>
</html>