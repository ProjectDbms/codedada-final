<!DOCTYPE html>
<html lang="en">
<head>
<title>Codedada - JoinContest</title>
	<?php
		session_start();
		include_once("includes/db_connection.php");
		if(!(isset($_SESSION["username"]))) {
			header("location: login.php");
		} else {
			
		}
        if(isset($_GET['contestId'])) {
            $contest_id = $_GET['contestId'];
            $con_sql = "SELECT * FROM contest WHERE contest_id=$contest_id";
            $con_result = mysqli_query($conn, $con_sql);
            $contest_row = mysqli_fetch_assoc($con_result);
            $start_time_contest = $contest_row['start_time'];
            $end_time_contest = $contest_row['end_time'];
            if(strtotime($start_time_contest)>time() || strtotime($end_time_contest)<time()) {
            	header("location: contest.php");
            }
            if(isset($_SESSION["is_registered_$contest_id"])) {
                
            } else {
                echo "<script>
                    window.alert('You did\'nt register for the contest');
                    window.location.href = 'contest.php';
                </script>";
            }
            $username = $_SESSION['username'];
			$us_sql = "SELECT * FROM accounts WHERE username='$username'";
			$us_result = mysqli_query($conn, $us_sql);
			$us_row = mysqli_fetch_assoc($us_result);
			$account_id = $us_row['account_id'];

			$par_sql = "SELECT * FROM participant WHERE account_id=$account_id AND contest_id=$contest_id";
			$par_result = mysqli_query($conn, $par_sql);
			$par_row = mysqli_fetch_assoc($par_result);
			$participant_id = $par_row['participant_id'];
        } else {
            header("location: contest.php");
        }
    ?>

    <link rel="stylesheet" href="assets/css/contestIde.css" type="text/css">
    <link rel="icon" href="assets/images/programming.png" type="image/png">
</head>
<body>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php 
    include_once("includes/header.php");
    include("includes/navbar_contest.php"); 
    ?>
    <div class="container-fluid">
    	<?php if(isset($_GET['contestId']) && isset($_GET['problems'])) { ?>
    		<div class="question-table mt-5">
	            <?php
	                $q_sql = "SELECT * FROM question WHERE contest_id=$contest_id";
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
	            <a href="contest_submission.php?contestId=<?php echo $contest_id; ?>&participantId=<?php echo $participant_id; ?>">MySubmissions</a>
	            <br>

	            <link rel="stylesheet" href="assets/css/timeTo.css?q=<?php echo time(); ?>" type="text/css" />
				<script type="text/javascript" src="assets/js/jquery-time-to.js"></script>

			    <script type="text/javascript">
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
				</script>
	            <?php
	            	$c_sql = "SELECT * FROM contest WHERE contest_id=$contest_id";
	                $c_result = mysqli_query($conn, $c_sql);
	                $c_contest = mysqli_fetch_assoc($c_result);
					$entime = $c_contest['end_time'];
					$contest_end_time = date("M d Y H:i:s", strtotime($entime))." UTC+5:30";
					echo "<script>countdownCalc('$contest_end_time','#timer');</script>";
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
	                    				$account_id = $us_row['account_id'];
	                    				$par_sql = "SELECT * FROM participant WHERE account_id=$account_id AND contest_id=$contest_id";
	                    				$par_result = mysqli_query($conn, $par_sql);
	                    				$par_row = mysqli_fetch_assoc($par_result);
	                    				// print_r($par_row);
	                    				$participant_id = $par_row['participant_id'];
	                    				$question_id = $question['question_id'];
	                    				$tc_sql = "SELECT points FROM testcase WHERE question_id=$question_id";
	                    				$ts_result = mysqli_query($conn, $tc_sql);
	                    				$testcases = mysqli_fetch_all($ts_result, MYSQLI_ASSOC);
	                    				$points = 0;
	                    				foreach ($testcases as $testcase) {
	                    					$points += $testcase['points'];
	                    				}
	                    				$subm_sql = "SELECT submission.participant_id, submission.question_id, submission.submission_id ,SUM(points) AS points FROM submission_verdict sv, submission 
WHERE sv.submission_id=submission.submission_id AND participant_id=$participant_id AND question_id=$question_id GROUP BY sv.submission_id";
	                    				$subm_result = mysqli_query($conn, $subm_sql);
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
	                    			<a href="join_contest.php?contestId=<?php echo $contest_id; ?>&readQuestion=<?php echo $question['question_id'] ?>"><?php echo $question['question_name'] ?></a>
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
    	<?php } ?>
    </div>

	<?php if(isset($_GET['contestId']) && isset($_GET['readQuestion'])) { ?>
		<?php
			$contest_id = $_GET['contestId'];
			$question_id = $_GET['readQuestion'];
            $q_sql = "SELECT * FROM question WHERE contest_id=$contest_id AND question_id=$question_id";
            $result = mysqli_query($conn, $q_sql);
            if($result) {
                $question = mysqli_fetch_assoc($result);
                $question_id = $question['question_id'];
                $question_desc = $question['question_description'];
                $question_name = $question['question_name'];
                $level = $question['level'];
            } else {
                echo "<script>
                    window.alert('Something went wrong');
                    window.location.href = 'join_contest.php?contestId=<?php echo $contest_id; ?>&problems=<?php echo $contest_id ?>';
                </script>";
            }
        ?>
        <form action="contest_submission.php" id="submitForm" method="post" style="display: none;">
        	<input type="hidden" name="contest_id" value="<?php echo $contest_id; ?>">	
        	<input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
        	<input type="text" name="code" id="edCode">
        	<input type="text" name="language" id="edLanguage">
        </form>
        <div class="fcn-grid">
        	<div class="fcn-component">
        		<div class="card text-white bg-primary mr-3 mt-1">
        			<div class="card-header bg-dark"><?php echo $question_name; ?></div>
					<div class="card-body">
        				<?php echo $question_desc; ?>
	            	</div>
	            </div>
        	</div>
    		<div class="fcnn-component">
    			<div class="ide" style="height: 67%; position: relative;">
    				<?php
    					include('ide.php');
    				?>
    			</div>
    			<div class="buttons-out">
    				<div class="buttons" style="background-color: black; height: 20%; padding-top: 2px;">
    					<button class="btn btn-primary" id="run-btn" style="float: left; margin-left: 40px;">Run</button>
    					<select name="lang" id="lang" class="form-control" style="width: 150px; float: left; margin-left: 290px;">
    						<option value="C">C</option>
    						<option value="CPP14">C++ 14</option>
    						<option value="JAVA8">Java 8</option>
    						<option value="PYTHON">Python 2</option>
    						<option value="PYTHON3">Python 3</option>
    						<option value="JAVASCRIPT_NODE">Javascript(NodeJS)</option>
    					</select>
    					<button class="btn btn-danger" id="submit-btn" style="float: right; margin-right: 40px;">Submit</button>
    				</div>
    				<div class="output-input" style="clear: both; height: 80%; background-color: #272822;">
    					<div class="input" style="float: left; width: 46%; margin-left: 20px;">
    						<label style="color: white;">Input</label><br>
    						<textarea rows="7" class="form-control" id="in" style="font-size: 12px; font-weight: 600;"></textarea>
    					</div>
    					<div class="output" id="outId" style="float: right; width: 46%; margin-right: 20px;">
    						<label style="color: white;">Output</label><br>
    						<textarea rows="7" class="form-control" id="out" style="font-size: 12px; font-weight: 600;" readonly></textarea>
    						<div class="loader" style="display: none;"></div>
    					</div>
    				</div>
    			</div>
    		</div>
        </div>
	<?php } ?>

    <?php
        if(isset($_GET['problems'])) {
            echo "<script type='text/javascript'>
                activate('nav1');
            </script>";
        }
        if(isset($_GET['users'])) {
            echo "<script type='text/javascript'>
                activate('nav3');
            </script>";
        }
    ?>
    <script type="text/javascript">
    	c_temp = '#include <stdio.h>\n\
int main() {\n\
	\n\
	return 0;\n\
}';
		cpp_temp  = '#include <iostream>\n\
using namespace std;\n\
int main() {\n\
	\n\
	return 0;\n\
}';
		java_temp = 'public class Main {\n\
	public static void main(String[] args) {\n\
		System.out.println("Hello, World");\n\
	}\n\
}';
		pyth3_temp = 'print("Hello, World")';
		pyth_temp = 'print "Hello, World"';
		node_temp = 'console.log("Hello, World")';
		var language = "C";
		var lang = $("#lang");
		$(lang).on('change', function() {
			language = lang.val();
			if(language == "C" || language == "CPP14") {
				editor.session.setMode("ace/mode/c_cpp");
			} else if(language == "JAVA8") {
				editor.session.setMode("ace/mode/java");
			} else if(language == "PYTHON" || language == "PYTHON3") {
				editor.session.setMode("ace/mode/python");
			} else if(language == "JAVASCRIPT_NODE") {
				editor.session.setMode("ace/mode/javascript");
			}
			if(language == "C" && (editor.session.getValue()==java_temp || editor.session.getValue() == cpp_temp || editor.session.getValue() == pyth3_temp || editor.session.getValue() == pyth_temp || editor.session.getValue() == node_temp)) {
				editor.session.setValue(c_temp);	
			} else if(language == "CPP14" && (editor.session.getValue()==java_temp || editor.session.getValue() == c_temp || editor.session.getValue() == pyth3_temp || editor.session.getValue() == pyth_temp || editor.session.getValue() == node_temp)) {
				editor.session.setValue(cpp_temp);
			} else if(language == "JAVA8" && (editor.session.getValue()==cpp_temp || editor.session.getValue() == c_temp || editor.session.getValue() == pyth3_temp || editor.session.getValue() == pyth_temp || editor.session.getValue() == node_temp)) {
				editor.session.setValue(java_temp);
			} else if(language == "PYTHON" && (editor.session.getValue()==cpp_temp || editor.session.getValue() == c_temp || editor.session.getValue() == pyth3_temp || editor.session.getValue() == java_temp || editor.session.getValue() == node_temp)) {
				editor.session.setValue(pyth_temp);
			} else if(language == "PYTHON3" && (editor.session.getValue()==cpp_temp || editor.session.getValue() == c_temp || editor.session.getValue() == pyth_temp || editor.session.getValue() == java_temp || editor.session.getValue() == node_temp)) {
				editor.session.setValue(pyth3_temp);
			} else if(language == "JAVASCRIPT_NODE" && (editor.session.getValue()==cpp_temp || editor.session.getValue() == c_temp || editor.session.getValue() == pyth_temp || editor.session.getValue() == java_temp || editor.session.getValue() == pyth3_temp)) {
				editor.session.setValue(node_temp);
			}
		});
    	$("#run-btn").click(function() {
    		console.log("run");
    		$("#out").css("display", "none");
    		$(".loader").css("display", "block");
    		var code = editor.session.getValue();
    		var object = {
    			"type": "run",
    			"code": code,
    			"lang": language,
    			"input": $("#in").val()
    		}
    		$("#out").val("");
    		$.ajax({
				url: "compile.php",
				method: "post",
				data: object,
				success: function(res) {
					if(res) {
						var obj = JSON. parse(res)
						$("#out").css("display", "block");
    					$(".loader").css("display", "none");
						if(obj.compile_status != "OK") {
							$("#out").val(obj["compile_status"]);
						} else {
							$("#out").val(obj["run_status"]["output"]);
						}
					} else {
						window.alert("Error in sending code");
					}
				}
			});
    	});
    	$("#submit-btn").click(function(){
    		$("#out").css("display", "none");
    		$(".loader").css("display", "block");
    		$("#edCode").val(editor.session.getValue());
			$("#edLanguage").val($("#lang").val());
			if($("#lang").val() == "C" || $("#lang").val() == "CPP14") {
				var code = editor.session.getValue();
				var object = {
	    			"type": "submit",
	    			"code": code,
	    			"lang": language
    			}
    			$.ajax({
					url: "compile.php",
					method: "post",
					data: object,
					success: function(res) {}
				});
			}
			$("#submitForm").submit();
    	});
    </script>
    <script type="text/javascript">
    </script>
</body>
</html>