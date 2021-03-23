<?php
  //create short variable name
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
?>
<html>
<head>
  <title>Meetings List</title>
</head>
<body>
<?php
// Connects to the SQL Server
if($myconnection = mysqli_connect('localhost', 'root', '') ){
    // echo "Connected to Sql Server<br>";
} else  {  
    die ('Could not connect to server');

}

//Connects to database2
if($mydb = mysqli_select_db ($myconnection, 'db2')){
    // echo "Connection made to DB2<br>";
} else  {  
    die ('Could not connect to DB2');
}

// prints out meetings that need more mentees
echo "<h3>Meetings that need mentees</h3>";
$sql = "SELECT meet_id
FROM enroll
GROUP BY meet_id
having COUNT(mentee_id) < 3;";
$result = mysqli_query($myconnection , $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  echo "<p> ";
  while($row = mysqli_fetch_assoc($result)) {
    echo "id: " . $row["meet_id"]. "<br>";
  }
  echo " </p> ";
} else {
  echo "0 results";
}

// prints out a list of students that have to be alerted beccause of the cancled meeting
echo "<h3>Students that need to be alerted to closed meetings</h3>";
$sql = "SELECT name, email, meet_name
FROM users , meetings, enroll
WHERE users.id = enroll.mentee_id AND meetings.meet_id = enroll.meet_id AND enroll.meet_id IN (SELECT meet_id
FROM enroll
GROUP BY meet_id
having COUNT(mentee_id) < 3);";
$result = mysqli_query($myconnection , $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  echo "<p> ";
  while($row = mysqli_fetch_assoc($result)) {
    echo "Name: " . $row["name"]. "     Email: " . $row["email"]. "     Meeting Name: " . $row["meet_name"]. "<br>";
  }
  echo " </p> ";
} else {
  echo "0 results";
}

// Gets rid of the empty meeetings 
// $sql = "DELETE
// FROM enroll
// Where meet_id IN (SELECT meet_id
// FROM enroll
// GROUP BY meet_id
// having COUNT(mentee_id) < 3);";

// if (mysqli_query($myconnection, $sql)) {
//   echo "Empty meetings deleted successfully";
// } else {
//   echo "Error deleting empty metings: " . mysqli_error($myconnection);
// }

// prints out meetings that need more mentors
echo "<h3>Meetings that need mentors</h3>";
$sql = "SELECT meet_id
FROM enroll2
Where meet_id IN (SELECT meet_id From enroll)
GROUP BY meet_id
having COUNT(mentor_id) < 2;";
$result = mysqli_query($myconnection , $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  echo "<p> ";
  while($row = mysqli_fetch_assoc($result)) {
    echo "id: " . $row["meet_id"]. "<br>";
  }
  echo " </p> ";
} else {
  echo "0 results";
}

?>
</body>
</html>
