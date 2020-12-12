<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <link rel="stylesheet" href="mainPage.css">
    <style>
        .person{
            font-size: 20px;
            display: flex;
            justify-content: space-around;
        }
        .view-profile, .logout {
            font-size: 20px;
            background-color: transparent;
            border: 0px solid black;
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
        @media (max-width: 1200px){
            .links{
                right: 6%;
            }
        }
        @media (max-width: 950px){
            .links{
                right: 0%;
            }
        }
        @media (max-width: 768px){
            .links{
                display: flex;
            }
            
        }
    </style>
</head>
<body>
    <?php
        session_start();
        require_once("connection.php");
        $sql = "select S_id, S_Name from student";
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_all($res);
        $stud = json_encode($arr);

        $sql = "select T_id, T_Name from teacher";
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_all($res);
        $teach = json_encode($arr);
    ?>
    <nav class="navbar">
        <span>STUDY ROOM</span>
        <span class="links">
            <button class="view-profile" id="view-profile">View Profile</button>
            <button class="logout" id="logout">Logout</button>
        </span>
    </nav>
    <br>
    <div class="container">
        <h2>Teachers</h2>
        <div id="teachers"></div>
        <h2>Students</h2>
        <div id="students"></div>
    </div>
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

        var teachers = <?= $teach?>;
        for(var i=0;i<teachers.length;i++){
            var div = document.createElement("div");
            div.setAttribute('class','person');
            var teach_id = document.createElement("span");
            teach_id.textContent = teachers[i][0];
            var teach_name = document.createElement("span");
            teach_name.textContent=teachers[i][1];
            var del = document.createElement("a");
            del.textContent="Delete";
            del.href = "delete.php?del=T&id="+teachers[i][0];
            del.style.color = "black";
            div.style.width = "98%";
            div.style.margin = "0 1%";
            div.style.borderRadius = "8px";
            div.style.backgroundColor = "#f99245";        
            div.appendChild(teach_id);   
            div.appendChild(teach_name); 
            div.appendChild(del);           
            document.getElementById("teachers").appendChild(div);
            document.getElementById("teachers").appendChild(document.createElement('br'));
        }
        var students = <?= $stud?>;
        for(var i=0;i<students.length;i++){
            var div = document.createElement("div");
            div.setAttribute('class','person');
            var stud_id = document.createElement("span");
            stud_id.textContent = students[i][0];
            var stud_name = document.createElement("span");
            stud_name.textContent=students[i][1];
            var del = document.createElement("a");
            del.textContent="Delete";
            del.href = "delete.php?del=S&id="+students[i][0];
            del.style.color = "black";
            div.style.width = "98%";
            div.style.margin = "0 1%";
            div.style.borderRadius = "8px";
            div.style.backgroundColor = "#f99245";        
            div.appendChild(stud_id);   
            div.appendChild(stud_name); 
            div.appendChild(del);           
            document.getElementById("students").appendChild(div);
            document.getElementById("students").appendChild(document.createElement('br'));
        }
    </script>
</body>
</html>