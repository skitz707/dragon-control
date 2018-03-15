<?php
//-------------------------------------------------------------------------------------------
// index.php - Index/login page.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// January 14th, 2018
//
// Change log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// debug values
//-------------------------------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', 1);
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// program includes
//-------------------------------------------------------------------------------------------
require_once("classes/DCDatabase.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$passwordHash = md5($_POST['password']);
$userMaster = $database->getDatabaseRecord("dragons.userMaster", array("emailAddress"=>$_POST['emailAddress'], "passwordHash"=>$passwordHash));
$cookieExpirationTime = time() + 60 * 60 * 4; // four hours from now

if ($userMaster['userId'] > 0) {
	setcookie('userId', $userMaster['userId'], $cookieExpirationTime);
	
	header("Location: userHome.php");
} else {
	header("Location: index.php?error=loginFailed");
}
//-------------------------------------------------------------------------------------------