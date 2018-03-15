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
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// mainline
//-------------------------------------------------------------------------------------------
$database = new DCDatabase();
$security = new DCSecurity($database);
$user = new DCUser($database);
$character = new DCCharacter($database);

$character->loadCharacterById($_GET['characterId']);

// check for active user
$security->checkLogin();

$pageTitle = "DC - Item Detail";
$crumbTrail = "Characters > " . $character->getName() . " &gt Item";

// get menu options
ob_start();
require('includes/characterMenuOptions.php');
$menuOptions = ob_get_clean();

$itemDropDownHTML = getItemDropdownHTML($database, $character->getCampaignId());

require_once("includes/header.php");
?>

<div id="mainContent">
	<div id="selectItem" style="padding-top: 110px; text-align: left;">
		<form method="post" action="addCharacterItem.php" id="itemForm">
		<span style="font-size: 56pt;">Item:<br />
		<?php echo $itemDropDownHTML; ?><br /><br />
		Qty: <input type="text" id="itemQuantity" name="itemQuantity" size="1" style="font-size: 56pt;" /> <div class="greenButton" onClick="document.getElementById('itemForm').submit();">Add</div></span>
		<input type="hidden" name="returnTo" id="returnTo" value="characterItems.php?characterId=<?php echo $character->getId(); ?>" />';
		<input type="hidden" name="characterId" id="characterId" value="<?php echo $character->getId(); ?>" />
		</form>
	</div>
</div>

<script>

</script>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get item dropdown html
//-------------------------------------------------------------------------------------------
function getItemDropdownHTML($database, $campaignId) {
	$selectStmt = "select * from dragons.itemMaster where campaignId = ? order by itemName";
	$returnHTML = "";
	
	$returnHTML .= '<select id="itemId" name="itemId" style="font-size: 42pt;"><option value="0">[select item]</option>';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$campaignId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetcH(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<option value="' . $data['itemId'] . '">' . $data['itemName'] . '</option>';
		}
	} else {
		var_dump($database->databaseConnection->errorInfo());
	}
	
	$returnHTML .= "</select>";
	
	return $returnHTML;
}
//-------------------------------------------------------------------------------------------