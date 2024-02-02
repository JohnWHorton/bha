<?php

$host = 'localhost';
$db = 'prhwgzau_bha';
$user = 'prhwgzau_john';
$pass = 'prhwgzau_jon';
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
$year = isset($request->year) ? $request->year : "";
$fixtureid = isset($request->fixtureid) ? $request->fixtureid : "";
$fixtureyear = isset($request->fixtureyear) ? $request->fixtureyear : "";
$fixturedate = isset($request->fixturedate) ? $request->fixturedate : "";
$fixturetime = isset($request->fixturetime) ? $request->fixturetime : "";
$emailaddr = isset($request->emailaddr) ? $request->emailaddr : "";
$subject = isset($request->subject) ? $request->subject : "";
$message = isset($request->message) ? $request->message : "";
// testing stand alone
// $operation = "getResults";
// $operation = "getRace";
// $raceid = 45591;
// $year = "2019";

$resparr = array();

if ($operation == "getRace") {
  $resparr = getRace($conn, $raceid, $year);
}

if ($operation == "message") {
  $resparr = message($conn, $emailaddr, $subject, $message);
}

echo json_encode($resparr);

function getRace($conn, $raceid, $year)
{
  $resparr = array();
  $sql = 'SELECT * FROM `races` WHERE `raceId` = ' . $raceid . ' AND substring(`raceDate`,1,4) = "' . $year . '"';

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      array_push($resparr, $row);
    }
  } else {
    array_push($resparr, [$sql]);
  }
  return $resparr;
}
function message($conn,$emailaddr, $subject, $message)
{
  $resparr = array();
  $sql = "INSERT INTO messages (emailaddr, subject, message)
			VALUES
			('$emailaddr','$subject', '$message')";

  if ($conn->query($sql) === true) {
    array_push($resparr, 'success', "added");
  } else {
    array_push($resparr, 'error', $sql);
  }

  return $resparr;
}