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

if ($operation == "upComing") {
  $resparr = addUser($conn, $email, $pswd);
}

function upComing($conn)
{
  $resparr = array();
  $sql = "SELECT * FROM rounds";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      array_push($resparr, $row);
    }
  } else {
    array_push($resparr, [$sql]);
  }

  $sql = "UPDATE `rounds` SET `no_of_predictions` = (SELECT count(*) FROM predictions where predictions.roundnumber = rounds.roundnumber), `prize_pool` = (SELECT IFNULL(sum(amount),0) FROM predictions where predictions.roundnumber = rounds.roundnumber)";
  $result = $conn->query($sql);

  return $resparr;
}
function games($conn, $roundnumber)
{
  $resparr = array();
  $sql = "SELECT * FROM games
          WHERE roundnumber = '$roundnumber'";

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
function addUser($conn, $email, $pswd)
{

  $resparr = array();

  $sql = "SELECT email from users WHERE email = '" . $email . "'";

  $result = $conn->query($sql);

  // echo ("num-rows" . $result->num_rows . "\n");
  if ($result->num_rows > 0) {
    array_push($resparr, 'error', 'exists');
    return $resparr;
  }

  $sql = "INSERT INTO users (email, pswd, datecreated, dateupdated)
			VALUES
			('$email',MD5('$pswd'), now(), now())";

  if ($conn->query($sql) === true) {
    array_push($resparr, 'success', "added");
  } else {
    array_push($resparr, 'error', $sql);
  }

  return $resparr;
}
function loginUser($conn, $email, $pswd)
{
  $resparr = array();
  $sql = "SELECT * FROM users WHERE email = '$email' AND pswd = MD5('$pswd')";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      array_push($resparr, $row);
    }
  } else {
    array_push($resparr, 'error', $sql);
    return $resparr;
  }

  return $resparr;
}
function transhistory($conn, $email)
{
  $resparr = array();

  $sql = "SELECT date_format(`date`, '%Y-%m-%d %H:%i:%S') as date, transtype, amount FROM `trans_history` WHERE `email` = '$email' ORDER BY date desc";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      array_push($resparr, $row);
    }
  }

  return $resparr;
}
function resetPassword($conn, $email, $pswd)
{
  $resparr = array();
  $sql = "UPDATE users SET pswd = MD5('$pswd'), dateupdated = now() WHERE email = '$email'";

  if ($conn->query($sql) === TRUE) {
    array_push($resparr, 'success', "reset");
  } else {
    array_push($resparr, 'error', $conn->error);
  }

  return $resparr;
}
function makeprediction($conn, $email, $roundnumber, $predictthisjson, $amount)
{
  $resparr = array();
  $sql = "INSERT INTO predictions (email, roundnumber, predictthisjson, amount, datecreated) VALUES ('$email','$roundnumber','$predictthisjson', $amount, now())";

  if ($conn->query($sql) === true) {
    array_push($resparr, 'success', "added");
  } else {
    array_push($resparr, 'error', $sql);
  }

  return $resparr;
}
function getPredictions($conn, $email, $roundnumber)
{
  $resparr = array();
  $sql = "SELECT * FROM predictions WHERE email = '$email' AND roundnumber = $roundnumber ORDER BY id desc";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      array_push($resparr, $row);
      // array_push($resparr, $sql);
    }
  } else {
    array_push($resparr, []);
  }

  return $resparr;
}
function getResults($conn, $email, $roundnumber)
{
  $resparr = array();

  $sql = "SELECT * FROM results WHERE email = '$email' AND roundnumber = $roundnumber ORDER BY predictionid, id";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      array_push($resparr, $row);
    }
  } else {
    array_push($resparr, []);
  }

  return $resparr;
}
function deposit($conn, $email, $amount)
{

  $resparr = array();

  $sql = "INSERT INTO deposits (email, amount, datecreated) VALUES ('$email','$amount', now())";

  if ($conn->query($sql) === true) {
    array_push($resparr, 'success', "success");
  } else {
    array_push($resparr, 'error', $sql);
  }

  return $resparr;
}
function withdrawalrequest($conn, $email, $amount)
{

  $resparr = array();

  $sql = "INSERT INTO withdrawalrequests (email, amount, datecreated) VALUES ('$email','$amount', now())";

  if ($conn->query($sql) === true) {
    array_push($resparr, 'success', "success");
  } else {
    array_push($resparr, 'error', $sql);
  }

  return $resparr;
}
function withdrawalscompleted($conn, $email, $amount)
{

  $resparr = array();

  $sql = "INSERT INTO withdrawalscompleted (email, amount, datecreated) VALUES ('$email','$amount',  now())";

  if ($conn->query($sql) === true) {
    array_push($resparr, 'success', "success");
  } else {
    array_push($resparr, 'error', $sql);
  }

  return $resparr;
}
function winnings($conn, $email, $amount)
{

  $resparr = array();

  $sql = "INSERT INTO winnings (email, amount, datecreated) VALUES ('$email','$amount',  now())";

  if ($conn->query($sql) === true) {
    array_push($resparr, 'success', "success");
  } else {
    array_push($resparr, 'error', $sql);
  }

  return $resparr;
}

?>