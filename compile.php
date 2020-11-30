<?php
	include_once("includes/db_connection.php");
	include_once("api.php");
	if($_POST['type'] == 'run') {
		$config['source']=$_POST['code'];
		$config['input']=$_POST['input'];
		$config['language']=$_POST['lang'];
		$responseOfRun = run($hackerearth,$config);
		echo json_encode($responseOfRun);
	} elseif($_POST['type'] == 'submit') {
		$code_file = fopen("code.txt", "w") or die("Unable to open file!");
		$lang_file = fopen("lang.txt", "w") or die("Unable to open file!");
		fwrite($code_file, "");
		fwrite($lang_file, "");
		fwrite($code_file, $_POST['code']);
		fwrite($lang_file, $_POST['lang']);
		fclose($code_file);
		fclose($lang_file);
		echo true;
	}
?>