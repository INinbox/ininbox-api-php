<?php

class INinboxTeam extends INinboxAPI
{
	public $TeamID;
	public $Title;
	public $TotalMembers;
	public $CreatedDate;
	public $AccessModules;
	
	public function loadFromXMLObject($xml_obj)
	{
		//echo print_r($xml_obj->AccessModules->AccessModule[1]);exit;
		//echo "<pre>";print_r($xml_obj);exit;
		$this->setTeamID($xml_obj->{'TeamID'});
		$this->setTitle($xml_obj->{'Title'});
		$this->setTotalMembers($xml_obj->{'TotalMembers'});
		$this->setCreatedAt($xml_obj->{'CreatedDate'});
		$this->loadAccessModuleDataFromXMLObject($xml_obj->{'AccessModules'});
		return true;
	}
	
	public function loadAccessModuleDataFromXMLObject($xml_obj)
	{
		//echo "<pre>";print_r($xml_obj);exit;
		//$this->AccessModule = array();
		
		if (isset($xml_obj->{'AccessModule'}))
		{
			foreach($xml_obj->{'AccessModule'} as $value)
			{	
				//echo "<pre>";print_r($value);
				$access_m = new INinboxAccessModule($value->{'AccessModulesTitle'}, $value->{'Assigned'});
				$this->AccessModules[] = $access_m;
			}				
		}
	}

	public function setCreatedAt($CreatedDate)
	{
	  $this->CreatedDate = (string)$CreatedDate;
	}

	public function getCreatedAt()
	{
	  return $this->CreatedDate;
	}
	
	public function setAccessModules($AccessModules)
	{
	  $this->AccessModules = (string)$AccessModules;
	}

	public function getAccessModules()
	{
	  return $this->AccessModules;
	}

	public function setTitle($Title)
	{
	  $this->Title = (string)$Title;
	}

	public function getTitle()
	{
	  return $this->Title;
	}

	public function setTotalMembers($TotalMembers)
	{
	  $this->TotalMembers = (int)$TotalMembers;
	}

	public function getTotalMembers()
	{
	  return $this->TotalMembers;
	}
	
	public function setTeamID($TeamID)
	{
	  $this->TeamID = (string)$TeamID;
	}

	public function getTeamID()
	{
	  return $this->TeamID;
	}
}

?>