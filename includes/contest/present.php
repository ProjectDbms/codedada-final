<div class="present-contest mt-5">
	<table class="table table-bordered">
		<br>
		<br>
		<br>
		<h4 class="text-danger">Running contest</h4>
		<thead class="thead-dark">
			<tr>
				<th>Contest name</th>
				<th>Organizer</th>
				<th>Start time</th>
				<th>End time</th>
				<th>Ends in</th>
				<th>Register</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($contests as $contest) { ?>
				<?php if (strtotime($contest['start_time']) < time() && strtotime($contest['end_time']) > time()) { $presentFlag=false; ?>
					<tr>
						<td>
							<?php echo $contest["contest_name"] ?>
						</td>
						<td>
							<?php 
								$acc_id = $contest["account_id"];
								$sql = "SELECT username FROM accounts WHERE account_id=$acc_id";
								$result = mysqli_query($conn, $sql);
								$organizer = mysqli_fetch_assoc($result);
								echo $organizer["username"];
							?>
						</td>
						<td>
							<?php
								$sttime = $contest["start_time"];
								$entime = $contest["end_time"];
								echo date("d-m-Y h:i:s a", strtotime($sttime))."<br>";
							?>
						</td>
						<td>
							<?php
								echo date("d-m-Y h:i:s a", strtotime($entime))."<br>";
							?>
						</td>
						<td>
							<div id="timer<?php echo $contest['contest_id'] ?>"></div>
							<?php
								$present_contest_id = $contest['contest_id'];
								$contest_end_time = date("M d Y H:i:s", strtotime($entime))." UTC+5:30";
								echo "<script>countdownCalc('$contest_end_time', $present_contest_id);</script>";
							?>
						</td>
						<td>
							<?php if(check_reg($_SESSION['username'], $contest['contest_id'])) { $_SESSION["is_registered_$present_contest_id"]=true; ?>
								<a href="join_contest.php?contestId=<?php echo $contest['contest_id'] ?>&problems=<?php echo $contest['contest_id'] ?>">Join</a>
							<?php } else { ?>
								<a href="contest.php?registerContest=<?php echo $contest['contest_id'] ?>" style="color: white;background-color: red !important;">Register</a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>