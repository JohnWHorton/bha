<?php

$host = 'localhost:82';
$db   = 'bha';
$user = 'john';
$pass = 'john';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
} else {
    echo "db ok ";
}
echo "hello";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("HTTP/1.1 200 OK");


$data = new stdClass();
$operation = "upcoming";

echo "upcoming";
if ($operation == "upcoming") {
    $data = doUpcoming($conn);
}


function doUpcoming($conn)
{
    $upcoming = new stdClass();
    $fixtures = array();
    $races = array();
    $entries = array();
    $currentDate = date('Ymd');
    $fromdate =    $currentDate - 1;
    $todate = $currentDate + 2;
    //get fixtures
    $response = shell_exec('curl --location "https://api09.horseracing.software/bha/v1/fixtures?fields=abandonedReasonCode,courseId,courseName,fixtureYear,fixtureId,fixtureDate,distance,firstRace,firstRaceTime,fixtureName,fixtureSession,fixtureType,highlightTitle,majorEvent,meetingId,resultsAvailable,bcsEvent&fromdate=' . $fromdate . '&page=1&per_page=150&todate=' . $todate . '"');

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
            for ($t = 0; $t < count($r); $t++) {
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
            
            for($i=0; $i<count($entries); $i++) {
                for ($j = 0; $j < count($entries[$i]); $j++) {
                    $raceId = $entries[$i][$j]["raceId"];
                    $yearOfRace = $entries[$i][$j]["yearOfRace"];
                    $animalId = $entries[$i][$j]["animalId"];
                    $racehorseName = $entries[$i][$j]["racehorseName"];

                    $sql = 'INSERT INTO entries (raceId, yearOfRace, animalId, racehorseName) 
                    VALUES (' . $raceId . ',' . $yearOfRace . ',' . $animalId . ',"' . $racehorseName . '")';
                    echo $sql;
                    // if ($conn->query($sql) === true) {
                    //     array_push($resparr, 'success', "added");
                    // } else {
                    //     array_push($resparr, 'error', $sql);
                    // }
                }
            }            
        }
    }

$file = fopen(__DIR__ . '/upcoming.json', 'w');
fwrite($file, json_encode($upcoming));
fclose($file);
}