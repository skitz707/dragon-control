<?php
session_start();
$user = $_SESSION['UserName'];
$loggedIn = $_SESSION['LoggedIn'];
$userID = $_SESSION['UserID'];
$IsAdmin = $_SESSION['IsAdmin'];

if($loggedIn == false){
  header ('Location: /Sites/Dragon-Control/Login/');
}
?>
