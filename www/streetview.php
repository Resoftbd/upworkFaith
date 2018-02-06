<?php
include '../globals.php';

if (isset($_GET['preview']) && $_GET['preview']!=""){
  session_start();
  $_SESSION['preview']=$_GET['preview'];
}else{
  include 'streetPanorama.php';
}
echo($_SESSION['preview']);
?>