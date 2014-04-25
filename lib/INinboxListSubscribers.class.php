<?php

class INinboxListSubscribers extends INinboxAPI
{
	public $ContactID;
	public $Name;
	public $Email;
	public $Date;
	public $Status;
	public $CountryCode;
	public $RegisterThrough;
	
	public function loadFromObject($obj)
	{
		//echo "<pre>";print_r($obj->List);
		$this->setContactID($obj->{'ContactID'});
		$this->setName($obj->{'Name'});
		$this->setEmail($obj->{'Email'});
		$this->setCreatedAt($obj->{'Date'});
		$this->setCountryCode($obj->{'CountryCode'});
		$this->setRegisterThrough($obj->{'RegisterThrough'});
		$this->setStatus($obj->{'Status'});

		$xx = (array)$obj->{'CustomFields'};
		if($this->getFormat() == "xml") {
			if(count($xx['CustomField'])==1)
				$CFields[] = (array)$xx['CustomField'];
			else
				$CFields = (array)$xx['CustomField'];
		}
		else {
			if(count($xx)==1)
				$CFields[] = (array)$xx;
			else
				$CFields = (array)$xx;
		}
		$this->setCustomFields($CFields);
		return true;
	}
	public function setStatus($Status)
	{
	  $this->Status = (string)$Status;
	}

	public function getStatus()
	{
	  return $this->Status;
	}

	public function setCustomFields($CustomFields)
	{
	  $this->CustomFields = $CustomFields;
	}

	public function getCustomFields()
	{
	  return $this->CustomFields;
	}

	public function setRegisterThrough($RegisterThrough)
	{
	  $this->RegisterThrough = (string)$RegisterThrough;
	}

	public function getRegisterThrough()
	{
	  return $this->RegisterThrough;
	}

	public function setCountryCode($CountryCode)
	{
	  $this->CountryCode = (string)$CountryCode;
	}

	public function getCountryCode()
	{
	  return $this->CountryCode;
	}

	public function setCreatedAt($Date)
	{
	  $this->Date = (string)$Date;
	}

	public function getCreatedAt()
	{
	  return $this->Date;
	}
	
	public function setName($Name)
	{
	  $this->Name = (string)$Name;
	}

	public function getName()
	{
	  return $this->Name;
	}
	
	public function setEmail($Email)
	{
	  $this->Email = (string)$Email;
	}

	public function getEmail()
	{
	  return $this->Email;
	}
	
	public function setContactID($ContactID)
	{
	  $this->ContactID = (string)$ContactID;
	}

	public function getContactID()
	{
	  return $this->ContactID;
	}
	
}
?>