<?php
//-------------------------------------------------------------------------------------------
// DDCreature.php
// Written by: Michael C. Szczepanik
// November 19th, 2017
// DDCreature() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DDObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// creature class definition
//-------------------------------------------------------------------------------------------
class DDCreature extends DDObject {
	// class properties
	protected $characterId;
	protected $campaignId;
	protected $questId;
	protected $battleDetailId;
	protected $characterName;
	protected $characterRace;
	protected $characterClass;
	protected $characterLevel;
	protected $characterXP;
	protected $maxHP;
	protected $currentHP;
	protected $armorClass;
	protected $strength;
	protected $strengthModifier;
	protected $dexterity;
	protected $dexterityModifier;
	protected $constitution;
	protected $constitutionModifier;
	protected $intelligence;
	protected $intelligenceModifier;
	protected $wisdom;
	protected $wisdomModifier;
	protected $charisma;
	protected $charismaModifier;
	protected $initiative;
	protected $damageResistances;
	protected $damageImmunities;
	protected $conditionImmunities;
	protected $magicSpells;
	protected $specialSkills;
	protected $imageLocation;
	protected $statusFlag;
	protected $lastChange;
	protected $creationDate;
	
	
	//------------------------------------------------------------------------
	// heal character
	//------------------------------------------------------------------------
	public function heal($value) {
		$this->currentHP = $this->currentHP + $value;
		
		if ($this->currentHP > $this->maxHP) {
			$this->currentHP = $this->maxHP;
		}
		
		$this->updateHP();
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// take damage
	//------------------------------------------------------------------------
	public function takeDamage($damage) {
		$this->currentHP = $this->currentHP - $damage;
		
		if ($this->currentHP < 0) {
			$this->currentHP = 0;
		}
		
		$this->updateHP();
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// calculate modifier
	//------------------------------------------------------------------------
	protected function calculateModifier($value) {
		if ($value == 1) {
			return -5;
		} else if ($value == 2 || $value == 3) {
			return -4;
		} else if ($value == 4 || $value == 5) {
			return -3;
		} else if ($value == 6 || $value == 7) {
			return -2;
		} else if ($value == 8 || $value == 9) {
			return -1; 
		} else if ($value == 10 || $value == 11) {
			return 0;
		} else if ($value == 12 || $value == 13) {
			return 1;
		} else if ($value == 14 || $value == 15) {
			return 2;
		} else if ($value == 16 || $value == 17) {
			return 3;
		} else if ($value == 18 || $value == 19) {
			return 4;
		} else if ($value == 20 || $value == 21) {
			return 5;
		} else if ($value == 22 || $value == 23) {
			return 6;
		} else if ($value == 24 || $value == 25) {
			return 7;
		} else if ($value == 26 || $value == 27) {
			return 8;
		} else if ($value == 28 || $value == 29) {
			return 9;
		} else if ($value >= 30) {
			return 10;
		}
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get character name
	//------------------------------------------------------------------------
	public function getCharacterName() {
		return $this->characterName;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get max hp
	//------------------------------------------------------------------------
	public function getMaxHP() {
		return $this->maxHP;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get armor class
	//------------------------------------------------------------------------
	public function getArmorClass() {
		return $this->armorClass;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get strength
	//------------------------------------------------------------------------
	public function getStrength() {
		return $this->strength;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get dexterity
	//------------------------------------------------------------------------
	public function getDexterity() {
		return $this->dexterity;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get constitution
	//------------------------------------------------------------------------
	public function getConstitution() {
		return $this->constitution;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get intelligence
	//------------------------------------------------------------------------
	public function getIntelligence() {
		return $this->intelligence;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get wisdom
	//------------------------------------------------------------------------
	public function getWisdom() {
		return $this->wisdom;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get charisma
	//------------------------------------------------------------------------
	public function getCharisma() {
		return $this->charisma;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get strength modifier
	//------------------------------------------------------------------------
	public function getStrengthModifier() {
		return $this->strengthModifier;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get dexterity modifier
	//------------------------------------------------------------------------
	public function getDexterityModifier() {
		return $this->dexterityModifier;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get constitution modifier
	//------------------------------------------------------------------------
	public function getConstitutionModifier() {
		return $this->constitutionModifier;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get intelligence modifier
	//------------------------------------------------------------------------
	public function getIntelligenceModifier() {
		return $this->intelligenceModifier;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get wisdom modifer
	//------------------------------------------------------------------------
	public function getWisdomModifier() {
		return $this->wisdomModifier;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get charisma modifier
	//------------------------------------------------------------------------
	public function getCharismaModifier() {
		return $this->charismaModifier;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get damage resistances
	//------------------------------------------------------------------------
	public function getDamageResistances() {
		return $this->damageResistances;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get damage immunities
	//------------------------------------------------------------------------
	public function getDamageImmunities() {
		return $this->damageImmunities;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get condition immunities
	//------------------------------------------------------------------------
	public function getConditionImmunities() {
		return $this->conditionImmunities;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get magic spells
	//------------------------------------------------------------------------
	public function getMagicSpells() {
		return $this->magicSpells;
	}
	//------------------------------------------------------------------------
	
	
	//------------------------------------------------------------------------
	// get magic spells
	//------------------------------------------------------------------------
	public function getSpecialSkills() {
		return $this->specialSkills;
	}
	//------------------------------------------------------------------------
}	
//-------------------------------------------------------------------------------------------