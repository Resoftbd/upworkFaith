<?php
     include '../environment.php';
    include ROOT.'/www/ctrl.php';
     $tasks = Database::connect()->query('SELECT t.*, GROUP_CONCAT(CONCAT(tl.log_time , ": ", tl.event) SEPARATOR "<br>") log, CASE WHEN TIMESTAMPDIFF(SECOND, t.last_pending_time, NOW()) > 3600 THEN 1 ELSE 0 END AS dead
            FROM `tasks` t
            LEFT JOIN `tasks_log` tl ON t.id = tl.task_id
            GROUP BY t.id
            ORDER BY t.start_time DESC, tl.id ASC
            LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
     $logApi = Database::connect()->query('SELECT GROUP_CONCAT(CONCAT(tl.log_time , ": ", tl.event) SEPARATOR "<br>") log
            FROM `tasks` t
            LEFT JOIN `tasks_api_log` tl ON t.id = tl.task_id
            GROUP BY t.id
            ORDER BY t.start_time DESC, tl.id ASC
            LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
     $ind=0;
     
     function cut($str) {
         return mb_substr($str, 0, 63) . (mb_strlen($str) > 63 ? '...' : '');
     }
     
     function human_filesize($bytes, $dec = 2)
     {
         $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
         $factor = floor((strlen($bytes) - 1) / 3);
     
         return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . @$size[$factor];
     }

     foreach ($tasks as $i => $task) {
         $filepath = OUTPUT_DIR.'/'.$tasks[$i]['csv_with_pano'];
         if (is_readable($filepath) && is_file($filepath)) {
            $tasks[$i]['csv_with_pano_size'] = human_filesize(filesize($filepath), 0);
         }
         $filepath = OUTPUT_DIR.'/'.$tasks[$i]['csv_without_pano'];
         if (is_readable($filepath) && is_file($filepath)) {
            $tasks[$i]['csv_without_pano_size'] = human_filesize(filesize($filepath), 0);
         }
     }
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
        window.setInterval(function () {location.reload();}, 10000);

        $(document).ready(function () {
            $('.remove-button').click(function () {
                if (!confirm('Are you sure?')) {
                    return;
                }
                var id = $(this).attr('data-id');
                $.post('ajaxRemoveTask.php', {id: id}, function() {location.reload();});
            });
            $('.csv-button').click(function () {
                if (!confirm('Are you sure?')) {
                    return;
                }
                var id = $(this).attr('data-id');
                $.post('ajaxConvertCSV.php', {id: id}, function() {location.reload();});
            });

            $('.stop-button').click(function () {
                if (!confirm('Are you sure?')) {
                    return;
                }
                var id = $(this).attr('data-id');
                $.post('ajaxStopTask.php', {id: id}, function() {location.reload();});
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
<table style="width:100%" border="1">
    <thead>
        <tr>
            <td>Name</td>
            <td>Progress</td>
            <td>Failed</td>
            <td>Status</td>
            <td>Log</td>
            <td>Api Log</td>
            <td>Started at</td>
            <td>Updated at</td>
            <td>Links</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
        <tr>
            <td title="<?php echo $task['name'] ?>"><?php echo cut($task['name']); ?></td>
            <td><?php echo (int)$task['processed'].'/'.(int)$task['total']; ?></td>
            <td><?php echo (int)$task['failed'].'/'.(int)$task['total']; ?></td>
            <td><?php echo $task['status']; ?></td>
            <td><?php echo $task['log']; ?></td>
            <td><?php echo $logApi[$ind]['log'];$ind++; ?></td>
            <td><?php echo $task['start_time']; ?></td>
            <td><?php echo $task['last_pending_time']; ?></td>
            <td>
                <?php if ($task['csv_with_pano'] != NULL && $task['csv_without_pano'] != NULL) : ?>
                    <?php if ($task['csv_with_pano_size']): ?>
                        <a href="<?php echo Url::getCurrent() ?>/downloadCSV.php?filename=<?php echo $task['csv_with_pano'] ?>" download="<?php echo $task['csv_with_pano'] ?>"><?php echo cut($task['csv_with_pano']) ?></a> (<?php echo $task['csv_with_pano_size'] ?>)<br>
                    <?php endif; ?>
                    <?php if ($task['csv_without_pano_size']): ?>
                        <a href="<?php echo Url::getCurrent() ?>/downloadCSV.php?filename=<?php echo $task['csv_without_pano'] ?>" download="<?php echo $task['csv_without_pano'] ?>"><?php echo cut($task['csv_without_pano']) ?></a> (<?php echo $task['csv_without_pano_size'] ?>)
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($task['csv_with_pano_size'] || $task['dead']): ?>
                    <input type="button" value="Remove" class="remove-button" data-id="<?php echo $task['id'] ?>" />
                    <?php if ($task['csv_with_pano'] == NULL && $task['csv_without_pano'] == NULL) :?>
                    <input type="button" value="Export CSV" class="csv-button" data-id="<?php echo $task['id'] ?>" />
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (!$task['stop'] && !($task['csv_with_pano_size'] || $task['dead'])): ?>
                    <input type="button" value="Stop" class="stop-button" data-id="<?php echo $task['id'] ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>