<?php
//File purpose: Updating information in database. Ran off of click of button for form.
$userID = $_POST['userID'];
$userEmail = $_POST['userEmail'];
$userPassword = $_POST['userPassword'];
$userName = $_POST['userName'];
$userPhone = $_POST['userPhone'];

//Opening Connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}


session_start();
$gid = $_SESSION['passedId'];

//First query to get id of all students who belong to parent
$stmt = $dbConnection->prepare("SELECT student_id from students where parent_id=? AND student_id=?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("ss", $gid,$userID);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
//Stores results of students that are tied to parent
$arrayIsStudentOf = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
 $stmt->close();
 if(!(empty($arrayIsStudentOf))){
  //echo "in here";

//These if checks all make sure that fields are not empty and if they are not empty then change the information regarding to that field
  if (!empty($userEmail))
  {
  $stmt = $dbConnection->prepare("UPDATE users SET email = ? Where id = ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("ss", $userEmail, $userID);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
    $stmt->close();
    echo " Echoing Email update: ".$userEmail;
  }//Email Update Done

  //Password change
  if (!empty($userPassword))
  {
  $stmt = $dbConnection->prepare("UPDATE users SET password = ? Where id = ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("ss", $userPassword, $userID);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
    $stmt->close();
    echo " Echoing Password update: ".$userPassword;
  }//Password Update Done

  //Update -- Name
  if (!empty($userName))
  {
  $stmt = $dbConnection->prepare("UPDATE users SET name = ? Where id = ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("ss", $userName, $userID);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
    $stmt->close();
    echo " Echoing Name update: ".$userName;
  }//Name Update Done

  //Update -- Phone
  if (!empty($userPhone))
  {
  $stmt = $dbConnection->prepare("UPDATE users SET phone = ? Where id = ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("ss", $userPhone, $userID);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
    $stmt->close();
    echo " Echoing Phone update: ".$userPhone;
  }//Phone Update Done

 }

//This will tell you if the student ID you entered is wrong and will not change any information
if(empty($arrayIsStudentOf)){
  echo "The ID you entered is not of a valid student that is your student.";
}




?>
