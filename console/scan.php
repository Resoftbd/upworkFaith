<?php
    include __DIR__.'/../environment.php';
    
    $options = getopt("t:o:s:c:z:f:i:p:");
    
    if (!$options['o'] && !$options['s'] && !$options['c'] && !$options['z'] && !$options['f']) {
        Console::showErrorAndDie('Please enter an address or filter as one of the -o, -s, -c, -z or -f params');
    }

    $types = isset($options['t']) ? explode(',', str_replace(' ', '', $options['t'])) : 'all';

    $taskManager = new TaskManager();
    $taskManager->setInteractiveMode(isset($options['i']) ? (int)$options['i'] : true);
    
    $scanner = new PlacesScanner($taskManager);
    $scanner->setPlaceTypes($types);
    $scanner->setCountry(isset($options['o']) ? $options['o'] : '');
    $scanner->setState(isset($options['s']) ? $options['s'] : '');
    $scanner->setCity(isset($options['c']) ? $options['c'] : '');
    $scanner->setZipcode(isset($options['z']) ? $options['z'] : '');
    $scanner->setFilter(isset($options['f']) ? $options['f'] : '');
//     $scanner->setId(isset($options['p']) ? (int)$options['p'] : null);
    
    $error = $taskManager->run($scanner);
    if ($error) {
        Console::showErrorAndDie($error);
    }