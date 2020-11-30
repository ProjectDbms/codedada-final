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
	?>
	<link rel="stylesheet" href="assets/css/index.css?q=<?php echo time(); ?>" type="text/css">
</head>
<body>
	<?php include("includes/navbar.php"); ?>
	<br><br><br><br><br>
	<div class="container-fluid main">
		<?php
			$sql = "SELECT DISTINCT username, AVG(`timestamp`) AS avg_time, AVG(points) AS avg_points FROM rank GROUP BY username ORDER BY AVG(points) DESC, AVG(`timestamp`) ASC";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_all($result, MYSQLI_ASSOC);
		?>
		<table class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th style="width:10%">Rank</th>
					<th style="width:70%">Username</th>
					<th style="width:20%">Avg Score</th>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; foreach($row as $user) { ?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php echo $user['username'] ?></td>
						<td><?php echo $user['avg_points'] ?></td>
					</tr>
				<?php $i+=1; } ?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		activate("nav3");
	</script>
</body>
</html>