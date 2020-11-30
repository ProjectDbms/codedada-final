<!DOCTYPE html>
<html>
<head>
	<title>Codedada - Contest</title>
	<?php
		session_start(); 
		include_once("includes/db_connection.php");
		if(!(isset($_SESSION["username"]))) {
			header("location: login.php");
		}
		include("includes/header.php");
		if(!function_exists('check_reg')) {
			function check_reg($username, $contest_id) {
				global $conn;
				$sql = "SELECT account_id FROM accounts WHERE username='$username'";
				$result = mysqli_query($conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$account_id = $row['account_id'];
				$sql = "SELECT * FROM participant WHERE account_id=$account_id AND contest_id=$contest_id";
				$result = mysqli_query($conn, $sql);
				if(mysqli_num_rows($result) > 0) {
					return true;
				} else {
					return false;
				}
			}
		}
		$sql = "SELECT * FROM contest ORDER BY start_time DESC";
		$result = mysqli_query($conn, $sql);
		$contests = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$futureFlag = true;
		$presentFlag = true;
		$pastFlag = true;

		if(isset($_GET['registerContest'])) {
			$username = $_SESSION['username'];
			$sql = "SELECT account_id FROM accounts WHERE username='$username'";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($result);
			$account_id = $row['account_id'];
			$reg_contest_id = $_GET['registerContest'];
			$sql = "SELECT * FROM participant WHERE account_id=$account_id AND contest_id=$reg_contest_id";
			$result = mysqli_query($conn, $sql);
			if(mysqli_num_rows($result) > 0) {
				header("location: contest.php");
			} else {
				$sql = "INSERT INTO participant(account_id, contest_id) VALUES($account_id, $reg_contest_id)";
				if(mysqli_query($conn, $sql)) {
					echo "<script>window.alert('Registered');
						window.location.href = 'contest.php';
					</script>";
				} else {
					echo "<script>window.alert('Connection erroor');
						window.location.href = 'contest.php';
					</script>";
				}
			}
		}
	?>
	<link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
        <!-- Font Awesome icons -->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="./assets/css/index_profile.css" rel="stylesheet" />
	
	<link rel="stylesheet" href="assets/css/timeTo.css?q=<?php echo time(); ?>" type="text/css">
	<script type="text/javascript" src="assets/js/jquery-time-to.js"></script>
	<script type="text/javascript">
		function countdownCalc(startTime, contestId) {
			$("#timer"+contestId).timeTo({
				timeTo: new Date(new Date(startTime)),
				theme: "black",
				displayCaptions: true,
				fontSize: 21,
				captionSize: 10,
				callback: function() {
					window.location.href = "contest.php";
				}
			});
		}
	</script>
	<script type="text/javascript" src="assets/js/contestFunctions.js"></script>
</head>
<body>

	<?php include("includes/navbar.php"); ?>
	<div class="container-fluid main">
		<?php
			include('includes/contest/present.php');
			include('includes/contest/future.php');
			include('includes/contest/past.php');
		?>
	</div>
	<script type="text/javascript">
		activate("nav2");
	</script>
	<?php
		if($futureFlag) {
			echo '<script>
				$(".future-contest").css("display", "none");
			</script>';
		}
		if($presentFlag) {
			echo '<script>
				$(".present-contest").css("display", "none");
			</script>';
		}
		if($pastFlag) {
			echo '<script>
				$(".past-contest").css("display", "none");
			</script>';
		}
	?>
</body>
</html>