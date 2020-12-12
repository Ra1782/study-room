<?php 
    require("connection.php");
    if(isset($_GET['id']) && $_GET['id']!=""){
        if($_GET['table']==="submissions_attachments"){
            $res = mysqli_query($conn, "SELECT * from submissions_attachments WHERE Attach_id = ".$_GET['id']);
            $row = mysqli_fetch_array($res);
            header("Content-Type:".$row[4]);
            echo $row[2];
        }
        else if($_GET['table']==="notes_attachments"){
            $res = mysqli_query($conn, "SELECT * from notes_attachments WHERE N_a_id = ".$_GET['id']);
            $row = mysqli_fetch_array($res);
            header("Content-Type:".$row[5]);
            echo $row[3];
        }
    }
?>