<?php
include '../environment.php';

include ROOT.'/www/ctrl.php';
$cities  = Country::getCities($_GET['country']);

if ($_GET['json']) {
    echo json_encode($cities);
    exit;
}

if (!count($cities)) {
    exit;
}
 ?>
 <select name="city">
 <option>Not selected</option>
 <?php foreach($cities as $city): ?>
 <option value="<?php echo $city ?>"><?php echo $city ?></option>
 <?php endforeach; ?>
 </select>
