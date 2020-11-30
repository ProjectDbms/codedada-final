<!DOCTYPE html>
<html>
<head>
	<title>Codedada - Submission</title>
	<link rel="icon" href="assets/images/programming.png" type="image/png">
	<?php
		session_start();
		include_once("includes/db_connection.php");
		if(!(isset($_SESSION["username"]))) {
			header("location: login.php");
		}
        // include("includes/header.php");
        if(isset($_GET['contestId'])) {
        	$contest_id = $_GET['contestId'];
        } elseif(isset($_POST['code'])) {

        } else {
        	header('location: contest.php');
        }
        if(isset($_POST['code'])) {
        	include_once("api.php");
        	$contest_id = $_POST['contest_id'];
        	$question_id = $_POST['question_id'];
        	$code = $_POST['code'];
        	$lang = $_POST['language'];
        	$q_sql = "SELECT * FROM question WHERE contest_id=$contest_id AND question_id=$question_id";
	        $q_result = mysqli_query($conn, $q_sql);
	        if($q_result) {
	        	$question = mysqli_fetch_assoc($q_result);

	        	if($lang == 'C' || $lang == 'CPP14') {
					$myfile = fopen("code.txt", "r") or die("Unable to open file!");
					$config['source'] = fread($myfile,filesize("code.txt"));
					$code = $config['source'];
					fclose($myfile);
				}

	        	// Getting the username
	        	$username = $_SESSION['username'];
	        	$acc_sql = "SELECT * FROM accounts WHERE username='$username'";
	        	$acc_result = mysqli_query($conn, $acc_sql);
	        	$account = mysqli_fetch_assoc($acc_result);
	        	$account_id = $account['account_id'];

	        	// Getting the account
	        	$part_sql = "SELECT * FROM participant WHERE account_id=$account_id AND contest_id=$contest_id";
	        	$part_result = mysqli_query($conn, $part_sql);
	        	$participant = mysqli_fetch_assoc($part_result);
	        	$participant_id = $participant['participant_id'];

	        	// Inserting into the submit table
	        	$sub_sql = "INSERT INTO submission(question_id, participant_id, contest_id, code_desc, language) VALUES($question_id, $participant_id, $contest_id, '$code', '$lang')";
	        	if(mysqli_query($conn, $sub_sql)) {
	        		$last_id = mysqli_insert_id($conn);
	        	} else {
	        		echo "<script>window.alert('Error in saving the code');</script>";
	        	}

	        	if(!function_exists('insertVerdict')) {
					function insertVerdict($verdict, $testcase_output, $user_output, $points) {
						global $conn;
						global $last_id;
						$ver_sql = "INSERT INTO submission_verdict(submission_id, verdict, testcase_output, user_output, points) VALUES($last_id, '$verdict', '$testcase_output', '$user_output', $points)";
						if(!(mysqli_query($conn, $ver_sql))) {
							echo "<script>window.alert('Error in saving the verdict');</script>";
							// echo "Error ".mysqli_error($conn)."<br>";
							// echo $ver_sql."<br>";
						}
					}
				}

	        	$tc_sql = "SELECT * FROM testcase WHERE question_id=$question_id";
				$ts_result = mysqli_query($conn, $tc_sql);
				$testcases = mysqli_fetch_all($ts_result, MYSQLI_ASSOC);
				$total_points = 0;
				$outputs = array();
				$verdicts = array();
				$points = array();
				$config['source'] = $code;
				$config['language'] = $lang;

				foreach ($testcases as $testcase) {
					$testcase_input = $testcase['testcase_input'];
					$testcase_output = $testcase['testcase_output'];
					$testcase_output = trim($testcase_output);
					$testcase_points = $testcase['points'];
					$config['input'] = $testcase_input;
					$responseOfRun = run($hackerearth,$config);
					// echo "<pre>";
					// print_r($responseOfRun);
					// echo "</pre>";
					if($lang == 'PYTHON' || $lang=='PYTHON3') {
						if($responseOfRun['compile_status'] != 'OK') {
							$verdicts[] = 'CE';
							$outputs[] = $responseOfRun['run_status']['stderr'];
							$points[] = 0;
							insertVerdict('CE', $testcase_output, $responseOfRun['run_status']['stderr'], 0);
						} else {
							if($responseOfRun['run_status']['stderr'] == '') {
								$tempOut = $responseOfRun["run_status"]["output"];
								$tempOut = trim($tempOut);
								if($tempOut == $testcase_output) {
									$verdicts[] = 'AC';
									$outputs[] = $tempOut;
									$points[] = $testcase_points;
									$total_points += $testcase_points;
									insertVerdict('AC', $testcase_output, $tempOut, $testcase_points);
								} else {
									$verdicts[] = 'WA';
									$outputs[] = $tempOut;
									$points[] = 0;
									insertVerdict('WA', $testcase_output, $tempOut, 0);
								}
							} else {
								$tempOut = " ".trim($responseOfRun['run_status']['stderr'])." ";
								$verdicts[] = 'CE';
								$outputs[] = $responseOfRun['run_status']['stderr'];
								$points[] = 0;
								// echo $testcase_output." ".$responseOfRun['run_status']['stderr'];
								insertVerdict('CE', $testcase_output, 'Error', 0);
							}
						}
					} elseif($responseOfRun['compile_status'] != 'OK') {
						$verdicts[] = 'CE';
						$outputs[] = $responseOfRun['compile_status'];
						$points[] = 0;
						insertVerdict('CE', $testcase_output, $responseOfRun['compile_status'], 0);
					} else {
						$tempOut = $responseOfRun["run_status"]["output"];
						$tempOut = trim($tempOut);
						if($tempOut == $testcase_output) {
							$verdicts[] = 'AC';
							$outputs[] = $tempOut;
							$points[] = $testcase_points;
							$total_points += $testcase_points;
							insertVerdict('AC', $testcase_output, $tempOut, $testcase_points);
						} else {
							$verdicts[] = 'WA';
							$outputs[] = $tempOut;
							$points[] = 0;
							insertVerdict('WA', $testcase_output, $tempOut, 0);
						}
					}
				}
	        } else {
	        	echo "<script>console.log('Error');</script>";
	        }
        }
        ?>
</head>
<body>
	<?php 
	include("includes/header.php");
	include("includes/navbar_contest.php"); 
	?>
	<?php if(isset($_POST['code'])) { ?>
		<div class="container">
			<ul class="list-group">
				<h3><?php echo $question["question_name"]; ?></h3>
				<?php $i=0; foreach($outputs as $output) { ?>
					<?php if($verdicts[$i] == 'AC') { ?>
						<li class="list-group-item list-group-item-success"><?php echo "Test-".($i+1) ?> : AC Points:<?php echo $points[$i]; ?></li>
					<?php } else if($verdicts[$i] == 'WA') { ?>
						<li class="list-group-item list-group-item-danger"><?php echo "Test-".($i+1) ?> : WA Points:<?php echo $points[$i]; ?></li>
					<?php } else { ?>
						<li class="list-group-item list-group-item-danger"><?php echo "Test-".($i+1) ?> : <b>CE</b><br><?php echo $outputs[$i]; ?></li>
					<?php } ?>
				<?php $i+=1; } ?>
			</ul>
		</div>
	<?php } else if(!isset($_GET['contestId'])) { ?>
		<div class="container-fluid">
			<h3>All submissions</h3>
			<div class="row">
				<div class="col-md-9">
					<ul class="list-group">
						<?php
							// For pagination
							$limit = 20;  
				            if (isset($_GET["page"])) {
				                $page  = $_GET["page"]; 
				            } 
				            else{ 
				                $page=1;
				            }
				            $start_from = ($page-1) * $limit; 
				            //$sql = "SELECT * FROM rank WHERE contest_id=$contest_id ORDER BY rank ASC LIMIT $start_from, $limit";
				            // $result = mysqli_query($conn, $sql);
				            // $ranks = mysqli_fetch_all($result, MYSQLI_ASSOC);
							$all_sub_sql = "SELECT * FROM submission ORDER BY time_submitted DESC LIMIT $start_from, $limit";
							$all_sub_result = mysqli_query($conn, $all_sub_sql);
							$all_sub = mysqli_fetch_all($all_sub_result, MYSQLI_ASSOC);
							$all_submissions_array = array();
							foreach ($all_sub as $sub) {
								$entities = array();
								$question_id = $sub['question_id'];
								$language = $sub['language'];
								$submission_id = $sub['submission_id'];
								$participant_id = $sub['participant_id'];
								$time_submitted = $sub['time_submitted'];

								$qu_sql = "SELECT * FROM question WHERE question_id=$question_id";
								$qu_result = mysqli_query($conn, $qu_sql);
								$question = mysqli_fetch_assoc($qu_result);
								$question_name = $question['question_name'];

								$verdict_sql = "SELECT * FROM submission_verdict WHERE submission_id=$submission_id";
								$verdict_result = mysqli_query($conn, $verdict_sql);
								$verdicts = mysqli_fetch_all($verdict_result, MYSQLI_ASSOC);
								$ac = true;
								$wa = false;
								$ce = false;
								$total_points = 0;
								$verdicts_array = array();
								$pie_ac = 0;
								$pie_ce = 0;
								$pie_wa = 0;
								foreach ($verdicts as $verdict) {
									$temp = array();

									if($verdict['verdict'] != 'AC') {
										$ac = false;
									}
									if ($verdict['verdict'] == 'WA') {
										$wa = true;
										$temp['verdict'] = 'WA';
										$temp['points'] = 0;
									} elseif($verdict['verdict'] == 'CE') {
										$ce = true;
										$temp['verdict'] = 'CE';
										$temp['points'] = 0;
									} else {
										$total_points += $verdict['points'];
										$temp['verdict'] = 'AC';
										$temp['points'] = $verdict['points'];
									}
									$verdicts_array[] = $temp;
								}
								$entities['verdicts'] = $verdicts_array;
								$entities['ac'] = $ac;
								$entities['question_name'] = $question_name;
								$entities['total_points'] = $total_points;
								$all_submissions_array[] = $entities;
							}
						?>

						<?php foreach($all_submissions_array as $all_sub_ar) { ?>
							<?php if($all_sub_ar['ac']) { ?>
								<?php $pie_ac+=1; ?>
								<li class="list-group-item list-group-item-success">
									<a href=""><?php echo $all_sub_ar['question_name'] ?></a>
									<span>| AC | Points: <?php echo $all_sub_ar['total_points']; ?></span>
								</li>
								<br>
							<?php } else { ?>
								<li class="list-group-item list-group-item-danger">
									<a href=""><?php echo $all_sub_ar['question_name'] ?></a>
									<p>
										<?php foreach($all_sub_ar['verdicts'] as $verdict_ar) ?>
											<?php echo "|".$verdict_ar['verdict']."| "; ?>

											<?php 
											echo "|".$verdict_ar['verdict']."| ";
											if($verdict_ar['verdict'] == 'WA') $pie_wa+=1;
											else $pie_ce+=1; 
											?>

										<?php ?>
										<span>Points: <?php echo $all_sub_ar['total_points']; ?></span>
									</p>	
								</li>
								<br>
							<?php } ?>
						<?php } ?>
					</ul>
				</div>
				<div class="col-md-3">
					<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
					<canvas id="myChart" width="400" height="400"></canvas>
					<script>
					var ctx = document.getElementById('myChart').getContext('2d');
					var myChart = new Chart(ctx, {
					    type: 'pie',
					    data: {
					        labels: ['WA', 'CE', 'AC'],
					        datasets: [{
					            label: '# of Votes',
					            data: ["<?php echo $pie_wa ?>", "<?php echo $pie_ce ?>", "<?php echo $pie_ac ?>"],
					            backgroundColor: [
					                '#dc3545',
					                '#ffc107',
					                '#28a745'
					            ],
					            borderColor: [
					                '#dc3545',
					                '#ffc107',
					                '#28a745'
					            ],
					            borderWidth: 1
					        }]
					    },
					    // options: {
					    //     scales: {
					    //         yAxes: [{
					    //             ticks: {
					    //                 beginAtZero: true
					    //             }
					    //         }]
					    //     }
					    // }
					    options: {
					    	responsive: true
					    }
					});
					</script>
				</div>
			</div>
			<?php  
	            $result_db = mysqli_query($conn,"SELECT COUNT(submission_id) FROM submission"); 
	            $row_db = mysqli_fetch_row($result_db);  
	            $total_records = $row_db[0];  
	            $total_pages = ceil($total_records / $limit); 
	            // echo  $total_pages;
	            $pagLink = "<ul class='pagination'>"; 
	            if($total_pages != 1) { 
		            for ($i=1; $i<=$total_pages; $i++) {
		                $pagLink .= "<li class='page-item'><a class='page-link' href='contest_submission.php?page=".$i."'>".$i."</a></li>";	
		            }
		            echo $pagLink . "</ul>";
		        }
	        ?>
		</div>
	<?php } elseif(isset($_GET['contestId']) && isset($_GET['participantId'])) { ?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-9">
					<ul class="list-group">
						<h3>My Submissions - (<?php echo $_SESSION['username'] ?>)</h3>
						<?php
							$participant_id = $_GET['participantId'];
							// For pagination
							$limit = 20;  
				            if (isset($_GET["page"])) {
				                $page  = $_GET["page"]; 
				            } 
				            else{ 
				                $page=1;
				            }
				            $start_from = ($page-1) * $limit; 
				            $contest_id = $_GET['contestId'];
							$all_sub_sql = "SELECT * FROM submission WHERE contest_id=$contest_id AND participant_id=$participant_id ORDER BY time_submitted DESC LIMIT $start_from, $limit";
							$all_sub_result = mysqli_query($conn, $all_sub_sql);
							$all_sub = mysqli_fetch_all($all_sub_result, MYSQLI_ASSOC);
							$all_submissions_array = array();
							foreach ($all_sub as $sub) {
								$entities = array();
								$question_id = $sub['question_id'];
								$language = $sub['language'];
								$submission_id = $sub['submission_id'];
								$participant_id = $sub['participant_id'];
								$time_submitted = $sub['time_submitted'];

								$qu_sql = "SELECT * FROM question WHERE question_id=$question_id";
								$qu_result = mysqli_query($conn, $qu_sql);
								$question = mysqli_fetch_assoc($qu_result);
								$question_name = $question['question_name'];

								$verdict_sql = "SELECT * FROM submission_verdict WHERE submission_id=$submission_id";
								$verdict_result = mysqli_query($conn, $verdict_sql);
								$verdicts = mysqli_fetch_all($verdict_result, MYSQLI_ASSOC);
								$ac = true;
								$wa = false;
								$ce = false;
								$total_points = 0;
								$pie_ac = 0;
								$pie_ce = 0;
								$pie_wa = 0;
								$verdicts_array = array();
								foreach ($verdicts as $verdict) {
									$temp = array();

									if($verdict['verdict'] != 'AC') {
										$ac = false;
									}
									if ($verdict['verdict'] == 'WA') {
										$wa = true;
										$temp['verdict'] = 'WA';
										$temp['points'] = 0;
										// $pie_wa += 1;
									} elseif($verdict['verdict'] == 'CE') {
										$ce = true;
										$temp['verdict'] = 'CE';
										$temp['points'] = 0;
										// $pie_ce += 1;
									} else {
										$total_points += $verdict['points'];
										$temp['verdict'] = 'AC';
										$temp['points'] = $verdict['points'];
										// $pie_ac += 1;
									}
									$verdicts_array[] = $temp;
								}
								$entities['verdicts'] = $verdicts_array;
								$entities['ac'] = $ac;
								$entities['question_name'] = $question_name;
								$entities['total_points'] = $total_points;
								$all_submissions_array[] = $entities;
							}
						?>

						<?php foreach($all_submissions_array as $all_sub_ar) { ?>
							<?php if($all_sub_ar['ac']) { ?>
								<?php $pie_ac+=1; ?>
								<li class="list-group-item list-group-item-success">
									<a href=""><?php echo $all_sub_ar['question_name'] ?></a>
									<span>| AC | Points: <?php echo $all_sub_ar['total_points']; ?></span>
								</li>
								<br>
							<?php } else { ?>
								<li class="list-group-item list-group-item-danger">
									<a href=""><?php echo $all_sub_ar['question_name'] ?></a>
									<p>
										<?php foreach($all_sub_ar['verdicts'] as $verdict_ar) ?>
											<?php 
											echo "|".$verdict_ar['verdict']."| ";
											if($verdict_ar['verdict'] == 'WA') $pie_wa+=1;
											else $pie_ce+=1; 
											?>
										<?php ?>
										<span>Points: <?php echo $all_sub_ar['total_points']; ?></span>
									</p>	
								</li>
								<br>
							<?php } ?>
						<?php } ?>
					</ul>
				</div>
				<div class="col-md-3">
					<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
					<canvas id="myChart" width="400" height="400"></canvas>
					<script>
					var ctx = document.getElementById('myChart').getContext('2d');
					var myChart = new Chart(ctx, {
					    type: 'pie',
					    data: {
					        labels: ['WA', 'CE', 'AC'],
					        datasets: [{
					            label: '# of Votes',
					            data: ["<?php echo $pie_wa ?>", "<?php echo $pie_ce ?>", "<?php echo $pie_ac ?>"],
					            backgroundColor: [
					                '#dc3545',
					                '#ffc107',
					                '#28a745'
					            ],
					            borderColor: [
					                '#dc3545',
					                '#ffc107',
					                '#28a745'
					            ],
					            borderWidth: 1
					        }]
					    },
					    // options: {
					    //     scales: {
					    //         yAxes: [{
					    //             ticks: {
					    //                 beginAtZero: true
					    //             }
					    //         }]
					    //     }
					    // }
					    options: {
					    	responsive: true
					    }
					});
					</script>
				</div>
			</div>
			<?php  
	            $result_db = mysqli_query($conn,"SELECT COUNT(submission_id) FROM submission"); 
	            $row_db = mysqli_fetch_row($result_db);  
	            $total_records = $row_db[0];  
	            $total_pages = ceil($total_records / $limit); 
	            // echo  $total_pages;
	            $pagLink = "<ul class='pagination'>"; 
	            if($total_pages != 1) { 
		            for ($i=1; $i<=$total_pages; $i++) {
		                $pagLink .= "<li class='page-item'><a class='page-link' href='contest_submission.php?contestId=$contest_id&page=".$i."'>".$i."</a></li>";	
		            }
		            echo $pagLink . "</ul>";
		        }
	        ?>
		</div>
	<?php } elseif(isset($_GET['contestId'])) { ?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-9">
					<ul class="list-group">
						<h3>Contest Submissions - (<?php echo $_GET['contestId'] ?>)</h3>
						<?php
							// For pagination
							$limit = 20;  
				            if (isset($_GET["page"])) {
				                $page  = $_GET["page"]; 
				            } 
				            else{ 
				                $page=1;
				            }
				            $start_from = ($page-1) * $limit; 
				            $contest_id = $_GET['contestId'];
							$all_sub_sql = "SELECT * FROM submission WHERE contest_id=$contest_id ORDER BY time_submitted DESC LIMIT $start_from, $limit";
							$all_sub_result = mysqli_query($conn, $all_sub_sql);
							$all_sub = mysqli_fetch_all($all_sub_result, MYSQLI_ASSOC);
							$all_submissions_array = array();
							foreach ($all_sub as $sub) {
								$entities = array();
								$question_id = $sub['question_id'];
								$language = $sub['language'];
								$submission_id = $sub['submission_id'];
								$participant_id = $sub['participant_id'];
								$time_submitted = $sub['time_submitted'];

								$qu_sql = "SELECT * FROM question WHERE question_id=$question_id";
								$qu_result = mysqli_query($conn, $qu_sql);
								$question = mysqli_fetch_assoc($qu_result);
								$question_name = $question['question_name'];

								$verdict_sql = "SELECT * FROM submission_verdict WHERE submission_id=$submission_id";
								$verdict_result = mysqli_query($conn, $verdict_sql);
								$verdicts = mysqli_fetch_all($verdict_result, MYSQLI_ASSOC);
								$ac = true;
								$wa = false;
								$ce = false;
								$total_points = 0;
								$pie_ac = 0;
								$pie_ce = 0;
								$pie_wa = 0;
								$verdicts_array = array();
								foreach ($verdicts as $verdict) {
									$temp = array();

									if($verdict['verdict'] != 'AC') {
										$ac = false;
									}
									if ($verdict['verdict'] == 'WA') {
										$wa = true;
										$temp['verdict'] = 'WA';
										$temp['points'] = 0;
										// $pie_wa += 1;
									} elseif($verdict['verdict'] == 'CE') {
										$ce = true;
										$temp['verdict'] = 'CE';
										$temp['points'] = 0;
										// $pie_ce += 1;
									} else {
										$total_points += $verdict['points'];
										$temp['verdict'] = 'AC';
										$temp['points'] = $verdict['points'];
										// $pie_ac += 1;
									}
									$verdicts_array[] = $temp;
								}
								$entities['verdicts'] = $verdicts_array;
								$entities['ac'] = $ac;
								$entities['question_name'] = $question_name;
								$entities['total_points'] = $total_points;
								$all_submissions_array[] = $entities;
							}
						?>

						<?php foreach($all_submissions_array as $all_sub_ar) { ?>
							<?php if($all_sub_ar['ac']) { ?>
								<?php $pie_ac+=1; ?>
								<li class="list-group-item list-group-item-success">
									<a href=""><?php echo $all_sub_ar['question_name'] ?></a>
									<span>| AC | Points: <?php echo $all_sub_ar['total_points']; ?></span>
								</li>
								<br>
							<?php } else { ?>
								<li class="list-group-item list-group-item-danger">
									<a href=""><?php echo $all_sub_ar['question_name'] ?></a>
									<p>
										<?php foreach($all_sub_ar['verdicts'] as $verdict_ar) ?>
											<?php 
											echo "|".$verdict_ar['verdict']."| ";
											if($verdict_ar['verdict'] == 'WA') $pie_wa+=1;
											else $pie_ce+=1; 
											?>
										<?php ?>
										<span>Points: <?php echo $all_sub_ar['total_points']; ?></span>
									</p>	
								</li>
								<br>
							<?php } ?>
						<?php } ?>
					</ul>
				</div>
				<div class="col-md-3">
					<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
					<canvas id="myChart" width="400" height="400"></canvas>
					<script>
					var ctx = document.getElementById('myChart').getContext('2d');
					var myChart = new Chart(ctx, {
					    type: 'pie',
					    data: {
					        labels: ['WA', 'CE', 'AC'],
					        datasets: [{
					            label: '# of Votes',
					            data: ["<?php echo $pie_wa ?>", "<?php echo $pie_ce ?>", "<?php echo $pie_ac ?>"],
					            backgroundColor: [
					                '#dc3545',
					                '#ffc107',
					                '#28a745'
					            ],
					            borderColor: [
					                '#dc3545',
					                '#ffc107',
					                '#28a745'
					            ],
					            borderWidth: 1
					        }]
					    },
					    // options: {
					    //     scales: {
					    //         yAxes: [{
					    //             ticks: {
					    //                 beginAtZero: true
					    //             }
					    //         }]
					    //     }
					    // }
					    options: {
					    	responsive: true
					    }
					});
					</script>
				</div>
			</div>
			<?php  
	            $result_db = mysqli_query($conn,"SELECT COUNT(submission_id) FROM submission"); 
	            $row_db = mysqli_fetch_row($result_db);  
	            $total_records = $row_db[0];  
	            $total_pages = ceil($total_records / $limit); 
	            // echo  $total_pages;
	            $pagLink = "<ul class='pagination'>"; 
	            if($total_pages != 1) { 
		            for ($i=1; $i<=$total_pages; $i++) {
		                $pagLink .= "<li class='page-item'><a class='page-link' href='contest_submission.php?contestId=$contest_id&page=".$i."'>".$i."</a></li>";	
		            }
		            echo $pagLink . "</ul>";
		        }
	        ?>
		</div>
	<?php } ?>
	<script type="text/javascript">
		activate('nav4');
	</script>
</body>
</html>