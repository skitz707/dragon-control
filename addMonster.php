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
$battleId = $database->getColumnMax("dragons.battleHeader", "entryId", array("statusFlag"=>"A"));
$i = 1;

while ($i <= $_POST['quantity']) {
	$monsterRecord = $database->getDatabaseRecord("dragons.monsters", array("entryId"=>$_POST['monsterId']));
	
	$battleData['battleId'] = $battleId;
	$battleData['entryType'] = "M";
	$battleData['associatedId'] = $_POST['monsterId'];
	$battleData['currentHP'] = $monsterRecord['health'];
	$battleData['initiative'] = 0;
	
	$database->insertDatabaseRecord("dragons.battleDetail", $battleData);
	
	$i++;
}

header("Location: DDBattleManager.php");
//-------------------------------------------------------------------------------------------