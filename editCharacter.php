<?php
//-------------------------------------------------------------------------------------------
// editCharacter.php - Edit character
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
$pageTitle = "DC - Edit Character";
$characterId = $_GET['characterId'];
$characterMaster = $database->getDatabaseRecord("dragons.characters", array("characterId"=>$characterId));
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$characterMaster['campaignId']));

require_once("includes/header.php");
?>

<div id="mainContent">
	<span class="largeHeading">Edit Character: <?php echo $characterMaster['characterName']; ?></span>
	<br /><br />
	<form method="post" id="characterForm" action="updateCharacter.php">
	<table class="standardResultTable" style="width: 30%; margin-left: auto; margin-right: auto;">
		<tr>
			<td>Character Name</td>
			<td><input type="text" class="textField" name="characterName" id="characterName" size="20" value="<?php echo $characterMaster['characterName']; ?>" /></td>
		</tr>
		<tr>
			<td>Armor Class</td>
			<td><input type="text" class="textField" name="armorClass" id="armorClass" size="3" value="<?php echo $characterMaster['armorClass']; ?>" /></td>
		</tr>
		<tr>
			<td>Health</td>
			<td><input type="text" class="textField" name="health" id="health" size="3" value="<?php echo $characterMaster['maxHP']; ?>" /></td>
		</tr>
		<tr>
			<td>Strength</td>
			<td><input type="text" class="textField" name="strength" id="strength" size="3" value="<?php echo $characterMaster['strength']; ?>" /></td>
		</tr>
		<tr>
			<td>Dexterity</td>
			<td><input type="text" class="textField" name="dexterity" id="dexterity" size="3" value="<?php echo $characterMaster['dexterity']; ?>" /></td>
		</tr>
		<tr>
			<td>Constitution</td>
			<td><input type="text" class="textField" name="constitution" id="constitution" size="3" value="<?php echo $characterMaster['constitution']; ?>" /></td>
		</tr>
		<tr>
			<td>Intelligence</td>
			<td><input type="text" class="textField" name="intelligence" id="intelligence" size="3" value="<?php echo $characterMaster['intelligence']; ?>" /></td>
		</tr>
		<tr>
			<td>Wisdom</td>
			<td><input type="text" class="textField" name="wisdom" id="wisdom" size="3" value="<?php echo $characterMaster['wisdom']; ?>" /></td>
		</tr>
		<tr>
			<td>Charisma</td>
			<td><input type="text" class="textField" name="charisma" id="charisma" size="3" value="<?php echo $characterMaster['charisma']; ?>" /></td>
		</tr>
		<tr>
			<td>Status</td>
			<td><input type="text" class="textField" name="statusFlag" id="statusFlag" size="3" value="<?php echo $characterMaster['statusFlag']; ?>" /></td>
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
	<input type="hidden" name="campaignId" id="campaignId" value="<?php echo $campaignHeader['campaignId']; ?>" />
	<input type="hidden" name="characterId" id="characterId" value="<?php echo $characterId; ?>" />
	</form>
	<div class="greenButton" onClick="document.getElementById('characterForm').submit();">Update Character</div>
	<br /><br />
	<span class="mediumHeading">Character Items</span>
	<br /><br />
	<form method="post" action="addCharacterItem.php" id="itemForm">
	Add Item: <?php echo getItemDropdown($database); ?> Quantity: <input type="text" id="itemQuantity" name="itemQuantity" size="2" /> <div class="blueButton" onClick="document.getElementById('itemForm').submit();">Add Item</div>
	<input type="hidden" id="characterId" name="characterId" value="<?php echo $characterId; ?>" />
	</form>
	<br /><br />
	<?php echo getCharacterItems($database, $characterId); ?>
</div>

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
// get item dropdown
//-------------------------------------------------------------------------------------------
function getItemDropdown($database) {
	$selectStmt = "select * from dragons.itemMaster order by itemName";
	$returnHTML = "";
	
	$returnHTML .= '<select id="itemId" name="itemId"><option>[select an item]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['itemId'] . '">' . $data['itemName'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= '</select>';
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get character items
//-------------------------------------------------------------------------------------------
function getCharacterItems($database, $characterId) {
	$selectStmt = "select * from dragons.characterItems t1 inner join dragons.itemMaster t2 on t1.itemId = t2.itemId where t1.characterId = ? order by t2.itemName";
	$returnHTML = "";
	
	$returnHTML .= '<table class="standardResultTable" style="width: 35%;">
						<tr>
							<th>Item</th>
							<th>Type</th>
							<th>Quantity</th>
						</tr>
	';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$characterId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$itemType = $database->getDatabaseRecord("dragons.itemTypes", array("itemTypeId"=>$data['itemType']));
			
			$returnHTML .= '<tr>
								<td>' . $data['itemName'] . '</td>
								<td>' . $itemType['itemType'] . '</td>
								<td>' . $data['quantity'] . '</td>
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

