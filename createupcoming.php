<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("HTTP/1.1 200 OK");

//namecheap server
$host = 'localhost';
$db = 'prhwgzau_bha';
$user = 'prhwgzau_john';
$pass = 'prhwgzau_jon';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
}

$data = new stdClass();
$operation = "upcoming";

// echo "upcoming";
if ($operation == "upcoming") {
    $data = doUpcoming($conn);
}
function doUpcoming($conn)
{
    $upcoming = new stdClass();
    $fixtures = array();
    $races = array();
    $entries = array();
    $horses = "";
    $results = array();
    $currentDate = date('Ymd');
    $fromdate = date('Ymd', strtotime(' - 1 day', strtotime($currentDate)));  
    $todate = date('Ymd', strtotime(' + 2 day', strtotime($currentDate)));
    echo $fromdate . " - " . $todate ;
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
    for ($a = 0; $a < count($fixtures); $a++) {
        $fixyear = $fixtures[$a]["fixtureYear"];
        $fixtureId = $fixtures[$a]["fixtureId"];
        if ($fixtures[$a]["abandonedReasonCode"] == 0) {
            $response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v3/fixtures/' . $fixyear . '/' . $fixtureId . '/races"');
            $resp = json_decode($response, true);
            $r = $resp["data"];
            for ($t = 0; $t < count($r); $t++) {
                $r[$t]["fixtureId"] = $fixtureId;
            }
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
                if ($resp["data"] != []) {
                    array_push($entries, $resp["data"]);
                } else {
                    $response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v3/races/' . $fixyear . '/' . $raceId . '/0/trans"');
                    $resp = json_decode($response, true);
                    if ($resp["data"] != []) {
                        array_push($entries, $resp["data"]);
                    }
                }
            }
            $upcoming->entries = $entries;
        }
    }

    try {
        $jsonUpcoming = json_encode($upcoming);
        if ($jsonUpcoming === false) {
            throw new Exception('Error encoding data to JSON.');
        }
        $filePath = __DIR__ . '/upcoming.json';
        $result = file_put_contents($filePath, $jsonUpcoming);
        if ($result === false) {
            throw new Exception('Error writing to file.');
        }
        echo 'Data written to file successfully.' . "\n";
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
    }
    // previous results for all runners

    for ($i = 0; $i < count($entries); $i++) {
        for ($j = 0; $j < count($entries[$i]); $j++) {
            if ($entries[$i][$j]["animalId"] && $entries[$i][$j]["animalId"] > 0) {
                if ($i == 0 && $j == 0) {
                    $horses = $horses . $entries[$i][$j]["animalId"];
                } else {
                    $horses = $horses . "," . $entries[$i][$j]["animalId"];
                }
            }
        }
    }
    //tmp comment
    $sql = "SELECT re.*, ra.raceName 
        FROM results AS re
        JOIN races AS ra ON re.raceId = ra.raceId
        WHERE horseId IN ($horses) 
        AND re.yearOfRace = SUBSTRING(ra.raceDate, 1, 4)";

    $result = $conn->query($sql);
    $rows = [];

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    } else {
        $rows[] = ['error' => 'No results found for the query: ' . $sql];
    }

    try {
        // $rows = mb_convert_encoding($rows, 'UTF-8', 'UTF-8');
        $jsonUpcoming = json_encode($rows, JSON_INVALID_UTF8_IGNORE);
        // var_dump($jsonUpcoming);
        if (json_last_error() === JSON_ERROR_NONE) {
            // JSON is valid 
            echo "JSON is valid!";
            // Now you can use $jsonData as a PHP object or array 
        } else {
            // JSON is invalid 
            echo "upcomingresults JSON is invalid. Error: " . json_last_error_msg();
        } 
        
        $filePath = __DIR__ . '/upcomingresults.json';
        $result = file_put_contents($filePath, $jsonUpcoming);
        if ($result === false) {
            throw new Exception('Error writing to file.');
        }
        echo 'Data written to file successfully.';
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
    }
}
