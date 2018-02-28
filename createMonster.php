<?php
//-------------------------------------------------------------------------------------------
// campaignDetail.php - Campaign detail page.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// January 15th, 2018
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
$pageTitle = "DC - Manage Monsters";
$campaignId = $_GET['campaignId'];
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>

<br /><br />
<div id="mainContent">
	<span class="largeHeading">Create Monsters: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<form method="post" id="monsterForm" action="addNewMonster.php">
	<table class="standardResultTable" style="width: 30%; margin-left: auto; margin-right: auto;">
		<tr>
			<td>Monster Name</td>
			<td><input type="text" class="textField" name="monsterName" id="monsterName" size="20" /></td>
		</tr>
		<tr>
			<td>Armor Class</td>
			<td><input type="text" class="textField" name="armorClass" id="armorClass" size="3" /></td>
		</tr>
		<tr>
			<td>Health</td>
			<td><input type="text" class="textField" name="health" id="health" size="3" /></td>
		</tr>
		<tr>
			<td>Strength</td>
			<td><input type="text" class="textField" name="strength" id="strength" size="3" /></td>
		</tr>
		<tr>
			<td>Dexterity</td>
			<td><input type="text" class="textField" name="dexterity" id="dexterity" size="3" /></td>
		</tr>
		<tr>
			<td>Constitution</td>
			<td><input type="text" class="textField" name="constitution" id="constitution" size="3" /></td>
		</tr>
		<tr>
			<td>Intelligence</td>
			<td><input type="text" class="textField" name="intelligence" id="intelligence" size="3" /></td>
		</tr>
		<tr>
			<td>Wisdom</td>
			<td><input type="text" class="textField" name="wisdom" id="charisma" size="3" /></td>
		</tr>
		<tr>
			<td>Charisma</td>
			<td><input type="text" class="textField" name="charisma" id="charisma" size="3" /></td>
		</tr>
		<tr>
			<td>XP Rating</td>
			<td><input type="text" class="textField" name="xpRating" id="xpRating" size="3" /></td>
		</tr>
		<tr>
			<td>Damage Resistances</td>
			<td><?php echo getDamageTypesCheckboxes($database, "damageResistances"); ?></td>
		</tr>
		<tr>
			<td>Damage Immunities</td>
			<td><?php echo getDamageTypesCheckboxes($database, "damageImmunities"); ?></td>
		</tr>
		<tr>
			<td>Condition Immunities</td>
			<td><?php echo getConditionTypesCheckboxes($database, "conditionImmunities"); ?></td>
		</tr>
	</table>
	<input type="hidden" name="campaignId" id="campaignId" value="<?php echo $campaignId; ?>" />
	</form>
	<br /><br />
	<span class="mediumHeading">Add Attacks</span>
	<br /><br />
	Attack: <?php echo getMonsterAttacksDropdown($database); ?> 
	# of Dice: <input type="text" class="textField" name="numberOfDice" id="numberOfDice" size="2" /> Dice Type: <?php echo getDiceDropdown($database); ?> 
		<?php echo getItemActionTypesDropdown($database); ?> Damage Type: <?php echo getDamageTypeDropdown($database); ?>
			<?php echo getDiceRoles($database); ?> <div class="blueButton" onClick="addDice()">Add Attacks</div>
	<br /><br />
	<div id="hiddenParms"></div>
	<table style="width: 35%; margin-left: auto; margin-right: auto; text-align: center;" id="diceRollsAssigned">
		<tr>
			<th>Attack</th>
			<th># of Dice</th>
			<th>Dice Roll</th>
			<th>Affect</th>
			<th>Damage Type</th>
			<th>P/S</th>
		</tr>
	</table>
	<br /><br />
	<span class="mediumHeading">Add Spells</span>
	<br /><br />
	Spell: <?php echo getSpellDropdown($database); ?> <div class="blueButton" onClick="addSpell()">Add Spell</div>
	<br /><br />
	Assigned Spells:<br />
	<div id="magicSpellsAssigned"></div>
	<br /><br />
	<span class="mediumHeading">Add Special Skills</span>
	<br /><br />
	Spell: <?php echo getSpecialSkillsDropdown($database); ?> <div class="blueButton" onClick="addSpecialSkill()">Add Skill</div>
	<br /><br />
	Assigned Skills:<br />
	<div id="specialSkillsAssigned"></div>
	<br /><br />
	<div class="greenButton" onClick="document.getElementById('monsterForm').submit();">Create Monster</div>
</div>

<script>
//--------------------------------------------------------------------------
// add dice roll
//--------------------------------------------------------------------------
function addDice() {
	diceRollsAssignedTable = document.getElementById('diceRollsAssigned');
	hiddenParmsDiv = document.getElementById('hiddenParms');
	
	numberOfDice = document.getElementById('numberOfDice').value;
	rollId = getSelectedValue('diceRollId');
	rollText = getSelectedText('diceRollId');
	itemActionType = getSelectedValue('itemActionType');
	itemActionTypeText = getSelectedText('itemActionType');
	damageType = getSelectedValue('damageType');
	damageTypeText = getSelectedText('damageType');
	diceRole = getSelectedValue('diceRole');
	diceRoleText = getSelectedText('diceRole');
	monsterAttackMasterId = getSelectedValue('monsterAttackMasterId');
	monsterAttackMasterText = getSelectedText('monsterAttackMasterId');
	
	// create a new row in the table
	var newRow = diceRollsAssignedTable.insertRow(diceRollsAssignedTable.rows.length);
	
	// insert new data to the table
	var newCell  = newRow.insertCell(0);
	newCell.appendChild(document.createTextNode(monsterAttackMasterText));
	
	var newCell  = newRow.insertCell(1);
	newCell.appendChild(document.createTextNode(numberOfDice));
	
	var newCell  = newRow.insertCell(2);
	newCell.appendChild(document.createTextNode(rollText));
	
	var newCell  = newRow.insertCell(3);
	newCell.appendChild(document.createTextNode(itemActionTypeText));
	
	var newCell  = newRow.insertCell(4);
	newCell.appendChild(document.createTextNode(damageTypeText));
	
	var newCell  = newRow.insertCell(5);
	newCell.appendChild(document.createTextNode(diceRoleText));
	
	// create new hidden forms for posting
	// create monsterAttackIds 
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "monsterAttackMasterId[]");
	input.setAttribute("value", monsterAttackMasterId);
	document.getElementById('monsterForm').appendChild(input);

	// create number of dice hidden field
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "numberOfDice[]");
	input.setAttribute("value", numberOfDice);
	document.getElementById('monsterForm').appendChild(input);
	
	// create roll id hidden field
	var input = document.createElement("input");
	
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "rollIds[]");
	input.setAttribute("value", rollId);
	document.getElementById('monsterForm').appendChild(input);
		
	// create item action types hidden field
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "itemActionTypes[]");
	input.setAttribute("value", itemActionType);
	document.getElementById('monsterForm').appendChild(input);
	
	// create damage type hidden field
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "damageTypes[]");
	input.setAttribute("value", damageType);
	document.getElementById('monsterForm').appendChild(input);

	// create dice role hidden field
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "diceRoles[]");
	input.setAttribute("value", diceRole);
	document.getElementById('monsterForm').appendChild(input);
}
//--------------------------------------------------------------------------


//--------------------------------------------------------------------------
// add spell
//--------------------------------------------------------------------------
function addSpell() {
	spellId = getSelectedValue('spellId');
	spellIdText = getSelectedText('spellId');
	assignedSpellDiv = document.getElementById('magicSpellsAssigned');
	
	// create spells hidden field
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "spellIds[]");
	input.setAttribute("value", spellId);
	document.getElementById('monsterForm').appendChild(input);
	
	assignedSpellDiv.innerHTML += spellIdText + '<br />';
}
//--------------------------------------------------------------------------


//--------------------------------------------------------------------------
// add special skill id
//--------------------------------------------------------------------------
function addSpecialSkill() {
	specialSkillId = getSelectedValue('specialSkillId');
	specialSkillText = getSelectedText('specialSkillId');
	assignedSkillDiv = document.getElementById('specialSkillsAssigned');
	
	// create special skill ids
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "specialSkillIds[]");
	input.setAttribute("value", specialSkillId);
	document.getElementById('monsterForm').appendChild(input);
	
	assignedSkillDiv.innerHTML += specialSkillText + '<br />';
}
//--------------------------------------------------------------------------


//--------------------------------------------------------------------------
// get selected text
//--------------------------------------------------------------------------
function getSelectedText(elementId) {
    var elt = document.getElementById(elementId);

    if (elt.selectedIndex == -1) {
        return null;
	}

    return elt.options[elt.selectedIndex].text;
}
//--------------------------------------------------------------------------


//--------------------------------------------------------------------------
// get selected value
//--------------------------------------------------------------------------
function getSelectedValue(elementId) {
    var elt = document.getElementById(elementId);

    if (elt.selectedIndex == -1) {
        return null;
	}

    return elt.options[elt.selectedIndex].value;
}
//--------------------------------------------------------------------------
</script>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// print damage types checkbox
//-------------------------------------------------------------------------------------------
function getDamageTypesCheckboxes($database, $fieldName) {
	$selectStmt = "select * from dragons.damageTypes order by damageType";
	$returnHTML = "";
	$boxesPerLine = 3;
	$i = 1;
	
	$returnHTML .= '<table>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			if ($i == 1) {
				$returnHTML .= '<tr style="background-color: transparent;">';
			}
			
			$returnHTML .= '<td><input type="checkbox" name="' . $fieldName . '[]" id="' . $data['damageType'] . '" value="' . $data['damageTypeId'] . '" /></td>';
			$returnHTML .= '<td>' . $data['damageType'] . '</td>';
			
			if ($i == $boxesPerLine) {
				$returnHTML .= '</tr>';
				$returnHTML .= '<tr>';
				
				$i = 1;
			} else {
				$i++;
			}
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</table>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// print condition types checkbox
//-------------------------------------------------------------------------------------------
function getConditionTypesCheckboxes($database, $fieldName) {
	$selectStmt = "select * from dragons.conditions order by conditionName";
	$returnHTML = "";
	$boxesPerLine = 3;
	$i = 1;
	
	$returnHTML .= '<table>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			if ($i == 1) {
				$returnHTML .= '<tr style="background-color: transparent;">';
			}
			
			$returnHTML .= '<td><input type="checkbox" name="' . $fieldName . '[]" id="' . $data['conditionName'] . '" value="' . $data['conditionId'] . '" /></td>';
			$returnHTML .= '<td>' . $data['conditionName'] . '</td>';
			
			if ($i == $boxesPerLine) {
				$returnHTML .= '</tr>';
				$returnHTML .= '<tr>';
				
				$i = 1;
			} else {
				$i++;
			}
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</table>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get dice dropdown
//-------------------------------------------------------------------------------------------
function getDiceDropdown($database) {
	$selectStmt = "select * from dragons.diceRolls order by rollid";
	$returnHTML = "";
	
	$returnHTML .= '<select id="diceRollId" name="diceRollId"><option value="0">[dice type]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['rollId'] . '">' . $data['diceRoll'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get damage type dropdown
//-------------------------------------------------------------------------------------------
function getDamageTypeDropdown($database) {
	$selectStmt = "select * from dragons.damageTypes order by damageType";
	$returnHTML = "";
	
	$returnHTML .= '<select id="damageType" name="damageType"><option value="0">[damage type]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['damageTypeId'] . '">' . $data['damageType'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get item roles
//-------------------------------------------------------------------------------------------
function getDiceRoles($database) {
	$selectStmt = "select * from dragons.diceRoles order by diceRole";
	$returnHTML = "";
	
	$returnHTML .= '<select id="diceRole" name="diceRole">';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['diceRoleId'] . '">' . $data['diceRole'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get item action types dropdown 
//-------------------------------------------------------------------------------------------
function getItemActionTypesDropdown($database) {
	$selectStmt = "select * from dragons.itemActionTypes order by itemActionType";
	$returnHTML = "";
	
	$returnHTML .= '<select id="itemActionType" name="itemActionType">';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['itemActionTypeId'] . '">' . $data['itemActionType'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get monster attacks
//-------------------------------------------------------------------------------------------
function getMonsterAttacksDropdown($database) {
	$selectStmt = "select * from dragons.monsterAttackMaster where campaignId = ? order by attackName";
	$returnHTML = "";
	
	$returnHTML .= '<select id="monsterAttackMasterId" name="monsterAttackMasterId"><option>[monster attack]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$_GET['campaignId']))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['monsterAttackId'] . '">' . $data['attackName'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get spell dropdown 
//-------------------------------------------------------------------------------------------
function getSpellDropdown($database) {
	$selectStmt = "select * from dragons.spells where campaignId = ? order by spellName";
	$returnHTML = "";
	
	$returnHTML .= '<select id="spellId" name="spellId"><option>[magic spells]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$_GET['campaignId']))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetcH(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['spellId'] . '">' . $data['spellName'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get special skills dropdown
//-------------------------------------------------------------------------------------------
function getSpecialSkillsDropdown($database) {
	$selectStmt = "select * from dragons.specialSkills where campaignId = ? order by specialSkillName";
	$returnHTML = "";
	
	$returnHTML .= '<select id="specialSkillId" name="specialSkillId"><option>[special skill]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$_GET['campaignId']))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetcH(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['specialSkillId'] . '">' . $data['specialSkillName'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------