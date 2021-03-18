

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


<h3>Meeting Information</h3>
<!-- Here I need to dispaly Meetings and infor for them -->
<?php
// Old code from ParentLogin that needs to be adapted
//Opening Connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Gid stands for get id of whos ever logged in
//session_start();
//$gid = $_SESSION['passedId'];

$stmt = $dbConnection->prepare("SELECT * from meetings");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
/*
$check = $stmt->bind_param("s", $arrayOfStudents[$i]['student_id']);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
*/
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
