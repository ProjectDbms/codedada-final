<?php
	include("includes/db_connection.php");
	if($_POST["field"] == "email") {
		$email = $_POST["value"];
		$sql = "SELECT * FROM accounts WHERE email_id='$email'";
		$result = mysqli_query($conn, $sql);
		if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo "Email already exists";
        } else {
        	echo "";
        }
        mysqli_free_result($result);
	} else if($_POST["field"] == "username") {
		$username = $_POST["value"];
		$sql = "SELECT * FROM accounts WHERE username='$username'";
		$result = mysqli_query($conn, $sql);
		if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo "Username already exists";
        } else {
        	echo "";
        }
        mysqli_free_result($result);
	} else if($_POST["field"] == "submit") {
		$username = $_POST["username"];
		$email = $_POST["email"];
		$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
		$sql = "INSERT INTO accounts(email_id, username, password) VALUES('$email', '$username', '$password')";
		if(mysqli_query($conn, $sql)) {
			echo true;
		} else {
			echo false;
		}
	}
    mysqli_close($conn);
?>