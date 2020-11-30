<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="icon" href="assets/images/programming.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Karla&display=swap" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<?php
	// $myfile = fopen("code.txt", "r") or die("Unable to open file!");
	// // echo fread($myfile,filesize("code.txt"));
	// $q = fread($myfile,filesize("code.txt"));
	// echo $q;
	// fclose($myfile);
	$contest_id = $_GET['contestId'];
	// include_once("api.php");
	// $config['language'] = 'PYTHON3';
	// $config['source'] = 'prin("Hello")';
	// $config['language'] = 'CPP14';
	// $config['source'] = '
	// #include<iostream>
	// using namespace std;
	// int main() {
	// 	cout << "Hello" << endl
	// 	return 0;
	// }';
	// $responseOfRun = run($hackerearth,$config);
	// echo "<pre>";
	// print_r($responseOfRun);
	// echo "</pre>";
	// echo "<br>";
	// echo $responseOfRun['run_status']['stderr']=='';
?>

<script>
	var object = {
		"contestId": parseInt("<?php echo $contest_id; ?>")
	};
	$.ajax({
		url: "calculate_rank.php",
		method: "post",
		data: object,
		success: function(res) {
			if(res) {
				console.log(res);
			} else {
				window.alert("Error calculating rank");
			}
		}
	});
</script>