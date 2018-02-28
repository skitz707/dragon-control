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
$battleId = $database->getColumnMax("dragons.battleHeader", "battleId", array("questId"=>$_POST['questId'], "statusFlag"=>"A"));
$questMaster = $database->getDatabaseRecord("dragons.questHeader", array("questId"=>$_POST['questId']));
$i = 1;

while ($i <= $_POST['quantity']) {
	$monsterRecord = $database->getDatabaseRecord("dragons.monsters", array("monsterId"=>$_POST['monsterId']));
	
	$battleData['battleId'] = $battleId;
	$battleData['entryType'] = "M";
	$battleData['associatedId'] = $_POST['monsterId'];
	$battleData['currentHP'] = $monsterRecord['health'];
	$battleData['initiative'] = 0;
	
	$database->insertDatabaseRecord("dragons.battleDetail", $battleData);
	
	$i++;
}

header("Location: DCBattleManager.php?campaignId=" . $questMaster['campaignId']);
//-------------------------------------------------------------------------------------------