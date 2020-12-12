<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.css">
    <title>Create class</title>
    <style>
        .create-class{
            height: 400px;
            padding-top: 40px;
 
        }
        
        textarea{
            box-sizing: border-box;
        }
        .back{
            font-size: 20px;
            background-color: transparent;
            border: 0px solid;
            padding: 5px;
            margin: 5px;
            position: absolute;
            left: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar"><span>STUDY ROOM</span></nav>
    <div>
    <a href="teacherHomePage.php" class="back" style="text-decoration:none;color:blue;">&lt; back</a>
    </div>

    <p><strong>CREATE CLASS</strong></p>
    <div class="create-class container">
    <form method="post" id ="createclass" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table align="center" cellpadding = "10" cellspacing = "15">
        
        <tr>
            <td>Class Name</td>
            <td><input type="text" name="C_name" maxlength="30" placeholder="Enter Class name" /></td>
        </tr>

        <tr>
            <td>Subject</td>
            <td><input type="subject" name="subject" maxlength="30" placeholder="Enter subject" /></td>
        </tr>

        <tr>
        <td>Enter the emails here </td>
        <td>
        <textarea  align = "center" rows="10" cols="50" name="Emails" ></textarea>
        </td>
        </tr>

        <tr>
            <td></td>
            <td><button class="app-button">Submit</button>
        </tr>
        </table>
 
    </form>
    </div>
    <?php
    require_once("connection.php");
    session_start();
    function random_strings($length_of_string) 
    { 
      
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result),  
                           0, $length_of_string); 
    } 
      
        if(isset($_POST['C_name']) && $_POST['C_name']!="" && isset($_POST['subject']) && $_POST['subject']!=""){
            $sql = "insert into class (C_Name,subject,Code,T_id) 
            Values('".$_POST["C_name"]."','".$_POST["subject"]."','".random_strings(8)."',".$_SESSION["log_id"].")";
            
            if(mysqli_query($conn, $sql)){  
                
                echo "<script>alert('Class created succesfully')</script>";
            }
        }
        
        if (isset($_POST['Emails'])) {
            $arrayOfEmails = explode("\n", $_POST['Emails']);
            $Emails = array_map('trim',$arrayOfEmails);
            $sql="select C_id from class where C_Name='".$_POST['C_name']."'";
            $res = mysqli_query($conn, $sql);
            $arr = mysqli_fetch_row($res);
            $C_id=$arr[0];
                
            
            $sql="select C_id from class where C_Name='".$_POST['C_name']."'";
            $res = mysqli_query($conn, $sql);
            $arr = mysqli_fetch_row($res);
            $C_id=$arr[0];
                
            for($i=0;$i<count($Emails);$i++){
                $sql="select S_id from student where Email='".$Emails[$i]."'";
                $res = mysqli_query($conn, $sql);
                $arr = mysqli_fetch_row($res);
                echo var_dump($Emails[$i]);
                $e=$arr[0];
    
                $sql="insert into class_student(C_id,S_id) Values(".$C_id.",".$e.")";
                if(!mysqli_query($conn, $sql)){  
                echo"<script> alert('student not entered')</script>";

    
            }
        
        }
    }
    
    ?>         
    
</body>
</html>