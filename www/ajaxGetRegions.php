<?php
    include '../environment.php';
    
    include ROOT.'/www/ctrl.php';
    $states = Country::getStates($_GET['country']);
    
//    if ($_GET['json']) {
//        echo json_encode($states);
//        exit;
//    }
//
//    if (!count($states)) {
//        exit;
//    }
?>
<select name="state">
<option>Not selected</option>
<?php foreach($states as $key => $state): ?>
    <option value="<?php echo $state['name']?>"><?php echo $state['name']?></option>
<?php endforeach; ?>
</select>