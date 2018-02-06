<?php
session_start();  
include '../environment.php';
if(isset($_POST['login']))  
{  
    $user_email=$_POST['email'];  
    $user_pass=$_POST['pass'];
  
    $run=Database::connect()->query("SELECT * FROM users WHERE email='$user_email'")->fetchAll(PDO::FETCH_ASSOC); 
  
    if(count($run) > 0 && count($run) <2)  
    {  
        if (password_verify($user_pass, $run[0]["password"]) || $user_pass==$run[0]["password"] ) {
            $_SESSION['username']=$user_email;
            $_SESSION['name']=$run[0]["name"];
            $_SESSION["role"]=$run[0]["role"];
            $_SESSION["id"]=$run[0]["id"];
            header("location:index.php");
        } else {
            echo "<script>alert('Email or password is incorrect!')</script>";
        }
    }  
    else  
    {  
      echo "<script>alert('Email or password is incorrect!')</script>";  
    }  
} 
?>
<html>  
<head lang="en">  
    <meta charset="UTF-8">
    <link rel='shortcut icon' href="resources/images/printer.ico" type='image/x-icon'/ >
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <title>Login</title>  
</head>  
<style>  
    .login-panel {  
        margin-top: 150px;
    }  
  
</style>  
<body>
<div class="container">  
    <div class="container">
        <div class="row">

            <div class="col-md-4 col-sm-4 col-lg-4">
            </div> 
            <div class="col-md-4 col-sm-4 col-lg-4">  
                <div class="login-panel panel panel-success">  
                    <div class="panel-heading">  
                        <h3 class="panel-title">Sign In</h3>  
                    </div>  
                    <div class="panel-body">  
                        <form role="form" method="post" action="login.php">  
                            <fieldset>  
                                <div class="form-group"  >  
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>  
                                </div>  
                                <div class="form-group">  
                                    <input class="form-control" placeholder="Password" name="pass" type="password" value="">  
                                </div>  
      
      
                                    <input class="btn btn-lg btn-success btn-block" type="submit" value="login" name="login" > 
                            </fieldset>  
                        </form>  
                    </div>  
                </div>  
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4">
            </div>  
        </div>  
    </div>
</div>  
  
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
  
</body>  
  
</html>
