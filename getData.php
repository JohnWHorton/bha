<?php

$operation = isset($_GET["operation"]) ? $_GET["operation"] : null;
$year = isset($_GET["year"]) ? $_GET["year"] : null;
$courseId =  isset($_GET["courseId"]) ? $_GET["courseId"] : null;
$fixtureId =  isset($_GET["fixtureId"]) ? $_GET["fixtureId"] : null;
$raceId =  isset($_GET["raceId"]) ? $_GET["raceId"] : null;

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
$operation = "upcoming";
$respArray = array();

// if ($operation == "racecourses") {
// 	$response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v1/racecourses"');
// 	$resp = json_decode($response, true);
// 	$data = $resp["data"];
// }
// if ($operation == "fixtures") {
// 	//	$response = shell_exec('curl --location //"https://www.britishhorseracing.com/feeds/v3/fixtures?fields=courseId%2CcourseName%2CfixtureDate%2CfixtureType%2CfixtureSession%2CabandonedReasonC//ode%2ChighlightTitle&month=1&order=desc&page=1&per_page=1000&resultsAvailable=true&year='.$year.'"');


// 	$response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v3/fixtures?year=' . $year . '"');
// 	$resp = json_decode($response, true);
// 	$data = $resp["data"];
// }

if ($operation == "upcoming") {
	$data = doUpcoming();
}

echo json_encode($data);
function doUpcoming()
{
	$upcoming = new stdClass();
	$fixtures = array();
	$races = array();
	$entries = array();
	$fromdate = "20240116";
	$todate = "20240118";
	//get fixtures
	$response = shell_exec('curl --location "https://api09.horseracing.software/bha/v1/fixtures?fields=abandonedReasonCode,courseId,courseName,fixtureYear,fixtureId,fixtureDate,distance,firstRace,firstRaceTime,fixtureName,fixtureSession,fixtureType,highlightTitle,majorEvent,meetingId,resultsAvailable,bcsEvent&fromdate=20240117&page=1&per_page=15&todate=20240217"');

	$tmp = array();
	$resp = json_decode($response, true);
	$fixtures = $resp["data"];
	for ($t = 2; $t < count($fixtures); $t++) {
		if ($fixtures[$t]["abandonedReasonCode"] == 0) {
			array_push($tmp, $fixtures[$t]);
		}
	}
	$fixtures = $tmp;
	$upcoming->fixtures = $fixtures;
	//get races per fixture
	for ($a = 2; $a < count($fixtures); $a++) {
		$fixyear = $fixtures[$a]["fixtureYear"];
		$fixtureId = $fixtures[$a]["fixtureId"];
		if ($fixtures[$a]["abandonedReasonCode"] == 0) {
			$response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v3/fixtures/' . $fixyear . '/' . $fixtureId . '/races"');
			$resp = json_decode($response, true);
			$r = $resp["data"];
			for($t=0; $t<count($r); $t++) {
				$r[$t]["fixtureId"] = $fixtureId;
			}
			// var_dump($r);
			array_push($races, $r);
		}
	}
	$upcoming->races = $races;
	
	//get entries per race

	for ($b = 0; $b < count($races); $b++) {
		// echo $b . " " . json_encode($races[$b]) . "\n";
		for ($e = 0; $e < count($races[$b]); $e++) {
			// var_dump($races[$b][$e]);
			if (!isset($races[$b][$e]["yearOfRace"])) {
				$races[$b][$e]["yearOfRace"] = 2024;
			}
			if (isset($races[$b][$e]["raceId"])) {
				// echo $b . " " . $e . " " . json_encode($races[$b][$e]) . "\n";
				$raceId = $races[$b][$e]["raceId"];
				// echo $raceId."\n";
				$response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v3/races/' . $fixyear . '/' . $raceId . '/0/entries"');
				$resp = json_decode($response, true);
				// echo $resp."\n";
				array_push($entries, $resp["data"]);
			}
			$upcoming->entries = $entries;
		}
	}

	// array_push($upcoming, $entries);
	return $upcoming;
}
