<?php
	include("includes/db_connection.php");
	if(isset($_POST['contestId'])) {
	$contest_id = $_GET['contestId'];}
	// $contest_id = $_GET['contestId'];
	$contest_id = $_GET['contestId'];
	$par_sql = "SELECT * FROM participant WHERE contest_id=$contest_id";
	$par_result = mysqli_query($conn, $par_sql);
	$par_row = mysqli_fetch_all($par_result, MYSQLI_ASSOC);
	$ques_sql = "SELECT * FROM question WHERE contest_id=$contest_id";
	$ques_result = mysqli_query($conn, $ques_sql);
	$ques_row = mysqli_fetch_all($ques_result, MYSQLI_ASSOC);
	$all_ranks = array();
	foreach ($par_row as $participant) {
		$participant_id = $participant['participant_id'];
		$timeSize = 0;
		$sumTime = 0;
		$sumPoints = 0;
		$participant_sub_row = array();
		foreach($ques_row as $question) {
			$question_id = $question['question_id'];
			$subm_sql = "SELECT submission.participant_id, submission.time_submitted, submission.question_id, submission.submission_id ,SUM(points) AS points FROM submission_verdict sv, submission WHERE sv.submission_id=submission.submission_id AND participant_id=$participant_id AND question_id=$question_id GROUP BY sv.submission_id ORDER BY points DESC, submission.time_submitted";
			$subm_result = mysqli_query($conn, $subm_sql);
			$max_points = 0;
			$time_submitted = 0;
			if(mysqli_num_rows($subm_result) > 0) {
				$subm_row = mysqli_fetch_all($subm_result, MYSQLI_ASSOC);
				// print_r($subm_row);	
				foreach ($subm_row as $sub_marks) {
					// print_r($sub_marks);
					if($sub_marks['points'] > $max_points || ($max_points==0 && $sub_marks['points']==0)) {
						$max_points = $sub_marks['points'];
						$time_submitted = $sub_marks['time_submitted'];
					}
				}
				$timeSize += 1;
			}
			$sumPoints += $max_points;
			$sumTime += strtotime($time_submitted);
			// echo $time_submitted."<br>";
		}

		$avgDate = date("Y-m-d\TH:i:s", ceil($sumTime/$timeSize));
		$account_id = $participant['account_id'];
		$acc_sql = "SELECT * FROM accounts WHERE account_id=$account_id";
		$acc_result = mysqli_query($conn, $acc_sql);
		$acc_row = mysqli_fetch_assoc($acc_result);
		$username = $acc_row['username'];
		// print_r($acc_result);

		$participant_sub_row['participant_id'] = $participant['participant_id'];
		$participant_sub_row['username'] = $username;
		$participant_sub_row['contest_id'] = $participant['contest_id'];
		$participant_sub_row['average_time'] = $avgDate;
		$participant_sub_row['points'] = $sumPoints;

		$all_ranks[] = $participant_sub_row;
	}

	foreach($all_ranks as $ranks) {
		$participant_id = $ranks['participant_id'];
		$username = $ranks['username'];
		$points = $ranks['points'];
		$average_time = $ranks['average_time'];
		$contest_id = $ranks['contest_id'];
		$sql = "INSERT INTO temp_rank(participant_id, username, average_time, points, contest_id) VALUES($participant_id, '$username', '$average_time', $points, $contest_id)";
		if(!mysqli_query($conn, $sql)) {
			echo "Error ".mysqli_error($conn)."<br>";
		}
	}

	$sql = "SELECT * FROM temp_rank WHERE contest_id=$contest_id ORDER BY points DESC, average_time ASC";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_all($result, MYSQLI_ASSOC);
	$i = 1;
	foreach ($row as $rank) {
		$contest_id = $rank['contest_id'];
		$points = $rank['points'];
		$participant_id = $rank['participant_id'];
		$username = $rank['username'];
		$sql = "INSERT INTO `rank` (`rank_id`, `contest_id`, `rank`, `points`, `participant_id`, `username`) VALUES (NULL, '$contest_id', '$i', '$points', '$participant_id', '$username')";
		if(!mysqli_query($conn, $sql)) {
			echo "Error ".mysqli_error($conn)."<br>";
		}
		$i += 1;
	}
	echo $contest_id;
	header('location: contest.php');
?>