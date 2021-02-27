<?php

$uid = "222222";
$userEmail = "mike@tes.com";
$userPassword ="testing123";
$userName ="Mike-Test";
$userPhone ="508-966-2221";


$dbConnection = new mysqli('localhost', 'root', '', 'database2');

if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}


// Style borrowed : https://stackoverflow.com/questions/2552545/mysqli-prepared-statements-error-reporting
$stmt = $dbConnection->prepare("INSERT INTO users (id, email, password, name, phone) VALUES (?, ?, ?, ?, ?)");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("sssss", $uid, $userEmail, $userPassword, $userName,$userPhone);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}

$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
$lastId = $stmt->insert_id;
echo "last ID: ".$lastId."Tested|||";

echo " New Records Created successfully with ID:".$uid." Email: ".$userEmail;

$stmt->close();
$dbConnection->close();


// Inserting ID into parents
$dbConnection = new mysqli('localhost', 'root', '', 'database2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Connection opened and Tested

$stmt = $dbConnection->prepare("INSERT INTO parents (parent_id) VALUES (?)");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("s", $lastId);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}

$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}

$stmt->close();
$dbConnection->close();


?>
