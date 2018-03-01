<?php
//-------------------------------------------------------------------------------------------
// manageSpells.php
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

$pageTitle = "DC - Manage Spells";
$crumbTrail = "";
$menuOptions = "";

require_once("includes/header.php");
include_once("includes/leaderNavigation.php");
?>

<br /><br />
<div id="mainContent">
	<span class="largeHeading">Manage Spells: <?php echo $campaignHeader['campaignName']; ?></span>
	<br /><br />
	<a href="createSpell.php?campaignId=<?php echo $campaignId; ?>">Create Spell</a>
	<br /><br />
	<?php echo getSpellTableHTML($database, $campaignId); ?>
</div>

<?php
require_once("includes/footer.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// get spell table
//-------------------------------------------------------------------------------------------
function getSpellTableHTML($database, $campaignId) {
	$selectStmt = "select * from dragons.spells where campaignId = ? order by spellLevel, spellName";
	$returnHTML = "";
	
	$returnHTML .= '<table class="standardResultTable">
						<tr>
							<th>Name</th>
							<th>Level</th>
							<th>School</th>
							<th></th>
						</tr>
	';
	
	if ($selectHandle = $database->databaseConnection->prepare($selectStmt)) {
		if (!$selectHandle->execute(array(0=>$campaignId))) {
			var_dump($database->databaseConnection->errorInfo());
		}
		
		while ($data = $selectHandle->fetch(PDO::FETCH_ASSOC)) {
			$magicSchool = $database->getDatabaseRecord("dragons.magicSchools", array("magicSchoolId"=>$data['spellSchoolId']));
			
			$returnHTML .= '<tr>
								<td>' . $data['spellName'] . '</td>
								<td>' . $data['spellLevel'] . '</td>
								<td>' . $magicSchool['magicSchool'] . '</td>
								<td><a href="editSpell.php?spellId=' . $data['spellId'] . '">Edit</a></td>
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