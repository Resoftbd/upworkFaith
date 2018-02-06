<?php
    include '../environment.php';
    include ROOT.'/www/ctrl.php';
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        exit;
    }
    
    $id = (int)$_POST['id'];
    $task = Database::connect()->query("SELECT * FROM tasks WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
    if ($task['csv_with_pano']) {
        @unlink(OUTPUT_DIR.'/'.$task['csv_with_pano']);
    }
    if ($task['csv_without_pano']) {
        @unlink(OUTPUT_DIR.'/'.$task['csv_without_pano']);
    }
    Database::connect()->query("DELETE FROM tasks WHERE id = $id");
    Database::connect()->query("DELETE FROM tasks_log WHERE task_id = $id");
    Database::connect()->query("DELETE FROM places WHERE task_id = $id");