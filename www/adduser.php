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
    <script type="text/javascript">
        function adduser() {
            var name=$("#name").val();
            var email = $("#email").val();
            var pass = $("#pass").val();
            //$.post('ajaxAddUser.php', {name: name,email:email,pass:pass}, function(response) {console.log(response)});
            $.ajax({
              type: "POST",
              url: "ajaxAddUser.php",
              data: { name: name, email:email, pass:pass},
                //added success callback instead of done
              success: function(response){
                  //decode json response
                  response = JSON.parse(response);
                  if(response.success === true){

                      $(".forAlertMessages").text("User successfully created");
                      $(".forAlertMessages").addClass("alert alert-success");
                      //clear fields
                      $("#name").val('');
                      $("#email").val('');
                      $("#pass").val('');
                      //remove alert box after 3 sec
                      setTimeout(function(){
                          removeAlert(1);
                      }, 3000);
                  }else{
                      $(".forAlertMessages").html(response.error);
                      $(".forAlertMessages").addClass("alert alert-danger");
                      //remove alert box after 3 sec
                      setTimeout(function(){
                          removeAlert(2);
                      }, 3000);
                  }
                }
            });
        };
        //callback function to remove alert boxes
        function removeAlert(param){
            if(param == 1){
                $(".forAlertMessages").removeClass("alert alert-success");
                $(".forAlertMessages").text('');
            }else{
                $(".forAlertMessages").removeClass("alert alert-danger");
                $(".forAlertMessages").text('');
            }
        }
    </script>
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
        <div class="panel panel-default panelFullHeight" style="margin-top: 30px;">
            <div class="panel-heading">
              <h3 class="panel-title">Add New User</h3>
            </div>
            <div class="panel-body" style="margin-top: 30px;">
                <div class="forAlertMessages"></div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="name" class="form-control"></input>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" id="email" class="form-control"></input>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="pass" class="form-control"></input>
                </div>
            </div>
            <div class="panel-footer">
                <input type="button" onclick="adduser()" class="btn btn-default adduser" value="Add User"></input>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>