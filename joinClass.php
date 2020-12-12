<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.css">
    <title>Register Student</title>
    <style>
        .form1{
            height: 400px;
            padding-top: 40px;
 
        }
        p{
            margin-top: 40px;
            
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
        <a href="studentHomePage.php" class="back" style="text-decoration:none;color:blue;">&lt; back</a>
    </div>
    <p><strong>JOIN CLASS</strong></p>
    <div class="container">
    <div class="form1">
    <form method='post' action='studentHomePage.php'>
        
        <table align="center" cellpadding = "10" cellspacing="10">
        
        <tr>
            <td>Class code</td>
            <td><input type="text" name="Class_code" maxlength="30" placeholder="Enter your class code" /></td>
        </tr>
        
        <tr>
            <td></td>
            <td><input type="submit" name ="submit" class="app-button">
                      </tr>
        </table>
 
    </form>
    </div>
    </div>
                      
</body>
</html>