<?php
//-------------------------------------------------------------------------------------------
// DDMonster.php
// Written by: Michael C. Szczepanik
// November 19th, 2017
// DDEnemy() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DDObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// database class definition
//-------------------------------------------------------------------------------------------
class DDMonster extends DDObject {
	// class properties
	protected $monsterId;
	protected $battleDetailId;
	protected $monsterName;
	protected $maxHP;
	protected $currentHP;
	protected $armorClass;
	protected $strength;
	protected $dexterity;
	protected $constitution;
	protected $intelligence;
	protected $wisdom;
	protected $charisma;
	protected $initiative;
	protected $imageLocation;
	protected $statusFlag;
	protected $lastChange;
	protected $creationDate;
	
	
	//------------------------------------------------------------------------
	// load player by playerId
	//------------------------------------------------------------------------
	public function loadMonsterById($monsterId) {
		$monsterRecord = $this->database->getDatabaseRecord("dragons.monsters", array("monsterId"=>$monsterId));
		
		$this->monsterId = $monsterId;
		$this->monsterName = $monsterRecord['monsterName'];
		$this->maxHP = $monsterRecord['health'];
		$this->currentHP = $monsterRecord['health'];
		$this->armorClass = $monsterRecord['armorClass'];
		$this->strength = $monsterRecord['strength'];
		$this->dexterity = $monsterRecord['dexterity'];
		$this->constitution = $monsterRecord['constitution'];
		$this->intelligence = $monsterRecord['intelligence'];
		$this->wisdom = $monsterRecord['wisdom'];
		$this->charisma = $monsterRecord['charisma'];
		$this->imageLocation = $monsterRecord['imageLocation'];
		$this->lastChange = $monsterRecord['lastChange'];
		$this->creationDate = $monsterRecord['creationDate'];
		
		// check for battle detail id to override hp
		if ($this->battleDetailId > 0) {
			$battleDetail = $this->database->getDatabaseRecord("dragons.battleDetail", array("entryId"=>$this->battleDetailId));
			$this->currentHP = $battleDetail['currentHP'];
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// load monster by battle id
	//------------------------------------------------------------------------
	public function loadMonsterByBattleDetailId($battleDetailId) {
		$battleRecord = $this->database->getDatabaseRecord("dragons.battleDetail", array("entryId"=>$battleDetailId));
		$this->battleDetailId = $battleDetailId;
		$this->initiative = $battleRecord['initiative'];
		
		$this->loadMonsterById($battleRecord['associatedId']);
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// print monster card
	//------------------------------------------------------------------------
	public function printMonsterCard() {
		echo '
			<div class="monsterCard">
				<img src="' . $this->imageLocation . '" width="120px" height="160px" /><br />
				<span class="monsterName">' . $this->monsterName . '</span><br />
				AC: ' . $this->armorClass . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '
			</div>
		';
	}
	//------------------------------------------------------------------------
	
	
	
	//------------------------------------------------------------------------
	// print admin monster card
	//------------------------------------------------------------------------
	public function printAdminMonsterCard() {
		echo '
			<div class="adminMonsterCard">
				<img src="' . $this->imageLocation . '" width="80px" height="120px" /><br />
				<span class="adminMonsterName">' . $this->monsterName . '</span><br />
				<em>Enemy</em><br />
				AC: ' . $this->armorClass . '<br />
				HP: ' . $this->currentHP . '/' . $this->maxHP . '<br />
				Initiative: ' . number_format($this->initiative, 0, "", "") . '<br />
				<div class="blueButton" onClick="setInit(\'M\', ' . $this->battleDetailId . ');">Set Init</div><br /><div class="redButton" onClick="takeDamage(\'M\', ' . $this->battleDetailId . ');">Take Damage</div><br /><div class="greenButton" onClick="heal(\'M\', ' . $this->battleDetailId . ');">Heal</div>
			</div>
		';
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------