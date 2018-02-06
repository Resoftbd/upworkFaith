<?php
    include '../environment.php';
    
    include ROOT.'/www/ctrl.php';
    $states = Country::getStates($_GET['country']);
    
    if ($_GET['json']) {
        echo json_encode($states);
        exit;
    }
    
    if (!count($states)) {
        exit;
    }
?>
<select name="state">
<option>Not selected</option>
<?php foreach($states as $state): ?>
<option value="<?php echo $state ?>"><?php echo $state ?></option>
<?php endforeach; ?>
</select>