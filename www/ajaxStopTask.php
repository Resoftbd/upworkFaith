<?php
    include '../environment.php';
    include ROOT.'/www/ctrl.php';
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        exit;
    }
    
    $id = (int)$_POST['id'];
    Database::connect()->query("UPDATE tasks SET stop = 1, status = 'Stopped' WHERE id = $id")->fetch(PDO::FETCH_ASSOC);