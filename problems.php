<!DOCTYPE html>
<html>
<head>
	<title>Codedada - Problems</title>
	<?php
		session_start();
		include("includes/db_connection.php");
		if(!(isset($_SESSION["username"]))) {
			header("location: login.php");
		}
		include("includes/header.php");
	?>
	<link rel="stylesheet" href="assets/css/index.css?q=<?php echo time(); ?>" type="text/css">
</head>
<body>
	<?php include("includes/navbar.php"); ?>
	<div class="container-fluid main">
		<div class="question-table mt-5">
			<?php
				$q_sql = "SELECT * FROM question";
				$result = mysqli_query($conn, $q_sql);
				if($result) {
					$questions = mysqli_fetch_all($result, MYSQLI_ASSOC);
				} else {
					echo "<script>
						window.alert('Something went wrong');
					</script>";
				}
			?>
			<h3 style="color: red;">Ends In</h3>
			<div class="content">
				<div id="timer"></div><br>
			</div>
			<br>
			<!-- <a href="contest_submission.php?contestId=<?php //echo $contest_id; ?>&participantId=<?php //echo $participant_id; ?>">MySubmissions</a> -->
			<br>

			<link rel="stylesheet" href="assets/css/timeTo.css?q=<?php echo time(); ?>" type="text/css" />
			<script type="text/javascript" src="assets/js/jquery-time-to.js"></script>

			<!-- <script type="text/javascript">
				function countdownCalc(startTime, el) {
					$(el).timeTo({
						timeTo: new Date(new Date(startTime)),
						theme: "black",
						displayCaptions: true,
						fontSize: 21,
						captionSize: 10,
						callback: function() {
							window.location.href = "calculate_rank.php?contestId=<?php echo $contest_id; ?>";
						}
					});
				}
			</script> -->
			<?php
				/*$c_sql = "SELECT * FROM contest WHERE contest_id=$contest_id";
				$c_result = mysqli_query($conn, $c_sql);
				$c_contest = mysqli_fetch_assoc($c_result);
				$entime = $c_contest['end_time'];
				$contest_end_time = date("M d Y H:i:s", strtotime($entime))." UTC+5:30";
				echo "<script>countdownCalc('$contest_end_time','#timer');</script>";*/
			?>
			<table class="table table-bordered">
				<thead class="thead-dark">
					<tr>
						<th style="width: 3%"><i class="fa fa-check" aria-hidden="true"></i></th>
						<th style="width:67%">Question</th>
						<th style="width:10%">Points</th>
						<th style="width:10%">Level</th>
						<th style="width:10%">Users</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($questions as $question) { ?>
						<tr>
							<td>
								<!-- <i title="Unattempted" class="fa fa-minus-circle" aria-hidden="true" style="color: #dc3545;"></i>
								<i class="fa fa-times-circle" aria-hidden="true" style="color: #dc3545;"></i>
								<i class="fa fa-check-circle" aria-hidden="true" style="color: #dc3545;"></i>
								<i class="fa fa-check-circle" aria-hidden="true" style="color: #28a745;"></i>
								<i class="fa fa-check-circle" aria-hidden="true" style="color: #ffc107;"></i> -->
								<?php
									$username = $_SESSION['username'];
									$us_sql = "SELECT * FROM accounts WHERE username='$username'";
									$us_result = mysqli_query($conn, $us_sql);
									$us_row = mysqli_fetch_assoc($us_result);
									// print_r($us_row);
									/*$account_id = $us_row['account_id'];
									$par_sql = "SELECT * FROM accounts WHERE username='$username'";
									$par_result = mysqli_query($conn, $par_sql);
									$par_row = mysqli_fetch_assoc($par_result);*/
									// print_r($par_row);
									$participant_id = $us_row['account_id'];
									// echo $participant_id;

									$question_id = $question['question_id'];
									$tc_sql = "SELECT points FROM testcase WHERE question_id=$question_id";
									$ts_result = mysqli_query($conn, $tc_sql);
									$testcases = mysqli_fetch_all($ts_result, MYSQLI_ASSOC);
									$points = 0;
									foreach ($testcases as $testcase) {
										$points += $testcase['points'];
									}
									$subm_sql = "SELECT all_submission.account_id, all_submission.question_id, all_submission.submission_id ,SUM(points) AS points FROM all_submission_verdict sv, all_submission 
WHERE sv.submission_id=all_submission.submission_id AND account_id=$participant_id AND question_id=$question_id GROUP BY sv.submission_id";
									$subm_result = mysqli_query($conn, $subm_sql);
									// if(!(mysqli_query($conn, $subm_sql))) {
									// 	echo mysqli_error($conn);
									// }
									if(mysqli_num_rows($subm_result) > 0) {
										$subm_row = mysqli_fetch_all($subm_result, MYSQLI_ASSOC);
										$max_points = 0;
										foreach ($subm_row as $sub_marks) {
											if($sub_marks['points'] >= $max_points) {
												$max_points = $sub_marks['points'];
											}
										}
										if($max_points == 0) {
											echo '<i title="Wrong Answer" class="fa fa-times-circle" aria-hidden="true" style="color: #dc3545;"></i>';
										} elseif($max_points == $points) {
											echo '<i title="Solved" class="fa fa-check-circle" aria-hidden="true" style="color: #28a745;"></i>';
										} else {
											echo '<i title="Partially Correct" class="fa fa-check-circle" aria-hidden="true" style="color: #ffc107;"></i>';
										}
									} else {
										echo '<i title="Unattempted" class="fa fa-minus-circle" aria-hidden="true" style="color: #dc3545;"></i>';
									}
								?>
							</td>
							<td>
								<a href="read_problem.php?questionId=<?php echo $question_id; ?>"><?php echo $question['question_name'] ?></a>
							</td>
							<td>	
								<?php 
									$question_id = $question['question_id'];
									$tc_sql = "SELECT points FROM testcase WHERE question_id=$question_id";
									$ts_result = mysqli_query($conn, $tc_sql);
									$testcases = mysqli_fetch_all($ts_result, MYSQLI_ASSOC);
									$points = 0;
									foreach ($testcases as $testcase) {
										$points += $testcase['points'];
									}
									echo $points;
								?>
							</td>
							<td>
								<?php echo $question['level']; ?>
							</td>
							<td>
								Users
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		activate("nav1");
	</script>
</body>
</html>