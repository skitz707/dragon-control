<?php
//-------------------------------------------------------------------------------------------
// monsterAttacks.php
// Written by: Michael C. Szczepanik
// rocknrollwontdie@gmail.com
// February 21st, 2018
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

$pageTitle = "DC - Monster Attacks";
$crumbTrail = "";
$menuOptions = "";

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>

<br /><br />
<div id="mainContent">
	<span class="largeHeading">Monster Attacks: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<a href="createMonsterAttack.php?campaignId=<?php echo $campaignId; ?>">Create Attack</a>
	<br /><br />
	<?php echo getMonsterAttacksTableHTML($database, $campaignId); ?>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get monster attacks table html
//-------------------------------------------------------------------------------------------
function getMonsterAttacksTableHTML($database, $campaignId) {
	$selectStmt = "select t1.monsterAttackId, t1.attackName as attackName, t2.attackName as attackType from dragons.monsterAttackMaster t1 
					inner join dragons.attackTypes t2 on t1.attackTypeId = t2.attackTypeId 
						where campaignId = ? order by attackName";
	$returnHTML = "";
	
	$returnHTML .= '<table class="standardResultTable" width="30%">
						<tr>
							<th>Attack Name</th>
							<th>Attack Type</th>
							<th></th>
						</tr>
	';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$campaignId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$returnHTML .= '<tr>
								<td><span>' . $data['attackName'] . '</span></td>
								<td>' . $data['attackType'] . '</td>
								<td><a href="editMonsterAttack.php?monsterAttackId=' . $data['monsterAttackId'] . '">Edit</a></td>
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