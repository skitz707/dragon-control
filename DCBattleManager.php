<?php
//-------------------------------------------------------------------------------------------
// DDBattleManager.php - Tracks and admins the current active battle.
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
require_once("classes/DDSecurity.php");
require_once("classes/DDUser.php");
require_once("classes/DDCampaign.php");
require_once("classes/DDQuest.php");
require_once("classes/DDBattle.php");
require_once("classes/DDCharacter.php");
require_once("classes/DDMonster.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DDDatabase();
$security = new DDSecurity($database);
$campaign = new DDCampaign($database);
$character = new DDCharacter($database);
$monster = new DDMonster($database);
$battle = new DDBattle($database);					
$pageTitle = "DD Battle Manager";
$campaignId = $_GET['campaignId'];
$campaign->loadCampaignById($campaignId);

// get active quest
$questHeader = $database->getDatabaseRecord("dragons.questHeader", array("campaignId"=>$campaignId, "statusFlag"=>"A"));

if ($questHeader['questId'] > 0) {
	// check for active battle
	$battleCount = $database->getUniqueCount("dragons.battleHeader", "battleId", array("questId"=>$questHeader['questId'], "statusFlag"=>"A"));

	if ($battleCount > 0) {
		$inBattle = true;
		$battleStatus = "In-Battle";
		$battleId = $database->getColumnMax("dragons.battleHeader", "battleId", array("statusFlag"=>"A"));
		$battle->loadBattleById($battleId);
		$battleOrder = $battle->getBattleOrder();
	} else {
		$inBattle = false;
		$battleStatus = "Open Exploration";
		$activeCharacters = $campaign->getActiveCharacters();
	}
} else {
	$inBattle = false;
	$battleStatus = "Between Quests";
}


// calculate battle difficulty
if ($inBattle) {
	$battleDifficulty = $battle->getBattleDifficulty();
} else {
	$battleDifficulty = "N/A";
}

require_once("includes/header.php");

?>
<div class="battleHeader">
	Battle Status: <span style="font-weight: bold;"><?php echo $battleStatus; ?></span> | Difficulty Rating: <span style="font-weight: bold;"> <?php echo $battleDifficulty ?></span> | <div class="blueButton" onClick="createBattle();">New Battle</div> <div class="redButton" onClick="addMonsters();">Add Monster</div>
</div>
<?php

// battle or expoloration views
if ($inBattle) {
	foreach ($battleOrder as $unitInBattle) {
		if ($unitInBattle['type'] == "C") {
			$character->loadCharacterByBattleDetailId($unitInBattle['detailId']);
			$character->printAdminCharacterCard();
		} else if ($unitInBattle['type'] == "M") {
			$monster->loadMonsterByBattleDetailId($unitInBattle['detailId']);
			$monster->printAdminMonsterCard();
		}
	}
} else {
	foreach ($activeCharacters as $characterId) {
		$character->loadCharacterById($characterId);
		$character->printAdminCharacterCard();
	}
}

?>
<div id="popUpBox" title="Enter Values"></div>
<script>
//----------------------------------------------------------------------------
// create battle
//----------------------------------------------------------------------------
function createBattle() {
	inBattle = <?php if ($inBattle) { echo "true"; } else { echo "false"; } ?>;
	
	if (!inBattle) {
		document.location.href = "newBattle.php?questId=<?php print($questHeader['questId']); ?>";
	}
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
// add monsters
//----------------------------------------------------------------------------
function addMonsters() {
	inBattle = <?php if ($inBattle) { echo "true"; } else { echo "false"; } ?>;
	divObj = document.getElementById('popUpBox');
	
	if (inBattle) {
		divHTML = "";
		divHTML += '<form method="post" action="addMonster.php" id="monsterForm">';
		divHTML += 'Monster: <?php printMonsterList($database); ?> Qty: <input type="text" size="2" id="quantity" name="quantity" /> <div class="redButton" onClick="document.getElementById(\'monsterForm\').submit();">Add</div>';
		divHTML += '<input type="hidden" id="campaignId" name="questId" value="<?php print($questHeader['questId']); ?>" ?>';
		divHTML += '</form>';
		
		divHTML = divHTML.replace(/null/g, '');
		divObj.innerHTML = divHTML;
		
		$(function() {
			$( "#popUpBox" ).dialog({
				width: 550,
				height: 150
			});
		});
	}
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
// set initiative
//----------------------------------------------------------------------------
function setInit(type, id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="setInitiative.php?type=' + type + '" id="initForm">';
	divHTML += 'Initiative: <input type="text" size="2" id="initiative" name="initiative" /> <div class="blueButton" onClick="document.getElementById(\'initForm\').submit();">Set</div>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 350,
			height: 150
		});
	});
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
// take damage
//----------------------------------------------------------------------------
function takeDamage(type, id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="takeDamage.php?type=' + type + '" id="damageForm">';
	divHTML += 'Damage: <input type="text" size="2" id="damage" name="damage" /> <div class="redButton" onClick="document.getElementById(\'damageForm\').submit();">Damage</div>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '<input type="hidden" name="campaignId" id="campaignId" value="<?php print($campaignId); ?>" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 350,
			height: 150
		});
	});
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
// heal
//----------------------------------------------------------------------------
function heal(type, id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="heal.php?type=' + type + '" id="healForm">';
	divHTML += 'Heal: <input type="text" size="2" id="heal" name="heal" /> <div class="greenButton" onClick="document.getElementById(\'damageForm\').submit();">Heal</div>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '<input type="hidden" name="campaignId" id="campaignId" value="<?php print($campaignId); ?>" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 350,
			height: 150
		});
	});
}
//----------------------------------------------------------------------------
</script>
</div>
<?php

require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// print monster list
//-------------------------------------------------------------------------------------------
function printMonsterList($database) {
	$selectStmt = "select * from dragons.monsters order by monsterName";
	
	echo '<select id="monsterId" name="monsterId">';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			echo '<option value="' . $data['monsterId'] . '">' . str_replace("'", "\'", $data['monsterName']) . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	echo '</select>';
}
//-------------------------------------------------------------------------------------------