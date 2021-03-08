<?php
$userEmail = $_POST['userEmail'];
$userPassword = $_POST['userPassword'];


//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}

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
//testing to see what is entered works
echo "Email: ".$userEmail;
echo "Password: ".$userPassword;


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
  $exampleArray = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close(); // closing statement

  $id=$exampleArray[0]["id"];
  // example array is an array with an associative array inside of it so [0] selects associative array
  //and ["id"] selects the first value in the associative array aka the id we want
  //echo " |".$id."| ";  // checking id is grabbed correctly

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
    echo"log in as admin";
    //Admin log in page
    header("Location: sample2.html");
    // replace the above code The "sample2.html" with the login page for Admins
  }

//check if parent or student
elseif(empty($exampleArray1)){
  echo"check in Parent or student";
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
  //check if array is not empty which idicates its a parent id
  if(!(empty($exampleArray1))){
    echo"log in as parent";
    //Log in as parent
    header("Location: parentUpdate.html");
      // replace the above code The "sample2.html" with the login page for Parents
  }
  // if array was emnpty it must be a student id
  elseif(empty($exampleArray1)){
  //log in as child
  echo" Log in as student";
  header("Location:studentLogin.html");
  // replace the above code The "sample2.html" with the login page for Students
}
//the file redirection is the header(...) currently on lines 81 108 115

}//Parent elseif close
}//if$_post{==user password } close
}// if($stmt->num_rows > 0) { close

?>
