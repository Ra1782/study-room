<?php 
    session_start();
    require_once("connection.php");
    if(! $conn ) {
        die('Could not connect: ' . mysqli_connect_error());
    }
    if(isset($_POST['login-email'])&&isset($_POST['login-password'])
        && $_POST['login-email']!="" && $_POST['login-password']!=""){
        $tMailsResult = mysqli_query($conn, "select Email from teacher");
        $sMailsResult = mysqli_query($conn, "select Email from student");
        $aMailsResult = mysqli_query($conn, "select A_email from admin");
        $tMails = mysqli_fetch_all($tMailsResult,MYSQLI_NUM);
        $sMails = mysqli_fetch_all($sMailsResult,MYSQLI_NUM);
        $aMails = mysqli_fetch_all($aMailsResult,MYSQLI_NUM);
        $email = $_POST['login-email'];
        $password = $_POST['login-password'];
        
        $flag=0;
        $login_flag=0;

        foreach($aMails as $value){
            foreach($value as $val){
                if($val==$email){
                    $flag=1;
                    $res=mysqli_query($conn, "select * from admin where A_email='".$email."' and A_pass='".$password."'");
                    $passwordDB = mysqli_fetch_assoc($res);
                    $login_id = $passwordDB['A_id'];
                    $login_name = $passwordDB['A_name'];
                    unset($_POST);
                    if($passwordDB){
                        $_SESSION['log_id'] = $login_id;
                        $_SESSION['log_name'] = $login_name;
                        $_SESSION['log_email'] = $email;
                        $_SESSION['log_pass'] = $password;
                        echo "<script> location.replace('admin.php'); </script>";
                    }
                }
            }    
        }
        
        foreach($tMails as $value){
            foreach($value as $val){
                if($val==$email){
                    $flag=1;
                    $res=mysqli_query($conn, "select * from teacher where Email='".$email."' and Pass='".$password."'");
                    $passwordDB = mysqli_fetch_assoc($res);
                    $login_id = $passwordDB['T_id'];
                    $login_name = $passwordDB['T_Name'];
                    unset($_POST);
                    if($passwordDB){
                        $_SESSION['log_id'] = $login_id;
                        $_SESSION['log_name'] = $login_name;
                        $_SESSION['log_email'] = $email;
                        $_SESSION['log_pass'] = $password;
                        echo "<script> location.replace('teacherHomePage.php'); </script>";
                    }
                }
            }    
        }
        foreach($sMails as $value){
            foreach($value as $val){
                if($val==$email){
                    $flag=1;
                    $res=mysqli_query($conn, "select * from student where Email='".$email."' and Pass='".$password."'");
                    $passwordDB = mysqli_fetch_assoc($res);
                    $login_id = $passwordDB['S_id'];
                    $login_name = $passwordDB['S_Name'];
                    unset($_POST);
                    if($passwordDB){
                        $_SESSION['log_id'] = $login_id;
                        $_SESSION['log_name'] = $login_name;
                        $_SESSION['log_email'] = $email;
                        $_SESSION['log_pass'] = $password;
                        echo "<script> location.replace('studentHomePage.php'); </script>";
                    }
                }
            }
        }
        if($flag==0){
            echo "<script>
                alert('Entered Email does not exists. Please register first');
                window.location.href = 'index.php';
            </script>";
        }
        else if($login_flag==0){
            echo "<script>
                alert('Login failed, please try again');
                window.location.href = 'index.php';
            </script>";
        }
    }
?>