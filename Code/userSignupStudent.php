<?php
// Getting info posted from userSignupStudent.html form action line 10
$userEmail = $_POST['userEmail'];
$userPassword = $_POST['userPassword'];
$userName = $_POST['userName'];
$userPhone = $_POST['userPhone'];
$grade = $_POST['grade'];
$parent_id = $_POST['parent_id'];

//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}


//This query matches an existing parent id to the Id entered in the parent id field
$stmt = $dbConnection->prepare("SELECT parent_id FROM parents Where parent_id = ?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("s", $parent_id);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
//Array holding results of valid parents
$validParentCheck = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();

//Checks if there is any valid parents
if(!(empty($validParentCheck))){
  
//Uses Prepared Statements to prepare Query String, Uses bind_param to insert variables into the Query String
//then pushes the query to the Database with Execute()
//This specific prepared statesment inserts information about a student into the users table
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



//Inserting ID into students
//Doing the above but just the ID into student table
//Connection opened and Tested
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
$stmt = $dbConnection->prepare("INSERT INTO students (student_id, grade, parent_id) VALUES (?, ? , ?)");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("sss", $lastId, $grade, $parent_id);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}

$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}

$stmt->close();
$dbConnection->close();
}

//If parent id is not valid then let user know and dont create account
if(empty($validParentCheck)){
  echo "Not valid parent cannot sign up";
}
?>
