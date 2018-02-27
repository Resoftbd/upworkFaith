<?php
include __DIR__.'/../environment.php';

$result = '';

if(isset($_POST)){
	if($_POST['country'] || $_POST['state'] || $_POST['city'] || $_POST['zipcode'] || $_POST['filter']) {
	    $types = (array)$_POST['types'];

	    $scanner = new PlacesScanner();

	    $scanner->setPlaceTypes($types);
	    $scanner->setCountry($_POST['country']);
	    $scanner->setState($_POST['state']);
	    $scanner->setCity($_POST['city']);
	    $scanner->setZipcode($_POST['zipcode']);
	    $scanner->setFilter($_POST['filter']);
	    $scanner->setId((int)$_POST['pid']);
	    $scanner->setAuto($_POST['auto']);

        $taskManager = new TaskManager();
        if (!$scanner->getId()) {
            $taskManager->setBackgroundMode(true);
        }
        if ($_POST['email']) {
            $scanner->setEmailToReport($_POST['email']);
        }
        $result .= "The proccess was run successfully. You can check statuses of your requests and download the report files on the <a href=\"".Url::getCurrent()."/processing.php\">Status checking page</a>";


	    $error = $taskManager->run($scanner);
	} else {
		$message .= 'Please fill at least one field';
	}
}
$countryName  = Country::getCountry();
?>

<html>
<head>
    <title>Google SEE INSIDE</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.11.2.custom.css">
    <link rel="stylesheet" type="text/css" href="css/ui.dropdownchecklist.themeroller.css">
		<link rel="stylesheet" type="text/css" href="backend_resources/chosen/chosen.min.css"></script>
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.11.2.custom.min.js"></script>
    <script type="text/javascript" src="js/ui.dropdownchecklist-1.5-min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(e) {
        	$("#category").dropdownchecklist({ firstItemChecksAll: true });

        	$('select[name=country]').change(function () {
            	var country = $(this).val();
            	console.log(country);


            	if (!country) {
                	$('#state-field').html('<input type="text" name="state" value="">');
                	return;
            	}

							$.get(
									'ajaxGetRegions.php',
									{ country: country },
									function (response) {
											if (response) {
													$('#state-field').html(response);
											} else {
												//get cities
												//getCities(country);
													$('#state-field').html('<input type="text" name="state" value="">');
											}
									}
							);
            });
						function getCities(country){
								$.get(
									'ajaxGetCities.php',
									{ country : country },
									function(response){
										console.log(response);
										if(response){
                                            $('#city-field').html(response);
										}
									}
								);
						}
        });
    </script>
    <style type="text/css">
        #email-field {
            display: none;
        }
    </style>
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

<div style="text-align:center">
    <p style='color:red'><?php echo $message; ?></p>
    <div><?php echo $result; ?></div>
    <form class="" id="" name="" method="post" action="">
        <div>
            <label>Country:</label>
            <select name="country" id="country">
            <option value="-">Not selected</option>
            <?php foreach($countryName as $country): ?>
            <option value="<?php echo $country['country_code'] ?>"><?php echo $country['name'] ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <br>
        <div>
            <label>State:</label>
            <span id="state-field"><input type="text" name="state" value=""></span>
        </div>
        <br>
        <div>
            <label>City:</label>
          		  <input type="text" name="city" value="">
        </div>
        <br>
        <div>
            <label>Zip:</label>
            <input type="text" name="zipcode" value="">
        </div>
        <br>
        <div>
            <label>Additional filter:</label>
            <input type="text" name="filter" value="">
        </div>
        <br>
        <div>
            <label>Search for:</label>
            <select id="category" name="types[]" multiple="multiple">
                <option>All</option>
                <?php foreach (PlacesScanner::$availableTypes as $type => $name): ?>
                <option value="<?php echo $type ?>"><?php echo $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>
        <div>
            <label>Email to report: <input type="text" name="email" /></label>
        </div>
        <br><br>
        <div>
            <input class="btn btn-lg btn-primary" type="submit" name="Search" value="Search">
        </div>
    </form>
</div>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="backend_resources/chosen/chosen.jquery.min.js"></script>
<!-- init autofill -->
<script>
	(function($){
			$('#country').chosen();
	})(jQuery);

</script>
</body>
</html>
