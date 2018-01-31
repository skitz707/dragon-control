<?php
//-------------------------------------------------------------------------------------------
// createItem.php - Create campaign item.
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// January 31st, 2018
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
$pageTitle = "DC - Manage Monsters";
$campaignId = $_GET['campaignId'];
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));

require_once("includes/header.php");
?>

<div id="mainContent">
	<span class="largeHeading">Create Item: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<form method="post" id="itemForm" action="addNewItem.php">
	<table class="standardResultTable" style="width: 30%; margin-left: auto; margin-right: auto;">
		<tr>
			<td>Item Type</td>
			<td><?php echo getItemTypeDropdown($database); ?></td>
		</tr>
		<tr>
			<td>Item Name</td>
			<td><input type="text" class="textField" name="itemName" id="itemName" size="20" /></td>
		</tr>
		<tr>
			<td>Item Description</td>
			<td><input type="text" class="textField" name="itemDescription" id="itemDescription" size="20" /></td>
		</tr>
		<tr>
			<td>Cost</td>
			<td><input type="text" class="textField" name="cost" id="cost" size="3" /></td>
		</tr>
		<tr>
			<td>Weight</td>
			<td><input type="text" class="textField" name="weight" id="weight" size="3" /></td>
		</tr>
		<tr>
			<td>Properties</td>
			<td><?php echo getPropertiesCheckboxes($database); ?></td>
		</tr>
	</table>
	<input type="hidden" name="campaignId" id="campaignId" value="<?php echo $campaignId; ?>" />
	</form>
	<br /><br />
	<span class="mediumHeading">Add Dice Rolls</span>
	<br /><br />
	# of Dice: <input type="text" class="textField" name="numberOfDice" id="numberOfDice" size="2" /> Dice Type: <?php echo getDiceDropdown($database); ?> 
		<select id="damageHeal" name="damageHeal"><option value="D">Damage</option><option value="H">Heal</option></select> Damage Type: <?php echo getDamageTypeDropdown($database); ?>
			<select id="primarySecondary" name="primarySecondary"><option value="P">Primary</option><option value="S">Secondary</option></select> <div class="blueButton" onClick="addDice()">Add Dice</div>
	<br /><br />
	<div id="hiddenParms"></div>
	<table style="width: 35%; margin-left: auto; margin-right: auto; text-align: center;" id="diceRollsAssigned">
		<tr>
			<th># of Dice</th>
			<th>Dice Roll</th>
			<th>Affect</th>
			<th>Damage Type</th>
			<th>P/S</th>
		</tr>
	</table>
	<div class="greenButton" onClick="document.getElementById('itemForm').submit();">Create Item</div>
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
	damageHeal = getSelectedValue('damageHeal');
	damageHealText = getSelectedText('damageHeal');
	damageType = getSelectedValue('damageType');
	damageTypeText = getSelectedText('damageType');
	primarySecondary = getSelectedValue('primarySecondary');
	primarySecondaryText = getSelectedText('primarySecondary');
	
	// create a new row in the table
	var newRow = diceRollsAssignedTable.insertRow(diceRollsAssignedTable.rows.length);
	
	// insert new data to the table
	var newCell  = newRow.insertCell(0);
	newCell.appendChild(document.createTextNode(numberOfDice));
	
	var newCell  = newRow.insertCell(1);
	newCell.appendChild(document.createTextNode(rollText));
	
	var newCell  = newRow.insertCell(2);
	newCell.appendChild(document.createTextNode(damageHealText));
	
	var newCell  = newRow.insertCell(3);
	newCell.appendChild(document.createTextNode(damageTypeText));
	
	var newCell  = newRow.insertCell(4);
	newCell.appendChild(document.createTextNode(primarySecondaryText));

	// create new hidden forms for posting
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "numberOfDice[]");
	input.setAttribute("value", numberOfDice);
	document.getElementById('itemForm').appendChild(input);
	
	var input = document.createElement("input");

	// create roll id hidden field
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "rollIds[]");
	input.setAttribute("value", rollId);
	document.getElementById('itemForm').appendChild(input);
	
	var input = document.createElement("input");

	// create damage/heal hidden field
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "damageHeals[]");
	input.setAttribute("value", damageHeal);
	document.getElementById('itemForm').appendChild(input);
	
	// create damage type hidden field
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "damageTypes[]");
	input.setAttribute("value", damageType);
	document.getElementById('itemForm').appendChild(input);
	
	var input = document.createElement("input");

	// create damage primary/secondary hidden field
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "primarySecondary[]");
	input.setAttribute("value", primarySecondary);
	document.getElementById('itemForm').appendChild(input);
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
// get item type dropdown
//-------------------------------------------------------------------------------------------
function getItemTypeDropdown($database) {
	$selectStmt = "select * from dragons.itemTypes order by itemType";
	$returnHTML = "";
	
	$returnHTML .= '<select id="itemType" name="itemType"><option value="N">[select item type]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['itemTypeId'] . '">' . $data['itemType'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get dice dropdown
//-------------------------------------------------------------------------------------------
function getDiceDropdown($database) {
	$selectStmt = "select * from dragons.diceRolls order by rollid";
	$returnHTML = "";
	
	$returnHTML .= '<select id="diceRollId" name="diceRollId"><option>[dice type]</option>';
	
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
	
	$returnHTML .= '<select id="damageType" name="damageType"><option>[damage type]</option>';
	
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
// print condition types checkbox
//-------------------------------------------------------------------------------------------
function getPropertiesCheckboxes($database) {
	$selectStmt = "select * from dragons.weaponProperties order by weaponProperty";
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
			
			$returnHTML .= '<td><input type="checkbox" name="weaponProperties[]" id="' . $data['weaponProperty'] . '" value="' . $data['weaponPropertyId'] . '" /></td>';
			$returnHTML .= '<td>' . $data['weaponProperty'] . '</td>';
			
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