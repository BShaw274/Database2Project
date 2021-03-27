<?php

// Getting info posted from StudentLogin.php form\
$meetingId = $_POST['meetingId'];
$MentorRadioVal= $_POST['MentorSelect'];
session_start();
$studentId = $_SESSION['passedId'];

//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Actual Code:
// checks to see if the student is in the meeting
$sql = "SELECT meet_id
FROM enroll2
WHERE meet_id = " . $meetingId. " AND mentor_id = " . $studentId . ";";
$result = mysqli_query($dbConnection , $sql);
if (!mysqli_num_rows($result)) {
    // if student is not in the meeting, close out
    Echo "Student is not in this meeting";
    $dbConnection->close();
    die();
}

// checks to see if the meeting is due
$sql = "SELECT meet_id
FROM meetings
Where DATEDIFF( date, CURRENT_TIMESTAMP) < 3 AND meet_id = " . $meetingId. ";";
$result = mysqli_query($dbConnection , $sql);
if (mysqli_num_rows($result)) {
    // if meeting is due, close out
    Echo "It is to late to cancel meeting";
    $dbConnection->close();
    die();
}

//removes student from group
if ($MentorRadioVal == 'single'){
    // removes student from single group
    $sql = "DELETE FROM enroll2  WHERE  meet_id = " .$meetingId ." AND mentor_id = " . $studentId . ";";
    $result = mysqli_query($dbConnection , $sql);
    if ($result) {
        echo "Student has been removed <br>";
    }
}else{
    // removes student from multiiple group
    // finds name of meeting stdent is trying to drop from
    $sql = "SELECT meet_name
    FROM meetings
    WHERE meet_id = " . $meetingId.";";
    $result = mysqli_query($dbConnection , $sql);
    if (mysqli_num_rows($result)) {
        // finds id of meeting
        $row = mysqli_fetch_assoc($result);
        $sql2 = "SELECT meet_id
        FROM meetings
        WHERE meet_name = \"" . $row["meet_name"]."\";";
        $result2 = mysqli_query($dbConnection , $sql2);
        // finds meeting name of meeting
        while($row2 = mysqli_fetch_assoc($result2)) {
            // drops meeting and uid from enroll
            $sql3 = "DELETE FROM enroll2  WHERE  meet_id = " .$row2["meet_id"] ." AND mentor_id = " . $studentId . ";";
            $result3 = mysqli_query($dbConnection , $sql3);
            if($result3){
                Echo "Student removed from meeting " .$row2["meet_id"] . " <br>";
            }
        }
            
      
    } else {
        echo "Student not enrolled as mentee in this class <br>";
    }  
}

// checks to see if the student is still a mentee
$query = 'SELECT mentor_id FROM enroll2 WHERE mentor_id= ' . $studentId;
$result = mysqli_query($dbConnection, $query);

if(mysqli_num_rows($result) > 0){
    echo "Student is still a mentee <br>";
} else {
    // if student is no longer a mentee in any meetings then remove them from the mentee list
    $stmt = $dbConnection->prepare("DELETE from mentors  WHERE  mentor_id = (?)");
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
    echo "Student has been removed from mentees <br>";
    $stmt->close();
}
$dbConnection->close();

?>