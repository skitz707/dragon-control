<?php
//-------------------------------------------------------------------------------------------
// newBattle.php - Updates set inititative.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// December 21st, 2017
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

$itemMaster['campaignId'] = $_POST['campaignId'];
$itemMaster['itemName'] = $_POST['itemName'];
$itemMaster['itemDescription'] = $_POST['itemDescription'];
$itemMaster['itemType'] = $_POST['itemType'];
$itemMaster['cost'] = $_POST['cost'];
$itemMaster['itemWeight'] = $_POST['weight'];

$database->insertDatabaseRecord("dragons.itemMaster", $itemMaster);

// get item id
$itemId = $database->getColumnMax("dragons.itemMaster", "itemId", array("campaignId"=>$_POST['campaignId']));

// build item properties entries
foreach ($_POST['weaponProperties'] as $weaponPropertyId) {
	$itemProperty['itemId'] = $itemId;
	$itemProperty['weaponPropertyId'] = $weaponPropertyId;
	
	$database->insertDatabaseRecord("dragons.itemProperties", $itemProperty);
}

// build equipable location data
foreach ($_POST['equipableLocations'] as $equipableLocationId) {
	$equipableLocation['itemId'] = $itemId;
	$equipableLocation['equipableLocationId'] = $equipableLocationId;
	
	$database->insertDatabaseRecord("dragons.itemEquipableLocations", $equipableLocation);
}

// build item dice entries
$i = 0;

foreach ($_POST['numberOfDice'] as $numberOfDice) {
	$itemDice['itemId'] = $itemId;
	$itemDice['numberOfDice'] = $numberOfDice;
	$itemDice['rollId'] = $_POST['rollIds'][$i];
	$itemDice['rollModifier'] = $_POST['rollModifiers'][$i];
	$itemDice['itemActionTypeId'] = $_POST['itemActionTypes'][$i];
	$itemDice['damageTypeId'] = $_POST['damageTypes'][$i];
	$itemDice['diceRoleId'] = $_POST['diceRoles'][$i];
	
	$database->insertDatabaseRecord("dragons.itemDice", $itemDice);
	
	$i++;
}

// check if armor class is set
if ($_POST['armorClass'] > '') {
	$armorClass['itemId'] = $itemId;
	$armorClass['armorClass'] = $_POST['armorClass'];
	
	$database->insertDatabaseRecord("dragons.itemArmorClass", $armorClass);
}

header("Location: manageItems.php?campaignId=" . $_POST['campaignId']);
//-------------------------------------------------------------------------------------------