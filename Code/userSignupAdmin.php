<?php

    /*$myconnection = mysqli_connect('localhost', 'root', '') 
        or die ('Could not connect: ' . mysql_error());

    $mydb = mysqli_select_db ($myconnection, 'users') or die ('Could not select database');

    $query
    */

    //variables
    $userEmail = $_POST['userEmail'];
    $userPassword = $_POST['userPassword'];
    $userName = $_POST['userName'];
    $userPhone = $_POST['userPhone'];
    $role = $_POST['role'];

    echo $userEmail." email<br />";
    echo $userPassword." password<br />";
    echo $userName." name<br />";
    echo $userPhone." phone<br />";
    echo $role." role<br />";
    echo "Great test here!<br />";

?>