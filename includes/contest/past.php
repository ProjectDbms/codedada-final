<div class="past-contest mt-5">
	<table class="table table-bordered">
	<br>
		<br>
		<br>
		<h4 class="text-muted">Past contest</h4>
		<thead class="thead-dark">
			<tr>
				<th>Contest name</th>
				<th>Organizer</th>
				<th>Start time</th>
				<th>Duration</th>
				<th>Ranks</th>
				<th>Users</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($contests as $contest) { ?>
				<?php if (strtotime($contest['end_time']) < time()) { $pastFlag=false ?>
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
							?>
						</td>
						<td>
							<div id="contestDuration<?php echo $contest['contest_id'] ?>"></div>
							<?php
								$years = floor($diff / (365*60*60*24));
								$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
								$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
								$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
								$minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
								$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
								$contId = $contest['contest_id'];
								echo "<script>pastDuration($contId, $years, $months, $days, $hours, $minutes, $seconds);</script>";
							?>
						</td>
						<td>
							<a href="rank.php?contestId=<?php echo $contId ?>">Ranks</a>
						</td>
						<td>
							<!-- Users -->
							<?php
								$contest_id = $contest['contest_id'];
								$count_sql = "SELECT COUNT(participant_id) FROM participant WHERE contest_id=$contest_id";
								$count_restult = mysqli_query($conn, $count_sql);
								$row = mysqli_fetch_row($count_restult);
								// print_r($row);
								echo $row[0];
							?>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>
