<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.css">
    <title>Studyroom</title>
    <style>
        .back{
            font-size: 20px;
            background-color: transparent;
            border: 0px solid;
            padding: 5px;
            margin: 5px;
            position: absolute;
            left: 5px;
        }
        a{ font-family: "Arial"}
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
        }
        .links {    
            position: absolute;
            right: 8%;
        }
        .logout:active{
            color: red;
        }
        .classes {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .create-class {
            margin-top: 25px;
        }
        .table-input{
            background-color:#f4ae72;
            border: 2px solid #ff6030;
            width: 90%;
            height: 90%;
        }
        table {
            border-collapse: collapse;
        }
        td{
            padding:5px;
        }
        td a {
            color: #042530;
        }
        .line { border: 1px solid #ff6030;width:100%;}
        @media (max-width: 1200px){
            .classes span {
                width: 25%;
            }
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
            .classes span {
                width: 50%;
            }
            .links{
                display: flex;
            }
        }
        @media (max-width: 600px){
            .classes span {
                width: 100%;
            }
        }
        #Tdate{
            text-align:right;
        }

    </style>
</head>
<body>
    <?php
        session_start();
        if(!isset($_SESSION['log_email']) && $_SESSION['log_email']!=""){
            echo "<script> location.replace('index.php'); </script>";
        }
        require("connection.php");
        $sql = "SELECT s.S_id,S_Name,Out_of,m.M_id FROM student as s JOIN marks as m ON s.S_id=m.S_id 
        where m.C_id=".$_GET["class_id"]." AND Task_id=".$_GET['assign_id'];
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $count_stud = count($arr);
        mysqli_free_result($res);

        $sql = "SELECT N_a_id,na.Task_id,Instructions,File_name from class_task as ct 
        join notes_attachments as na on ct.Task_id=na.Task_id where na.Task_id=".$_GET['assign_id'];
        $res = mysqli_query($conn, $sql);
        $arr1 = mysqli_fetch_all($res,MYSQLI_ASSOC);
        $new_arr = json_encode($arr1);
        mysqli_free_result($res);

        if (isset($_POST['assign-marks'])){
            for($i=1;$i<=$count_stud;$i+=1){
                $marks = $_POST["marks-".$i];
                $id = $arr[$i-1]["M_id"];
                $sql = "UPDATE marks SET Marks = ".$marks." WHERE M_id = ".$id;
                if(!mysqli_query($conn, $sql)){
                    echo "<script>alert('Error faced in updating...');</script>";
                }
            }
        }
        $sql = "select Due_Date from class_task where Task_id =".$_GET['assign_id'];
        $res = mysqli_query($conn, $sql);
        $arr_4 = mysqli_fetch_row($res);
        $new_arr2 = json_encode($arr_4);
        
    ?>
    <nav class="navbar">
        <span>STUDY ROOM</span>
        <span class="links">
            <button class="logout" id="logout">Logout</button>
        </span>
    </nav>
    <div>
    <a  href="<?php echo "teacherClassPage.php?class_id=".$_GET['class_id']?>" class="back" style="text-decoration:none;color:blue;">&lt; back</a>
    </div><br><br>
    <div class="container">
            <div><br>
                <div class="theoryDiv">
                    <div id="Tdate"></div>
                    <h2>Instructions:</h2>
                    <p class="theory"></p>
                    <h2>Attachments:</h2>
                    <p class="attachments"></p>
                </div>
            </div>
        <div><br>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?class_id=".$_GET['class_id']."&assign_id=".$_GET['assign_id'];?>">
            <table align="center" width="60%" border="1" cellpadding="2" cellspacing='1'>

            <tr bgcolor="#ff6030">
                <th>Student Name</th>
                <th>Marks</th>
                <th>Out of</th>
                <th>Submission date</th>
            </tr>
            <?php
                $count=1;
                foreach($arr as $element)
                {
            ?>
                    <tr>
                        <td>
                            <span id="stud-<?php echo $count?>"><?php echo $element['S_Name']?></span>
                            <div id="attach-<?php echo $count?>" class="stud-attach">
                                <hr class="line">
                                <?php
                                    $sql = "SELECT Attach_id,file_name FROM 
                                    submissions_attachments WHERE Sub_id IN (SELECT Sub_id 
                                    FROM task_submission WHERE S_id=".$element['S_id']." AND Task_id=".$_GET['assign_id'].")";
                                    $res = mysqli_query($conn, $sql);
                                    $sub = mysqli_fetch_all($res, MYSQLI_ASSOC);
                                    foreach($sub as $single_sub){
                                        echo "<a target='_blank' href='view.php?id=".$single_sub['Attach_id']."&table=submissions_attachments'>".$single_sub['file_name']."</a><br>";
                                    }
                                ?>
                            </div>
                        </td>
                        <td width=20%><input type="text" name="marks-<?php echo $count?>" id="<?php echo $element['M_id']?>" class="table-input"></td>
                        <td><?php echo $element['Out_of']?></td> 
                        <td>
                            <?php
                                $sql = "select Sub_id from task_submission where S_id='".$element['S_id']."' and Task_id=".$_GET['assign_id'];
                                $res = mysqli_query($conn,$sql);
                                $arr = mysqli_fetch_row($res);
                                $Sub_id = $arr[0];

                                $sql = "select Sub_date from task_submission where Sub_id=".$Sub_id;
                                $res = mysqli_query($conn, $sql);
                                $arr = mysqli_fetch_row($res);
                                $sub_date = $arr[0];

                                echo $sub_date;
                            ?>
                        </td>
                    </tr>         
            <?php
                    $count+=1;
                }
            ?>
            </table><br>
            <button name="assign-marks" class="app-button">Assign Marks</button>
        </form>
        </div>
    </div>
    
    <script>
        document.getElementById("logout").onclick = function(){
            var url = "logout.php";
            window.location.href = url;
        }
        var p =document.querySelector('.theory'); 
        theoryDiv=document.querySelector('.theoryDiv');  
        var theory= <?= $new_arr?>;
        p.textContent=theory[0].Instructions;
        p.style.width="100%";
        p.style.minHeight="10vh";
        p.style.fontFamily="Arial";
        theory.forEach(element => {
            
            var link = document.createElement('a');
            link.textContent=element.File_name;
            link.target='_blank';
            link.href = "view.php?id="+element.N_a_id+"&table=notes_attachments";
            link.style.color = "#000";
            link.style.padding="10px";
            document.querySelector(".attachments").appendChild(link);    
        });
        theoryDiv.style.backgroundColor="#dcdcdc";
        theoryDiv.style.padding="20px";
        theoryDiv.style.margin="0 10%";
        theoryDiv.style.borderRadius="15px";

        var R =<?=$new_arr2?>[0];
        var date =document.getElementById("Tdate");
        var h1 =document.createElement("h3");
        h1.textContent="Due Date: "+R;
        date.appendChild(h1);
    </script>
</body>
</html>