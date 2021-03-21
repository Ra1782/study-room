<?php

    $dbhost = 'localhost';  
    $dbuser = 'hosted_db_username';
    $dbpass = 'hosted_db_password';
    $dbname = 'hosted_db_name';

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if(! $conn ) {
        die('Could not connect: ' . mysqli_connect_error());
    }
?>
