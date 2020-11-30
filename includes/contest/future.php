<div class="future-contest mt-5">
	<table class="table table-bordered">
	<br>
		<br>
		<br>
		<h4 class="text-info">Upcoming contest</h4>
		<thead class="thead-dark">
			<tr>
				<th>Contest name</th>
				<th>Organizer</th>
				<th>Start time</th>
				<th>End time</th>
				<th>Starts in</th>
				<th>Register</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($contests as $contest) { ?>
				<?php if (strtotime($contest['start_time']) > time()) { $futureFlag=false; ?>
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
								$diff=strtotime($entime) - strtotime($sttime);
								echo date("d-m-Y h:i:s a", strtotime($sttime))."<br>";
								$years = floor($diff / (365*60*60*24));
								$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
								$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
								$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
								$minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
								$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
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
								$future_contest_id = $contest['contest_id'];
								$contest_start_time = date("M d Y H:i:s", strtotime($sttime))." UTC+5:30";
								echo "<script>countdownCalc('$contest_start_time', $future_contest_id);</script>";
							?>
						</td>
						<td>
							<?php if(check_reg($_SESSION['username'], $contest['contest_id'])) { ?>
								<p>Registered</p>
							<?php } else { ?>
								<a href="contest.php?registerContest=<?php echo $contest['contest_id'] ?>">Register</a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>