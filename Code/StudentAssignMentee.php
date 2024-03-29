<?php

// Getting info posted from StudentLogin.php form\
$meetingId = $_POST['meetingId'];
$MenteeRadioVal= $_POST['MenteeSelect'];
session_start();
$studentId = $_SESSION['passedId'];

//Opening Connection to database and testing connection
$dbConnection = new mysqli('localhost', 'root', '', 'db2');
if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}
//Actual Code:

/* checks to see if the meeting is late
$sql = "SELECT  meet_id
FROM meetings
Where ((DAYOFWEEK(date) = 7 AND DATEDIFF( date, CURRENT_TIMESTAMP) < 3) OR (DAYOFWEEK(date) = 1 AND DATEDIFF( date, CURRENT_TIMESTAMP) < 2)) AND meet_id = " . $meetingId. ";";
$result = mysqli_query($dbConnection , $sql);
if (mysqli_num_rows($result)) {
    // if meeting is due, close out
    Echo "It is to late to join meeting";
    $dbConnection->close();
    die();
}
*/

if ($MenteeRadioVal == 'single'){

  //Time and Date Check code
  //Getting time slot of entered meeting
  $stmt = $dbConnection->prepare("SELECT time_slot_id, date from meetings where meet_id=?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("s", $meetingId);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
  //store time slot of entered meeting
  $timeSlotResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
   $stmt->close();
   //var_dump($timeSlotResult);

  //Checking time slots of other meetings student is currently in for mentee
   $stmt = $dbConnection->prepare("SELECT meet_id from enroll where mentee_id=?");
   if(false ===$stmt){
     die('prepare() failed: ' . htmlspecialchars($mysqli->error));
   }
   $check = $stmt->bind_param("s", $studentId);
   if(false ===$check){
     die('bind_param() failed: ' . htmlspecialchars($stmt->error));
   }
   $check = $stmt->execute();
   if(false ===$check){
     die('execute() failed: ' . htmlspecialchars($stmt->error));
   }
   $meetIdMentee = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();



    //Checking time slots of other meetings student is currently in for mentor
     $stmt = $dbConnection->prepare("SELECT meet_id from enroll2 where mentor_id=?");
     if(false ===$stmt){
       die('prepare() failed: ' . htmlspecialchars($mysqli->error));
     }
     $check = $stmt->bind_param("s", $studentId);
     if(false ===$check){
       die('bind_param() failed: ' . htmlspecialchars($stmt->error));
     }
     $check = $stmt->execute();
     if(false ===$check){
       die('execute() failed: ' . htmlspecialchars($stmt->error));
     }
     $meetIdMentor = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      $stmt->close();

      //merge both mentor and mentee meeting ids into one
     $allEnrolledId=array_merge($meetIdMentee, $meetIdMentor);
     //var_dump($allEnrolledId);
     //echo "||||||||||||||||||";

     //for all meetings you are mentee/mentor in get the timeslot and compare it with the meeting you are trying to sign up for.
     for($i=0; $i<count($allEnrolledId); $i++){
       $stmt = $dbConnection->prepare("SELECT time_slot_id, date from meetings where meet_id=?");
       if(false ===$stmt){
         die('prepare() failed: ' . htmlspecialchars($mysqli->error));
       }
       $check = $stmt->bind_param("s", $allEnrolledId[$i]['meet_id']);
       if(false ===$check){
         die('bind_param() failed: ' . htmlspecialchars($stmt->error));
       }
       $check = $stmt->execute();
       if(false ===$check){
         die('execute() failed: ' . htmlspecialchars($stmt->error));
       }
       $checkTimeId = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
       $stmt->close();
       //var_dump($checkTimeId);
       if($timeSlotResult[0]['time_slot_id']==$checkTimeId[0]['time_slot_id'] && $timeSlotResult[0]['date']==$checkTimeId[0]['date']){
         echo "Cannot sign up for meeting because of time conflict with other meetings.";
         return;
       }

     }//Closing out for loop that checks times and dates to verify if student has time conflict


  $query = 'SELECT mentee_id FROM mentees WHERE mentee_id= ' . $studentId;
  $result = mysqli_query($dbConnection, $query);

  //checking if the id is already in the mentees
  if(mysqli_num_rows($result) > 0){
    echo "Student already a mentee";
  } else{
    echo "The student is not already a mentee";
    $stmt = $dbConnection->prepare("INSERT INTO mentees (mentee_id) VALUES (?)");
    if(false ===$stmt){
      die('prepare() failed: ' . htmlspecialchars($mysqli->error));
    }
    $check = $stmt->bind_param("s", $studentId);
    if(false ===$check){
      die('bind_param() failed: ' . htmlspecialchars($stmt->error));
    }
    $check = $stmt->execute();
    if(false ===$check){
      die('execute() failed: ' . htmlspecialchars($stmt->error));
    }
    //Outputs user info based on what was inputted, Including ID which parent must keep track of
    echo " New Records Created successfully with Info:: ID: ".$studentId;
    //Closes stmt and connection
    $stmt->close();
    $dbConnection->close();
  }


  //Opening the connection to the database

  $dbConnection = new mysqli('localhost', 'root', '', 'db2');
  if ($dbConnection->connect_error) {
    die("Connection failed: " . $dbConnection->connect_error);
  }

  /*

  This section is fetching different values from the database in order to check multiple things

  This section acquires 5 different attributes

  1. Selects the student grade level
  2. Selects the meetings grade level
  3. Selects the capacity of the meeting
  4. Selects the amount of mentees already enrolled in the meeting
  5. Selects the amount of mentors already enrolled in the meeting

  */

  //grabbing the grade levels for comparison
  $query = 'SELECT grade FROM students WHERE student_id= ' . $studentId;
  $studentGradeLevel = mysqli_query($dbConnection, $query);
  $query = 'SELECT group_id FROM meetings WHERE meet_id=' . $meetingId;
  $meetingGradeLevel = mysqli_query($dbConnection, $query);

  while ($row = mysqli_fetch_array ($studentGradeLevel, MYSQLI_ASSOC)) {
    $studentGrade = $row["grade"];
    //echo $studentGrade;
  }

  while ($row = mysqli_fetch_array ($meetingGradeLevel, MYSQLI_ASSOC)) {
    $meetingGrade = $row["group_id"];
    //echo $meetingGrade;
  }

  //getting the total capacity
  $query =  'SELECT capacity FROM meetings WHERE meet_id= ' . $meetingId;
  $meetingCapacity = mysqli_query($dbConnection, $query);
  while ($row = mysqli_fetch_array ($meetingCapacity, MYSQLI_ASSOC)) {
    $meetingCapacityNum = $row["capacity"];
    //echo $meetingCapacityNum;
  }

  //getting the amount of mentees
  $query = 'SELECT mentee_id FROM enroll WHERE meet_id= ' . $meetingId;
  $menteeAmount = mysqli_query($dbConnection, $query);
  $totalMentees = mysqli_num_rows($menteeAmount);
  //echo $totalMentees;

  //getting the amount of mentors
  $query = 'SELECT mentor_id FROM enroll2 WHERE meet_id= ' . $meetingId;
  $mentorAmount = mysqli_query($dbConnection, $query);
  $totalMentors = mysqli_num_rows($mentorAmount);
  //echo $totalMentors;

  /*

  This is where the students are actually being assigned to the specified meeting as the specified role

  3 Major Checks are done here:
    1. Is the student in the same grade level?
    2. Is there room for another student based off the meeting's capacity?
    3. Can another student be assigned to mentees, the max for mentees is 6?

  If these 3 checks pass then the student will be assigned into the meeting as a mentee.

  */
  if($studentGrade == $meetingGrade){
    if(($totalMentees + $totalMentors) < $meetingCapacityNum){
      if($totalMentees < 6){
        //Inserting mentee Id and meeting Id into enroll
        $stmt = $dbConnection->prepare("INSERT INTO enroll (meet_id, mentee_id) VALUES (?,?)");
        if(false ===$stmt){
        die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $check = $stmt->bind_param("ss", $meetingId, $studentId);
        if(false ===$check){
          die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        }

        $check = $stmt->execute();
        if(false ===$check){
          die('execute() failed: ' . htmlspecialchars($stmt->error));
        }

        echo "Mentee was added to meeting";

        $stmt->close();

      }else{
        echo "This meeting already has enough mentees";
      }

    }else {
      echo "The meeting is already full";
    }

  }else{
    echo "Student is not in the same grade level as the meeting";
  }

}//Single radio vlaue close

//Recurring option selected
if ($MenteeRadioVal == 'Recurring'){
  $stmt = $dbConnection->prepare("SELECT meet_name FROM meetings WHERE meet_id = ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("s", $meetingId);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
  $MeetName = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  //var_dump($MeetName);
  //Get all IDs of $MeetName

  $stmt = $dbConnection->prepare("SELECT meet_id FROM meetings WHERE meet_name = ?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("s", $MeetName[0]['meet_name']);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
  $RecurIDs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  //Grab all id's with the same name
  for($k=0;$k<count($RecurIDs);$k++){
  $meetingId = $RecurIDs[$k]['meet_id'];

  //Time and Date Check code
  //Getting time slot of entered meeting
  $stmt = $dbConnection->prepare("SELECT time_slot_id, date from meetings where meet_id=?");
  if(false ===$stmt){
    die('prepare() failed: ' . htmlspecialchars($mysqli->error));
  }
  $check = $stmt->bind_param("s", $meetingId);
  if(false ===$check){
    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
  }
  $check = $stmt->execute();
  if(false ===$check){
    die('execute() failed: ' . htmlspecialchars($stmt->error));
  }
  $timeSlotResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
   $stmt->close();
   //var_dump($timeSlotResult);

  //Checking time slots of other meetings student is currently in for mentee
   $stmt = $dbConnection->prepare("SELECT meet_id from enroll where mentee_id=?");
   if(false ===$stmt){
     die('prepare() failed: ' . htmlspecialchars($mysqli->error));
   }
   $check = $stmt->bind_param("s", $studentId);
   if(false ===$check){
     die('bind_param() failed: ' . htmlspecialchars($stmt->error));
   }
   $check = $stmt->execute();
   if(false ===$check){
     die('execute() failed: ' . htmlspecialchars($stmt->error));
   }
   $meetIdMentee = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();



    //Checking time slots of other meetings student is currently in for mentor
     $stmt = $dbConnection->prepare("SELECT meet_id from enroll2 where mentor_id=?");
     if(false ===$stmt){
       die('prepare() failed: ' . htmlspecialchars($mysqli->error));
     }
     $check = $stmt->bind_param("s", $studentId);
     if(false ===$check){
       die('bind_param() failed: ' . htmlspecialchars($stmt->error));
     }
     $check = $stmt->execute();
     if(false ===$check){
       die('execute() failed: ' . htmlspecialchars($stmt->error));
     }
     $meetIdMentor = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      $stmt->close();

      //storing meetings you mentor and mentee in
     $allEnrolledId=array_merge($meetIdMentee, $meetIdMentor);
     //var_dump($allEnrolledId);
     //echo "||||||||||||||||||";

     //comparing timeslot of meeting you are trying to sign up for and meetings you are in
     for($i=0; $i<count($allEnrolledId); $i++){
       $stmt = $dbConnection->prepare("SELECT time_slot_id, date from meetings where meet_id=?");
       if(false ===$stmt){
         die('prepare() failed: ' . htmlspecialchars($mysqli->error));
       }
       $check = $stmt->bind_param("s", $allEnrolledId[$i]['meet_id']);
       if(false ===$check){
         die('bind_param() failed: ' . htmlspecialchars($stmt->error));
       }
       $check = $stmt->execute();
       if(false ===$check){
         die('execute() failed: ' . htmlspecialchars($stmt->error));
       }
       $checkTimeId = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
       $stmt->close();
       //var_dump($checkTimeId);
       if($timeSlotResult[0]['time_slot_id']==$checkTimeId[0]['time_slot_id'] && $timeSlotResult[0]['date']==$checkTimeId[0]['date']){
         echo "Cannot sign up for meeting because of time conflict with other meetings.";
         return;
       }

     }//Closing out for loop that checks times and dates to verify if student has time conflict


  $query = 'SELECT mentee_id FROM mentees WHERE mentee_id= ' . $studentId;
  $result = mysqli_query($dbConnection, $query);

  //checking if the id is already in the mentees
  if(mysqli_num_rows($result) > 0){
    echo "Student already a mentee";
  } else{
    echo "The student is not already a mentee";
    $stmt = $dbConnection->prepare("INSERT INTO mentees (mentee_id) VALUES (?)");
    if(false ===$stmt){
      die('prepare() failed: ' . htmlspecialchars($mysqli->error));
    }
    $check = $stmt->bind_param("s", $studentId);
    if(false ===$check){
      die('bind_param() failed: ' . htmlspecialchars($stmt->error));
    }
    $check = $stmt->execute();
    if(false ===$check){
      die('execute() failed: ' . htmlspecialchars($stmt->error));
    }
    //Outputs user info based on what was inputted, Including ID which parent must keep track of
    echo " New Records Created successfully with Info:: ID: ".$studentId;
    //Closes stmt and connection
    $stmt->close();
    $dbConnection->close();
  }


  //Opening the connection to the database

  $dbConnection = new mysqli('localhost', 'root', '', 'db2');
  if ($dbConnection->connect_error) {
    die("Connection failed: " . $dbConnection->connect_error);
  }

  /*

  This section is fetching different values from the database in order to check multiple things

  This section acquires 5 different attributes

  1. Selects the student grade level
  2. Selects the meetings grade level
  3. Selects the capacity of the meeting
  4. Selects the amount of mentees already enrolled in the meeting
  5. Selects the amount of mentors already enrolled in the meeting

  */

  //grabbing the grade levels for comparison
  $query = 'SELECT grade FROM students WHERE student_id= ' . $studentId;
  $studentGradeLevel = mysqli_query($dbConnection, $query);
  $query = 'SELECT group_id FROM meetings WHERE meet_id=' . $meetingId;
  $meetingGradeLevel = mysqli_query($dbConnection, $query);

  while ($row = mysqli_fetch_array ($studentGradeLevel, MYSQLI_ASSOC)) {
    $studentGrade = $row["grade"];
    //echo $studentGrade;
  }

  while ($row = mysqli_fetch_array ($meetingGradeLevel, MYSQLI_ASSOC)) {
    $meetingGrade = $row["group_id"];
    //echo $meetingGrade;
  }

  //getting the total capacity
  $query =  'SELECT capacity FROM meetings WHERE meet_id= ' . $meetingId;
  $meetingCapacity = mysqli_query($dbConnection, $query);
  while ($row = mysqli_fetch_array ($meetingCapacity, MYSQLI_ASSOC)) {
    $meetingCapacityNum = $row["capacity"];
    //echo $meetingCapacityNum;
  }

  //getting the amount of mentees
  $query = 'SELECT mentee_id FROM enroll WHERE meet_id= ' . $meetingId;
  $menteeAmount = mysqli_query($dbConnection, $query);
  $totalMentees = mysqli_num_rows($menteeAmount);
  //echo $totalMentees;

  //getting the amount of mentors
  $query = 'SELECT mentor_id FROM enroll2 WHERE meet_id= ' . $meetingId;
  $mentorAmount = mysqli_query($dbConnection, $query);
  $totalMentors = mysqli_num_rows($mentorAmount);
  //echo $totalMentors;

  /*

  This is where the students are actually being assigned to the specified meeting as the specified role

  3 Major Checks are done here:
    1. Is the student in the same grade level?
    2. Is there room for another student based off the meeting's capacity?
    3. Can another student be assigned to mentees, the max for mentees is 6?

  If these 3 checks pass then the student will be assigned into the meeting as a mentee.

  */
  if($studentGrade == $meetingGrade){
    if(($totalMentees + $totalMentors) < $meetingCapacityNum){
      if($totalMentees < 6){
        //Inserting mentee Id and meeting Id into enroll
        $stmt = $dbConnection->prepare("INSERT INTO enroll (meet_id, mentee_id) VALUES (?,?)");
        if(false ===$stmt){
        die('prepare() failed: ' . htmlspecialchars($mysqli->error));
        }
        $check = $stmt->bind_param("ss", $meetingId, $studentId);
        if(false ===$check){
          die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        }

        $check = $stmt->execute();
        if(false ===$check){
          die('execute() failed: ' . htmlspecialchars($stmt->error));
        }

        echo "Mentee was added to meeting";

        $stmt->close();

      }else{
        echo "This meeting already has enough mentees";
      }

    }else {
      echo "The meeting is already full";
    }

  }else{
    echo "Student is not in the same grade level as the meeting";
  }
}//for RecurID loop CLose

}//Recurring radio value close
$dbConnection->close();
?>
