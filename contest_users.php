<!DOCTYPE html>
<html>
<head>
	<title>Codedada - Users</title>
	<?php
		session_start();
		include("includes/db_connection.php");
		if(!(isset($_SESSION["username"]))) {
			header("location: login.php");
		}
		include("includes/header.php");
		if(isset($_GET['contestId'])) {
			$contest_id = $_GET['contestId'];
		} else {
			header('location: contest.php');
		}
	?>
	<link rel="stylesheet" href="assets/css/index.css?q=<?php echo time(); ?>" type="text/css">
</head>
<body>
	<?php include("includes/navbar_contest.php"); ?>
	<div class="container-fluid main">
		<?php
			$sql = "SELECT * FROM participant WHERE contest_id=$contest_id";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_all($result, MYSQLI_ASSOC);
			// print_r($row);
		?>
		<div class="container">
			<br><br>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th style="width:30%">#</th>
                        <th style="width:70%">Username</th>
                    </tr>
                </thead>
                <tbody>
				<?php $i=1; foreach($row as $user) { ?>
					<?php
						$account_id = $user['account_id'];
						$sql = "SELECT * FROM accounts WHERE account_id=$account_id";
						$result = mysqli_query($conn, $sql);
						$usr = mysqli_fetch_assoc($result);
						$username=$usr['username'];
					?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php echo $username ?></td>
					</tr>
				<?php $i+=1; } ?>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		activate("nav3");
	</script>
</body>
</html>