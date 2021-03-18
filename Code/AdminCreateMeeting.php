<?php

// Getting info posted from AdminSignedIn.html form
$meetingName = $_POST['meetingName'];
$meetingDate = $_POST['meetingDate'];
$meetingCapacity = $_POST['meetingCapacity'];
$meetingAnnouncement = $_POST['meetingAnnouncement'];
$meetingTimeSlot = $_POST['meetingTimeSlot'];
$meetingGroupId = $_POST['meetingGroupId'];

//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Actual Code:

// Style borrowed : https://stackoverflow.com/questions/2552545/mysqli-prepared-statements-error-reporting
//Uses Prepared Statements to prepare Query String, Uses bind_param to insert variables into the Query String e
//then pushes the query to the Database with Execute()
$stmt = $dbConnection->prepare("INSERT INTO meetings (meet_name, date, time_slot_id, capacity, announcement, group_id) VALUES (?, ?, ?, ?, ?, ?)");
if(false ===$stmt){
  die('prepare() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->bind_param("ssssss", $meetingName, $meetingDate, $meetingTimeSlot, $meetingCapacity, $meetingAnnouncement, $meetingGroupId);
if(false ===$check){
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}
$check = $stmt->execute();
if(false ===$check){
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
//Here im getting the ID of the meeting previously created
$lastId = $stmt->insert_id;
//Outputs user info based on what was inputted, Including ID which parent must keep track of
echo " New Records Created successfully with Info:: ID: ".$stmt->insert_id."  Meeting Name: ".$meetingName." Meeting Date: ".$meetingDate." Time Slot ID: ".$meetingTimeSlot." Capacity: ".$meetingCapacity." Announcement: ".$meetingAnnouncement." Group ID: ".$meetingGroupId;
//Closes stmt and connection
$stmt->close();
$dbConnection->close();

?>
