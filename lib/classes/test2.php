<?php
$place = new StdClass();
$panoid;
$foundSeeInside;
include 'GooglePanoramaScraper.php';
$links = array(
	//'https://maps.google.com/?cid=17261734203977448641',
	'https://maps.google.com/?cid=14833539909395457229'
	);
$g = array(
	//array('lat'=>'29.6524773','lng'=>'-82.3438962'),
	array('lat'=>'47.6285584','lng'=>'-122.1643029'),
);
$panorama = new GooglePanoramaScraper();
foreach ($links as $key => $value) {
	$place=new StdClass();
	$place->geometry = new StdClass();
	$place->geometry->location = new StdClass();
	$place->geometry->location->lat = $g[$key]['lat'];
	$place->geometry->location->lng = $g[$key]['lng'];
	$place->url = $value;
	$data = $panorama->fetchPanoramaData($place);
	///$data = (string)file_get_contents($place->url);

	var_dump($place);
}

$date = new DateTime();
var_dump($date);
$date->setTimezone(new DateTimeZone('PST'));
var_dump($date);

?>