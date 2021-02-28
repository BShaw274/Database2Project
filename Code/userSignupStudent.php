<?php
// Getting info posted from userSignupStudent.html form action line 9
$userEmail = $_POST['userEmail'];
$userPassword = $_POST['userPassword'];
$userName = $_POST['userName'];
$userPhone = $_POST['userPhone'];

//Attributes that are pertains to student
$grade = $_POST['grade'];
$parent_id = $_POST['parent_id'];

//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}


echo " Testing echo ID: ".$parent_id."  Email: ".$userEmail." Password: ".$userPassword." Name: ".$userName." Phone: ".$userPhone;

/*$query='SELECT DISTINCT parent_id, FROM parents where parent_id = ' .$parent_id;
$result = mysqli_query($dbConnection, $query) or die ('Query Failed: Parent ID does not exist');*/


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
$stmt->close();
//Actual Code (creating user first):

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
//Doing the above but just the ID into parent table, parent_id
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

?>
