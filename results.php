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

$currentDate = date('Ymd');
$fromdate = date('Y-m-d', strtotime(' - 4 day', strtotime($currentDate)));
$todate = date('Y-m-d');
echo $fromdate;
$a = 2024;

// for ($a = 2024; $a < 2025; $a++) {
$resparr = array();
// $sql = "SELECT raceId, raceDate FROM races WHERE substring(raceDate,1,4) = {$a}";
$sql = "SELECT raceId, raceDate FROM races WHERE raceDate >= '{$fromdate}' and raceDate  < '{$todate}'";
// echo $sql . "\n";
$result = $conn->query($sql);
// echo "Number of races - " . $result-> num_rows . "\n";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // echo $sql . "\n";
        echo $row['raceId'], "\t", $row['raceDate'], "\n";
        $url = "https://www.britishhorseracing.com/feeds/v3/races/" . $a . "/" . $row['raceId'] . "/0/results";
        // echo $url . "\n";
        $response = shell_exec('curl --location ' . $url);
        $resp = json_decode($response, true);
        if (!$resp["data"])
            continue;
        $data = $resp["data"];
        // var_dump($data);

        for ($b = 0; $b < count($data); $b++) {
            $escName = mysqli_real_escape_string($conn, $data[$b]["racehorseName"]);
            $escJockeyName = mysqli_real_escape_string($conn, $data[$b]["jockeyName"]);
            $escTrainerName = mysqli_real_escape_string($conn, $data[$b]["trainerName"]);
            if (!$data[$b]["performanceFigure"] || $data[$b]["performanceFigure"] == null) {
                $data[$b]["performanceFigure"] = "0";
            }
            if ($data[$b]["resultFinishPos"] > "") {
                $sql = "INSERT INTO `results`(`raceId`, `yearOfRace`, `horseId`, `racehorseName`, `resultFinishPos`, `ageYear`, `weightValue`, `sexType`, `jockeyName`, `trainerName`, `bettingRatio`, `performanceFigure`, `raceCriteriaRaceType`, `finishTime`) VALUES ({$data[$b]["raceId"]},{$data[$b]["yearOfRace"]},{$data[$b]["horseId"]},'{$escName}',{$data[$b]["resultFinishPos"]},{$data[$b]["ageYear"]},{$data[$b]["weightValue"]},'{$data[$b]["sexType"]}','{$escJockeyName}','{$escTrainerName}','{$data[$b]["bettingRatio"]}',{$data[$b]["performanceFigure"]},'{$data[$b]["raceCriteriaRaceType"]}','{$data[$b]["finishTime"]}')";

                if ($conn->query($sql) === TRUE) {
                    // echo 'success' . "\n";
                } else {
                    // echo "failed " . "\n" . $sql . "\n";
                }
            }
        }
    }
}



exit;
