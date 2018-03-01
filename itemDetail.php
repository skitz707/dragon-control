<?php
//-------------------------------------------------------------------------------------------
// characters.php
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// February 28th, 2018
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

$characterItem = $database->getDatabaseRecord("dragons.characterItems", array("characterItemId"=>$_GET['characterItemId']));
$character->loadCharacterById($characterItem['characterId']);
$item->loadItemById($characterItem['itemId']);


// check for active user
$security->checkLogin();

$user->loadUserById($_SESSION['userId']);

$pageTitle = "DC - Item Detail";
$crumbTrail = "Characters > " . $character->getName() . " &gt Item";

// get menu options
ob_start();
require('includes/characterMenuOptions.php');
$menuOptions = ob_get_clean();

$attackInfo = getAttackInfo($database, $item);
$properties = getItemProperties($database, $item);

require_once("includes/header.php");
?>

<div id="mainContent">
	<div id="characterDetail" style="padding-top: 110px; text-align: left;">
		<img src="<?php echo $item->getImageLocation(); ?>" width="250px" height="250px" style="float: left; padding-right: 25px; padding-left: 25px;" />
		<span style="font-size: 38pt;"><?php echo $item->getItemName(); ?></span><br />
		<span style="font-style: italic; font-size: 32pt;"><?php echo $item->getItemType(); ?> </span><br />
		<span style="font-size: 30pt;">Quantity: <?php echo $characterItem['quantity']; ?><br />
		Value: <?php echo number_format($item->getItemCost(), 0, "", ""); ?>gp <span style="padding-left: 25px;">Weight: <?php echo $item->getItemWeight(); ?>lb</span></span>
		<div style="clear:both"></div>
		<br />
		<?php echo $attackInfo; ?>
		<?php echo $properties; ?>
		<br /><br /><br />
		<div style="text-align: center; font-size: 62pt;">
			<div class="redButton" onClick="dropItem('<?php echo $_GET['characterItemId']; ?>');">Drop</div> 
			<div class="blueButton" onClick="useItem('<?php echo $_GET['characterItemId']; ?>');">Use</div> 
			<div class="greenButton">Give</div><br /><br />
			<div class="blueButton" onClick="document.location.href='characterItems.php?characterId=<?php echo $character->getId(); ?>';">&lt Back</div>
		</div>
	</div>
	<div id="popUpBox"></div>
</div>

<script>
//----------------------------------------------------------------------------
// drop item
//----------------------------------------------------------------------------
function dropItem(id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="dropItem.php" id="dropForm">';
	divHTML += '<span style="font-size: 48pt;">Are you sure?<br /> <div class="greenButton" onClick="document.getElementById(\'dropForm\').submit();">Yes</div> <div class="redButton">Cancel</div></span>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '<input type="hidden" name="returnTo" id="returnTo" value="characterItems.php?characterId=<?php echo $character->getId(); ?>" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 500,
			height: 300
		});
	});
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
// use item
//----------------------------------------------------------------------------
function useItem(id) {
	divObj = document.getElementById('popUpBox');
	divHTML = "";
	divHTML += '<form method="post" action="useItem.php" id="useForm">';
	divHTML += '<span style="font-size: 48pt;">How many?<br /> <input type="text" size="2" id="quantity" name="quantity" /> <div class="blueButton" onClick="document.getElementById(\'useForm\').submit();">Use</div></span>';
	divHTML += '<input type="hidden" name="id" id="id" value="' + id + '" />';
	divHTML += '<input type="hidden" name="returnTo" id="returnTo" value="characterItems.php?characterId=<?php echo $character->getId(); ?>" />';
	divHTML += '</form>';
	
	divHTML = divHTML.replace(/null/g, '');
	divObj.innerHTML = divHTML;
	
	$(function() {
		$( "#popUpBox" ).dialog({
			width: 450,
			height: 300
		});
	});
}
//----------------------------------------------------------------------------
</script>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get attack info
//-------------------------------------------------------------------------------------------
function getAttackInfo($database, $item) {
	$itemDiceIds = $item->getItemDice();
	$returnHTML = "";
	
	if (count($itemDiceIds) > 0) {
		$returnHTML .= '<span style="font-size: 38pt;">Attacks</span><br /><span style="font-size: 32pt; font-style: italic;">';
		
		foreach ($itemDiceIds as $itemDiceId) {
			$itemDice = $database->getDatabaseRecord("dragons.itemDice", array("itemDiceId"=>$itemDiceId));
			$dice = $database->getDatabaseRecord("dragons.diceRolls", array("rollId"=>$itemDice['rollId']));
			$damageType = $database->getDatabaseRecord("dragons.damageTypes", array("damageTypeId"=>$itemDice['damageTypeId']));
			$itemAction = $database->getDatabaseRecord("dragons.itemActionTypes", array("itemActionTypeId"=>$itemDice['itemActionTypeId']));
			$diceRole = $database->getDatabaseRecord("dragons.diceRoles", array("diceRoleId"=>$itemDice['diceRoleId']));
			
			$returnHTML .= $diceRole['diceRole'] . ' - ' . $itemDice['numberOfDice'] . $dice['diceRoll'] . ' ' . $damageType['damageType'] . ' ' . $itemAction['itemActionType'] . '<br />';
		}
		
		$returnHTML .= "</span>";
	}
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get item properties
//-------------------------------------------------------------------------------------------
function getItemProperties($database, $item) {
	$itemPropertyIds = $item->getItemProperties();
	$returnHTML = "";
	$properties = array();
	
	if (count($itemPropertyIds) > 0) {
		$returnHTML .= '<br /><span style="font-size: 38pt;">Properties</span><br /><span style="font-size: 32pt; font-style: italic;">';
		
		foreach ($itemPropertyIds as $itemPropertyId) {
			$propertyData = $database->getDatabaseRecord("dragons.weaponProperties", array("weaponPropertyId"=>$itemPropertyId));
			$properties[] = $propertyData['weaponProperty'];
		}
		
		$returnHTML .= implode(",", $properties) . '</span>';
	}
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------