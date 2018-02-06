<?php
    define('ROOT', __DIR__);
    define('OUTPUT_DIR', ROOT.'/data/places');
    error_reporting(0);
    ini_set('display_errors', 1);
    set_time_limit(0);
    ignore_user_abort(true);
    ini_set('memory_limit', '712M');
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('max_execution_time', 300);
    ini_set("error_log", ROOT."/error_log");
    include ROOT.'/config/database.php';
    
    if (!isset($_SERVER['SERVER_NAME'])) {
        include ROOT.'/lib/classes/Console.php';
    }
    include ROOT.'/lib/classes/Country.php';
    include ROOT.'/lib/classes/CSVExporter.php';
    include ROOT.'/lib/classes/Database.php';
    include ROOT.'/lib/classes/TaskManager.php';
    include ROOT.'/lib/classes/PlaceMapper.php';
    include ROOT.'/lib/classes/PlacesScanner.php';
    include ROOT.'/lib/classes/GoogleGeoCode.php';
    include ROOT.'/lib/classes/GooglePlaceRadarSearch.php';
    include ROOT.'/lib/classes/GooglePlaceDetails.php';
    include ROOT.'/lib/classes/GooglePanoramaScraper.php';
    include ROOT.'/lib/classes/Settings.php';
    include ROOT.'/lib/classes/Url.php';
    include ROOT.'/lib/classes/ZipCodesSearcher.php';
    include ROOT.'/lib/classes/CreateCSV.php';
