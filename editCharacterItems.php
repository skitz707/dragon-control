<?php
//-------------------------------------------------------------------------------------------
// editCharacterItems.php - Edit character
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
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$pageTitle = "DC - Edit Character";
$characterId = $_GET['characterId'];
$characterMaster = $database->getDatabaseRecord("dragons.characters", array("characterId"=>$characterId));
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$characterMaster['campaignId']));
$_GET['campaignId'] = $campaignHeader['campaignId'];

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>

<br /><br />
<div id="mainContent">
	<span class="largeHeading">Edit Character: <?php echo $characterMaster['characterName']; ?></span>
	<br /><br />
	<a href="editCharacterInfo.php?characterId=<?php echo $characterId; ?>">Character Info</a> | <a href="editCharacterItems.php?characterId=<?php echo $characterId; ?>">Character Items</a> |
	<a href="editCharacterEquipment.php?characterId=<?php echo $characterId; ?>">Character Equipment</a>
	<br /><br />
	<span class="mediumHeading">Character Items</span>
	<br /><br />
	<form method="post" action="aDCCharacterItem.php" id="itemForm">
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