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

for ($a = 2019; $a < 2025; $a++) {
    $resparr = array();
    $sql = "SELECT fixtureId, fixtureYear FROM fixtures WHERE fixtureYear = $a";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['fixtureId'], "\t", $row['fixtureYear'], "\n";
            $url = "https://www.britishhorseracing.com/feeds/v3/fixtures/" . $row['fixtureYear'] . "/" . $row['fixtureId'] . "/races";
            $response = shell_exec('curl --location ' . $url);
            $resp = json_decode($response, true);
            $data = $resp["data"];
            // var_dump($data);
            for ($b = 0; $b < count($data); $b++) {
                $escName = mysqli_real_escape_string($conn, $data[$b]["raceName"]);
                $sql = "INSERT INTO `races`(`raceId`, `fixtureId`, `raceDate`, `raceTime`, `raceName`, `distanceText`, `distanceValue`, `goingText`) VALUES ({$data[$b]["raceId"]},{$row['fixtureId']},'{$data[$b]["raceDate"]}','{$data[$b]["raceTime"]}','{$escName}','{$data[$b]["distanceText"]}',{$data[$b]["distanceValue"]},'{$data[$b]["goingText"]}')";

                if ($conn->query($sql) === TRUE) {
                    // echo 'success' . "\n";
                } else {
                    echo "failed " . "\n" . $sql . "\n";
                }
            }
        }
    }
}

exit;

?>