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
require_once("classes/DDBattle.php");
require_once("classes/DDPlayer.php");
require_once("classes/DDMonster.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();
$battle = new DDBattle($database);
$player = new DDPlayer($database);
$monster = new DDMonster($database);

// check for active battle
$battleId = $database->getColumnMax("dragons.battleHeader", "entryId", array("statusFlag"=>"A"));
$battleRecord = $database->getDatabaseRecord("dragons.battleHeader", array("entryId"=>$battleId));

if ($battleRecord['entryId'] > 0) {
	$battle->loadBattleById($battleRecord['entryId']);
	$inBattle = true;
	$battleOrder = $battle->getBattleOrder();
}
					
$pageTitle = "DD Battle Tracker";

require_once("includes/header.php");

foreach ($battleOrder as $unitInBattle) {
	if ($unitInBattle['type'] == "P") {
		$player->loadPlayerByBattleDetailId($unitInBattle['detailId']);
		$player->printPlayerCard();
	} else if ($unitInBattle['type'] == "M") {
		$monster->loadMonsterByBattleDetailId($unitInBattle['detailId']);
		$monster->printMonsterCard();
	}
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