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
require_once("classes/DCDatabase.php");
require_once("classes/DCItem.php");
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$item = new DCItem($database);
$itemId = $_GET['itemId'];
$item->loadItemById($itemId);
$itemMaster = $database->getDatabaseRecord("dragons.itemMaster", array("itemId"=>$itemId));
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$itemMaster['campaignId']));
$_GET['campaignId'] = $campaignHeader['campaignId'];

// check for armor class entry
$armorClass = $database->getDatabaseRecord("dragons.itemArmorClass", array("itemId"=>$itemId));
if ($armorClass['itemArmorClassId'] > 0) {
	$itemArmorClass = $armorClass['armorClass'];
} else {
	$itemArmorClass = "";
}

$pageTitle = "DC - Edit Item";
$crumbTrail = "";
$menuOptions = "";

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>

<br /><br />
<div id="mainContent">
	<span class="largeHeading">Edit Item: <?php echo $itemMaster['itemName']; ?></span>
	<br /><br />
	<form method="post" id="itemForm" action="updateItem.php">
	<table class="standardResultTable" style="width: 30%; margin-left: auto; margin-right: auto;">
		<tr>
			<td>Item Type</td>
			<td><?php echo getItemTypeDropdown($database, $itemMaster['itemType']); ?></td>
		</tr>
		<tr>
			<td>Item Name</td>
			<td><input type="text" class="textField" name="itemName" id="itemName" size="20" value="<?php echo $itemMaster['itemName']; ?>" /></td>
		</tr>
		<tr>
			<td>Item Description</td>
			<td><input type="text" class="textField" name="itemDescription" id="itemDescription" size="20" value="<?php echo $itemMaster['itemDescription']; ?>" /></td>
		</tr>
		<tr>
			<td>Cost</td>
			<td><input type="text" class="textField" name="cost" id="cost" size="3" value="<?php echo $itemMaster['cost']; ?>" /></td>
		</tr>
		<tr>
			<td>Weight</td>
			<td><input type="text" class="textField" name="weight" id="weight" size="3" value="<?php echo $itemMaster['itemWeight']; ?>" /></td>
		</tr>
		<tr>
			<td>AC</td>
			<td><input type="text" class="textField" name="armorClass" id="armorClass" size="3" value="<?php echo $itemArmorClass; ?>" /></td>
		</tr>
		<tr>
			<td>Properties</td>
			<td><?php echo getPropertiesCheckboxes($database, $item); ?></td>
		</tr>
		<tr>
			<td>Is Equipable</td>
			<td><?php echo getEquipableCheckboxes($database, $item); ?></td>
		</tr>
	</table>
	<input type="hidden" name="itemId" id="itemId" value="<?php echo $itemId; ?>" />
	</form>
	<br /><br />
	<span class="mediumHeading">Add Dice Rolls</span>
	<br /><br />
	# of Dice: <input type="text" class="textField" name="numberOfDice" id="numberOfDice" size="2" /> Dice Type: <?php echo getDiceDropdown($database); ?> Modifier: <input type="text" id="rollModifier" name="rollModifier" size="2" value="0" />
		<?php echo getItemActionTypesDropdown($database); ?> Damage Type: <?php echo getDamageTypeDropdown($database); ?>
			<?php echo getDiceRoles($database); ?> <div class="blueButton" onClick="addDice()">Add Dice</div>
	<br /><br />
	<div id="hiddenParms"></div>
	<?php echo getItemDiceHTML($database, $itemId); ?>
	<br />
	<div class="greenButton" onClick="document.getElementById('itemForm').submit();">Update Item</div>
</div>

<script>
//--------------------------------------------------------------------------
// add dice roll
//--------------------------------------------------------------------------
function addDice() {
	diceRollsAssignedTable = document.getElementById('diceRollsAssigned');
	hiddenParmsDiv = document.getElementById('hiddenParms');
	
	numberOfDice = document.getElementById('numberOfDice').value;
	rollModifier = document.getElementById('rollModifier').value;
	rollId = getSelectedValue('diceRollId');
	rollText = getSelectedText('diceRollId');
	itemActionType = getSelectedValue('itemActionType');
	itemActionTypeText = getSelectedText('itemActionType');
	damageType = getSelectedValue('damageType');
	damageTypeText = getSelectedText('damageType');
	diceRole = getSelectedValue('diceRole');
	diceRoleText = getSelectedText('diceRole');
	
	// create a new row in the table
	var newRow = diceRollsAssignedTable.insertRow(diceRollsAssignedTable.rows.length);
	
	// insert new data to the table
	var newCell  = newRow.insertCell(0);
	newCell.appendChild(document.createTextNode(numberOfDice));
	
	var newCell  = newRow.insertCell(1);
	newCell.appendChild(document.createTextNode(rollText));
	
	var newCell  = newRow.insertCell(2);
	newCell.appendChild(document.createTextNode(rollModifier));
	
	var newCell  = newRow.insertCell(3);
	newCell.appendChild(document.createTextNode(itemActionTypeText));
	
	var newCell  = newRow.insertCell(4);
	newCell.appendChild(document.createTextNode(damageTypeText));
	
	var newCell  = newRow.insertCell(5);
	newCell.appendChild(document.createTextNode(diceRoleText));

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
	
	// create roll modifiers hidden field
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "rollModifiers[]");
	input.setAttribute("value", rollModifier);
	document.getElementById('itemForm').appendChild(input);
	
	var input = document.createElement("input");

	// create item action types hidden field
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "itemActionTypes[]");
	input.setAttribute("value", itemActionType);
	document.getElementById('itemForm').appendChild(input);
	
	// create damage type hidden field
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "damageTypes[]");
	input.setAttribute("value", damageType);
	document.getElementById('itemForm').appendChild(input);

	// create dice role hidden field
	var input = document.createElement("input");

	input.setAttribute("type", "hidden");
	input.setAttribute("name", "diceRoles[]");
	input.setAttribute("value", diceRole);
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
function getItemTypeDropdown($database, $itemTypeId) {
	$selectStmt = "select * from dragons.itemTypes order by itemType";
	$returnHTML = "";
	
	$returnHTML .= '<select id="itemType" name="itemType"><option value="N">[select item type]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			if ($itemTypeId == $data['itemTypeId']) {
				$selected = " selected";
			} else {
				$selected = "";
			}
			
			$returnHTML .= '<option value="' . $data['itemTypeId'] . '"' . $selected . '>' . $data['itemType'] . '</option>';
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
// print condition types checkbox
//-------------------------------------------------------------------------------------------
function getPropertiesCheckboxes($database, $item) {
	$selectStmt = "select * from dragons.weaponProperties order by weaponProperty";
	$returnHTML = "";
	$boxesPerLine = 3;
	$i = 1;
	$itemProperties = $item->getItemProperties();
	
	$returnHTML .= '<table>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			if ($i == 1) {
				$returnHTML .= '<tr style="background-color: transparent;">';
			}
			
			// check if weapon has property
			if (in_array($data['weaponPropertyId'], $itemProperties)) {
				$checked = " checked";
			} else {
				$checked = "";
			}
			
			$returnHTML .= '<td><input type="checkbox" name="weaponProperties[]" id="' . $data['weaponProperty'] . '" value="' . $data['weaponPropertyId'] . '"' . $checked . ' /></td>';
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


//-------------------------------------------------------------------------------------------
// print equipable locations checkbox
//-------------------------------------------------------------------------------------------
function getEquipableCheckboxes($database, $item) {
	$selectStmt = "select * from dragons.equipableLocations order by equipableLocationId";
	$returnHTML = "";
	$boxesPerLine = 3;
	$i = 1;
	$itemEquipableLocations = $item->getItemEquipableLocations();
	
	$returnHTML .= '<table>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			if ($i == 1) {
				$returnHTML .= '<tr style="background-color: transparent;">';
			}
			
			// check if weapon has property
			if (in_array($data['equipableLocationId'], $itemEquipableLocations)) {
				$checked = " checked";
			} else {
				$checked = "";
			}
			
			$returnHTML .= '<td><input type="checkbox" name="equipableLocations[]" id="' . $data['equipableLocation'] . '" value="' . $data['equipableLocationId'] . '"' . $checked . ' /></td>';
			$returnHTML .= '<td>' . $data['equipableLocation'] . '</td>';
			
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
// get item dice html
//-------------------------------------------------------------------------------------------
function getItemDiceHTML($database, $itemId) {
	$selectStmt = "select * from dragons.itemDice where itemId = ? order by itemActionTypeId";
	$returnHTML = "";
	
	$returnHTML .= '<table style="width: 35%; margin-left: auto; margin-right: auto; text-align: center;" id="diceRollsAssigned">
						<tr>
							<th># of Dice</th>
							<th>Dice Roll</th>
							<th>Modifier</th>
							<th>Affect</th>
							<th>Damage Type</th>
							<th>P/S</th>
							<th></th>
						</tr>
	';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$itemId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$diceRoll = $database->getDatabaseRecord("dragons.diceRolls", array("rollId"=>$data['rollId']));
			$diceRole = $database->getDatabaseRecord("dragons.diceRoles", array("diceRoleId"=>$data['diceRoleId']));
			$damageType = $database->getDatabaseRecord("dragons.damageTypes", array("damageTypeId"=>$data['damageTypeId']));
			$actionType = $database->getDatabaseRecord("dragons.itemActionTypes", array("itemActionTypeId"=>$data['itemActionTypeId']));
			
			$returnHTML .= '<tr>
								<td>' . $data['numberOfDice'] . '</td>
								<td>' . $diceRoll['diceRoll'] . '</td>
								<td>' . $data['rollModifier'] . '</td>
								<td>' . $actionType['itemActionType'] . '</td>
								<td>' . $damageType['damageType'] . '</tD>
								<td>' . $diceRole['diceRole'] . '</td>
								<td><a href="removeItemDice.php?itemDiceId=' . $data['itemDiceId'] . '">Remove</a></td>
							</tr>
			';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</table>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------