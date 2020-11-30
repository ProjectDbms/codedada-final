<!DOCTYPE html>
<html>
<head>
	<title>Codedada - SignUp</title>
	<script>
		
	</script>
	<?php
		include("includes/header.php");
        include("includes/db_connection.php");
        session_start();
        $username_error = "";
        $email_error = "";
        $password_error = "";
        if(isset($_SESSION["username"])) {
            header("location:index.php");
        }
	?>
	<link rel="stylesheet" href="./assets/css/signUp.css?q=<?php echo time(); ?>" type="text/css">
</head>
<body>
	<div class="container-fluid">
		<div class="row container-main">
			<div class="col-md-6 col-xs-12 signup-card">
				
			</div>
			<div class="col-md-6">
				<div class="signup-form pr-5 pl-5">
					<form action="signUp.php" method="post" class="signup-form">
						<h2 class="mb-2" style="color: #004E7C;">Sign Up - <span style="color: red;">Codedada</span></h2>
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control" name="email" id="email" required>
							<p id="email-error" class="text-danger" style="font-size: 12px;"></p>
						</div>
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" class="form-control" name="username" id="username" min="4" max="50" required>
							<p id="username-error" class="text-danger" style="font-size: 12px;"></p>
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" class="form-control" name="password" id="password" min="4" max="20" required>
						</div>
						<div class="form-group">
							<label for="confirmPassword">Confirm Password</label>
							<input type="password" class="form-control" name="confirmPassword" id="confirmPassword" min="4" max="20" required>
							<p id="password-mismatch-error" class="text-danger" style="font-size: 12px;"></p>
						</div>
						<p style="color: red;">Already have account? <span style="color: blue;"><a href="login.php">Login here</a></span></p>
						<div class="text-center">
							<input type="submit" name="signUp" value="Sign Up" class="btn btn-primary pr-3 pl-3">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var errors = {};
		$(document).ready(function() {
			$("#email").keyup(function() {
				var email = $("#email").val();
				var object = {
					"field": "email",
					"value": email
				};
				$.ajax({
					url: "readJson.php",
					method: "post",
					data: object,
					success: function(res) {
						$("#email-error").html(res);
						errors["email"] = res;
					}
				})
			});
			$("#username").keyup(function() {
				var username = $("#username").val();
				var object = {
					"field": "username",
					"value": username
				};
				$.ajax({
					url: "readJson.php",
					method: "post",
					data: object,
					success: function(res) {
						$("#username-error").html(res);
						errors["username"] = res;
					}
				})
			});
			$("#password").keyup(function() {
				var password = $("#password").val();
				var confirmPassword = $("#confirmPassword").val();
				if(password !== confirmPassword && confirmPassword !== "") {
					$("#password-mismatch-error").html("Passwords do not match");
					errors["password"] = true;
				} else {
					$("#password-mismatch-error").html("");
					errors["password"] = false;
				}
			});
			$("#confirmPassword").keyup(function() {
				var password = $("#password").val();
				var confirmPassword = $("#confirmPassword").val();
				if(password !== confirmPassword) {
					$("#password-mismatch-error").html("Passwords do not match");
					errors["password"] = true;
				} else {
					$("#password-mismatch-error").html("");
					errors["password"] = false;
				}
			});
			$("form").submit(function(event) {
				event.preventDefault();
				var email = $("#email").val();
				var username = $("#username").val();
				var password = $("#password").val();
				var object = {
					"field": "submit",
					"email": email,
					"username": username,
					"password": password
				}
				if(errors["email"] != "" || errors["username"] != "" || errors["password"] !== false) {
					window.alert("Form contains invalid credentials");
				} else {
					$.ajax({
						url: "readJson.php",
						method: "post",
						data: object,
						success: function(res) {
							if(res) {
								window.alert("Creating account success");
								window.location.href = "login.php";
							} else {
								window.alert("Creating account failure");
								window.location.href = "signUp.php";
							}
						}
					});
				}
			});
		});
	</script>
</body>
</html>