<?php
//-------------------------------------------------------------------------------------------
// DCBattleTracker.php - Tracks the current active battle.
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
require_once("classes/DCDatabase.php");
require_once("classes/DCCampaign.php");
require_once("classes/DCBattle.php");
require_once("classes/DCCharacter.php");
require_once("classes/DCMonster.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$campaign = new DCCampaign($database);
$battle = new DCBattle($database);
$character = new DCCharacter($database);
$monster = new DCMonster($database);

$campaign->loadCampaignById($_GET['campaignId']);

// check for active battle
$battleId = $database->getColumnMax("dragons.battleHeader", "battleId", array("statusFlag"=>"A"));
$battleRecord = $database->getDatabaseRecord("dragons.battleHeader", array("battleId"=>$battleId));

if ($battleRecord['battleId'] > 0) {
	$battle->loadBattleById($battleRecord['battleId']);
	$inBattle = true;
	$battleOrder = $battle->getBattleOrder();
} else {
	$inBattle = false;
	$battleStatus = "Open Exploration";
	$activeCharacters = $campaign->getActiveCharacters();
}
				
$pageTitle = "DD Battle Tracker";
$crumbTrail = "Battle Tracker";

require_once("includes/header.php");

echo '<div style="padding-top: 25px;">';

// battle or expoloration views
if ($inBattle) {
	foreach ($battleOrder as $unitInBattle) {
		if ($unitInBattle['type'] == "C") {
			$character->loadCharacterByBattleDetailId($unitInBattle['detailId']);
			$character->printCharacterCard();
		} else if ($unitInBattle['type'] == "M") {
			$monster->loadMonsterByBattleDetailId($unitInBattle['detailId']);
			$monster->printMonsterCard();
		}
	}
} else {
	foreach ($activeCharacters as $characterId) {
		$character->loadCharacterById($characterId);
		$character->printCharacterCard();
	}
}

echo '</div>';

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