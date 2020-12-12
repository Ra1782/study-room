<?php

    $dbhost = 'localhost';  
    $dbuser = 'id15650202_study_room';
    $dbpass = 'RohanParasSahil@1234';
    $dbname = 'id15650202_study_room_db';

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if(! $conn ) {
        die('Could not connect: ' . mysqli_connect_error());
    }
?>