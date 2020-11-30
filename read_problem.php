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
    <link rel="stylesheet" href="assets/css/contestIde.css" type="text/css">
    <link rel="icon" href="assets/images/programming.png" type="image/png">
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<?php include("includes/navbar.php"); ?>
    <br>
    <br>
    <br>    
    <br>
    <div class="container-fluid">
        <?php
            // $contest_id = $_GET['contestId'];
            $question_id = $_GET['questionId'];
            $q_sql = "SELECT * FROM question WHERE question_id=$question_id";
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
                    window.location.href = 'problems.php';
                </script>";
            }
        ?>
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
                        <!-- <button class="btn btn-danger" id="submit-btn" style="float: right; margin-right: 40px;">Submit</button> -->
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
    </div>
    <script type="text/javascript">
        $("#run-btn").click(function() {
    		console.log("run");
    		$("#out").css("display", "none");
            $(".loader").css("display", "block");
            var language = $("#lang").val();
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
    </script>
</body>
</html>