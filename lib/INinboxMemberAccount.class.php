<?php
## Created by MS as on Saturday, March 08, 2014
## Updated by MS as on Friday, April 11, 2014
class INinboxMemberAccount extends INinboxAPI
{
	/**
	 * @var array
	 */
	public $Profile;

	/**
	 * @var array
	 */
	public $Signature;
	
	/**
	 * @var array
	 */
	public $ConfirmationSetting;

	/**
	 * @var array
	 */
	public $Branding;

	/**
	 * @var array
	 */
	public $Webhook;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setProfile($obj->{'Profile'});
		$this->setSignature($obj->{'Signature'});
		$this->setConfirmationSetting($obj->{'ConfirmationSetting'});
		$this->setBranding($obj->{'Branding'});
		$this->setWebhook($obj->{'Webhook'});
		return true;
	}
	
	/**
	 * @param array $FieldLabel FieldLabel
	 */
	public function setProfile($Profile)
	{
	  $this->Profile = $Profile;
	}
	
	/**
	 * @return array
	 */
	public function getProfile()
	{
	  return $this->Profile;
	}
	
	/**
	 * @param array $Signature Signature
	 */
	public function setSignature($Signature)
	{
	  $this->Signature = $Signature;
	}
	
	/**
	 * @return array
	 */
	public function getSignature()
	{
	  return $this->Signature;
	}
		
	/**
	 * @param array $ConfirmationSetting ConfirmationSetting
	 */
	public function setConfirmationSetting($ConfirmationSetting)
	{
	  $this->ConfirmationSetting = $ConfirmationSetting;
	}
	
	/**
	 * @return array
	 */
	public function getConfirmationSetting()
	{
	  return $this->ConfirmationSetting;
	}
	
	/**
	 * @param array $Branding Branding
	 */
	public function setBranding($Branding)
	{
	  $this->Branding = $Branding;
	}
	
	/**
	 * @return array
	 */
	public function getBranding()
	{
	  return $this->Branding;
	}
	
	/**
	 * @param array $Webhook Webhook
	 */
	public function setWebhook($Webhook)
	{
	  $this->Webhook = $Webhook;
	}
	
	/**
	 * @return array
	 */
	public function getWebhook()
	{
	  return $this->Webhook;
	}

	/**
	 * @return object
	 * @throws Exception
	 */	
	public function get()
	{
		$array = $this->getURL("/member/details." . $this->getFormat());
		$this->checkForErrors("Member");
		//echo $array;exit;
		if($this->getFormat() == "xml")
			$object = simplexml_load_string($array);
		else
			$object = json_decode($array);
		$resData = new INinboxMemberAccount();
		$resData->loadFromObject($object);
		return $resData;
	}
}

class INinboxMemberBillingDetails extends INinboxAPI
{
	/**
	 * @var string
	 */
	public $PlanName;

	/**
	 * @var string
	 */
	public $PlanExpiryDate;

	/**
	 * @var string
	 */
	public $Credits;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		//echo "<pre>";print_r($obj);
		$this->setPlanName($obj->{'PlanName'});
		$this->setPlanExpiryDate($obj->{'PlanExpiryDate'});
		$this->setCredits($obj->{'Credits'});
		return true;
	}
	
	/**
	 * @param string $PlanName PlanName
	 */
	public function setPlanName($PlanName)
	{
	  $this->PlanName = (string)$PlanName;
	}

	/**
	 * @return string
	 */
	public function getPlanName()
	{
	  return $this->PlanName;
	}

	/**
	 * @param string $PlanExpiryDate PlanExpiryDate
	 */
	public function setPlanExpiryDate($PlanExpiryDate)
	{
	  $this->PlanExpiryDate = (string)$PlanExpiryDate;
	}

	/**
	 * @return string
	 */
	public function getPlanExpiryDate()
	{
	  return $this->PlanExpiryDate;
	}

	/**
	 * @param string $Credits Credits
	 */	
	public function setCredits($Credits)
	{
	  $this->Credits = (string)$Credits;
	}

	/**
	 * @return string
	 */
	public function getCredits()
	{
	  return $this->Credits;
	}

	/**
	 * @return object
	 * @throws Exception
	 */
	public function get()
	{
		$array = $this->getURL("/member/billing-details." . $this->getFormat());
		$this->checkForErrors("Member");
		//echo $array;exit;
		if($this->getFormat() == "xml")
			$object = simplexml_load_string($array);
		else
			$object = json_decode($array);
		$resData = new INinboxMemberBillingDetails();
		$resData->loadFromObject($object);
		return $resData;
	}
}

class INinboxMemberProfileUpdate extends INinboxAPI
{
	/**
	 * @var string
	 */
	public $FirstName;

	/**
	 * @var string
	 */
	public $LastName;

	/**
	 * @var string
	 */
	public $ProfileImageURL;

	/**
	 * @var string
	 */
	public $Gender;

	/**
	 * @var string
	 */
	public $AccountType;

	/**
	 * @var string
	 */
	public $Address;

	/**
	 * @var string
	 */
	public $City;

	/**
	 * @var string
	 */
	public $StateCode;

	/**
	 * @var string
	 */
	public $CountryCode;

	/**
	 * @var string
	 */
	public $HomePhone;

	/**
	 * @var boolean
	 */
	public $ReceiveNewsletter;

	/**
	 * @var boolean
	 */
	public $EuropeCompany;	// Used if account type is company

	/**
	 * @var string
	 */
	public $VATNo;	// Used if account type is company
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		//echo "<pre>";print_r($obj);
		$this->setFirstName($obj->{'FirstName'});
		$this->setLastName($obj->{'LastName'});
		$this->setProfileImageURL($obj->{'ProfileImageURL'});
		$this->setGender($obj->{'Gender'});
		$this->setAccountType($obj->{'AccountType'});
		$this->setAddress($obj->{'Address'});
		$this->setCity($obj->{'City'});
		$this->setStateCode($obj->{'StateCode'});
		$this->setCountryCode($obj->{'CountryCode'});
		$this->setHomePhone($obj->{'HomePhone'});
		if($this->getFormat() == "xml") {
			$this->setReceiveNewsletter($obj->{'ReceiveNewsletter'}=="True"?true:false);
			$this->setEuropeCompany($obj->{'EuropeCompany'}=="True"?true:false);	// Used if account type is company
		}
		else {
			$this->setReceiveNewsletter($obj->{'ReceiveNewsletter'});
			$this->setEuropeCompany($obj->{'EuropeCompany'});	// Used if account type is company
		}
		$this->setVATNo($obj->{'VATNo'});	// Used if account type is company
		return true;
	}
	
	/**
	 * @param string $VATNo VATNo
	 */
	public function setVATNo($VATNo)
	{
	  $this->VATNo = (string)$VATNo;
	}
	
	/**
	 * @return string
	 */
	public function getVATNo()
	{
	  return $this->VATNo;
	}
	
	/**
	 * @param boolean $EuropeCompany EuropeCompany
	 */
	public function setEuropeCompany($EuropeCompany)
	{
	  $this->EuropeCompany = (bool)$EuropeCompany;
	}
	
	/**
	 * @return boolean
	 */
	public function getEuropeCompany()
	{
	  return $this->EuropeCompany;
	}
	
	/**
	 * @param boolean $ReceiveNewsletter ReceiveNewsletter
	 */
	public function setReceiveNewsletter($ReceiveNewsletter)
	{
	  $this->ReceiveNewsletter = (bool)$ReceiveNewsletter;
	}
	
	/**
	 * @return boolean
	 */
	public function getReceiveNewsletter()
	{
	  return $this->ReceiveNewsletter;
	}
	
	/**
	 * @param string $HomePhone HomePhone
	 */
	public function setHomePhone($HomePhone)
	{
	  $this->HomePhone = (string)$HomePhone;
	}
	
	/**
	 * @return string
	 */
	public function getHomePhone()
	{
	  return $this->HomePhone;
	}
		
	/**
	 * @param string $CountryCode CountryCode
	 */
	public function setCountryCode($CountryCode)
	{
	  $this->CountryCode = (string)$CountryCode;
	}
	
	/**
	 * @return string
	 */
	public function getCountryCode()
	{
	  return $this->CountryCode;
	}
	
	/**
	 * @param string $StateCode StateCode
	 */
	public function setStateCode($StateCode)
	{
	  $this->StateCode = (string)$StateCode;
	}
	
	/**
	 * @return string
	 */
	public function getStateCode()
	{
	  return $this->StateCode;
	}
	
	/**
	 * @param string $City City
	 */
	public function setCity($City)
	{
	  $this->City = (string)$City;
	}
	
	/**
	 * @return string
	 */
	public function getCity()
	{
	  return $this->City;
	}
	
	/**
	 * @param string $Address Address
	 */
	public function setAddress($Address)
	{
	  $this->Address = (string)$Address;
	}
	
	/**
	 * @return string
	 */
	public function getAddress()
	{
	  return $this->Address;
	}
	
	/**
	 * @param string $AccountType AccountType
	 */
	public function setAccountType($AccountType)
	{
	  $this->AccountType = (string)$AccountType;
	}
	
	/**
	 * @return string
	 */
	public function getAccountType()
	{
	  return $this->AccountType;
	}
	
	/**
	 * @param string $Gender Gender
	 */
	public function setGender($Gender)
	{
	  $this->Gender = (string)$Gender;
	}
	
	/**
	 * @return string
	 */
	public function getGender()
	{
	  return $this->Gender;
	}
	
	/**
	 * @param string $ProfileImageURL ProfileImageURL
	 */
	public function setProfileImageURL($ProfileImageURL)
	{
	  $this->ProfileImageURL = (string)$ProfileImageURL;
	}
	
	/**
	 * @return string
	 */
	public function getProfileImageURL()
	{
	  return $this->ProfileImageURL;
	}
		
	/**
	 * @param string $LastName LastName
	 */
	public function setLastName($LastName)
	{
	  $this->LastName = (string)$LastName;
	}
	
	/**
	 * @return string
	 */
	public function getLastName()
	{
	  return $this->LastName;
	}
	
	/**
	 * @param string $FirstName FirstName
	 */
	public function setFirstName($FirstName)
	{
	  $this->FirstName = (string)$FirstName;
	}
	
	/**
	 * @return string
	 */
	public function getFirstName()
	{
	  return $this->FirstName;
	}
	
	/**
	 * @return boolean
	 * @throws Exception
	 */
	public function save()
	{
		if($this->getFormat() == "xml")
			$person_xml = $this->toXML();
		else
			$person_xml = $this->toJSON();
		//echo $person_xml;exit;
		$new_xml = $this->postDataWithVerb("/member/profile-update." . $this->getFormat(), $person_xml, "PUT");
		$this->checkForErrors("Member", 200);
		return true;
	}
	
	/**
	 * @return xml data
	 */
	public function toXML()
	{
		$xml[] = "<?xml version='1.0' encoding='utf-8'?><Member>";
		
		$fields = array("FirstName", "LastName", "ProfileImageURL", "Gender", "Address", "City", "StateCode", "CountryCode", "HomePhone", "ReceiveNewsletter", "AccountType", "EuropeCompany", "VATNo");
		
		foreach($fields as $field)
		{
			$xml_field_name = str_replace("_", "-", $field);
			$xml[] = "<" . $xml_field_name . ">" . $this->$field . "</" . $xml_field_name . ">";
		}

		$xml[] = "</Member>";
		return implode("\n", $xml);
	}
	
	/**
	 * @return json data
	 */
	public function toJSON() { // $with_id = true
		$fields = array("FirstName", "LastName", "ProfileImageURL", "Gender", "Address", "City", "StateCode", "CountryCode", "HomePhone", "ReceiveNewsletter", "AccountType", "EuropeCompany", "VATNo");
		foreach($fields as $field) {
			$json[$field] = $this->$field;
		}
		return json_encode($json);
	}
}
?>