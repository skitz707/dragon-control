<?php
//-------------------------------------------------------------------------------------------
// dropItem.php
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// March 1st, 2018
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
$deleteStmt = "delete from dragons.characterItems where characterItemId = ?";

if ($deleteHandle = $database->databaseConnection->prepare($deleteStmt)) {
	if (!$deleteHandle->execute(array(0=>$_POST['id']))) {
		var_dump($database->databaseConnection->errorInfo());
	}
} else {
	var_dump($database->databaseConnection->errorInfo());
}

header("Location: " . $_POST['returnTo']);
//-------------------------------------------------------------------------------------------