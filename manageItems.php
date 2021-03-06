<?php
//-------------------------------------------------------------------------------------------
// manageItems.php - Manage items.
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
$campaignId = $_GET['campaignId'];
$campaignHeader = $database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));

$pageTitle = "DC - Manage Items";
$crumbTrail = "";
$menuOptions = "";

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>

<br /><br />
<div id="mainContent">
	<span class="largeHeading">Manage Items: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<a href="createItem.php?campaignId=<?php echo $campaignId; ?>">Create Item</a>
	<br /><br />
	<?php echo getItemTableHTML($database, $campaignId); ?>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get item table
//-------------------------------------------------------------------------------------------
function getItemTableHTML($database, $campaignId) {
	$selectStmt = "select * from dragons.itemMaster where campaignId = ? order by itemName";
	$returnHTML = "";
	
	$returnHTML .= '<table class="standardResultTable" style="width: 35%;">
						<tr>
							<th>Name</th>
							<th>Type</th>
							<th>Cost</th>
							<th>Weight</th>
							<th></th>
						</tr>
	';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$campaignId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$itemType = $database->getDatabaseRecord("dragons.itemTypes", array("itemTypeId"=>$data['itemType']));
			
			$returnHTML .= '<tr>
								<td>' . $data['itemName'] . '</td>
								<td>' . $itemType['itemType'] . '</td>
								<td>' . $data['cost'] . '</td>
								<td>' . $data['itemWeight'] . '</td>
								<td><a href="editItem.php?itemId=' . $data['itemId'] . '">Edit</a></td>
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