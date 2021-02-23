<?php
//Creates new instance of the Database that Cindy gave us

// Connects to the SQL Server
$myconnection = mysqli_connect('localhost', 'root', '') 
or die ('Could not connect: ' . mysql_error());

//Checks to see if Database already exists
if(mysqli_select_db ($myconnection, 'db2')){
    echo "Database exists";
} else  {  
    echo "Database doesn't exist";
}
?>
