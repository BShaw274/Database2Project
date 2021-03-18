<?php

// Getting info posted from StudentLogin.php form\
$meetingId = $_POST['meetingId'];

session_start();
$studentId = $_SESSION['passedId'];

//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Actual Code:

// Style borrowed : https://stackoverflow.com/questions/2552545/mysqli-prepared-statements-error-reporting
//Uses Prepared Statements to prepare Query String, Uses bind_param to insert variables into the Query String e
//then pushes the query to the Database with Execute()

//inserting the students Id into mentees
$query = 'SELECT mentee_id FROM mentees WHERE mentee_id= ' . $studentId;
$result = mysqli_query($dbConnection, $query);

//checking if the id is already in the mentees
if(mysqli_num_rows($result) > 0){
  echo "Student already a mentee";
} else{
  echo "The student is not already a mentee";
  $stmt = $dbConnection->prepare("INSERT INTO mentees (mentee_id) VALUES (?)");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("s", $studentId);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
  //Outputs user info based on what was inputted, Including ID which parent must keep track of
  echo " New Records Created successfully with Info:: ID: ".$studentId;
  //Closes stmt and connection
  $stmt->close();
  $dbConnection->close();
}


//Inserting mentor Id and meeting Id into enroll

$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}


//grabbing the grade levels for comparison
$query = 'SELECT grade FROM students WHERE student_id= ' . $studentId;
$studentGradeLevel = mysqli_query($dbConnection, $query);
$query = 'SELECT group_id FROM meetings WHERE meet_id=' . $meetingId;
$meetingGradeLevel = mysqli_query($dbConnection, $query);

while ($row = mysqli_fetch_array ($studentGradeLevel, MYSQLI_ASSOC)) {
  $studentGrade = $row["grade"];
  //echo $studentGrade;
}

while ($row = mysqli_fetch_array ($meetingGradeLevel, MYSQLI_ASSOC)) {
  $meetingGrade = $row["group_id"];
  //echo $meetingGrade;
}




//verifies if the student grade is the same as the meeting grade level
if($studentGrade == $meetingGrade){

  $stmt = $dbConnection->prepare("INSERT INTO enroll (meet_id, mentee_id) VALUES (?,?)");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("ss", $meetingId, $studentId);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }

  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }

  echo "Mentee was added to meeting";

  $stmt->close();
} else{
  echo "Mentee needs to be the correct grade level";
}


$dbConnection->close();


?>
