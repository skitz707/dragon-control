<?php
//-------------------------------------------------------------------------------------------
// DCBattleManager.php - Tracks and admins the current active battle.
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
require_once("classes/DCDatabase.php");
require_once("classes/DCSecurity.php");
require_once("classes/DCUser.php");
require_once("classes/DCCampaign.php");
require_once("classes/DCQuest.php");
require_once("classes/DCBattle.php");
require_once("classes/DCCharacter.php");
require_once("classes/DCMonster.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$security = new DCSecurity($database);
$campaign = new DCCampaign($database);
$character = new DCCharacter($database);
$monster = new DCMonster($database);
$battle = new DCBattle($database);					
$pageTitle = "DD Battle Manager";
$campaignId = $_GET['campaignId'];
$campaign->loadCampaignById($campaignId);
$crumbTrail = $campaign->getCampaignName() . " > Battle Manager";

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
	Battle Status: <span style="font-weight: bold;"><?php echo $battleStatus; ?> | </span> Easy: <span style="font-weight: bold;"><?php echo $battle->getEasyEncounter(); ?></span> | Medium: <span style="font-weight: bold;"><?php echo $battle->getMediumEncounter(); ?></span> | 
	Hard: <span style="font-weight: bold;"><?php echo $battle->getHardEncounter(); ?></span> | Deadly: <span style="font-weight: bold;"><?php echo $battle->getDeadlyEncounter(); ?></span> | Difficulty Rating: <span style="font-weight: bold;"> <?php echo $battleDifficulty ?></span> | <div class="greenButton" onClick="createBattle();">New Battle</div> <div class="blueButton" onClick="aDCMonsters();">Add Monster</div> <div class="redButton" onClick="endBattle();">End Battle</div>
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
<div id="monsterDetail" title="Monster Detail"></div>
<div id="characterDetail" title="Character Detail"></div>
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
// end battle
//----------------------------------------------------------------------------
function endBattle() {
	inBattle = <?php if ($inBattle) { echo "true"; } else { echo "false"; } ?>;
	
	if (inBattle) {
		document.location.href = "endBattle.php?campaignId=<?php print($campaignId); ?>";
	}
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
// add monsters
//----------------------------------------------------------------------------
function aDCMonsters() {
	inBattle = <?php if ($inBattle) { echo "true"; } else { echo "false"; } ?>;
	divObj = document.getElementById('popUpBox');
	
	if (inBattle) {
		divHTML = "";
		divHTML += '<form method="post" action="addMonster.php" id="monsterForm">';
		divHTML += 'Monster: <?php printMonsterList($database); ?> Qty: <input type="text" size="2" id="quantity" name="quantity" /> <div class="redButton" onClick="document.getElementById(\'monsterForm\').submit();">Add</div>';
		divHTML += '<input type="hidden" id="questId" name="questId" value="<?php print($questHeader['questId']); ?>" ?>';
		divHTML += '</form>';
		
		divHTML = divHTML.replace(/null/g, '');
		divObj.innerHTML = divHTML;
		
		$(function() {
			$( "#popUpBox" ).dialog({
				width: 650,
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


//----------------------------------------------------------------------------
// pop monster details
//----------------------------------------------------------------------------
function monsterDetails(monsterId) {
	divObj = document.getElementById('monsterDetail');
	divHTML = "";
	
	$.ajax({
		url: "getMonsterDetail.php?monsterId=" + monsterId,
		data: "{}",
		dataType: "json",
		error: function (res, status) {
			if (status === "error") {
				var errorMessage = $.parseJSON(res.responseText);
				alert(errorMessage.Message);
			}
		},
		success: function (data) {

			divHTML += '<span style="font-size: 14pt; font-style: italic;">' + data.monsterName + ' - ' + data.xpRating + 'xp</span><br /><br />';
			divHTML += '<span style="font-weight: bold;">AC:</span> ' + data.armorClass + ' | <span style="font-weight: bold;">Health:</span> ' + data.health + '<br />';
			divHTML += '<span style="font-weight: bold;">STR:</span> ' + data.strength + '(' + data.strengthModifier + ') | <span style="font-weight: bold;">DEX:</span> ' + data.dexterity + '(' + data.dexterityModifier + ')<br />';
			divHTML += '<span style="font-weight: bold;">CON:</span> ' + data.constitution + '(' + data.constitutionModifier + ') | <span style="font-weight: bold;">INT:</span> ' + data.intelligence + '(' + data.intelligenceModifier + ')<br />';
			divHTML += '<span style="font-weight: bold;">WIS:</span> ' + data.wisdom + '(' + data.wisdomModifier + ') | <span style="font-weight: bold;">CHA:</span> ' + data.charisma + '(' + data.charismaModifier + ')<br /><br />';
			divHTML += '<span style="font-weight: bold;">Damage Resistances:</span> <span style="font-style: italic;">' + data.damageResistances + '</span><br /><br />';
			divHTML += '<span style="font-weight: bold;">Damage Immunities:</span> <span style="font-style: italic;">' + data.damageImmunities + '</span><br /><br />';
			divHTML += '<span style="font-weight: bold;">Condition Immunities:</span> <span style="font-style: italic;">' + data.conditionImmunities + '</span><br /><br />';
			divHTML += data.specialSkills;
			divHTML += 'Attacks:<br />';
			divHTML += data.monsterAttacks;
			divHTML += '<br />Magic:<br />';
			divHTML += data.magicSpells;
			
			divHTML = divHTML.replace(/null/g, '');

			divObj.innerHTML = divHTML;
		}
	});
	
	$(function() {
		$( "#monsterDetail" ).dialog({
			width: 450,
			height: 550
		});
	});
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
// pop character details
//----------------------------------------------------------------------------
function characterDetails(characterId) {
	divObj = document.getElementById('characterDetail');
	divHTML = "";
	
	$.ajax({
		url: "getCharacterDetail.php?characterId=" + characterId,
		data: "{}",
		dataType: "json",
		error: function (res, status) {
			if (status === "error") {
				var errorMessage = $.parseJSON(res.responseText);
				alert(errorMessage.Message);
			}
		},
		success: function (data) {
			
			divHTML += '<span style="font-size: 14pt; font-style: italic;">' + data.characterName + ' - ' + data.characterRace + '/' + data.characterClass + '</span><br />';
			divHTML += '<span style="font-weight: bold;">Level: ' + data.characterLevel + ' | XP: ' + data.characterXP + '</span><br /><br />';
			divHTML += '<span style="font-weight: bold;">AC:</span> ' + data.armorClass + ' | <span style="font-weight: bold;">HP:</span> ' + data.currentHP + '/' + data.maxHP + '<br />';
			divHTML += '<span style="font-weight: bold;">STR:</span> ' + data.strength + '(' + data.strengthModifier + ') | <span style="font-weight: bold;">DEX:</span> ' + data.dexterity + '(' + data.dexterityModifier + ')<br />';
			divHTML += '<span style="font-weight: bold;">CON:</span> ' + data.constitution + '(' + data.constitutionModifier + ') | <span style="font-weight: bold;">INT:</span> ' + data.intelligence + '(' + data.intelligenceModifier + ')<br />';
			divHTML += '<span style="font-weight: bold;">WIS:</span> ' + data.wisdom + '(' + data.wisdomModifier + ') | <span style="font-weight: bold;">CHA:</span> ' + data.charisma + '(' + data.charismaModifier + ')<br /><br />';
			divHTML += '<span style="font-weight: bold;">Proficiency Bonus:</span> ' + data.proficiencyBonus + '<br /><br />';
			//divHTML += '<span style="font-weight: bold;">Damage Resistances:</span> <span style="font-style: italic;">' + data.damageResistances + '</span><br /><br />';
			//divHTML += '<span style="font-weight: bold;">Damage Immunities:</span> <span style="font-style: italic;">' + data.damageImmunities + '</span><br /><br />';
			//divHTML += '<span style="font-weight: bold;">Condition Immunities:</span> <span style="font-style: italic;">' + data.conditionImmunities + '</span><br /><br />';
			//divHTML += data.specialSkills;
			//divHTML += 'Attacks:<br />';
			//divHTML += data.monsterAttacks;
			//divHTML += '<br />Magic:<br />';
			//divHTML += data.magicSpells;
			
			divHTML = divHTML.replace(/null/g, '');

			divObj.innerHTML = divHTML;
		}
	});
	
	$(function() {
		$( "#characterDetail" ).dialog({
			width: 450,
			height: 550
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