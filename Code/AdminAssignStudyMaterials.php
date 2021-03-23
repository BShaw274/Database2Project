<?php

// Getting info posted from AdminSignedIn.html form
$studymatId = $_POST['studymatId'];
$meetingId = $_POST['meetingId'];


//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}


$stmt = $dbConnection->prepare("INSERT INTO assign (meet_id, material_id) VALUES (?,?)");
if(false ===$stmt){
die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("ss", $meetingId, $studymatId);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}

$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}

echo "Study Materials were added to meeting";

$stmt->close();
$dbConnection->close();

?>