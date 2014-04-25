<?php
class INinboxGeneral extends INinboxAPI
{
	/**
	 * @return string
	 * @throws Exception
	 */
	public function getSystemDate() {
		$result = $this->getURL("/systemdate.".$this->getFormat());
		$this->checkForErrors("CurrentDate");
		if($this->getFormat() == "xml") {
			$array = (array)simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$array = (array)json_decode($result);
		}
		return $array['SystemDate'];
	}
	
	/**
	 * @return array
	 * @throws Exception
	 */
	public function getTimeZones() {
		$return = array();
		$final_url = "/general/timezones/list." . $this->getFormat();
		$result = $this->getUrl($final_url);
		$this->checkForErrors("TimeZones");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_timezones = $object->TimeZone;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_timezones = $object->TimeZones;
		}
		
		foreach($all_timezones as $timezones)
		{
			$timezones_obj = new INinboxValidTimeZones($this);
			$timezones_obj->setFormat($this->getFormat());
			$timezones_obj->loadFromObject($timezones); # Response data
			$return[] = $timezones_obj;
		}
		return $return;
	}
	

	/**
	 * @param array $param Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getValidCountries($param = array())
	{
		$return = array();
		$final_url = "/general/countries/list." . $this->getFormat();
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url.= $sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);
		$this->checkForErrors("Country");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_countries = $object->Country;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_countries = $object->Countries;
		}
		
		foreach($all_countries as $countries)
		{
			$countries_obj = new INinboxValidCountries($this);
			$countries_obj->setFormat($this->getFormat());
			$countries_obj->loadFromObject($countries); # Response data
			$return[] = $countries_obj;
		}
		return $return;
	}

	/**
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getValidStates($param=array())
	{
		$return = array();

		$final_url = "/general/states/list.".$this->getFormat() ;
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url.= $sep.http_build_query($param);
		}

		$result = $this->getUrl($final_url);
		$this->checkForErrors("State");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_states = $object->Results->State;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_states = $object->Results;
		}

		$this->setAllPagingParameter($object->TotalNumberOfRecords, $object->RecordsOnThisPage, $object->PageNumber, $object->PageSize);

		foreach($all_states as $state)
		{
			$state_obj = new INinboxValidStates();
			$state_obj->setFormat($this->getFormat());
			$state_obj->loadFromObject($state); # Response data
			$return[] = $state_obj;
		}
		return $return;
	}
}

class INinboxValidTimeZones extends INinboxGeneral
{
	/**
	 * @var string
	 */
	private $Code;

	/**
	 * @var string
	 */
	private $Name;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setCode($obj->{'Code'});
		$this->setName($obj->{'Name'});
	}
	
	/**
	 * @param string $Name Name
	 */
	public function setName($Name)
	{
	  $this->Name = (string)$Name;
	}
	
	/**
	 * @return string
	 */
	public function getName()
	{
	  return $this->Name;
	}
	
	/**
	 * @param string $Code Code
	 */
	public function setCode($Code)
	{
	  $this->Code = (string)$Code;
	}
	
	/**
	 * @return string
	 */
	public function getCode()
	{
	  return $this->Code;
	}
}

class INinboxValidCountries extends INinboxGeneral
{
	/**
	 * @var string
	 */
	private $CountryName;

	/**
	 * @var string
	 */
	private $CountryCode;

	/**
	 * @var string
	 */
	private $CountryCodeISO_3;

	/**
	 * @var string
	 */
	private $Status;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setCountryName($obj->{'CountryName'});
		$this->setCountryCode($obj->{'CountryCode'});
		$this->setCountryCodeISO_3($obj->{'CountryCodeISO_3'});
		$this->setStatus($obj->{'Status'});
	}
	
	/**
	 * @param string $Status Status
	 */
	public function setStatus($Status) {
	  $this->Status = (string)$Status;
	}
	
	/**
	 * @return string
	 */
	public function getStatus()
	{
	  return $this->Status;
	}

	/**
	 * @param string $CountryCodeISO_3 CountryCodeISO_3
	 */
	public function setCountryCodeISO_3($CountryCodeISO_3) {
	  $this->CountryCodeISO_3 = (string)$CountryCodeISO_3;
	}
	
	/**
	 * @return string
	 */
	public function getCountryCodeISO_3()
	{
	  return $this->CountryCodeISO_3;
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
	 * @param string $CountryName CountryName
	 */
	public function setCountryName($CountryName)
	{
	  $this->CountryName = (string)$CountryName;
	}
	
	/**
	 * @return string
	 */
	public function getCountryName()
	{
	  return $this->CountryName;
	}
}

class INinboxValidStates extends INinboxGeneral
{
	/**
	 * @var string
	 */
	private $CountryName;

	/**
	 * @var string
	 */
	private $CountryCode;

	/**
	 * @var string
	 */
	private $StateName;

	/**
	 * @var string
	 */
	private $StateCode;

	/**
	 * @var string
	 */
	private $Status;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setCountryName($obj->{'CountryName'});
		$this->setCountryCode($obj->{'CountryCode'});
		$this->setStateName($obj->{'StateName'});
		$this->setStateCode($obj->{'StateCode'});
		$this->setStatus($obj->{'Status'});
	}
	
	/**
	 * @param string $Status Status
	 */
	public function setStatus($Status) {
	  $this->Status = (string)$Status;
	}
	
	/**
	 * @return string
	 */
	public function getStatus()
	{
	  return $this->Status;
	}

	/**
	 * @param string $StateName StateName
	 */
	public function setStateName($StateName) {
	  $this->StateName = (string)$StateName;
	}
	
	/**
	 * @return string
	 */
	public function getStateName()
	{
	  return $this->StateName;
	}

	/**
	 * @param string $StateCode StateCode
	 */
	public function setStateCode($StateCode) {
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
	 * @param string $CountryName CountryName
	 */
	public function setCountryName($CountryName)
	{
	  $this->CountryName = (string)$CountryName;
	}
	
	/**
	 * @return string
	 */
	public function getCountryName()
	{
	  return $this->CountryName;
	}
}