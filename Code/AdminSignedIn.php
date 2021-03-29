<html>
<head>
  <title>Admin Meeting Creation</title>
</head>
<body>
<h1>Admin Panel</h1>
<h2>Create new Meeting</h2>

<form action="AdminCreateMeeting.php" method="post">
<table border="0">
<tr bgcolor="#cccccc">
  <td width="150">Create a Meeting</td>
</tr>

<tr>
  <td>Meeting Name</td>
  <td align="left"><input type="text" name="meetingName" size="20" maxlength="30"/></td>
</tr>

<tr>
  <td>Meeting Date</td>
  <td align="left"><input type="date" name="meetingDate" size="20" maxlength="30"/></td>
</tr>

<tr>
  <td>Capacity</td>
  <td align="center"><input type="number" name="meetingCapacity" size="20" maxlength="10"/></td>
</tr>

<tr>
  <td>Announcement</td>
  <td align="left"><input type="text" name="meetingAnnouncement" size="20" maxlength="30"/></td>
</tr>

</table>

<!--All of the different timeslots-->
<p>Select Timeslot:</p>
<input type="radio" id="1-2pm" name="meetingTimeSlot" value="8">
<label for="1-2pm">Saturday 1-2pm</label><br>

<input type="radio" id="2-3pm" name="meetingTimeSlot" value="9">
<label for="2-3pm">Saturday 2-3pm</label><br>

<input type="radio" id="3-4pm" name="meetingTimeSlot" value="10">
<label for="3-4pm">Saturday 3-4pm</label><br>

<input type="radio" id="4-5pm" name="meetingTimeSlot" value="11">
<label for="4-5pm">Saturday 4-5pm</label><br>

<input type="radio" id="5-6pm" name="meetingTimeSlot" value="12">
<label for="5-6pm">Saturday 5-6pm</label><br>

<input type="radio" id="6-7pm" name="meetingTimeSlot" value="13">
<label for="6-7pm">Saturday 6-7pm</label><br>

<input type="radio" id="7-8pm" name="meetingTimeSlot" value="14">
<label for="7-8pm">Saturday 7-8pm</label><br>

<input type="radio" id="8-9pm" name="meetingTimeSlot" value="15">
<label for="8-9pm">Saturday 8-9pm</label><br>

<input type="radio" id="s1-2pm" name="meetingTimeSlot" value="16">
<label for="s1-2pm">Sunday 1-2pm</label><br>

<input type="radio" id="s2-3pm" name="meetingTimeSlot" value="17">
<label for="s2-3pm">Sunday 2-3pm</label><br>

<input type="radio" id="s3-4pm" name="meetingTimeSlot" value="18">
<label for="s3-4pm">Sunday 3-4pm</label><br>

<input type="radio" id="s4-5pm" name="meetingTimeSlot" value="19">
<label for="s4-5pm">Sunday 4-5pm</label><br>

<input type="radio" id="s5-6pm" name="meetingTimeSlot" value="20">
<label for="s5-6pm">Sunday 5-6pm</label><br>

<input type="radio" id="s6-7pm" name="meetingTimeSlot" value="21">
<label for="s6-7pm">Sunday 1-2pm</label><br>

<input type="radio" id="s7-8pm" name="meetingTimeSlot" value="22">
<label for="s7-8pm">Sunday 7-8pm</label><br>

<input type="radio" id="s8-9pm" name="meetingTimeSlot" value="23">
<label for="s8-9pm">Sunday 8-9pm</label>


<p>Select Group:</p>
<input type="radio" id="6" name="meetingGroupId" value="6">
<label for="6">6th Grade</label><br>
<input type="radio" id="7" name="meetingGroupId" value="7">
<label for="7">7th Grade</label><br>
<input type="radio" id="8" name="meetingGroupId" value="8">
<label for="8">8th Grade</label><br>
<input type="radio" id="9" name="meetingGroupId" value="9">
<label for="9">9th Grade</label>

<br>


<p>Create Recurring Meetings or a Singular Meeting</p>
<tr>
<input type="radio" id="recurring" name="recurringCheck" value="1">
<label for="recurring">Recurring</label><br>

<input type="radio" id="notRecurring" name="recurringCheck" value="0">
<label for="notRecurring">Singular Meeting</label><br>
</tr>

<br>

<tr>
  <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
</tr>
</form>

<h2>Assigning Mentors, Mentees, and Study Materials to Meetings</h2>

<!--Assigning a mentor to a meeting-->
<form action="AdminAssignMentor.php" method="post">
<table border="0">
<tr bgcolor="#cccccc">
  <td width="150">Enroll a Mentor</td>
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

</form>
</table>



<!--Assigning a mentee to a meeting-->
<form action="AdminAssignMentee.php" method="post">
<table border="0">
<tr bgcolor="#cccccc">
  <td width="150">Enroll a Mentee</td>
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
  <td colspan="2" align="center">
  <input type="radio" id="Single" name="MenteeSelect" value="single" required>
  <label for="single">Single</label>
  <input type="radio" id="Reoccuring" name="MenteeSelect" value="Reoccuring">
  <label for="Reoccuring">Reoccuring</label>
</td>
</tr>
<tr>
  <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
</tr>

</form>
</table>

<form action="AdminAssignStudyMaterials.php" method="post">
<table border="0">
<tr bgcolor="#cccccc">
  <td width="150">Add Study Materials to a Meeting</td>
</tr>

<tr>
  <td>Study Materials Id</td>
  <td align="left"><input type="number" name="studymatId" size="20" maxlength="30"/></td>
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

<a href="ThursdayRun.php">
  <p>Thursday run</p>
</a>


<?php
//Opening Connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Gid stands for get id of whos ever logged in
//session_start();
//$gid = $_SESSION['passedId'];

// prints out all meetings and their values
echo "<h2>Meetings List</h2>";
$sql = "SELECT * from meetings;";
$result = mysqli_query($dbConnection  , $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  echo "<table> ";
  echo  "<tr>
  <th>Meeting #</th>
  <th>Meet_ID</th>
  <th>Meeting Name</th>
  <th>Date</th>
  <th>Time Slot ID</th>
  <th>Capacity</th>
  <th>Announcement</th>
  <th>Group ID</th>
  </tr>";
  $i = 0;
  while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo " <td> ". ++$i. "</td> ";
    echo " <td> ".$row['meet_id']. "</td> ";
    echo " <td> ".$row['meet_name']. "</td> ";
    echo " ";
    echo " <td> ".$row['date']. "</td> ";
    echo " ";
    echo " <td> ".$row['time_slot_id']. "</td> ";
    echo " ";
    echo " <td> ".$row['capacity']. "</td> ";
    echo "";
    echo " <td> ".$row['announcement']. "</td> ";
    echo "";
    echo " <td> ".$row['group_id']. "</td> ";
  }
} else {
  echo "No meetings in database";
}
?>

</body>
</html>
