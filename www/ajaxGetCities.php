<?php
include '../environment.php';

include ROOT.'/www/ctrl.php';
$cities  = Country::getCities($_GET['country']);

//if ($_GET['json']) {
//    echo json_encode($cities);
//    exit;
//}
//
//if (!count($cities)) {
//    exit;
//}
 ?>
 <select name="city">
 <option>Not selected</option>
 <?php foreach($cities as $key => $city): ?>
 <option value="<?php echo $city['state_name'] ?>"><?php echo $city['state_name'] ?></option>
 <?php endforeach; ?>
 </select>
