<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.css">
    <title>Studyroom</title>
<style>
  table{
    border-spacing: 15px;
  }
  .back{
      font-size: 20px;
      border: 0px solid;
      padding: 5px;
      margin: 5px;
      position: absolute;
      left: 5px;
  }
</style>

  </head>
<body>
<?php
      require_once("connection.php");
      $Task_Name = "";
      $C_id=$_GET['class_id'];
        if(isset($_POST['name'])&&isset($_POST['Due_Date'])&&isset($_POST['Instruction'])&&isset($_POST['marks'])){
        $sql="insert into class_task(Task_Name,C_id,Due_Date,Instructions,has_attach,max_marks)Values(?,?,?,?,?,?)";
        $Task_Name=$_POST['name'];
        $Due_Date=$_POST['Due_Date'];
        $Instructions=$_POST['Instruction'];
        $max_marks=$_POST['marks'];
      $has_attach=0;
      if(file_exists($_FILES['fileToUpload']['tmp_name'])){
        $has_attach=1;
      }

        if($stmt = mysqli_prepare($conn, $sql)){
          mysqli_stmt_bind_param($stmt, "ssssds",$Task_Name,$C_id,$Due_Date,$Instructions,$has_attach,$max_marks);
          if(mysqli_stmt_execute($stmt)){
              echo "<script>alert('Task created')</script>";
          }
          else{
              echo "<script>alert('Task Not created')</script>";
          }
      }
      if(isset($_POST['submit']) && isset($_FILES['fileToUpload'])){
        $sql = "select Task_id from class_task where Task_Name='".$Task_Name."' and C_id=".$C_id;
        $res = mysqli_query($conn,$sql);
        $arr = mysqli_fetch_row($res);
        $Task_id = $arr[0];
        $f_name = $_FILES['fileToUpload']['name'];
        $f_type = $_FILES['fileToUpload']['type'];
        $f_data = mysqli_real_escape_string($conn,file_get_contents($_FILES['fileToUpload']['tmp_name']));
        $sql = "insert into notes_attachments(Task_id,N_file,File_name,mime_type) 
            values(".$Task_id.",'".$f_data."','".$f_name."','".$f_type."')";
        if(!mysqli_query($conn, $sql)){
          echo "file not uploaded";
        }
      }
      
    }
      ?>
        <nav class="navbar"><span>STUDY ROOM</span></nav>
  <div>
    <a href=<?php echo "teacherClassPage.php?class_id=".$_GET['class_id']?> class="back" style="text-decoration:none;color:blue;">&lt; back</a>
  </div><br><br>
    <div class="container">
        <br>
        <br>
        <br>                    
        <form method="post" enctype="multipart/form-data">
        <table align="center" width="400" cellpadding = "10" cellspacing="2" >
        <tr>
          <td>Name</td>
          <td> <input type="text" name ="name" placeholder="Enter Name of Task"></td>
        </tr>
        <tr>
          <td >Enter the Instruction</td>          
          <td ><textarea name="Instruction"  cols="35" rows="7"></textarea></td>
          
        </tr>
        <tr>
          <td colspan="2">Select File to upload:</td>
        </tr>
        <tr>  
          <div>
          <td colspan="2"><input type="file" name="fileToUpload" id="fileToUpload" class='app-button'></td>
        </div>
        </tr>
        
        <tr>
            <td>Due date</td>
            <td><input type='date' name="Due_Date" placeholder="Enter date"></td>
        </tr>
        <tr>
            <td>Marks</td>
            <td><input type='number' name="marks" placeholder="Enter marks"></td>
        </tr>      
        <tr>
          <td></td>  
          <td><input type="submit"  name="submit" class='app-button'></td>
        </tr>
        
        </table>
    </form>
    </div>     
        </body>
        </html>