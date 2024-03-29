<?php
//This file is for the login of any user. This verifies that you are a valid user and determines what login page you go to, IE: Admin/Student/Parent

//Information entered by user trying to login
$userEmail = $_POST['userEmail'];
$userPassword = $_POST['userPassword'];


//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}

//This verifies that the user logging in entered the correct email
$stmt = $dbConnection->prepare("SELECT email, password FROM users Where email = ?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("s", $userEmail);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}

//Got help from here: https://codeshack.io/secure-login-system-php-mysql/
//Stores the result so we can check if account exists in database
$stmt->store_result();
//Checks if sql statement exists and has a row. If it has a row then call bind_result and fetch.
if($stmt->num_rows > 0) {
  //Binds result set from columns in database to variables. Here we bind email and password to userEmail and UserPassword
	$stmt->bind_result($userEmail, $userPassword);

	$stmt->fetch();

  $stmt->close();
//If the user enters a valid password associated with email then they are granted access to website
if ($_POST['userPassword'] === $userPassword) {
  $stmt = $dbConnection->prepare("SELECT id FROM users Where email = ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("s", $userEmail);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
  //exampleArray stores results of id that is tied to the user logging in.
  $exampleArray = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close(); // closing statement


  $id=$exampleArray[0]["id"];
  // example array is an array with an associative array inside of it so [0] selects associative array
  //and ["id"] selects the first value in the associative array a.k.a the id we want

  // Setting a session variable as id so that this id may be grabbed by other php files
  session_start();
  $_SESSION['passedId'] = $id;


  //Check if id is an admin id
  $stmt = $dbConnection->prepare("SELECT admin_id FROM admins Where admin_id = ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("s", $id);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }

  $exampleArray1 = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  if(!(empty($exampleArray1))){
    //echo"log in as admin";
    //Admin log in page
    header("Location: AdminSignedIn.php");
  }

//check if parent or student
elseif(empty($exampleArray1)){
  //echo"check in Parent or student";
  //check if id is a parent ID or if we default to student
  $stmt = $dbConnection->prepare("SELECT parent_id FROM parents Where parent_id = ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("s", $id);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }

  $exampleArray1 = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  //check if array is not empty which indicates its a parent id
  if(!(empty($exampleArray1))){
    //echo"log in as parent";
    //Log in as parent
    header("Location: parentLogin.php");

  }
  // if array was empty it must be a student id
  elseif(empty($exampleArray1)){
  //log in as child
  //echo" Log in as student";
  header("Location:studentLogin.php");
}


}//Parent elseif close
}//if$_post{==user password } close
else{
echo "Invalid information entered";
}
}// if($stmt->num_rows > 0) { close
  else{
  echo "Invalid information entered";
  }




?>
