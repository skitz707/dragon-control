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

$database->deleteDatabaseRecord("dragons.characterEquippedItems", array("characterId"=>$_POST['characterId'], "equipableLocationId"=>$_POST['equipableLocationId']));

if ($_POST['itemId'] > 0) {
	$equippedItem['characterId'] = $_POST['characterId'];
	$equippedItem['itemId'] = $_POST['itemId'];
	$equippedItem['equipableLocationId'] = $_POST['equipableLocationId'];
	
	$database->insertDatabaseRecord("dragons.characterEquippedItems", $equippedItem);
}

header("Location: editCharacterEquipment.php?characterId=" . $_POST['characterId']);
//-------------------------------------------------------------------------------------------