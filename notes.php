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
        ul {
            padding: 0;
            display: flex;
            justify-content: space-around;
        }
        ul li {
            list-style: none;
            width: 25%;
        }
        ul li a{
            text-decoration: none;
            color: black;
            font-size: 22px;
        }
       
        #Ndate{
            
            text-align:right;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <span>STUDY ROOM</span>
        <span class="links">
            <button class="logout" id="logout">Logout</button>
        </span>
    </nav>
    <div>
        <?php
            if($_GET['log']==="T"){
                $redirect = "teacherClassPage.php?class_id=".$_GET['class_id'];
            }
            else if($_GET['log']==="S"){
                $redirect = "classPage.php?class_id=".$_GET['class_id'];
            }
        ?>
        <a href=<?php echo $redirect ?> class="back" style="text-decoration:none;color:blue;">&lt; back</a>
    </div>
    <br>
    <br>
    <div class="container">
    
        <div><br>
            <div class="theoryDiv">
            <div id="Ndate"></div>
                <h2>Notes:</h2>
                <p class="theory"></p>
                <p class="attachments"></p>
            </div>
        </div>
    </div>
 
    <?php 
    session_start();
        require("connection.php");
        if(! $conn ) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "SELECT theory,has_attach from notes where N_id=".$_GET['notes_id'];
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_all($res,MYSQLI_ASSOC);
        $new_arr = json_encode($arr);
        mysqli_free_result($res);

        if($arr[0]["has_attach"]){
            $sql = "SELECT N_a_id, File_name from notes_attachments WHERE N_id=".$_GET['notes_id'];
            $res = mysqli_query($conn, $sql);
            $arr = mysqli_fetch_all($res,MYSQLI_ASSOC);
            $new_arr1 = json_encode($arr);
        }
        $sql = "select N_date from notes where N_id=".$_GET['notes_id'];
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_row($res);
        $new_arr2 = json_encode($arr);
    

    ?>
  
    <script>
            var Q =<?=$new_arr2?>[0];
            var date =document.getElementById("Ndate");
            var h1 =document.createElement("h3");
            h1.textContent="Date : "+Q;
            date.appendChild(h1);

            var p =document.querySelector('.theory');
            var theory= <?= $new_arr?>;
            theoryDiv=document.querySelector('.theoryDiv');
            p.textContent=theory[0].theory;
            p.style.width="100%";
            p.style.backgroundColor="#dcdcdc";
            p.style.minHeight="10vh";
            var attach = <?php echo isset($new_arr1)?$new_arr1:0 ?>;
            console.log(attach);
            if(attach!==0){
                h2=document.createElement("h2");
                h2.textContent = "Attachments:";
                document.querySelector(".attachments").appendChild(h2);
                for(var elem in attach){
                    var link = document.createElement('a');
                    link.textContent=attach[elem].File_name;
                    link.target='_blank';
                    link.href = "view.php?id="+attach[elem].N_a_id+"&table=notes_attachments";
                    link.style.color = "#000";
                    link.style.padding="10px";
                    document.querySelector(".attachments").appendChild(link);
                }
            }
            theoryDiv.style.backgroundColor="#dcdcdc";
            theoryDiv.style.padding="20px";
            theoryDiv.style.margin="0 10%";
            theoryDiv.style.borderRadius="15px";   
    </script>
</body>
</html>