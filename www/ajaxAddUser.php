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
    $result = $db->query("SELECT COUNT(*) as count FROM users WHERE email='$email'")->fetch(PDO::FETCH_ASSOC);
    if( $result['count']==0){
        $result = $db->query("INSERT INTO users(role , name , email , password) VALUES (1 , '$name' , '$email','$pass')")->fetch(PDO::FETCH_ASSOC);
        $msg =array("success"=>true,"error"=>"");
    }else{
        $msg =array("success"=>false,"error"=>"This email exists");
    }
    echo json_encode($msg);
