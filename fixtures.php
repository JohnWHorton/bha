<?php
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
} else {
  echo "db ok" . "\n";
}

// $$curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://www.britishhorseracing.com/feeds/v3/fixtures?fields=courseId%2CcourseName%2CfixtureDate%2CfixtureType%2CfixtureSession%2CabandonedReasonCode%2ChighlightTitle&month=2&order=desc&page=1&per_page=1000&resultsAvailable=true&year=2023',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Cookie: __cf_bm=9g6amqqWR4veJtQaJkJz713dOMU3qGOEIq4PguBkiJY-1704697663-1-AbHHlHgsaXoyb6kBdG5hRUPuWzVEWrDgQlpjImJnyrX1DJewKFujy6ZrA0ozL+ArP9LWgyPTMLxJF/H/8EGrePo='
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
$response = shell_exec('curl --location "https://www.britishhorseracing.com/feeds/v3/fixtures?fields=courseId%2CcourseName%2CfixtureDate%2CfixtureType%2CfixtureSession%2CabandonedReasonCode%2ChighlightTitle&order=desc&page=1&per_page=9000&year=2024"');
$resp = json_decode($response, true);
$data = $resp["data"];

$resp = json_decode($response, true);

$data = $resp["data"];
$fixarr = array();
echo "COUNT IS " . count($data);
for ($i = 0; $i < count($data); $i++) {
  echo gettype($data[$i]) . "\n";
  if (gettype($data[$i]) !== 'array') continue;

  // try {
    $sql = "INSERT INTO `fixturesnew` (`fixtureId`, `fixtureYear`, `fixtureDate`, `courseName`, `numberOfRaces`, `courseId`, `fixtureType`, `fixtureSession`, `abandonedReasonCode`) 
    VALUES 
    (" . $data[$i]['fixtureId'] . "," . $data[$i]['fixtureYear'] . ",'" . $data[$i]['fixtureDate'] . "','" . $data[$i]['courseName'] . "'," . $data[$i]['numberOfRaces'] . "," . $data[$i]['courseId'] . ",'" . $data[$i]['fixtureType'] . "','" . $data[$i]['fixtureSession'] . "'," . $data[$i]['abandonedReasonCode'] . ")";
    if ($conn->query($sql) === TRUE) {
      echo 'success' . "\n" . $sql . "\n";
    } else {
      echo "failed " . "\n" . $sql . "\n";
    }
  // } catch (Exception $e) {
  //   echo 'Caught exception: ', $e->getMessage(), "\n";
  // }
}
// curl_close($curl);

// doRaces($conn, $fixarr);

exit;
