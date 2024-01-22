<?php

$host = 'localhost:82';
$db   = 'bha';
$user = 'john';
$pass = 'john';
$charset = 'utf8mb4';

$conn = new mysqli($host,$user,$pass,$db);

// Check connection
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$operation = isset($request->operation) ? $request->operation : "";
$courseid = isset($request->courseid) ? $request->courseid : "";
$raceid = isset($request->raceid) ? $request->raceid : "";
$fixtureid = isset($request->fixtureid) ? $request->fixtureid : "";
$fixtureyear = isset($request->fixtureyear) ? $request->fixtureyear : "";
$fixturedate = isset($request->fixturedate) ? $request->fixturedate : "";
$fixturetime = isset($request->fixturetime) ? $request->fixturetime : "";
// testing stand alone
// $operation = "getResults";


$resparr = array();

