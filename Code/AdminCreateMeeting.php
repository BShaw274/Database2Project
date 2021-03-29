<?php

// Getting info posted from AdminSignedIn.html form
$meetingName = $_POST['meetingName'];
$meetingDate = $_POST['meetingDate'];
$meetingCapacity = $_POST['meetingCapacity'];
$meetingAnnouncement = $_POST['meetingAnnouncement'];
$meetingTimeSlot = $_POST['meetingTimeSlot'];
$meetingGroupId = $_POST['meetingGroupId'];
$recurringCheck = $_POST['recurringCheck'];


$meetingDateTime = new DateTime($meetingDate);


//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Actual Code:

// Style borrowed : https://stackoverflow.com/questions/2552545/mysqli-prepared-statements-error-reporting
//Uses Prepared Statements to prepare Query String, Uses bind_param to insert variables into the Query String e
//then pushes the query to the Database with Execute()

if ($recurringCheck == 0){


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

} else{
    $start_date = $meetingDateTime;
    //end date is when the semester ends
    // current date is based off off uml ending
    $end_date = date_add(date_create_from_format("Y",date('Y')),date_interval_create_from_date_string("134 days"));
  
    // echo date_diff($start_date,$end_date)->format("%r") == '';
    // echo date_diff($start_date,$end_date)->format("%r") != '';
    while (date_diff($start_date,$end_date)->format("%r") == '') {

      $stmt = $dbConnection->prepare("INSERT INTO meetings (meet_name, date, time_slot_id, capacity, announcement, group_id) VALUES (?, ?, ?, ?, ?, ?)");
      if(false ===$stmt){
        die('prepare() failed: ' . htmlspecialchars($stmt->error));
      }
      $x = $meetingDateTime->format('Y-m-d');
      $check =$stmt->bind_param("ssssss", $meetingName, $x , $meetingTimeSlot, $meetingCapacity, $meetingAnnouncement, $meetingGroupId);
      if(false ===$check){
        die('bind_param() failed: ' . htmlspecialchars($stmt->error));
      }
      $check = $stmt->execute();
      if(false ===$check){
        die('execute() failed: ' . htmlspecialchars($stmt->error));
      }
      //Here im getting the ID of the meeting previously created
      $lastId = $stmt->insert_id;

      $meetingDateTime->modify('+1 week');

    }
    //Closes stmt and connection
    $stmt->close();
    $dbConnection->close();  
}


?>
