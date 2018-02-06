<?php
    include '../environment.php';
    
    include ROOT.'/www/ctrl.php';
    $scheduledTasks = Database::connect()->query("SELECT * FROM scheduled_tasks ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Google SEE INSIDE</title>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.11.2.custom.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="css/ui.dropdownchecklist.themeroller.css">
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.11.2.custom.min.js"></script>
    <script type="text/javascript" src="js/ui.dropdownchecklist-1.5-min.js"></script>
    <script type="text/javascript" src="js/knockout-3.3.0.js"></script>
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
    Scheduled tasks:
    <table id="keys-fieldset">
        <thead>
            <th>Country</th>
            <th>State</th>
            <th>City</th>
            <th>Zip</th>
            <th>Additional filter</th>
            <th>Search for</th>
            <th></th>
        </thead>
        <tbody data-bind="foreach: tasks">
            <td>
                <select data-bind="options: availableCountries,
                       optionsCaption: 'Not selected',
                       value: country,
                       event: {change: loadStates}">
                </select>
            </td>
            <td>
                <span data-bind="visible: availableStates().length == 0"><input type="text" name="state" value=""></span>
                <span data-bind="visible: availableStates().length > 0">
                    <select data-bind="options: availableStates,
                           optionsCaption: 'Not selected',
                           value: state">
                    </select>
                </span>
            </td>
            <td>
                <input type="text" data-bind="value: city"> 
            </td>
            <td>
                <input type="text" data-bind="value: zipcode"> 
            </td>
            <td>
                <input type="text" data-bind="value: filter"> 
            </td>
            <td>
                <select data-bind="options: availableTypes,
                            optionsText: 'name',
                            optionsValue: 'type',
                            optionsCaption: 'All',
                            selectedOptions: types,
                            attr: {id: 'types-field-' + $index()}" multiple="multiple">
                </select>
            </td>
            <td>
                <input type="button" value="Remove" data-bind="click: remove" />
                <input type="button" value="Run" data-bind="click: run, visible: !running()" />
            </td>
            </tr>
        </tbody>
    </table>
    <input type="button" value="Add" data-bind="click: addNew" />
    <input type="button" value="Save" data-bind="click: save, visible: !saving()" />
</div>

<script type="text/javascript">

var Task = function (id, country, state, city, zipcode, filter, types) {
    this.id = ko.observable(id);
    this.country = ko.observable(country);
    this.state = ko.observable(state);
    this.city = ko.observable(city);
    this.zipcode = ko.observable(zipcode);
    this.filter = ko.observable(filter);
    this.types = ko.observableArray(types);
    this.availableStates = ko.observableArray([]);
    this.running = ko.observable(false);
};
    
var myViewModel = function () {
    var self = this;
    
    this.tasks = ko.observableArray([]);
    this.availableCountries = ko.observableArray(<?php echo json_encode(array_values(Country::$availableCountries)) ?>);
    this.availableTypes = ko.observableArray();

    this.saving = ko.observable(false);
    
	this.add = function(task) {
    	this.loadStates(task, function () {
        	self.tasks.push(task);
        	$("#types-field-" + self.tasks.indexOf(task)).dropdownchecklist({
            	firstItemChecksAll: true
        	});
    	});
	};
	
	this.addNew = function() {
    	this.add(new Task('', '', '', '', '', '', ''));
	};

	this.remove = function (task) {
		if (!confirm('Are you sure?')) {
			return;
		}
	    tasks.remove(task);
	};

	this.save = function () {
	    self.saving(true);
		var tasks = this.tasks();
		var tasksToSend = [];
		for (var i = 0; i < tasks.length; i++) {
		    tasksToSend.push({
			    id: tasks[i].id(),
			    country: tasks[i].country(),
			    state: tasks[i].state(),
			    city: tasks[i].city(),
			    zipcode: tasks[i].zipcode(),
			    filter: tasks[i].filter(),
			    types: tasks[i].types()
			});
		}
		$.post('ajaxSaveScheduledTasks.php', {tasks: tasksToSend}, function () {self.saving(false);alert('Changes were saved successfully.');});
	};

	this.run = function (task) {
		task.running(true);
		$.post('index.php', {
			country: task.country(),
			state: task.state(),
			city: task.city(),
			zipcode: task.zipcode(),
			filter: task.filter(),
			types: task.types(),
			auto: true
		}, function() {
			task.running(false);
			alert('The task was run successfully.');
		});
	};

	this.loadStates = function (task, callback) {
		$.get('ajaxGetRegions.php', {country: task.country(), json: 1}, function (response) {
		    task.availableStates.removeAll();
		    var states = JSON.parse(response);
			for (var i = 0; i < states.length; i++) {
			    task.availableStates.push(states[i]);
			}
			if (typeof callback == 'function') {
			    callback();
			}
		});
	};

	$(document).ready(function () {
	    <?php foreach (PlacesScanner::$availableTypes as $type => $name): ?>
	    self.availableTypes.push({type: <?php echo json_encode($type)?>, name: <?php echo json_encode($name)?>});
	    <?php endforeach; ?>
	    
	    <?php foreach ($scheduledTasks as $task): ?>
	    self.add(new Task(
	        <?php echo json_encode($task['id']); ?>,
	        <?php echo json_encode($task['country']); ?>,
	        <?php echo json_encode($task['state']); ?>,
	        <?php echo json_encode($task['city']); ?>,
	        <?php echo json_encode($task['zipcode']); ?>,
	        <?php echo json_encode($task['filter']); ?>,
	        <?php echo json_encode(unserialize($task['types'])); ?>
	    ));
	    <?php endforeach; ?>
	});
};

ko.applyBindings(myViewModel);

</script>

<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>