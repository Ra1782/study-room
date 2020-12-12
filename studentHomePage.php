<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.css">
    <title>Student Home</title>
    <style>
        .view-profile, .logout {
            font-size: 20px;
            background-color: transparent;
            border: 0px solid;
            padding: 5px;
            margin-right: 25px;
        }
        .view-profile:hover, .logout:hover {
            background-color: transparent;
            cursor: pointer;
            transform: scale(1.12);
        }
        .links {    
            position: absolute;
            right: 8%;
        }
        .logout:active{
            color: red;
        }
        .classes {
            overflow: auto;
        }
        .join-class {
            margin-top: 25px;
        }
        .classes span { 
            width: 21%;
            float: left;
            margin: 1%;
            padding: 1%;
        }
        @media (max-width: 1200px){
            .links{
                right: 6%;
            }
            .classes span {
                width: 28%;
            }
        }
        @media (max-width: 950px){
            .classes span {
                width: 45%;
            } 
            .links{
                right: 0%;
            }
        }
        @media (max-width: 768px){
            .links{
                display: flex;
            }
            
        }
        @media (max-width: 600px){
            .classes span {
                width: 95%
            }
        }

    </style>
</head>
<body>
    <nav class="navbar">
        <span>STUDY ROOM</span>
        <span class="links">
            <button class="view-profile" id="view-profile">View Profile</button>
            <button class="logout" id="logout">Logout</button>
        </span>
    </nav><br><br>
    <div class="container">
        <div class = "teacher-home">
            <button type="submit" class="join-class app-button">Join Class</button>
            <div class="classes" id="classes"></div>
        </div>
    </div>
    <?php
        session_start();
        require("connection.php");
        if(isset($_POST['Class_code'])){

            $Code=$_POST['Class_code'];
            $sql="select C_id from class where Code='".$Code."'";
            $res = mysqli_query($conn, $sql);
            $arr = mysqli_fetch_row($res);
          
    
            $C_id = $arr[0];
            $S_id = $_SESSION['log_id'];
            
            $sql="select CS_id from class_student where C_id=". $C_id." and S_id =".$S_id ;
            $res = mysqli_query($conn, $sql);
            $arr = mysqli_fetch_row($res);
            
            
            
    
            if ($arr!=Null){
            $sql="select E_id from enroll where C_id=".$C_id." and S_id=".$S_id;
            $res = mysqli_query($conn, $sql);
            $arr1 = mysqli_fetch_row($res);
            
            if($arr1==Null){
            $sql = "insert into enroll (C_id,S_id) Values(?,?) ";
            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "dd", $C_id,$S_id);
                mysqli_stmt_execute($stmt);  
                unset($_POST);
                echo "<script>alert('Class joined succesfully')</script>";
               }
            }
            }
        }
        if(!isset($_SESSION['log_email']) && $_SESSION['log_email']!=""){
            echo "<script> location.replace('index.php'); </script>";
        }
        require("connection.php");
        if(! $conn ) {
            die('Could not connect: ' . mysqli_connect_error());
        }
        $sql = "select C_id, C_Name, subject from class where C_id in 
            (select C_id from enroll where S_id='".$_SESSION['log_id']."')";
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $new_arr = json_encode($arr);
    
    ?>
    <script>
        document.getElementById("view-profile").onclick = function(){
            var name='<?=$_SESSION['log_name']?>';
            var email='<?=$_SESSION['log_email']?>';
            alert("\nName: "+name +"\n\nEmail-id: "+email);
        }
        document.getElementById("logout").onclick = function(){
            var url = "logout.php";
            window.location.href = url;
        }
        document.querySelector(".join-class").onclick = function(){
            var url = "joinClass.php";
            window.location.href = url;
        }

        var classes = <?= $new_arr?>;
        classes.forEach(element => {
            var classHeading = document.createElement('h1');
            var link = document.createElement('a');
            link.textContent=element.C_Name;
            link.href = "classPage.php?class_id="+element.C_id;
            link.style.color = "#000";
            link.style.textDecoration = "none";
            classHeading.appendChild(link);
            var subName = document.createElement('h3');
            subName.textContent = element.subject;
            subName.style.color = "white";
            subName.style.fontFamily = "Arial";
            classHeading.style.fontSize = "3.5vh";
            subName.style.fontSize = "2.25vh";
            span=document.createElement('span');
            span.setAttribute("id",element.C_id);
            span.style.backgroundColor = "#f99245";
            span.style.borderRadius = "8px";
            classHeading.style.wordWrap = "break-word";
            subName.style.wordWrap = "break-word";
            span.appendChild(classHeading);
            span.appendChild(subName);
            document.getElementById("classes").appendChild(span);
            console.log(span);
        });

    </script>
</body>
</html>