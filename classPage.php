<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.css">
    <title>Class</title>
    <style>
        .logout {
            font-size: 20px;
            background-color: transparent;
            border: 0px solid;
            padding: 5px;
            margin-right: 25px;
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
        .view-profile:hover, .logout:hover {
            background-color: transparent;
            cursor: pointer;
        }
        .links {    
            position: absolute;
            right: 8%;
        }
        .functions {
            width: 100%;
            background-color: #F09030;
        }
        ul {
            padding: 0;
            display: flex;
            justify-content: space-around;
        }
        ul li {
            list-style: none;
            width: 25%;
            font-size: 22px;
            cursor:pointer;
        }
        .active {
            transition: 1.2s;
            background-color: #fcd667;
        }
        #message{
            width:95%;height:25px;
        }
        #send-btn{
            height:28px;
            background-color:#fcd667;
            padding:5px;
            border-radius: 30%;
        }
        #send-message{
            width: 98%;
            visibility: hidden;
            position: fixed;
            bottom: 10px;
        }
        #classname span{
            font-size: 25px;
            padding: 20px 10px;
        }
        #Tname span{
            font-size: 20px;
            padding: 20px 10px;
            position: absolute;
            right: 0;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        if(!isset($_SESSION['log_email']) && $_SESSION['log_email'] != '' && !isset($_GET['class_id'])){
            die('Unauthorized user');
        }
    ?>
    <nav class="navbar">
        <span>STUDY ROOM</span>
        <span class="links">
            <button class="logout" id="logout">Logout</button>
        </span>
    </nav><br>
    <div>
    <a href="studentHomePage.php" class="back" style="text-decoration:none;color:blue;">&lt; back</a>
    <span id="classname"></span>
    <span id="Tname"></span>
    </div>
    <br>
    <div class="container">
        <div class="functions">
            <ul class="nav-links">
                <li class="stream active">Stream</li>
                <li class="notes">Notes</li>
                <li class="tasks">Tasks</li>
                <li class="classmates">Classmates</li>
            </ul>
        </div>
        <div id="content"></div>
        <div id="send-message">
            <form action="classPage.php?class_id=<?= $_GET['class_id'] ?>" method="post">
                <input id="message" type="text" name="message" placeholder="Type a message">
                <button id="send-btn" type="submit">=></button>
            </form>
        </div>
    </div>
    
    <?php 
        require("connection.php");
        if(! $conn ) {
            die('Could not connect: ' . mysqli_connect_error());
        }
        if(isset($_POST['message']) && $_POST['message']!=""){
            $sql = "insert into class_message(C_id,Message,Author) 
            Values(".$_GET['class_id'].",'".$_POST['message']."','".$_SESSION["log_name"]."')";
            if(mysqli_query($conn, $sql)){
                $_POST['message']="";
            }
            else{
                $_POST['message']="";
            };
        }
        $sql = "select Message_id, Message, Author from class_message where C_id=".$_GET['class_id'];
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $messages = json_encode($arr);

        $sql = "select N_id,N_Name ,N_date from notes where C_id=".$_GET['class_id'];
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $new_arr = json_encode($arr);

        $sql = "select Task_id,Task_Name ,Due_Date from class_task where C_id=".$_GET['class_id'];
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $new_arr1 = json_encode($arr);

        $sql = "select S_Name from student where S_id in 
        (select S_id from enroll where C_id=".$_GET['class_id'].")";
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_all($res);
        $class_stud = json_encode($arr);

        $sql = "select C_name from class where C_id=".$_GET['class_id'];
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_row($res);
        $new_arr2 = json_encode($arr);
        
        $sql = "select T_id from class where C_id= ".$_GET['class_id'];
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_row($res);
        $T_id=$arr[0];

        $sql = "select T_name from teacher where T_id=".$T_id;
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_row($res);
        $new_arr3 = json_encode($arr);
    ?>
    <script>
        document.getElementById("logout").onclick = function(){
            var url = "logout.php";
            window.location.href = url;
        }

        var Q =<?=$new_arr2?>[0];
        var cname =document.getElementById("classname");
        var h1 =document.createElement("span");
        h1.textContent=Q;
        cname.appendChild(h1);
        
        var n =<?=$new_arr3?>[0];
        var c =document.getElementById("Tname");
        var h2 =document.createElement("span");
        h2.textContent="Teacher:- "+n;
        c.appendChild(h2);

        ul = document.querySelector("ul");
        li = document.querySelectorAll("li");
        li.forEach(elem => {
            elem.addEventListener('click',function(){
                ul.querySelector('.active').classList.remove('active');
                elem.classList.add('active');
            })
        });

        var messages = <?= $messages?>;
        messages = messages.reverse();
        var default_active = "";
        messages.forEach(element => {
            var author = document.createElement('h4');
            author.textContent=element.Author;
            author.style.color = '#2980b9';
            author.style.padding = '5px 20px';
            var message = document.createElement('p');
            message.textContent = element.Message;
            message.style.padding = '5px 20px';
            default_active=document.createElement('div');
            default_active.setAttribute("id",element.Message_id);
            default_active.appendChild(author);
            default_active.appendChild(message);
            default_active.appendChild(document.createElement('hr'));
            default_active.style.textAlign = "left";
            document.getElementById('content').appendChild(default_active);
        });
        document.getElementById('send-message').style.visibility = "visible";

        document.querySelector('.stream').addEventListener('click',function(){
            document.getElementById("content").innerHTML = "";
            var messages = <?= $messages?>;
            messages = messages.reverse();
            messages.forEach(element => {
                var author = document.createElement('h4');
                author.textContent=element.Author;
                author.style.color = '#2980b9';
                author.style.padding = '5px 20px';
                var message = document.createElement('p');
                message.textContent = element.Message;
                message.style.padding = '5px 20px';
                div=document.createElement('div');
                div.setAttribute("id",element.Message_id);
                div.appendChild(author);
                div.appendChild(message);
                div.appendChild(document.createElement('hr'));
                div.style.textAlign = "left";
                document.getElementById("content").appendChild(div);
            });
            document.getElementById('send-message').style.visibility = "visible";
        });

        document.querySelector('.notes').addEventListener('click',function(){
            document.getElementById("content").innerHTML = "";
            var note = <?= $new_arr?>;
            note.forEach(element => {
                var noteHeading = document.createElement("span");
                var link = document.createElement('a');
                link.textContent=element.N_Name;
                link.href = "notes.php?class_id="+<?=$_GET["class_id"]?>+"&notes_id="+element.N_id+"&log=S";
                link.style.color = "#000";
                link.style.textDecoration = "none";
                noteHeading.appendChild(link);
                noteHeading.style.fontSize = "3.5vh";
                span=document.createElement('div');
                span.setAttribute("id",element.N_id);
                span.style.width = "98%";
                span.style.margin = "0 1%";
                span.style.backgroundColor = "#f99245";
                span.style.borderRadius = "8px";
                noteHeading.style.wordWrap = "break-word";               
                span.appendChild(noteHeading);              
                document.getElementById("content").appendChild(span);
                document.getElementById("content").appendChild(document.createElement('br'));
            });
            document.getElementById('send-message').style.visibility = "hidden";
        });
        document.querySelector('.tasks').addEventListener('click',function(){
            document.getElementById("content").innerHTML = "";
            var task = <?= $new_arr1?>;
            task.forEach(element => {
                var taskHeading = document.createElement("span");
                var link = document.createElement('a');
                link.textContent=element.Task_Name;
                link.href = "studentTask.php?class_id="+<?=$_GET["class_id"]?>+"&assign_id="+element.Task_id;
                link.style.color = "#000";
                link.style.textDecoration = "none";
                taskHeading.appendChild(link);
                taskHeading.style.fontSize = "3.5vh";
                span=document.createElement('div');
                span.setAttribute("id",element.Task_id);
                span.style.width = "98%";
                span.style.margin = "0 1%";
                span.style.backgroundColor = "#f99245";
                span.style.borderRadius = "8px";
                taskHeading.style.wordWrap = "break-word";               
                span.appendChild(taskHeading);              
                document.getElementById("content").appendChild(span);
                document.getElementById("content").appendChild(document.createElement('br'));
            });
            document.getElementById('send-message').style.visibility = "hidden";
        });
        document.querySelector('.classmates').addEventListener('click',function(){
            document.getElementById("content").innerHTML = "";
            var classmates = <?= $class_stud?>;
            for(var i=0;i<classmates.length;i++){
                var div = document.createElement("div");
                var stud_name = document.createElement("span");
                stud_name.textContent=classmates[i];
                stud_name.style.fontSize = "24px";
                div.style.width = "98%";
                div.style.margin = "0 1%";
                div.style.borderRadius = "8px";
                div.style.backgroundColor = "#f99245";             
                div.appendChild(stud_name);              
                document.getElementById("content").appendChild(div);
                document.getElementById("content").appendChild(document.createElement('br'));
            }
            document.getElementById('send-message').style.visibility = "hidden";
        });
    </script>