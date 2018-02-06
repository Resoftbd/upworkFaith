<?php
    include '../environment.php';
    include ROOT.'/www/ctrl.php';
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        exit;
    }

    $tasksToRemove = Database::connect()->query("SELECT id FROM scheduled_tasks")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($_POST['tasks'] as $task) {
        if ($task['id']) {
            Database::connect()->prepare("UPDATE scheduled_tasks
                SET
                    country = ?,
                    state = ?,
                    city = ?,
                    zipcode = ?,
                    filter = ?,
                    types = ?    
                WHERE id = ?")->execute(array(
                    $task['country'],
                    $task['state'],
                    $task['city'],
                    $task['zipcode'],
                    $task['filter'],
                    serialize($task['types']),
                    $task['id']
                )
            );
            if(($key = array_search($task['id'], $tasksToRemove)) !== false) {
                unset($tasksToRemove[$key]);
            }
            continue;
        }
        
        Database::connect()->prepare("INSERT INTO scheduled_tasks (country, state, city, zipcode, filter, types) VALUES (?, ?, ?, ?, ?, ?)")->execute(array(
            $task['country'],
            $task['state'],
            $task['city'],
            $task['zipcode'],
            $task['filter'],
            serialize($task['types'])
        ));
    }
    if (count($tasksToRemove)) {
        Database::connect()->query("DELETE FROM scheduled_tasks WHERE id IN (".implode(',', $tasksToRemove).")");
    }