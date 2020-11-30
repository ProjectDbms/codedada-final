<!DOCTYPE html>
<html>
<head>
	<title>Codedada - Contest</title>
	<?php
		session_start();
		include("../includes/db_connection.php");
		if(!(isset($_SESSION["username"]))) {
			header("location: ../login.php");
		}
		if(isset($_POST["create"])) {
			$contest_name = $_POST['contest_name'];
			$description = $_POST['description'];
			$start_time = $_POST['start_time'];
			$end_time = $_POST['end_time'];
			$difficulty = $_POST['difficulty'];
			$username = $_SESSION['username'];
			$sql = "SELECT account_id FROM accounts WHERE username='$username'";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($result);
			$account_id = $row['account_id'];
			$sql = "INSERT INTO contest(account_id, contest_name, description, start_time, end_time, difficulty) VALUES($account_id, '$contest_name', '$description', '$start_time', '$end_time', '$difficulty')";
			
			if(mysqli_query($conn, $sql)) {
				
			} else {
				echo "<script>window.alert('Cannot create');</script>";
			}
		}
		include("../includes/header.php");
	?>
	<link rel="icon" href="../assets/images/programming.png" type="image/png">
	<link rel="stylesheet" href="../assets/css/create.css?q=<?php echo time(); ?>" type="text/css">
</head>
<body>
	<?php include("navbar.php"); ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col col-md-3">
			</div>
			<div class="col col-md-6">
				<div class="contest-form">
					<h2>Create your contest</h2>
					<form action="create_contest.php" method="post">
						<div class="form-group">
							<label for="contest_name">Contest Name</label>
							<input type="text" class="form-control" id="contest_name" name="contest_name" min="1" max="250" required>
						</div>
						<div class="form-group">
							<label for="description">Description</label>
							<textarea class="form-control" id="description" name="description" rows="5" cols="30"></textarea>
						</div>
						<div class="form-group">
							<label for="start_time">Start Time</label>
							<input type="datetime-local" class="form-control" id="start_time" name="start_time" value='<?php echo date("Y-m-d\TH:i",time()); ?>' required>
						</div>
						<div class="form-group">
							<label for="end_time">End Time</label>
							<input type="datetime-local" class="form-control" id="end_time" name="end_time" value='<?php echo date("Y-m-d\TH:i",time()+3600); ?>' required>
						</div>
						<div class="form-group">
							<label for="difficulty">Difficulty</label>
							<select name="difficulty" id="difficulty" class="form-control">
								<option value="Easy">Easy</option>
								<option value="Medium">Medium</option>
								<option value="Difficult">Difficult</option>
							</select>
						</div>
						<input type="submit" name="create" value="Create" class="btn btn-primary">
					</form>
				</div>
			</div>
			<div class="col col-md-3">
			</div>
		</div>

		<div class="row">
			<div class="col">

			</div>
		</div>
	</div>
	<script type="text/javascript">
		// activate("nav2");
	</script>
</body>
</html>