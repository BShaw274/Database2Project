

<html>
<head>
  <title>Student's Homepage</title>
</head>
<body>
<h1>Sunny Youth Club</h1>
<h2>Student's  Page </h2>

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
session_start();
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
<h3>Meeting Information</h3>
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

$stmt = $dbConnection->prepare("SELECT * from meetings");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}

$meetingInfoArr= $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
//var_dump($meetingInfoArr);

  for($i=0; $i<10; $i++){
  echo "Meeting # ".$i+1;
  echo " ";
  echo " Meet_ID: ".$meetingInfoArr[$i]['meet_id'];
  echo " ";
  echo " Meeting Name: ".$meetingInfoArr[$i]['meet_name'];
  echo " ";
  echo " Date: ".$meetingInfoArr[$i]['date'];
  echo " ";
  echo " Time Slot ID: ".$meetingInfoArr[$i]['time_slot_id'];
  echo " ";
  echo " Capacity: ".$meetingInfoArr[$i]['capacity'];
  echo "";
  echo " Announcement: ".$meetingInfoArr[$i]['announcement'];
  echo "";
  echo " Group ID: ".$meetingInfoArr[$i]['group_id'];
  echo "";
  ?>
  <br>
  <br>
  <?php
}
//$stmt->close();
?>



</body>
</html>
