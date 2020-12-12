<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.css">
    <title>Studyroom</title>
    <style>
        .task-submit{
            padding:5px;
            background-color: #dcdcdc;
            width: 60%;
            margin: 3% 20%;
            border-radius: 15px;
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
        #Sdate,#Tdate{
            text-align:right;
        }
    </style>
</head>
<body>
    <?php
    require_once("connection.php");
    session_start();
    if(!isset($_SESSION['log_id'])){
        echo "<script> location.replace('index.php'); </script>";
    }
    if (isset($_POST['setSub'])){
        

        $sql = "insert into task_submission(S_id,Task_id,Sub_date) 
            values(".$_SESSION['log_id'].",".$_GET['assign_id'].",'".date("Y-m-d")."')";
        mysqli_query($conn, $sql);

        $sql = "select Sub_id from task_submission where S_id='".$_SESSION['log_id']."' and Task_id=".$_GET['assign_id'];
        $res = mysqli_query($conn,$sql);
        $arr = mysqli_fetch_row($res);
        $Sub_id = $arr[0];

        $fileCount = count($_FILES['files']['name']);
        for($i=0;$i<$fileCount;$i++){
            $f_name = $_FILES['files']['name'][$i];
            $f_type = $_FILES['files']['type'][$i];
            $f_data = mysqli_real_escape_string($conn,file_get_contents($_FILES['files']['tmp_name'][$i]));
            $sql = "insert into submissions_attachments(Sub_id,S_file,file_name,mime_type) 
                values(".$Sub_id.",'".$f_data."','".$f_name."','".$f_type."')";
            if(!mysqli_query($conn, $sql)){
            echo "file not uploaded";
            }
        }
        
        $sql = "select max_marks from class_task where Task_id= ".$_GET['assign_id'];
        $res = mysqli_query($conn, $sql);
        $arr = mysqli_fetch_row($res);
        $OutOf=$arr[0];

        $sql="insert into marks(C_id,S_id,Sub_id,Out_of,Task_id)Values(".$_GET['class_id'].",".$_SESSION['log_id'].",".$Sub_id.",".$OutOf.",".$_GET['assign_id'].")";
        mysqli_query($conn, $sql);   
    }
    else if(isset($_POST['cancel'])){
        $sql = "select Sub_id from task_submission where S_id='".$_SESSION['log_id']."' and Task_id=".$_GET['assign_id'];
        $res = mysqli_query($conn,$sql);
        $arr = mysqli_fetch_row($res);
        $Sub_id = $arr[0];

        $sql="select Attach_id from submissions_attachments where Sub_id=".$Sub_id;
        $res = mysqli_query($conn, $sql);
        $arr5=mysqli_fetch_all($res);

        $sql="select M_id from marks where S_id=" .$_SESSION['log_id']." and Sub_id= ".$Sub_id;
        $res = mysqli_query($conn, $sql);
        $arr_m = mysqli_fetch_row($res);
    
        

        foreach ($arr5[0] as $elem){
            $sql="Delete from submissions_attachments where Attach_id =".$elem;
            mysqli_query($conn, $sql);
        }
    
        if($arr_m != NULL){
            $sql="Delete from marks where M_id=".$arr_m[0];
            mysqli_query($conn, $sql);

        }
        $sql="Delete from task_submission where Sub_id= ".$Sub_id;
        mysqli_query($conn, $sql);
        
        
        
    }

    $sql = "select Task_Name,Instructions from class_task where C_id=".$_GET['class_id']." and Task_id=".$_GET['assign_id'];
    $res = mysqli_query($conn, $sql);
    $arr = mysqli_fetch_row($res);
    $new_arr = json_encode($arr);
    
    $sql = "SELECT N_a_id,na.Task_id,Instructions,File_name from class_task as ct 
    join notes_attachments as na on ct.Task_id=na.Task_id where na.Task_id=".$_GET['assign_id'];
    $res = mysqli_query($conn, $sql);
    $arr1 = mysqli_fetch_all($res,MYSQLI_ASSOC);
    $new_arr_tfiles = json_encode($arr1);
    ?>
        <nav class="navbar"><span>STUDY ROOM</span></nav>
    <div>
        <a  href="<?php echo "classPage.php?class_id=".$_GET['class_id']?>" class="back" style="text-decoration:none;color:blue;">&lt; back</a>
    </div>
    <br>

    <div class="container">
        <h2 id="task-name"></h2>
        <div class="task-submit">
            <div id="Tdate"></div>
            <div id ="Sdate"></div>
            <div id="Ques">
                <h2>Instructions: </h2>
             </div>
            <div id="attachments"></div><br>
            <div id ="marks"></div>
            <div id="prevSub"></div>
            <form method="post" enctype="multipart/form-data">   
                <h3>Select File to upload:</h3>
                <br>
                <div>      
                    <input type="file" name="files[]" class='app-button' multiple>
                </div>
                <br>
                <div>
                    <input type="submit" value="Upload file" name="setSub" class='app-button'>
                </div>
                
                <br>  
            </form>
        </div><br><br>
    </div>
        <?php
            $sql = "select Sub_id from task_submission where S_id='".$_SESSION['log_id']."' and Task_id=".$_GET['assign_id'];
            $res = mysqli_query($conn,$sql);
            $arr = mysqli_fetch_row($res);
            if($arr!=Null){
                $sql = "SELECT Attach_id,file_name FROM submissions_attachments WHERE Sub_id IN (SELECT Sub_id 
                FROM task_submission WHERE S_id=".$_SESSION['log_id']." AND Task_id=".$_GET['assign_id'].")";
                $res = mysqli_query($conn, $sql);
                $temp = mysqli_fetch_all($res, MYSQLI_ASSOC);
                $sub = json_encode($temp);
                echo "<script>
                    var elem = document.getElementById('prevSub');
                    var arr = $sub;
                    var c1=0;
                    arr.forEach(element => {
                        if(c1==0){
                            var h2=document.createElement('h2');
                            h2.textContent='Your Submissions:';
                            elem.appendChild(h2);
                        }
                        var link = document.createElement('a');
                        var br = document.createElement('br');
                        link.textContent=element.file_name;
                        link.target='_blank';
                        link.style.color = '#000';
                        link.href='view.php?id='+element.Attach_id+'&table=submissions_attachments';
                        elem.appendChild(link);
                        elem.appendChild(br);
                        c1++;
                    });
                    elem.appendChild(document.createElement('br'));
                    var button = document.createElement('button');
                    button.setAttribute('class','app-button');
                    button.setAttribute('name','cancel');
                    button.textContent = 'Cancel Uploads';
                    var form = document.createElement('form');
                    form.setAttribute('method','post');
                    console.log(form);
                    form.appendChild(button);
                    elem.appendChild(form);
                </script>";
                
            }
            $sql = "select Due_Date from class_task where Task_id =".$_GET['assign_id'];
            $res = mysqli_query($conn, $sql);
            $arr = mysqli_fetch_row($res);
            $new_arr2 = json_encode($arr);

            $sql = "select Sub_id from task_submission where S_id='".$_SESSION['log_id']."' and Task_id=".$_GET['assign_id'];
            $res = mysqli_query($conn,$sql);
            $arr = mysqli_fetch_row($res);
            $Sub_id = $arr[0];


            if($Sub_id){
            $sql = "select Sub_date from task_submission where Sub_id=".$Sub_id;
            $res = mysqli_query($conn, $sql);
            $arr = mysqli_fetch_row($res);
            $new_arr3 = json_encode($arr);
            echo "<script>
                var P =$new_arr3;
                var date1 =document.getElementById('Sdate');
                var h2 =document.createElement('h3');
                h2.textContent='Submitted Date: '+P[0];
                date1.appendChild(h2);
            </script>";
            
            $sql="select M_id from marks where S_id=" .$_SESSION['log_id']." and Sub_id= ".$Sub_id;
            $res = mysqli_query($conn, $sql);
            $arr_m = mysqli_fetch_row($res);
            
            if($arr_m!=Null){
                $sql="select Marks from marks where M_id=".$arr_m[0];
                $res = mysqli_query($conn, $sql);
                $arr = mysqli_fetch_row($res);
                $new_arr4 = json_encode($arr);
                }
                echo "<script>
                var E =$new_arr4;
                var M =document.getElementById('marks');
                var h =document.createElement('h3');
                h.textContent='Marks: '+E[0];
                M.appendChild(h);
                </script>";

            }
        ?>
        <script>
            var task_name =<?=$new_arr?>[0];
            document.getElementById("task-name").textContent = task_name;
            var Q =<?=$new_arr?>[1];
            var quess =document.getElementById("Ques");
            var p =document.createElement("p");
            p.textContent=Q;
            quess.appendChild(p);

            var R =<?=$new_arr2?>[0];
            var date =document.getElementById("Tdate");
            var h1 =document.createElement("h3");
            h1.textContent="Due Date: "+R;
            date.appendChild(h1);
            
            var theory= <?= $new_arr_tfiles?>;
            var c=0;
            theory.forEach(element => {
                if(c==0){
                    var h2=document.createElement('h2');
                    h2.textContent='References:';
                    document.querySelector("#attachments").appendChild(h2);
                }
                var link = document.createElement('a');
                link.textContent=element.File_name;
                link.target='_blank';
                link.href = "view.php?id="+element.N_a_id+"&table=notes_attachments";
                link.style.color = "#000";
                link.style.padding="10px";
                document.querySelector("#attachments").appendChild(link);  
                c++;
            });
 
        </script>
        </body>
        </html>