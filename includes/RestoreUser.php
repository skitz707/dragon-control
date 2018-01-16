<?php
require_once("Config.php");
include("Auth.php");
$userID = $_GET['UserID'];

$qry = "UPDATE USERS set DeletedDate=NULL, DeletedByUserID=NULL where UserID=" .  $userID;
echo $qry;
$results = mysqli_query($link, $qry);
header ('Location: /Sites/Dragon-Control/Admin/Users');
 ?>
