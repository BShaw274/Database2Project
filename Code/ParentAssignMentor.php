<?php

// Getting info posted from AdminSignedIn.html form
$studentId = $_POST['studentId'];
$meetingId = $_POST['meetingId'];


//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Actual Code:

// Style borrowed : https://stackoverflow.com/questions/2552545/mysqli-prepared-statements-error-reporting
//Uses Prepared Statements to prepare Query String, Uses bind_param to insert variables into the Query String e
//then pushes the query to the Database with Execute()

//inserting the students Id into mentors
$query = 'SELECT mentor_id FROM mentors WHERE mentor_id= ' . $studentId;
$result = mysqli_query($dbConnection, $query);

//checks if a student is already a mentor
if(mysqli_num_rows($result) > 0){
  //echo "Student already a mentor";
} else{
  $stmt = $dbConnection->prepare("INSERT INTO mentors (mentor_id) VALUES (?)");
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

//opening connection for enroll2
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



//verifies if the student grade is greater than the meeting grade level
if($studentGrade > $meetingGrade){
  //Inserting mentor Id and meeting Id into enroll2
  $stmt = $dbConnection->prepare("INSERT INTO enroll2 (meet_id, mentor_id) VALUES (?,?)");
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

  echo "Mentor was added to meeting";

  $stmt->close();
}else{
  echo "Student is not in high enough grade level to be a mentor";
}

$dbConnection->close();

?>
