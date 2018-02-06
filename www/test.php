<?php
include '../environment.php';

$params = array(
        'address' => 'restaurant - The Pumphouse Bar & Grill bellevue',
        'key' => 'AIzaSyCROoViFuQqNOXBLkZWFcM23xQ3pyxa7lo'
);
$queryString = http_build_query($params);
$jsonData = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?'.$queryString);
echo 'https://maps.googleapis.com/maps/api/geocode/json?'.$queryString;
$data = json_decode($jsonData);
print_r($data);