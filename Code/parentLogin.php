<!-- This is the home page for when parents log in. Update account information is currently represented on this page for updating themself and both their students -->

<html>
<head>
  <title>Parent's Homepage</title>
</head>
<body>
<h1>Sunny Youth Club</h1>
<h2>Parent's  Page </h2>

<h3>Update Account Information</h3>
<p>Fill in the fields with the updated information and sumbit to update your profile! </p>
<br>
<form action="selfUpdate.php" method="post">

Email:       <input type="email" id='email' name='userEmail'>
Password:    <input type="text" id='password' name='userPassword'>
Name:        <input type="text" id='name' name='userName'>
Phone Number:<input type="text" id='phoneNumber' name='userPhone'>
<input type="submit" value="Submit"><br>
</form>
<br>


<br>
<h3> Update Your students accounts</h3>
<!-- Create a table with the students Info for all the parents kids -->
<?php

//Opening Connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}


//Gid stands for get id of whos ever logged in
session_start();
$gid = $_SESSION['passedId'];


//Grab all student_id's tied to parent_id
$stmt = $dbConnection->prepare("SELECT student_id from students where parent_id=?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
//Use signed in id as parameter
$check = $stmt->bind_param("s", $gid);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
//Array of students is an array with an associative array that stores relevant student ids to parent
$arrayOfStudents = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

//In here this grabs all the user information about each student of the parent and then is displayed.
for($i=0; $i<count($arrayOfStudents); $i++){


//echo $arrayOfStudents[$i]['student_id'];

$stmt = $dbConnection->prepare("SELECT * from users where id=?");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($mysqli->error));
}
$check = $stmt->bind_param("s", $arrayOfStudents[$i]['student_id']);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}

$arrayOfStudentInfo= $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  echo "Student ".$i+1;
  echo " ";
  echo "ID: ".$arrayOfStudentInfo[0]['id'];
  echo " ";
  echo $arrayOfStudentInfo[0]['email'];
  echo " ";
  echo $arrayOfStudentInfo[0]['password'];
  echo " ";
  echo $arrayOfStudentInfo[0]['name'];
  echo " ";
  echo $arrayOfStudentInfo[0]['phone'];
  echo "";
  ?>
  <br>
  <?php

$stmt->close();
}

?>


<!-- Send parent to parentUpdateSTudent which will bring them to seperate webpage that lets them change their students information -->
<p> Please enter ID of student you want to change information for </p>
<form action="parentUpdateStudent.php" method="post">

ID:<input type="number" id='ID' name='userID' required>
New Email:       <input type="email" id='email' name='userEmail'>
New Password:    <input type="text" id='password' name='userPassword'>
New Name:        <input type="text" id='name' name='userName' >
New Phone Number:<input type="text" id='phoneNumber' name='userPhone' >
<input type="submit" value="Submit">
</form><br>

<!--Assigning a mentor to a meeting-->
<form action="ParentAssignMentor.php" method="post">
<table border="0">
<tr bgcolor="#cccccc">
  <td width="150">Enroll your student to be a Mentor</td>
</tr>

<tr>
  <td>Student Id</td>
  <td align="left"><input type="number" name="studentId" size="20" maxlength="30"/></td>
</tr>
<tr>
  <td>Meeting Id</td>
  <td align="left"><input type="number" name="meetingId" size="20" maxlength="30"/></td>
</tr>

<tr>
  <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
</tr>

</form>
</table>

<!--Assigning a mentee to a meeting-->
<form action="ParentAssignMentee.php" method="post">
  <table border="0">
  <tr bgcolor="#cccccc">
    <td width="150">Enroll your student to be a Mentee</td>
  </tr>

  <tr>
    <td>Student Id</td>
    <td align="left"><input type="number" name="studentId" size="20" maxlength="30"/></td>
  </tr>
  <tr>
    <td>Meeting Id</td>
    <td align="left"><input type="number" name="meetingId" size="20" maxlength="30"/></td>
  </tr>

  <tr>
    <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
  </tr>
  </form>
</table>
<!-- Meeting html stuff -->
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
