<?php
include '../environment.php';

    include ROOT.'/www/ctrl.php';
$google_api_keys = Settings::getAllGoogleAPIKeys();
?>


<html>
<head>
    <title>Google SEE INSIDE</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        $('#save-button').click(function () {
            $.post(
                'ajaxSaveSettings.php',
                {
                    google_api_keys: $('input[name^=google_api_key]').map(function(){return $(this).val();}).get() || []
                },
                function () {
                    alert('Settings were saved successfully');
                }
            );
        });
        
        var bindRemoveButtonEvent = function () {
            $('.remove-key-button').click(function () {
                if (!confirm('Are you sure?')) {
                    return;
                }
                $(this).parent().remove();
            });
        };

        bindRemoveButtonEvent();

        $('#add-key-button').click(function () {
            $('#keys-fieldset').append('<div><input size="50" type="text" name="google_api_key[]" value="" /> <input type="button" value="Remove" class="remove-key-button" /></div>');
            $('.remove-key-button').unbind();
            bindRemoveButtonEvent();
        });
    });
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
<div>
    Google API keys:
    <div id="keys-fieldset">
        <?php foreach ($google_api_keys as $key): ?>
        <div><input size="50" type="text" name="google_api_key[]" value="<?php echo $key ?>" /> <input type="button" value="Remove" class="remove-key-button" /></div>
        <?php endforeach; ?>
    </div>
    <input type="button" value="Add" id="add-key-button" />
    <input type="button" value="Save" id="save-button" />
</div>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

</body>
</html>