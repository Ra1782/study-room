<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.css">
    <title>Studyroom</title>
</head>
<body>
    <nav class="navbar">
        <span class="title">STUDY ROOM</span>
        <a class="navbar-brand" href="index.php" class="logo"><img src="./images/logo.png" width="7%" height="12%" alt="LOGO"/></a> 
    </nav>
    <div class="info" style="font-family:'Arial'">
        <p>Welcome to Studyroom,</p>
        <p>We here provide a platform for students to get their study notes, submit their 
            <br>
            tasks, attempt quizzes ... and much more.</p>
    </div>
    <div class = "mainPage">
        <div><h2>|| Login here ||</h2></div>
        <div class="logins">
            <button type ="submit" class="login app-button modal-btn-1" >Login </button>
        </div>
        <div><h2>|| New User? Register here ||</h2></div>
        <div class="registers">
            <button type ="submit" class="register app-button modal-btn-2">Register</button>
        </div>
    </div>
    <form method="post" action="login.php">
        <div class="modal-bg-1">
            <div class="modal">
                <h2>Enter Login details</h2>
                <label for="login-email" >Email:</label>
                <input type="email" name="login-email" required>
                <label for="login-password" >Password:</label>
                <input type="password" name="login-password" required>
                <button>LOGIN</button>
                <span class="modal-close-1">X</span>
                <div></div>
            </div>
        </div>
    </form>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="modal-bg-2">
        <div class="modal">
            <h2>Enter Registration details</h2>
            <label for="name" >Name:</label>
            <input type="text" name="name" required>
            <label for="email" >Email:</label>
            <input type="email" name="email" required>
            <label for="password" >Password:</label>
            <input type="password" name="password" required>
            <label for="role">Role:</label>
            <select name="role" id="role">
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
            <button>REGISTER</button>
            <span class="modal-close-2">X</span>
            <div></div>
        </div>
    </div>
    </form>
    <?php
    require_once("connection.php");
    if(isset($_POST['name'])&&isset($_POST['email'])&&isset($_POST['password'])
        && $_POST['name']!="" && $_POST['email']!="" && $_POST['password']!="" && $_POST['role']=='teacher'){
        $sql = "insert into teacher (T_Name, Email,Pass) Values(?,?,?)";
        $name = $_POST['name'];    
        $email=$_POST['email'];
        $password=$_POST['password'];
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $name, $email,$password);
            mysqli_stmt_execute($stmt);  
            unset($_POST);
            echo "<script>alert('Teacher Registered succesfully')</script>";
        }
    }
    else if(isset($_POST['name'])&&isset($_POST['email'])&&isset($_POST['password'])
        && $_POST['name']!="" && $_POST['email']!="" && $_POST['password']!="" && $_POST['role']=='student'){
        $sql = "insert into student (S_Name, Email,Pass) Values(?,?,?)";
        $name = $_POST['name'];    
        $email=$_POST['email'];
        $password=$_POST['password'];
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $name, $email,$password);
            mysqli_stmt_execute($stmt);  
            unset($_POST);
            echo "<script>alert('Student Registered succesfully')</script>";
        }
        
    }
    mysqli_close($conn);
?>
    <footer> 
        <p>Join us on</p>
        <div>
            <b>|</b>&nbsp;<a href="#">GITHUB</a>&nbsp;<b>|</b>&nbsp;
            <a href="#">LINKEDIN</a>&nbsp;<b>|</b>&nbsp;
            <a href="#">FACEBOOK</a>&nbsp;<b>|</b>&nbsp;
            <a href="#">TWITTER</a>&nbsp;<b>|</b>&nbsp;
        </div>
        <p>&copy; Studyroom. All rights reserved.</p>
    </footer>
    <script>
        var modalBtn1 = document.querySelector('.modal-btn-1');
        var modalBg1 = document.querySelector('.modal-bg-1');
        var modalClose1 = document.querySelector('.modal-close-1');

        modalBtn1.addEventListener('click', function(){
            modalBg1.classList.add('bg-active');
        });
        modalClose1.addEventListener('click', function(){
            modalBg1.classList.remove('bg-active');
        });

        var modalBtn2 = document.querySelector('.modal-btn-2');
        var modalBg2 = document.querySelector('.modal-bg-2');
        var modalClose2 = document.querySelector('.modal-close-2');

        modalBtn2.addEventListener('click', function(){
            modalBg2.classList.add('bg-active');
        });
        modalClose2.addEventListener('click', function(){
            modalBg2.classList.remove('bg-active');
        });

    </script>
</body>
</html>