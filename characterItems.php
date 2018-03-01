<?php
//-------------------------------------------------------------------------------------------
// characterItems.php
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

$pageTitle = "DC - Items";
$crumbTrail = "Characters > " . $character->getName() . " &gt Items";

// get menu options
ob_start();
require('includes/characterMenuOptions.php');
$menuOptions = ob_get_clean();

$itemsHTML = getItemsHTML($database, $character, $item);

require_once("includes/header.php");
?>

<div id="mainContent">
	<div id="items" style="padding-top: 110px; text-align: left;">
		<?php echo $itemsHTML; ?>
	</div>
	<div id="popUpBox"></div>
</div>

<script>
//----------------------------------------------------------------------------
// set initiative
//----------------------------------------------------------------------------
function useItem(id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="setInitiative.php?type=C" id="initForm">';
	divHTML += '<span style="font-size: 48pt;">Initiative: <input type="text" size="2" id="initiative" name="initiative" /> <div class="blueButton" onClick="document.getElementById(\'initForm\').submit();">Set</div></span>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '<input type="hidden" name="campaignId" id="campaignId" value="<?php echo $character->getCampaignId(); ?>" />';
	divHTML += '<input type="hidden" name="returnTo" id="returnTo" value="characterDetail.php?characterId=<?php echo $character->getId(); ?>" />';
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
function getItemsHTML($database, $character, $item) {
	$characterItemIds = $character->getItems();
	$returnHTML = "";
	
	foreach ($characterItemIds as $characterItemId) {
		$characterItemData = $database->getDatabaseRecord("dragons.characterItems", array("characterItemId"=>$characterItemId));
		$item->loadItemById($characterItemData['itemId']);
		
		$returnHTML .= '<div class="itemSelect" onClick="document.location.href=\'itemDetail.php?characterItemId=' . $characterItemId . '\';">
						<img src="' . $item->getImageLocation() . '" width="150px" height="150px" style="float: left; padding-right: 25px; padding-left: 25px;" />
						<span style="font-size: 38pt;">' . $item->getItemName() . '</span><br />
						<span style="font-size: 26pt; font-style: italic;">' . $item->getItemType() . '</span><br />
						<span style="font-size: 22pt;">Qty: ' . $characterItemData['quantity'] . '<span style="padding-left: 30px;">Weight: ' . $item->getItemWeight() . '</span>
						<span style="padding-left: 30px;">Value: ' . number_format($item->getItemCost(), 0, "", "") . 'gp</span></span></div>';
	}
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------