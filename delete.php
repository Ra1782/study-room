<?php 
    require("connection.php");
    if($_GET['del'] === 'T'){
        $sql = "DELETE FROM teacher WHERE T_id=".$_GET['id'];
        if(mysqli_query($conn, $sql)){
            echo "<script> location.replace('admin.php'); </script>";
        }
        else{
            echo "<script>
                alert('Error occured while deleting');
                window.location.href='admin.php';
            </script>";
        }
    }
    else if($_GET['del'] === 'S'){
        $sql = "DELETE FROM student WHERE S_id=".$_GET['id'];
        if(mysqli_query($conn, $sql)){
            echo "<script> location.replace('admin.php'); </script>";
        }
        else{
            echo "<script>
                alert('Error occured while deleting');
                window.location.href='admin.php';
            </script>";
        }
    }
?>