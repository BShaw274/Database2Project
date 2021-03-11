<?php

//This code is used for StudentLogin.html and parentUpdateStudent.php so users can update information. This file is ran off the click of a submit button.



$userEmail = $_POST['userEmail'];
$userPassword = $_POST['userPassword'];
$userName = $_POST['userName'];
$userPhone = $_POST['userPhone'];


//Opening Connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}

//get/Check what account we are updating

//getting the id from login page
session_start();
$gid = $_SESSION['passedId'];

//test echos
echo " Echoing Passed ID: ".$gid;
echo " Echoing Email update: ".$userEmail;
//echo " Echoing Password update: ".$userPassword;
echo " Echoing Name update: ".$userName;
//echo " Echoing Phone update: ".$userPhone;

//update the info
//Update -- Email

if (!empty($userEmail))

{
$stmt = $dbConnection->prepare("UPDATE users SET email = ? Where id = ?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("ss", $userEmail, $gid);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
  $stmt->close();
}//Email Update Done


//Update -- Password

if (!empty($userPassword))

{
$stmt = $dbConnection->prepare("UPDATE users SET password = ? Where id = ?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("ss", $userPassword, $gid);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
  $stmt->close();
}//Password Update Done

//Update -- Name

if (!empty($userName))

{
$stmt = $dbConnection->prepare("UPDATE users SET name = ? Where id = ?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("ss", $userName, $gid);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
  $stmt->close();
}//Name Update Done

//Update -- Phone

if (!empty($userPhone))

{
$stmt = $dbConnection->prepare("UPDATE users SET phone = ? Where id = ?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("ss", $userPhone, $gid);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
  $stmt->close();
}//Phone Update Done



//refreshs the page by gonig to same page, commented out for testing purposes

  //header("Location:studentLogin.html");

 ?>
