<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("HTTP/1.1 200 OK");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$operation = isset($_GET["operation"]) ? $_GET["operation"] : "upcoming";
$year = isset($_GET["year"]) ? $_GET["year"] : null;
$courseId =  isset($_GET["courseId"]) ? $_GET["courseId"] : null;
$fixtureId =  isset($_GET["fixtureId"]) ? $_GET["fixtureId"] : null;
$raceId =  isset($_GET["raceId"]) ? $_GET["raceId"] : null;

$data = new stdClass();
// $operation = "upcoming";
$respArray = array();
array_push($respArray, $operation);

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
	$currentDate = date('Ymd');
	$fromdate =	$currentDate - 1;
	$todate =
	$currentDate + 2;
	//get fixtures
	$response = shell_exec('curl --location "https://api09.horseracing.software/bha/v1/fixtures?fields=abandonedReasonCode,courseId,courseName,fixtureYear,fixtureId,fixtureDate,distance,firstRace,firstRaceTime,fixtureName,fixtureSession,fixtureType,highlightTitle,majorEvent,meetingId,resultsAvailable,bcsEvent&fromdate='. $fromdate.'&page=1&per_page=150&todate='. $todate.'"');

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
