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
require_once("classes/DDDatabase.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();
$itemId = $_POST['itemId'];

$itemMaster['itemName'] = $_POST['itemName'];
$itemMaster['itemDescription'] = $_POST['itemDescription'];
$itemMaster['itemType'] = $_POST['itemType'];
$itemMaster['cost'] = $_POST['cost'];
$itemMaster['itemWeight'] = $_POST['weight'];

$database->updateDatabaseRecord("dragons.itemMaster", $itemMaster, array("itemId"=>$itemId));

// remove all property entries
$deleteStmt = "delete from dragons.itemProperties where itemId = ?";

if ($deleteHandle = $database->databaseConnection->prepare($deleteStmt)) {
	if (!$deleteHandle->execute(array(0=>$itemId))) {
		var_dump($database->databaseConnection->errorInfo());
	}
} else {
	var_dump($database->databaseConnection->errorInfo());
}

// build item properties entries
foreach ($_POST['weaponProperties'] as $weaponPropertyId) {
	$itemProperty['itemId'] = $itemId;
	$itemProperty['weaponPropertyId'] = $weaponPropertyId;
	
	$database->insertDatabaseRecord("dragons.itemProperties", $itemProperty);
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
	// check for existing record
	$itemArmorClass = $database->getDatabaseRecord("dragons.itemArmorClass", array("itemId"=>$itemId));
	
	if ($itemArmorClass['itemArmorClassId'] > 0) {
		$database->updateDatabaseRecord("dragons.itemArmorClass", array("armorClass"=>$_POST['armorClass']), array("itemArmorClassId"=>$itemArmorClass['itemArmorClassId']));
	} else {
		$armorClass['itemId'] = $itemId;
		$armorClass['armorClass'] = $_POST['armorClass'];
		
		$database->insertDatabaseRecord("dragons.itemArmorClass", $armorClass);
	}
}

//header("Location: editItem.php?itemId=" . $itemId);
//-------------------------------------------------------------------------------------------