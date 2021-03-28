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

//creates name for target folder
$dir = "ReportFolder";
$direxists = false;
// scans for target folder
$scan = scandir('.');
foreach($scan as $file) {
   if ($file == $dir  ) {
      $direxists = true;
    }
}
// If target folder does not exist, make target folder
if(!$direxists){
    mkdir($dir);
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
$sql = "SELECT name, email, meet_name, \"Mentee\"
FROM users , meetings, enroll
WHERE DATEDIFF( date, CURRENT_TIMESTAMP) < 3 AND users.id = enroll.mentee_id AND meetings.meet_id = enroll.meet_id AND enroll.meet_id IN (SELECT meet_id
FROM enroll
GROUP BY meet_id
having COUNT(mentee_id) < 3) 
UNION
SELECT name, email, meet_name, \"Mentor\"
FROM users , meetings, enroll2 
WHERE DATEDIFF( date, CURRENT_TIMESTAMP) < 3 AND users.id = enroll2.mentor_id AND meetings.meet_id = enroll2.meet_id AND enroll2.meet_id IN (SELECT meet_id
FROM enroll2
GROUP BY meet_id
having COUNT(mentor_id) < 2);";
$result = mysqli_query($myconnection , $sql);

// opens file to write into to store list of students who need meetings
$myfile = fopen($dir . "/AlertStudents" .date("d-m-y") .".txt", "w");

if (mysqli_num_rows($result)) {
  // output data of each row
  echo "<p> ";
  while($row = mysqli_fetch_assoc($result)) {
    $text =  end($row) . "-  Name: " . $row["name"]. "     Email: " . $row["email"]. "     Meeting Name: " . $row["meet_name"] ;
    echo $text. "<br>" ;
    fwrite($myfile, $text . "\n");
  }
  echo " </p> ";
} else {
  echo "0 results";
}

// close file 
fclose($myfile);

// Gets rid of the empty meeetings 
$sql = "DELETE
FROM meetings
Where DATEDIFF( date, CURRENT_TIMESTAMP) < 3 AND  meet_id IN (SELECT meet_id
FROM enroll
GROUP BY meet_id
having COUNT(mentee_id) < 3 
Union
SELECT meet_id
FROM enroll2
GROUP BY meet_id
having COUNT(mentor_id) < 2) );";
// checks database deleted meetings correctlty
if (mysqli_query($myconnection, $sql)) {
  echo "<br>Empty meetings deleted successfully<br>";
} else {
  echo "Error deleting empty meetings: " . mysqli_error($myconnection);
}

// prints out meetings that need more mentors : runs after meetings to 
echo "<h3>Meetings that need mentors</h3>";
$sql = "SELECT meet_id
FROM enroll2
Where  meet_id IN (SELECT meet_id From enroll)
GROUP BY meet_id
having COUNT(mentor_id) < 2;";
$result = mysqli_query($myconnection , $sql);

// opens file to write into to store list of students who need meetings
$myfile = fopen($dir . "/MeetingsWithoutMentors" .date("d-m-y") .".txt", "w");

if (mysqli_num_rows($result)) {
  // output data of each row
  echo "<p> ";
  while($row = mysqli_fetch_assoc($result)) {
    $text = "id: " . $row["meet_id"];
    echo $text. "<br>" ;
    fwrite($myfile, $text . "\n");
  }
  echo " </p> ";
} else {
  echo "0 results";
}

fclose($myfile);

?>
</body>
</html>
