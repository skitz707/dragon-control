<?php
require_once("Config.php");
include("Auth.php");
$myUserID= $_SESSION['UserID'];
$deletedUserID = $_GET['UserID'];

$qry = "UPDATE USERS set DeletedDate='2017/10/10', DeletedByUserID=" . $myUserID . " where UserID=" .  $deletedUserID;
echo $qry;
$results = mysqli_query($link, $qry);
header ('Location: /Sites/Dragon-Control/Admin/Users');
 ?>
