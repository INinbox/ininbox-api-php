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
			$timezones_obj = new INinboxTimeZones($this);
		//	$timezones_obj->setFormat($this->getFormat());
			$timezones_obj->loadFromObject($timezones); # Response data
			$return[] = $timezones_obj;
		}
		return $return;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getAllPersonalizedFields() {
		$return = array();
		$final_url = "/emailfields/personalized." . $this->getFormat();
		$result = $this->getUrl($final_url);
		$this->checkForErrors("PersonalizedFields");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_personalizedfields = $object;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_personalizedfields = $object;
		}
		$personalizedfields_obj = new INinboxPersonalizedField();
		$personalizedfields_obj->setFormat($this->getFormat());
		$personalizedfields_obj->loadFromObject($object); # Response data
		return $personalizedfields_obj;
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
			$countries_obj = new INinboxCountry($this);
			$countries_obj->loadFromObject($countries); # Response data
			$return[] = $countries_obj;
		}
		return $return;
	}

	/**
	 * @param array $param Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getAllTemplates($param = array())
	{
		$return = array();
		$final_url = "/general/templates/list." . $this->getFormat();
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url.= $sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);
		//echo $result;exit;
		$this->checkForErrors("Template");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_template = $object->Category;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_template = $object->Categories;
		}
		foreach($all_template as $template)
		{
			$template_obj = new INinboxTemplate($this);
			$template_obj->setFormat($this->getFormat());
			$template_obj->loadFromObject($template); # Response data
			$return[] = $template_obj;
		}
		return $return;
	}

	/**
	 * @param int $id TemplateID
	 *
	 * @return array
	 * @throws Exception
	 */
	public function findTemplateById($id)
	{
		$return = array();
		$final_url = "/general/templates/$id/details." . $this->getFormat();
		$result = $this->getUrl($final_url);
		$this->checkForErrors("Template");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_template = $object->Template;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_template = $object->Templates;
		}
		foreach($all_template as $template)
		{
			$template_obj = new INinboxTemplateDetail($this);
			$template_obj->setFormat($this->getFormat());
			$template_obj->loadFromObject($template); # Response data
			$return[] = $template_obj;
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
			$state_obj = new INinboxState();
			$state_obj->loadFromObject($state); # Response data
			$return[] = $state_obj;
		}
		return $return;
	}
}

class INinboxTimeZone
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
		return true;
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

class INinboxCountry
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
		return true;
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

class INinboxState
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
		return true;
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

class INinboxTemplate extends INinboxAPI
{
	/**
	 * @var int
	 */
	private $CategoryID;

	/**
	 * @var string
	 */
	private $CategoryName;

	/**
	 * @var array
	 */
	private $Templates;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setCategoryID($obj->{'CategoryID'});
		$this->setCategoryName($obj->{'CategoryName'});
		$temp_arr = array();
		if($this->getFormat() == "xml") {
			$temp_obj = $obj->{'Templates'};
			if(is_array($temp_obj->Template) && array_key_exists("TemplateID", $temp_obj->Template)) {
				$temp_obj[] = $temp_obj;
			}
			if(is_object($temp_obj)) {
				$cnt = 0;
				foreach ($temp_obj->Template as $tk => $tv)
				{
					$temp_arr[$cnt]['TemplateID'] = (int)$tv->TemplateID;
					$temp_arr[$cnt]['TemplateName'] = (string)$tv->TemplateName;
					$style_obj = $tv->{'Styles'};
					$style_temp_arr = array();
					if(is_array($style_obj->Style) && array_key_exists("StyleID", $style_obj->Style)) {
						$style_obj[] = $style_obj;
					}
					if(is_object($style_obj)) {
						$style_cnt = 0;
						foreach($style_obj->Style as $sk => $sv) {
							$style_temp_arr[$style_cnt]['StyleID'] = (int)$sv->StyleID;
							$style_temp_arr[$style_cnt]['ScreenshotURL'] = (string)$sv->ScreenshotURL;
							$style_temp_arr[$style_cnt]['PreviewURL'] = (string)$sv->PreviewURL;
							$style_cnt++;
						}
					}
					$temp_arr[$cnt]['Styles'] = $style_temp_arr;
					$cnt++;
				}
			}
		}
		else {
			$temp_obj = $obj->{'Templates'};
			if(is_array($temp_obj)) {
				$cnt = 0;
				foreach ($temp_obj as $tk => $tv)
				{
					$temp_arr[$cnt]['TemplateID'] = (int)$tv->TemplateID;
					$temp_arr[$cnt]['TemplateName'] = (string)$tv->TemplateName;
					$style_obj = $tv->Styles;
					$style_temp_arr = array();
					$style_cnt = 0;
					if(is_array($style_obj)) {
						foreach($style_obj as $sk => $sv) {
							$style_temp_arr[$style_cnt]['StyleID'] = (int)$sv->StyleID;
							$style_temp_arr[$style_cnt]['ScreenshotURL'] = (string)$sv->ScreenshotURL;
							$style_temp_arr[$style_cnt]['PreviewURL'] = (string)$sv->PreviewURL;
							$style_cnt++;
						}
					}
					$temp_arr[$cnt]['Styles'] = $style_temp_arr;
					$cnt++;
				}
			}
		}
		$this->setTemplates($temp_arr);
		return true;
	}
	
	/**
	 * @param array $Templates Templates
	 */
	public function setTemplates($Templates) {
	  $this->Templates = $Templates;
	}
	
	/**
	 * @return array
	 */
	public function getTemplates()
	{
	  return $this->Templates;
	}

	/**
	 * @param string $CategoryName CategoryName
	 */
	public function setCategoryName($CategoryName)
	{
	  $this->CategoryName = (string)$CategoryName;
	}
	
	/**
	 * @return string
	 */
	public function getCategoryName()
	{
	  return $this->CategoryName;
	}
	
	/**
	 * @param int $CategoryID CategoryID
	 */
	public function setCategoryID($CategoryID)
	{
	  $this->CategoryID = (int)$CategoryID;
	}
	
	/**
	 * @return int
	 */
	public function getCategoryID()
	{
	  return $this->CategoryID;
	}
}

class INinboxTemplateDetail extends INinboxAPI
{
	/**
	 * @var string
	 */
	private $CategoryName;

	/**
	 * @var int
	 */
	private $TemplateID;

	/**
	 * @var string
	 */
	private $TemplateName;

	/**
	 * @var string
	 */
	private $ScreenshotURL;

	/**
	 * @var string
	 */
	private $PreviewURL;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setCategoryName($obj->{'CategoryName'});
		$this->setTemplateID($obj->{'TemplateID'});
		$this->setTemplateName($obj->{'TemplateName'});
		$this->setScreenshotURL($obj->{'ScreenshotURL'});
		$this->setPreviewURL($obj->{'PreviewURL'});
		return true;
	}
	
	/**
	 * @param string $PreviewURL PreviewURL
	 */
	public function setPreviewURL($PreviewURL) {
	  $this->PreviewURL = (string)$PreviewURL;
	}
	
	/**
	 * @return string
	 */
	public function getPreviewURL()
	{
	  return $this->PreviewURL;
	}

	/**
	 * @param string $ScreenshotURL ScreenshotURL
	 */
	public function setScreenshotURL($ScreenshotURL) {
	  $this->ScreenshotURL = (string)$ScreenshotURL;
	}
	
	/**
	 * @return string
	 */
	public function getScreenshotURL()
	{
	  return $this->ScreenshotURL;
	}

	/**
	 * @param string $TemplateName TemplateName
	 */
	public function setTemplateName($TemplateName) {
	  $this->TemplateName = (string)$TemplateName;
	}
	
	/**
	 * @return string
	 */
	public function getTemplateName()
	{
	  return $this->TemplateName;
	}

	/**
	 * @param int $TemplateID TemplateID
	 */
	public function setTemplateID($TemplateID)
	{
	  $this->TemplateID = (int)$TemplateID;
	}
	
	/**
	 * @return int
	 */
	public function getTemplateID()
	{
	  return $this->TemplateID;
	}
	
	/**
	 * @param string $CategoryName CategoryName
	 */
	public function setCategoryName($CategoryName)
	{
	  $this->CategoryName = (string)$CategoryName;
	}
	
	/**
	 * @return string
	 */
	public function getCategoryName()
	{
	  return $this->CategoryName;
	}
}

class INinboxPersonalizedField extends INinboxAPI
{
	/**
	 * @var array
	 */
	private $SubscriberFields;

	/**
	 * @var array
	 */
	private $DateFields;

	/**
	 * @var array
	 */
	private $SocialFields;

	/**
	 * @var array
	 */
	private $GeneralContentFields;

	/**
	 * @var array
	 */
	private $AccountInformationFields;

	/**
	 * @var array
	 */
	private $GeoLocationFields;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		if($this->getFormat() == "xml") {
			$xx = (array)$obj->{'SubscriberFields'};
			$this->setSubscriberFields($xx['SubscriberField']);

			$xx1 = (array)$obj->{'DateFields'};
			$this->setDateFields($xx1['DateField']);

			$xx2 = (array)$obj->{'SocialFields'};
			$this->setSocialFields($xx2['SocialField']);

			$xx3 = (array)$obj->{'GeneralContentFields'};
			$this->setGeneralContentFields($xx3['GeneralContentField']);

			$xx4 = (array)$obj->{'AccountInformationFields'};
			$this->setAccountInformationFields($xx4['AccountInformationField']);

			$xx5 = (array)$obj->{'GeoLocationFields'};
			$this->setGeoLocationFields($xx5['GeoLocationField']);
		}
		else {
			$xx = (array)$obj->{'PersonalizedFields'};
			$this->setSubscriberFields($xx['SubscriberFields']);

			$xx1 = (array)$obj->{'PersonalizedFields'};
			$this->setDateFields($xx1['DateFields']);

			$xx2 = (array)$obj->{'PersonalizedFields'};
			$this->setSocialFields($xx2['SocialFields']);

			$xx3 = (array)$obj->{'PersonalizedFields'};
			$this->setGeneralContentFields($xx3['GeneralContentFields']);

			$xx4 = (array)$obj->{'PersonalizedFields'};
			$this->setAccountInformationFields($xx4['AccountInformationFields']);

			$xx5 = (array)$obj->{'PersonalizedFields'};
			$this->setGeoLocationFields($xx5['GeoLocationFields']);
		}
		return true;
	}
	
	/**
	 * @param array $GeoLocationFields GeoLocationFields
	 */
	public function setGeoLocationFields($GeoLocationFields) {
	  $this->GeoLocationFields = $GeoLocationFields;
	}
	
	/**
	 * @return array
	 */
	public function getGeoLocationFields()
	{
	  return $this->GeoLocationFields;
	}

	/**
	 * @param array $AccountInformationFields AccountInformationFields
	 */
	public function setAccountInformationFields($AccountInformationFields) {
	  $this->AccountInformationFields = $AccountInformationFields;
	}
	
	/**
	 * @return array
	 */
	public function getAccountInformationFields()
	{
	  return $this->AccountInformationFields;
	}

	/**
	 * @param array $GeneralContentFields GeneralContentFields
	 */
	public function setGeneralContentFields($GeneralContentFields) {
	  $this->GeneralContentFields = $GeneralContentFields;
	}
	
	/**
	 * @return array
	 */
	public function getGeneralContentFields()
	{
	  return $this->GeneralContentFields;
	}

	/**
	 * @param array $SocialFields SocialFields
	 */
	public function setSocialFields($SocialFields) {
	  $this->SocialFields = $SocialFields;
	}
	
	/**
	 * @return array
	 */
	public function getSocialFields()
	{
	  return $this->SocialFields;
	}

	/**
	 * @param array $DateFields DateFields
	 */
	public function setDateFields($DateFields)
	{
	  $this->DateFields = $DateFields;
	}
	
	/**
	 * @return array
	 */
	public function getDateFields()
	{
	  return $this->DateFields;
	}
	
	/**
	 * @param array $SubscriberFields SubscriberFields
	 */
	public function setSubscriberFields($SubscriberFields)
	{
	  $this->SubscriberFields = $SubscriberFields;
	}
	
	/**
	 * @return array
	 */
	public function getSubscriberFields()
	{
	  return $this->SubscriberFields;
	}
}