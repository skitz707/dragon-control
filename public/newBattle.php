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

$database->insertDatabaseRecord("dragons.battleHeader", array("statusFlag"=>"A"));
$battleId = $database->getColumnMax("dragons.battleHeader", "entryId", array("1"=>"1"));

// add characters to the battle
$characterStmt = "select * from dragons.players where statusFlag = 'A'";

if ($characterHandle = $database->databaseConnection->prepare($characterStmt)) {
	if (!$characterHandle->execute()) {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	while ($characterData = $characterHandle->fetch(PDO::FETCH_ASSOC)) {
		$detail['battleId'] = $battleId;
		$detail['entryType'] = "P";
		$detail['associatedId'] = $characterData['playerId'];
		$detail['currentHP'] = $characterData['currentHP'];
		$detail['initiative'] = 0;
		
		$database->insertDatabaseRecord("dragons.battleDetail", $detail);
	}
} else {
	var_dump($database->databaseConnection->errorInfo());
}


header("Location: DDBattleManager.php");
//-------------------------------------------------------------------------------------------