<?php
require_once ("./Hackerearth-SDK-PHP/sdk/index.php");

$hackerearth = Array(
	'client_secret' => 'b1803e335e227a1320fc6dc3587a0d496e97d3e5',
    'time_limit' => '3',
    'memory_limit' => '262144'
);

$config = Array();
$config['time']='5';	 	//(OPTIONAL) Your time limit in integer and in unit seconds
//$config['memory']='262144'; //(OPTIONAL) Your memory limit in integer and in unit kb
$config['source']='';    	//(REQUIRED) Your source code for which you want to use hackerEarth api
$config['input']='';     	//(OPTIONAL) Input against which you have to test your source code
$config['language']='C';   	//(REQUIRED) Choose any one of them 
						 	// C, CPP, CPP11, CLOJURE, CSHARP, JAVA, JAVASCRIPT, HASKELL, PERL, PHP, PYTHON, RUBY
// $responseOfRun = run($hackerearth,$config);


// echo "<pre>";
// print_r($responseOfRun);
// echo "</pre>";
// $config['language']='CPP14'; 
// $config['input']='5';
// $config['source']='
// #include <iostream>
// using namespace std;
// int main() {
// cout << "Hello"
// return 0;
// }';

// $responseOfRun = run($hackerearth,$config);

// echo "<pre>";
// print_r($responseOfRun);
// echo "</pre>";
// echo $responseOfRun["run_status"]['output_html'];
?>
