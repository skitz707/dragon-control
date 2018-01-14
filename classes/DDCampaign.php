<?php
//-------------------------------------------------------------------------------------------
// DDCampaign.php
// Written by: Michael C. Szczepanik
// January 14th, 2018
// DDCampaign() class definition.
//
// Change Log:
//-------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------
// includes
//-------------------------------------------------------------------------------------------
include_once("classes/DDObject.php");
//-------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------
// campaign class definition
//-------------------------------------------------------------------------------------------
class DDCampaign extends DDObject {
	// class properties
	protected $campaignId;
	protected $campaignName;
	protected $campaignLeader;
	protected $statusFlag;
	protected $lastPlayed;
	protected $lastChange;
	protected $creationDate;
	
	
	//------------------------------------------------------------------------
	// load campaign by id
	//------------------------------------------------------------------------
	public function loadCampaignById($campaignId) {
		$campaignHeader = $this->database->getDatabaseRecord("dragons.campaignHeader", array("campaignId"=>$campaignId));
		
		$this->campaignId = $campaignId;
		$this->campaignName = $campaignHeader['campaignName'];
		$this->campaignLeader = $campaignHeader['campaignLeader'];
		$this->statusFlag = $campaignHeader['statusFlag'];
		$this->lastPlayer = $campaignHeader['lastPlayed'];
		$this->lastChange = $campaignHeader['lastChange'];
		$this->creationDate = $campaignHeader['creationDate'];
	}
	//------------------------------------------------------------------------
}
//-------------------------------------------------------------------------------------------