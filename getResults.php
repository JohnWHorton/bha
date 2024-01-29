<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

header("HTTP/1.1 200 OK");

$host = 'localhost';
$db = 'prhwgzau_bha';
$user = 'prhwgzau_john';
$pass = 'prhwgzau_jon';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db, '3306');

// Check connection
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
} 


$data = new stdClass();

$response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v3/races/2024/48497/0/results"');
$resp = json_decode($response, true);
$data = $resp["data"];

$resultsarr = array();

for ($i = 0; $i < count($data); $i++) {
    echo $data[$i]["raceId"]."\n";
    echo $data[$i]["horseId"] . "\n";
    echo $data[$i]["racehorseName"] . "\n";
    echo $data[$i]["resultFinishPos"] . "\n";


    $racehorseName = $conn->real_escape_string($data[$i]['racehorseName']);
    echo $racehorseName . "\n";
    $sql = "INSERT INTO results(raceId, horseId, racehorseName, resultFinishPos) 
    VALUES (" . $data[$i]['raceId'] . "," . $data[$i]['horseId'] . ",'" . $racehorseName . "'," . $data[$i]['resultFinishPos'] . ")";

    echo $sql . "\n";

    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo $sql . "\n";
        echo $conn->error;
    }
}


// var_dump($resultsarr);

// echo json_encode($data);

?>