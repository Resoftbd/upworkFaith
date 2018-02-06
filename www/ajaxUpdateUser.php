<?php
    include '../environment.php';
    include ROOT.'/www/ctrl.php';
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        exit;
    }
    
    $email = $_POST['email'];
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $db = Database::connect();
    $task = $db->query("UPDATE users set name='$name', password='$pass' WHERE email='$email'")->fetch(PDO::FETCH_ASSOC);
    $_SESSION['name']=$name;
    header("location:profile.php");