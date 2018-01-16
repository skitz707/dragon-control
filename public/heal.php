<?php
//-------------------------------------------------------------------------------------------
// heal.php - Take heal/update HP.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// December 1st, 2017
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
require_once("classes/DDPlayer.php");
require_once("classes/DDEnemy.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();
$battleId = $database->getColumnMax("dragons.battleHeader", "entryId", array("statusFlag"=>"A"));
$currentRecord = $database->getDatabaseRecord("dragons.battleDetail", array("entryId"=>$_POST['id']));

// find max hp
if ($currentRecord['entryType'] == "P") {
	$playerRecord = $database->getDatabaseRecord("dragons.players", array("playerId"=>$currentRecord['associatedId']));
	$maxHP = $playerRecord['maxHP'];
} else if ($currentRecord['entryType'] == "M") {
	$monsterRecord = $database->getDatabaseRecord("dragons.monsters", array("entryId"=>$currentRecord['associatedId']));
	$maxHP = $monsterRecord['health'];
}

$newHP = $currentRecord['currentHP'] + $_POST['heal'];

if ($newHP > $maxHP) {
	$newHP = $maxHP;
}

$updateData['currentHP'] = $newHP;
	
$database->updateDatabaseRecord("dragons.battleDetail", $updateData, array("entryId"=>$_POST['id']));

header("Location: DDBattleManager.php");
//-------------------------------------------------------------------------------------------