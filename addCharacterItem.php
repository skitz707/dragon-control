<?php
//-------------------------------------------------------------------------------------------
// updateCharacter.php - Updates set inititative.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// January 31st, 2018
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

// check if character already has itemId
$itemExists = $database->getDatabaseRecord("dragons.characterItems", array("characterId"=>$_POST['characterId'], "itemId"=>$_POST['itemId']));

if ($itemExists['characterItemId'] > 0) {
	$newValue = $itemExists['quantity'] + $_POST['itemQuantity'];
	
	$database->updateDatabaseRecord("dragons.characterItems", array("quantity"=>$newValue), array("characterItemId"=>$itemExists['characterItemId']));
} else {

	$characterItem['characterId'] = $_POST['characterId'];
	$characterItem['itemId'] = $_POST['itemId'];
	$characterItem['quantity'] = $_POST['itemQuantity'];
	
	$database->insertDatabaseRecord("dragons.characterItems", $characterItem);
}

header("Location: " . $_POST['returnTo']);
//-------------------------------------------------------------------------------------------