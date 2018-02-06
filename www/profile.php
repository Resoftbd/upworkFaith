<?php
    include ROOT.'/www/ctrl.php';
?>


<html>
<head>
    <title>Google SEE INSIDE</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <style type="text/css">
        thead {
            font-weight: bolder;
        }
    
        td {
            font-size: 12px;
            background-color: #eef;
        }
    </style>
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
</head>
<body>    
<nav class="navbar navbar-default">
    <div class="container-fluid main-toolbar myToolBar">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.php" >Dashboard</a></li>
                <li><a href="scheduler.php" >Scheduler</a></li>
                <li><a href="processing.php" >Processing</a></li>
                <li><a href="settings.php" >Settings</a></li>

            </ul>
            <ul class="nav navbar-nav pull-right">
                <li><a href="adduser.php" >Add User</a></li>
                <li><a href="profile.php"><?php echo $_SESSION["name"]; ?></a> </li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container">
    <div class="col-sm-12 col-lg-12 col-md-12">
        <form action="ajaxUpdateUser.php" method="POST">
            <div class="panel panel-default panelFullHeight" style="margin-top: 30px;">
                <div class="panel-heading">User Data for <b><span id="nameUserChanged"><?php echo $_SESSION["name"]; ?> </span></b>
                </div>
                <div class="panel-body" style="margin-top: 30px;">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value='<?php echo $_SESSION["name"]; ?>' class="form-control"></input>
                    </div>
                    <div class="form-group">
                        <label>Email (<span style="color: red;"> Email can't be changed</span>)</label>
                        <input type="text" readonly="readonly" name="email" value='<?php echo $_SESSION["username"]; ?>' class="form-control"></input>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="pass" class="form-control"></input>
                    </div>
                </div>
                <div class="panel-footer">
                    <input type="submit"  class="btn btn-default" value="Update Data"></input>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>