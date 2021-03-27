

<html>
<head>
  <title>Student's Homepage</title>
</head>
<body>
<h1>Sunny Youth Club</h1>
<h2>Student's  Page </h2>

<?php
$myconnection = new mysqli('localhost', 'root', '', 'db2');
if ($myconnection->connect_error) {
  die("Connection failed: " . $myconnection->connect_error);
}
session_start();
$gid = $_SESSION['passedId'];

// prints out student information
echo "<h3>Student Information</h3>";
$sql = "SELECT email, password, name, phone
FROM users
WHERE id = " .$gid.";";
$result = mysqli_query($myconnection , $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  echo "<p> ";
  while($row = mysqli_fetch_assoc($result)) {
    echo "Email: " . $row["email"]. "<br>";
    echo "Password: " . $row["password"]. "<br>" ;
    echo "Name: " . $row["name"]. "<br>";
    echo "Phone: " . $row["phone"]. "<br>";
  }
  echo " </p> ";
} else {
  echo "0 results";
}


?>

<h3>Update Account Information</h3>
<p>Fill in the fields with the updated information and sumbit to update your profile! </p>
<br>

<form action="selfUpdate.php" method="post">

Email:       <input type="email" id='email' name='userEmail'>
Password:    <input type="text" id='password' name='userPassword'>
Name:        <input type="text" id='name' name='userName'>
Phone Number:<input type="text" id='phoneNumber' name='userPhone'>

<input type="submit"><br>
</form>

<h3>Signup Information</h3>

<!--Assigning a mentor to a meeting-->
<form action="StudentAssignMentor.php" method="post">
<table border="0">
<tr bgcolor="#cccccc">
  <td width="150">Sign up as Mentor</td>
</tr>
<tr>
  <td>Meeting Id</td>
  <td align="left"><input type="number" name="meetingId" size="20" maxlength="30"/></td>
</tr>
<tr>
  <td colspan="2" align="center">
  <input type="radio" id="Single" name="MentorSelect" value="single" required>
  <label for="single">Single</label>
  <input type="radio" id="Reoccuring" name="MentorSelect" value="Reoccuring">
  <label for="Reoccuring">Reoccuring</label>
</td>
</tr>
<tr>
  <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
</tr>
</table>
</form>

<!--Assigning a mentee to a meeting-->
<form action="StudentAssignMentee.php" method="post">
  <table border="0">
  <tr bgcolor="#cccccc">
    <td width="150">Sign up as Mentee</td>
  </tr>
  <tr>
    <td>Meeting Id</td>
    <td align="left"><input type="number" name="meetingId" size="20" maxlength="30"/></td>
  </tr>

<tr>
  <td colspan="2" align="center">
    <input type="radio" id="Single" name="MenteeSelect" value="single" required>
  <label for="Single">Single</label>
  <input type="radio" id="Reoccuring" name="MenteeSelect" value="Reoccuring">
  <label for="Reoccuring">Reoccuring</label>
</td>
</tr>

  <tr>

    <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
  </tr>
</table>
  </form>

<h3>Signout Information</h3>
<!--Removing a mentor from a meeting-->
<form action="StudentRemoveMentor.php" method="post">
<table border="0">
<tr bgcolor="#cccccc">
  <td width="150">Stop Mentorship</td>
</tr>
<tr>
  <td>Meeting Id</td>
  <td align="left"><input type="number" name="meetingId" size="20" maxlength="30"/></td>
</tr>
<tr>
  <td colspan="2" align="center">
  <input type="radio" id="Single" name="MentorSelect" value="single" required>
  <label for="single">Single</label>
  <input type="radio" id="Reoccuring" name="MentorSelect" value="Reoccuring">
  <label for="Reoccuring">Reoccuring</label>
</td>
</tr>
<tr>
  <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
</tr>
</table>
</form>

<!--Removing a mentee from a meeting-->
<form action="StudentRemoveMentee.php" method="post">
  <table border="0">
  <tr bgcolor="#cccccc">
    <td width="150">Stop Menteeship</td>
  </tr>
  <tr>
    <td>Meeting Id</td>
    <td align="left"><input type="number" name="meetingId" size="20" maxlength="30"/></td>
  </tr>

<tr>
  <td colspan="2" align="center">
    <input type="radio" id="Single" name="MenteeSelect" value="single" required>
  <label for="Single">Single</label>
  <input type="radio" id="Reoccuring" name="MenteeSelect" value="Reoccuring">
  <label for="Reoccuring">Reoccuring</label>
</td>
</tr>

  <tr>

    <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
  </tr>
</table>
  </form>

<h3>Participants of Mentor Meetings </h3>
<?php
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
$gid = $_SESSION['passedId'];
//Gid stands for get id of whos ever logged in

//Get the Meeting ID of meetings the logged in user is Mentoring for
$stmt = $dbConnection->prepare("SELECT meet_id FROM enroll2 Where mentor_id = ?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("s", $gid);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}

//Gets all meetings Id incase you mentor multiple meetings
$MeetIdResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
 $stmt->close();
 if(!(empty($MeetIdResult))){

for($j=0; $j<count($MeetIdResult);$j++){
  //For loop will go through and print out mentors and mentees information for 1 specific meetings
  // before incrementing and checknig the next meeting untill there are no more meet_id's in $MeetIdResult
  ?><br><?php
  echo "Meet id: ".$MeetIdResult[$j]["meet_id"];
  $stmt = $dbConnection->prepare("SELECT mentor_id FROM enroll2 Where meet_id = ? AND mentor_id != ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("ss", $MeetIdResult[$j]["meet_id"], $gid);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
  $MentorIdResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  //Gets mentor ID's for meeting we are checking so we can then query the info of those mentors
  if(!(empty($MentorIdResult))){
      ?><br><?php
    echo"Mentors:";

    for($i=0; $i<count($MentorIdResult); $i++){

      $stmt = $dbConnection->prepare("SELECT name, email  FROM users Where id= ?");
      if(false ===$stmt){
        die('prepare() failed: ' . htmlspecialchars($mysqli->error));
      }
      $check = $stmt->bind_param("s", $MentorIdResult[$i]["mentor_id"]);
      if(false ===$check){
        die('bind_param() failed: ' . htmlspecialchars($stmt->error));
      }
      $check = $stmt->execute();
      if(false ===$check){
        die('execute() failed: ' . htmlspecialchars($stmt->error));
      }
      $MentorInfoResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
      //var_dump($MentorInfoResult);
      ?><br><?php
      for($k=0; $k<count($MentorInfoResult);$k++){
        //echo"Inside Test";
        echo" Name: ".$MentorInfoResult[$k]["name"].", Email: ".$MentorInfoResult[$k]["email"];
      }
    }
    //The above then prints the Name and Email for the mentor id's obtained in $MentorIdResult
  }//Mentor ID Result isempty Close
// We now swap gears and check mentees for the same Meeting ID
?><br><?php
echo"Mentees: ";
$stmt = $dbConnection->prepare("SELECT mentee_id FROM enroll Where meet_id = ? AND mentee_id != ?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("ss", $MeetIdResult[$j]["meet_id"],$gid);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
$MenteeIdResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
//Gets the Mentee id's so that we may print out mentee info that are contained in $MentorIdResult
  if(!(empty($MenteeIdResult))){ //Checks that there are mentee IDs
    for($i=0; $i<count($MenteeIdResult); $i++){

      $stmt = $dbConnection->prepare("SELECT name, email  FROM users Where id= ?");
      if(false ===$stmt){
        die('prepare() failed: ' . htmlspecialchars($mysqli->error));
      }
      $check = $stmt->bind_param("s", $MenteeIdResult[$i]["mentee_id"]);
      if(false ===$check){
        die('bind_param() failed: ' . htmlspecialchars($stmt->error));
      }
      $check = $stmt->execute();
      if(false ===$check){
        die('execute() failed: ' . htmlspecialchars($stmt->error));
      }
      $MenteeInfoResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
      ?><br><?php
      for($k=0; $k<count($MenteeInfoResult);$k++){
        echo" Name: ".$MenteeInfoResult[$k]["name"].", Email: ".$MenteeInfoResult[$k]["email"];
      }
    }
  // The above prints out the name and Email of the mentee id's contained in $MentorIdResult
  //by querying and storing that info in $MenteeInfoResult then printing in the for loop
  }//Mentee ID Result isempty Close
}// closes for loop cheching all meetings they are a mentor off
//now get mentee id for same meeting
} //this is the closing bracked for checking if $MeetIdResult is not Empty
?>
<h3>Study Materials</h3>
<!-- Here I need to dispaly Meetings and infor for them -->
<?php
// Old code from ParentLogin that needs to be adapted
//Opening Connection
/*
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
*/ //No need for 2 connections


$currentDate = date('Y-m-d');
$currentWeek = new dateTime($currentDate);
$previousWeek = new dateTime($currentDate);

$currentWeek->modify('+1 week');
$previousWeek->modify('-1 week');


$query  = "SELECT material_id FROM assign
INNER JOIN meetings ON assign.meet_id = meetings.meet_id
WHERE meetings.date >= '{$previousWeek->format('Y-m-d')}' AND meetings.date <= '{$currentWeek->format('Y-m-d')}'";
$results = mysqli_query($dbConnection, $query);

$materialIds = [];
while ($row = mysqli_fetch_array ($results, MYSQLI_ASSOC)) {
  //$materialIds += $row['material_id'];
  //echo $meetingGrade;
  array_push($materialIds, $row['material_id']);
}

for ($i = 0; $i < mysqli_num_rows($results); $i++){
  $query = "SELECT * FROM material WHERE material_id = {$materialIds[$i]}";
  $result = mysqli_query($dbConnection, $query);
  while($row = mysqli_fetch_assoc($result)){
    echo "Material's Id: ". $row['material_id'];
    echo " ";
    echo "Title: ". $row['title'];
    echo " ";
    echo "Author: ". $row['author'];
    echo " ";
    echo "Type: ". $row['type'];
    echo " ";
    echo "Url: ". $row['url'];
    echo " ";
    echo "Assigned Date: ". $row['assigned_date'];
    echo " ";
    echo "Notes: ". $row['notes'];
    echo "<br>";
  }
}


?>

<h4>Meeting Information</h4>
<?php
$sql = "SELECT * from meetings";
$result = mysqli_query($dbConnection , $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  echo "<p> ";
  $i = 1;
  while($row = mysqli_fetch_assoc($result)) {
    echo "Meeting # ".$i ++;
    echo " ";
    echo " Meet_ID: ". $row['meet_id'];
    echo " ";
    echo " Meeting Name: ".$row['meet_name'];
    echo " ";
    echo " Date: ".$row['date'];
    echo " ";
    echo " Time Slot ID: ".$row['time_slot_id'];
    echo " ";
    echo " Capacity: ".$row['capacity'];
    echo "";
    echo " Announcement: ".$row['announcement'];
    echo "";
    echo " Group ID: ".$row['group_id'];
    echo "";
    echo "<br>";

  }
  echo " </p> ";
} else {
  echo "0 results";
}


?>



</body>
</html>
