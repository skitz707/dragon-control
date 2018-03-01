<?php
//-------------------------------------------------------------------------------------------
// characterEquipment.php
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// March 1st, 2018
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
require_once("classes/DCCharacter.php");
require_once("classes/DCItem.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// program directives
//-------------------------------------------------------------------------------------------
session_start();
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$security = new DCSecurity($database);
$user = new DCUser($database);
$character = new DCCharacter($database);
$item = new DCItem($database);
$character->loadCharacterById($_GET['characterId']);

// check for active user
$security->checkLogin();

$user->loadUserById($_SESSION['userId']);

$pageTitle = "DC - Equipment";
$crumbTrail = "Characters > " . $character->getName() . " &gt Equipment";

// get menu options
ob_start();
require('includes/characterMenuOptions.php');
$menuOptions = ob_get_clean();

$equipmentHTML = getEquipmentHTML($database, $character, $item);

require_once("includes/header.php");
?>

<div id="mainContent">
	<div id="equipment" style="padding-top: 110px; text-align: left;">
		<?php echo $equipmentHTML; ?>
	</div>
	<div id="popUpBox"></div>
</div>

<script>
//----------------------------------------------------------------------------
// equip item
//----------------------------------------------------------------------------
function equipItem(equipableLocationId) {
	// get location drop down menus
	dropdownMenus = new Array();
	dropdownMenus[1] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 1, 0); ?>';
	dropdownMenus[2] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 2, 0); ?>';
	dropdownMenus[3] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 3, 0); ?>';
	dropdownMenus[4] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 4, 0); ?>';
	dropdownMenus[5] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 5, 0); ?>';
	dropdownMenus[6] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 6, 0); ?>';
	dropdownMenus[7] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 7, 0); ?>';
	dropdownMenus[8] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 8, 0); ?>';
	dropdownMenus[9] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 9, 0); ?>';
	dropdownMenus[10] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 10, 0); ?>';
	dropdownMenus[11] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 11, 0); ?>';
	dropdownMenus[12] = '<?php echo getCharacterItemDropdown($database, $character->getId(), 12, 0); ?>';
	
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="equipItem.php?type=C" id="equipForm">';
	divHTML += '<span style="font-size: 28pt;">Item:<br />' + dropdownMenus[equipableLocationId] + ' <div class="blueButton" onClick="document.getElementById(\'equipForm\').submit();">Equip</div></span>';
	divHTML += '<input type="hidden" name="characterId" id="characterId" value="' + <?php echo $character->getId(); ?> + '" />';
	divHTML += '<input type="hidden" name="equipableLocationId" id="equipableLocationId" value="' + equipableLocationId + '" />';
	divHTML += '<input type="hidden" name="campaignId" id="campaignId" value="<?php echo $character->getCampaignId(); ?>" />';
	divHTML += '<input type="hidden" name="returnTo" id="returnTo" value="characterEquipment.php?characterId=<?php echo $character->getId(); ?>" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 725,
			height: 250
		});
	});
}
//----------------------------------------------------------------------------
</script>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get items html
//-------------------------------------------------------------------------------------------
function getEquipmentHTML($database, $character, $item) {
	$returnHTML = "";
	$selectStmt = "select * from dragons.equipableLocations order by equipableLocationId";
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute()) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			// look for equipped item in location
			$equippedItem = $database->getDatabaseRecord("dragons.characterEquippedItems", array("characterId"=>$character->getId(), "equipableLocationId"=>$data['equipableLocationId']));
			
			if ($equippedItem['characterEquippedItemId'] > 0) {
				$itemMaster = $database->getDatabaseRecord("dragons.itemMaster", array("itemId"=>$equippedItem['itemId']));
				$itemName = $itemMaster['itemName'];
			} else {
				$itemName = "[empty]";
			}
	
			$returnHTML .= '<div class="itemSelect" onClick="equipItem(\'' . $data['equipableLocationId'] . '\');">
							<img src="' . $data['imageLocation'] . '" width="150px" height="150px" style="float: left; padding-right: 25px; padding-left: 25px;" />
							<span style="font-size: 38pt;">' . $data['equipableLocation'] . '</span><br />
							<span style="font-size: 26pt; font-style: italic;">' . $itemName . '</span><br /></div>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get character item dropdown
//-------------------------------------------------------------------------------------------
function getCharacterItemDropdown($database, $characterId, $equipableLocationId, $equippedItemId) {
	$selectStmt = "select * from dragons.characterItems t1 inner join dragons.itemMaster t2 on t1.itemId = t2.itemId 
					left join dragons.itemEquipableLocations t3 on t1.itemId = t3.itemId where t1.characterId = ? and t3.equipableLocationId = ? order by t2.itemName";
	$returnHTML = "";
	
	$returnHTML .= '<select id="itemId" name="itemId"><option>[select item]</option>';

	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$characterId, 1=>$equipableLocationId))) {
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