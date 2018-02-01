<?php
//-------------------------------------------------------------------------------------------
// editCharacterEquipment.php - Edit character
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
	<span class="mediumHeading">Character Equipment</span>
	<br /><br />
	<?php echo getEquippedItems($database, $characterId); ?>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get equipped items
//-------------------------------------------------------------------------------------------
function getEquippedItems($database, $characterId) {
	$selectStmt = "select * from dragons.equipableLocations order by equipableLocationId";
	$returnHTML = "";
	
	$returnHTML .= '<table class="standardResultTable" style="width: 35%;">';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			// look for equipped item in location
			$equippedItem = $database->getDatabaseRecord("dragons.characterEquippedItems", array("characterId"=>$characterId, "equipableLocationId"=>$data['equipableLocationId']));
			
			if ($equippedItem['characterEquippedItemId'] > 0) {
				$itemMaster = $database->getDatabaseRecord("dragons.itemMaster", array("itemId"=>$equippedItem['itemId']));
				$itemName = $itemMaster['itemName'];
			} else {
				$itemName = "[empty]";
			}
			
			$returnHTML .= '<tr>
								<td>' . $data['equipableLocation'] . '</td>
								<td>' . $itemName . '</td>
								<td>' . getCharacterItemDropdown($database, $characterId, $data['equipableLocationId'], $equippedItem['itemId']) . '</td>
								<td><div class="blueButton">Equip</div></td>
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



//-------------------------------------------------------------------------------------------
// get character item dropdown
//-------------------------------------------------------------------------------------------
function getCharacterItemDropdown($database, $characterId, $equipableLocationId, $equippedItemId) {
	$selectStmt = "select * from dragons.characterItems t1 inner join dragons.itemMaster t2 on t1.itemId = t2.itemId where t1.characterId = ? order by t2.itemName";
	$returnHTML = "";

	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$characterId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			var_dump($data);
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------