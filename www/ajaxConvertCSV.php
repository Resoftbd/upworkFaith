<?php
    include '../environment.php';
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        exit;
    }
    
    $id = (int)$_POST['id'];
    $db = Database::connect();
    $task = $db->query("SELECT * FROM tasks WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
    $filenameWithPano="manual-withpano-".substr($task['name'],0,50)."-generated.csv";
    $filenameWithoutPano="manual-withoutpano-".substr($task['name'],0,50)."-generated.csv";

    $csv = new CreateCSV();
    $csv->setDb($db);
    $csv->exportPlaces($id,$filenameWithPano,$filenameWithoutPano);