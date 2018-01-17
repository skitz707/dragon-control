<?php
session_start();
$IsAdmin = $_SESSION['IsAdmin'];

if($IsAdmin == false){
  header ('Location: /Sites/Dragon-Control/Login/');
}
?>
