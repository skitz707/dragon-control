<?php
//-------------------------------------------------------------------------------------------
// DDBattleTracker.php - Tracks the current active battle.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// November 30th, 2017
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
$player = new DDPlayer($database);
$enemy = new DDEnemy($database);
$selectStmt = "select playerId as characterId, 'P' as characterType, initiative from dragons.players where statusFlag = 'A' 
			   union 
			   select enemyId as characterId, 'E' as characterType, initiative from dragons.enemies where statusFlag = 'A'
					order by initiative desc";
					
$pageTitle = "DD Battle Tracker";

require_once("includes/header.php");

if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
	if (!$selectHandle->execute()) {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
		if ($data['characterType'] == "P") {
			$player->loadPlayerById($data['characterId']);
			$player->printPlayerCard();
		} else if ($data['characterType'] == "E") {
			$enemy->loadEnemyById($data['characterId']);
			$enemy->printEnemyCard();
		}
	}
} else {
	var_dump($database->databaseConnection->errorInfo());
}

?>
<script>
//--------------------------------------------------------------------------------------
// function to reload screen every 5 seconds
//--------------------------------------------------------------------------------------
setTimeout(function(){
   window.location.reload(1);
}, 5000);
//--------------------------------------------------------------------------------------
</script>
<?php

require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// print player card
//-------------------------------------------------------------------------------------------
function printPlayerCard($database, $playerId) {
	$playerRecord = $database->getDatabaseRecord("dragons.players", array("playerId"=>$playerId));
	
	echo '
		<div class="playerCard">
			<img src="images/default.jpg" width="120px" height="160px" /><br />
			<span class="playerName">' . $playerRecord['playerName'] . '</span><br />
			<em>' . $playerRecord['playerRace'] . ' / ' . $playerRecord['playerClass'] . '</em><br />
			AC: ' . $playerRecord['armorClass'] . '<br />
			HP: ' . $playerRecord['currentHP'] . '/' . $playerRecord['maxHP'] . '<br />
			Initiative: ' . number_format($playerRecord['initiative'], 0, "", "") . '
		</div>
	';
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// print player card
//-------------------------------------------------------------------------------------------
function printEnemyCard($database, $enemyId) {
	$enemyRecord = $database->getDatabaseRecord("dragons.enemies", array("enemyId"=>$enemyId));
	
	echo '
		<div class="playerCard">
			<img src="images/default.jpg" width="120px" height="160px" /><br />
			<span class="playerName">' . $enemyRecord['enemyName'] . '</span><br />
			AC: ' . $enemyRecord['armorClass'] . '<br />
			Initiative: ' . number_format($enemyRecord['initiative'], 0, "", "") . '
		</div>
	';
}
//-------------------------------------------------------------------------------------------