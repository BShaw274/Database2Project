<?php

// Getting info posted from userSignupParent.html form action line 9
$userEmail = $_POST['userEmail'];
$userPassword = $_POST['userPassword'];
$userName = $_POST['userName'];
$userPhone = $_POST['userPhone'];

//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Actual Code:

// Style borrowed : https://stackoverflow.com/questions/2552545/mysqli-prepared-statements-error-reporting
//Uses Prepared Statements to prepare Query String, Uses bind_param to insert variables into the Query String e
//then pushes the query to the Database with Execute()
$stmt = $dbConnection->prepare("INSERT INTO users (email, password, name, phone) VALUES (?, ?, ?, ?)");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("ssss", $userEmail, $userPassword, $userName,$userPhone);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
//Here im getting the ID of the User account previously created
//This allows me to open another connection and push the ID into Parent/Student/Admin table as necessary
$lastId = $stmt->insert_id;
//Outputs user info based on what was inputted, Including ID which parent must keep track of
echo " New Records Created successfully with Info:: ID: ".$stmt->insert_id."  Email: ".$userEmail." Password: ".$userPassword." Name: ".$userName." Phone: ".$userPhone;
//Closes stmt and connection
$stmt->close();
$dbConnection->close();



//Inserting ID into parents
//Doing the above but just the ID into admin table, admin_id
//Connection opened and Tested
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
$stmt = $dbConnection->prepare("INSERT INTO admins (admin_id) VALUES (?)");
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
