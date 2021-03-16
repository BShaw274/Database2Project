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
</body>
</html>
