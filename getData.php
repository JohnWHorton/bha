<?php

$operation = isset($_GET["operation"]) ? $_GET["operation"] : null;
$year = isset($_GET["year"]) ? $_GET["year"] : null;
$courseId =  isset($_GET["courseId"]) ? $_GET["courseId"] : null;
 
header("Access-Control-Allow-Origin: *");   
header("Content-Type: application/json; charset=UTF-8");    
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");    
header("Access-Control-Max-Age: 3600");    
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

/* if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {   
	header("HTTP/1.1 204 No Content");		
	return 0;    
}  */ 
 
header("HTTP/1.1 200 OK");

//$response = shell_exec('curl --location //"https://www.britishhorseracing.com/feeds/v3/fixtures?fields=courseId,courseName,fixtureDate,fixtureType,fixtureSession,abandonedReasonCode,highli//ghtTitle&month=1&order=desc&page=1&per_page=1000&resultsAvailable=true&year=2023"');

$data = new stdClass();

if($operation == "racecourses") {
	$response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v1/racecourses"');
	$resp = json_decode($response, true);
	$data = $resp["data"];
}
if($operation == "fixtures") {
//	$response = shell_exec('curl --location //"https://www.britishhorseracing.com/feeds/v3/fixtures?fields=courseId%2CcourseName%2CfixtureDate%2CfixtureType%2CfixtureSession%2CabandonedReasonC//ode%2ChighlightTitle&month=1&order=desc&page=1&per_page=1000&resultsAvailable=true&year='.$year.'"');
	
	
	$response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v3/fixtures?year='.$year.'"');
	$resp = json_decode($response, true);
	$data = $resp["data"];
}

echo json_encode($data);

?>



