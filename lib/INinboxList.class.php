<?php
class INinboxList extends INinboxAPI
{
	/**
	 * @var int
	 */
	public $ListID;

	/**
	 * @var string
	 */
	public $Title;

	/**
	 * @var boolean
	 */
	public $ConfirmedOptIn;

	/**
	 * @var string
	 */
	public $CreatedDate;

	/**
	 * @var string
	 */
	public $Status;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		if(isset($obj->{'ListID'}))
			$this->setListID((int)$obj->{'ListID'});
		$this->setTitle($obj->{'Title'});
		if($this->getFormat() == "xml") 
			$this->setConfirmedOptIn($obj->{'ConfirmedOptIn'}=="True"?true:false);
		else
			$this->setConfirmedOptIn($obj->{'ConfirmedOptIn'});
		$this->setCreatedAt($obj->{'CreatedDate'});
		$this->setStatus($obj->{'Status'});
		return true;
	}
	
	/**
	 * @param string $Status Status
	 */
	public function setStatus($Status)
	{
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
	 * @param string $CreatedDate CreatedDate
	 */
	public function setCreatedAt($CreatedDate)
	{
	  $this->CreatedDate = (string)$CreatedDate;
	}
	
	/**
	 * @return string
	 */
	public function getCreatedAt()
	{
	  return $this->CreatedDate;
	}
	
	/**
	 * @param string $Title Title
	 */
	public function setTitle($Title)
	{
	  $this->Title = (string)$Title;
	}
	
	/**
	 * @return string
	 */
	public function getTitle()
	{
	  return $this->Title;
	}
	
	/**
	 * @param boolean $ConfirmedOptIn ConfirmedOptIn
	 */
	public function setConfirmedOptIn($ConfirmedOptIn)
	{
	  $this->ConfirmedOptIn = (bool)$ConfirmedOptIn;
	}
	
	/**
	 * @return boolean
	 */
	public function getConfirmedOptIn()
	{
	  return $this->ConfirmedOptIn;
	}
	
	/**
	 * @param int $ListID ListID
	 */
	public function setListID($ListID)
	{
	  $this->ListID = (int)$ListID;
	}
	
	/**
	 * @return int
	 */
	public function getListID()
	{
	  return $this->ListID;
	}
	
	/**
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function get($param=array())
	{
		return $this->parseAll("/lists/list.".$this->getFormat(), $param);
	}
	
	/**
	 * @param string $url
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function parseAll($url, $param=array())
	{
		
		$return = array();
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url = $url.$sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);

		$this->checkForErrors("List");
		
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_lists = $object->Results->List;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_lists = $object->Results;
		}
		$this->setAllPagingParameter($object->TotalNumberOfRecords, $object->RecordsOnThisPage, $object->PageNumber, $object->PageSize);

		foreach($all_lists as $list)
		{
			$list_obj = new INinboxList($this);
			$list_obj->setFormat($this->getFormat());
			$list_obj->loadFromObject($list);
			$return[] = $list_obj;
		}
		return $return;
	}


	/**
	 * @param int $id ListID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findActiveSubscribersById($id, $param = array())
	{
		return $this->parseAllSubscribers("/lists/$id/active-subscribers." . $this->getFormat(), $param);
	}
	
	/**
	 * @param int $id ListID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findUnconfirmedSubscribersById($id, $param = array())
	{
		return $this->parseAllSubscribers("/lists/$id/unconfirmed-subscribers." . $this->getFormat(), $param);
	}
	
	/**
	 * @param int $id ListID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findUnsubscribedSubscribersById($id, $param = array())
	{
		return $this->parseAllSubscribers("/lists/$id/unsubscribers-subscribers." . $this->getFormat(), $param);
	}
	
	/**
	 * @param int $id ListID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findBouncedSubscribersById($id, $param = array())
	{
		return $this->parseAllSubscribers("/lists/$id/bounced-subscribers." . $this->getFormat(), $param);
	}

	/**
	 * @param int $id ListID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findDeletedSubscribersById($id, $param = array())
	{
		return $this->parseAllSubscribers("/lists/$id/deleted-subscribers." . $this->getFormat(), $param);
	}

	/**
	 * @param string $url
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function parseAllSubscribers($url, $param=array())
	{
		$return = array();
		$final_url = $url;
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url = $url.$sep.http_build_query($param);
		}
		//echo $final_url;exit;
		$result = $this->getUrl($final_url);
		$this->checkForErrors("List Subscriber");
		
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_subscribers = $object->Results->Subscriber;

		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_subscribers = $object->Results;
		}
		$this->setAllPagingParameter($object->TotalNumberOfRecords, $object->RecordsOnThisPage, $object->PageNumber, $object->PageSize);

		foreach($all_subscribers as $subscriber)
		{
			$list_obj = new INinboxListSubscribers($this);
			$list_obj->setFormat($this->getFormat());
			$list_obj->loadFromObject($subscriber);
			$return[] = $list_obj;
		}
			
		return $return;
	}

	/**
	 * @param int $id ListID
	 *
	 * @return object
	 * @throws Exception
	 */
	public function findById($id)
	{
		$result = $this->getURL("/lists/$id/details.".$this->getFormat());
		$this->checkForErrors("List");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
		}
		$list = new INinboxListDetail();
		$list->setFormat($this->getFormat());
		$list->loadFromObject($object);
		return $list;
	}
	
	/**
	 * @return array
	 * @throws Exception
	 */
	public function delete()
	{
		$result = $this->postDataWithVerb("/lists/" . $this->getListID() . "/delete." . $this->getFormat(), "", "DELETE");
		$this->checkForErrors("List", 200);

		if($this->getFormat() == "xml") {
			$object = (array)simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$object = (array)json_decode($result);
		}
		return $object;
	}

	/**
 	 * @param int $id ListID
	 *
	 * @return object
	 * @throws Exception
	 */
	public function findStatsById($id)
	{
		$result = $this->getURL("/lists/$id/stats." . $this->getFormat());
		$this->checkForErrors("List");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
		}
		$list = new INinboxListStats($id);
		$list->loadFromObject($object);
		return $list;
	}
}

class INinboxListDetail extends INinboxList
{
	/**
	 * @var string
	 */
	public $CompanyName;

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
	public $State;

	/**
	 * @var string
	 */
	public $CountryCode;

	/**
	 * @var string
	 */
	public $Country;

	/**
	 * @var string
	 */
	public $Zip;

	/**
	 * @var string
	 */
	public $PostalAddress;

	/**
	 * @var string
	 */
	public $PostalDesign;

	/**
	 * @var string
	 */
	public $PostalDesignPreview;

	/**
	 * @var array
	 */
	public $CustomConfirmationPage;

	/**
	 * @var array
	 */
	public $CustomUnsubscribePage;

	/**
	 * @var array
	 */
	public $ConfirmationSetting;

	/**
	 * @var array
	 */
	public $Webhook;

	/**
	 * @var array
	 */
	public $EmailNotification;
	

	/**
	  Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setCompanyName($obj->{'CompanyName'});
		$this->setAddress($obj->{'Address'});
		$this->setCity($obj->{'City'});
		$this->setStateCode($obj->{'StateCode'});
		$this->setState($obj->{'State'});
		$this->setCountryCode($obj->{'CountryCode'});
		$this->setCountry($obj->{'Country'});
		$this->setZip($obj->{'Zip'});
		$this->setPostalDesign($obj->{'PostalDesign'});
		$this->setPostalDesignPreview($obj->{'PostalDesignPreview'});
		$this->setCustomConfirmationPage((array)$obj->{'CustomConfirmationPage'});
		$this->setCustomUnsubscribePage((array)$obj->{'CustomUnsubscribePage'});
		$this->setWebhook((array)$obj->{'Webhook'});
		$this->setConfirmationSetting((array)$obj->{'ConfirmationSetting'});

		$xx = (array)$obj->{'EmailNotification'};
		$email_arr = (array)$xx['SubscriptionEmailAddresses'];
		$CFields = array();
		if($this->getFormat() == "xml") {
			
			if(count($email_arr['EmailAddress'])==1)
				$CFields[] = (array)$email_arr['EmailAddress'];
			else {
				$CFields = (array)$email_arr['EmailAddress'];
			}
		}
		else {
			if(count($email_arr)==1)
				$CFields[] = (array)$email_arr;
			else
				$CFields = (array)$email_arr;
		}
		$this->setEmailNotification($CFields);
		return true;
	}

	/**
	 * @param string $CompanyName CompanyName
	 */
	public function setCompanyName($CompanyName)
	{
	  $this->CompanyName = (string)$CompanyName;
	}
	
	/**
	 * @return string
	 */
	public function getCompanyName()
	{
	  return $this->CompanyName;
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
	 * @param string $State StateName
	 */
	public function setState($State)
	{
	  $this->State = (string)$State;
	}
	
	/**
	 * @return string
	 */
	public function getState()
	{
	  return $this->State;
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
	 * @param string $Country Country
	 */
	public function setCountry($Country)
	{
	  $this->Country = (string)$Country;
	}
	
	/**
	 * @return string
	 */
	public function getCountry()
	{
	  return $this->Country;
	}
	
	/**
	 * @param string $Zip ZipCode
	 */
	public function setZip($Zip)
	{
	  $this->Zip = (string)$Zip;
	}
	
	/**
	 * @return string
	 */
	public function getZip()
	{
	  return $this->Zip;
	}
	
	/**
	 * @param string $PostalDesign PostalDesign
	 */
	public function setPostalDesign($PostalDesign)
	{
	  $this->PostalDesign = (string)$PostalDesign;
	}
	
	/**
	 * @param string $PostalAddress PostalAddress
	 */
	public function setPostalAddress($PostalAddress)
	{
	  $this->PostalAddress = (string)$PostalAddress;
	}
	
	/**
	 * @return string
	 */
	public function getPostalDesign()
	{
	  return $this->PostalDesign;
	}
	
	/**
	 * @param string $PostalDesignPreview PostalDesignPreview
	 */
	public function setPostalDesignPreview($PostalDesignPreview)
	{
	  $this->PostalDesignPreview = (string)$PostalDesignPreview;
	}
	
	/**
	 * @return string
	 */
	public function getPostalDesignPreview()
	{
	  return $this->PostalDesignPreview;
	}
	
	/**
	 * @param array $CustomConfirmationPage CustomConfirmationPage
	 */
	public function setCustomConfirmationPage($CustomConfirmationPage)
	{
	 $this->CustomConfirmationPage = $CustomConfirmationPage;
	}
	
	/**
	 * @return array
	 */
	public function getCustomConfirmationPage()
	{
	  return $this->CustomConfirmationPage;
	}

	/**
	 * @param array $CustomUnsubscribePage CustomUnsubscribePage
	 */
	public function setCustomUnsubscribePage($CustomUnsubscribePage)
	{
	  $this->CustomUnsubscribePage = $CustomUnsubscribePage;
	}

	/**
	 * @return array
	 */
	public function getCustomUnsubscribePage()
	{
	  return $this->CustomUnsubscribePage;
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
	 * @param array $EmailNotification EmailNotification
	 */
	public function setEmailNotification($EmailNotification)
	{
	  $this->EmailNotification = $EmailNotification;
	}
	
	/**
	 * @return array
	 */
	public function getEmailNotification()
	{
	  return $this->EmailNotification;
	}
	
	/**
	 * @return boolean
	 * @throws Exception
	 */
	public function save()
	{
		if($this->getFormat() == "xml"){
			
			# Creating seperate tag of CustomField
			if(count($this->getEmailNotification())>0){
				$cf_arr = array();
				foreach($this->getEmailNotification() as $ind=>$data){
					$cf_arr['SubscriptionEmailAddresses']['EmailAddress'][] = $data;
				}
				$this->setEmailNotification($cf_arr);
			}
			$person_xml = $this->toXML();
		}
		else{
			$person_xml = $this->toJSON();
		}
		if ($this->getListID() != null)
		{
			$new_xml = $this->postDataWithVerb("/lists/" . $this->getListID() . "/update.".$this->getFormat(), $person_xml, "PUT");
			$this->checkForErrors("List");
		}
		else
		{
			$new_xml = $this->postDataWithVerb("/lists/create.".$this->getFormat(), $person_xml, "POST");
			$this->checkForErrors("List", 201);
		}
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($new_xml);
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($new_xml);
		}
		return (array)$object;
	}
	
	/**
	 * @return xml data
	 */
	public function toXML()
	{
		$xml[] = "<?xml version='1.0' encoding='utf-8'?><List>";
		
		$fields = array("Title", "ConfirmedOptIn", "CompanyName", "Address", "City", "StateCode", "CountryCode", "Zip", "PostalAddress", "ConfirmationSetting", "CustomConfirmationPage", "CustomUnsubscribePage", "Webhook", "EmailNotification");
		
		if ($this->getListID() != null)
			$xml[] = '<ListID>' . $this->getListID() . '</ListID>';
		
		foreach($fields as $field)
		{
			if($field == "ConfirmationSetting") {
				if( isset($this->ConfirmationSetting) && is_array( $this->ConfirmationSetting) ) {
					foreach( $this->ConfirmationSetting as $c => $v ) {
						$xml_str1 .= '<'.$c.'>' . $v . '</'.$c.'>';
					}
					$xml[] = "<ConfirmationSetting>" . $xml_str1 . "</ConfirmationSetting>";
				}
			}
			else if($field == "CustomConfirmationPage") {
				if( isset($this->CustomConfirmationPage) && is_array( $this->CustomConfirmationPage) ) {
					foreach( $this->CustomConfirmationPage as $c1 => $v1 ) {
						$xml_str2 .= '<'.$c1.'>' . $v1 . '</'.$c1.'>';
					}
					$xml[] = "<CustomConfirmationPage>" . $xml_str2 . "</CustomConfirmationPage>";
				}
			}
			else if($field == "CustomUnsubscribePage") {
				if( isset($this->CustomUnsubscribePage) && is_array( $this->CustomUnsubscribePage) ) {
					foreach( $this->CustomUnsubscribePage as $c2 => $v2 ) {
						$xml_str3 .= '<'.$c2.'>' . $v2 . '</'.$c2.'>';
					}
					$xml[] = "<CustomUnsubscribePage>" . $xml_str3 . "</CustomUnsubscribePage>";
				}
			}
			else if($field == "Webhook") {
				if( isset($this->Webhook) && is_array( $this->Webhook) ) {
					foreach( $this->Webhook as $c3 => $v3 ) {
						$xml_str4 .= '<'.$c3.'>' . $v3. '</'.$c3.'>';
					}
					$xml[] = "<Webhook>" . $xml_str4 . "</Webhook>";
				}
			}
			else if($field == "EmailNotification") {
				//echo "<pre>";print_r($this->getEmailNotification());exit;
				if( isset($this->EmailNotification) && is_array( $this->EmailNotification) ) {
					foreach( $this->EmailNotification as $cf => $value ) {
						$xml_sub1=array();
						foreach( $value as $cf1 => $value1 ) {
							$xml_str='<SubscriptionEmailAddresses>';
							foreach( $value1 as $kk => $vv ) {
								$xml_str .= '<EmailAddress>' . $vv . '</EmailAddress>';
							}
							$xml_str.='</SubscriptionEmailAddresses>';
							$xml_sub1[] = $xml_str; 
						}
					}
					$xml_str = implode("\n", $xml_sub1);
					$xml[] = "<EmailNotification>" . $xml_str . "</EmailNotification>";
				}
			}
			else {
				$xml_field_name = str_replace("_", "-", $field);
				$xml[] = "\t<" . $xml_field_name . ">" . $this->$field . "</" . $xml_field_name . ">";
			}
		}

		$xml[] = "</List>";
		return implode("\n", $xml);
	}
	
	/**
	 * @return json data
	 */
	public function toJSON() {
		$fields = array("Title", "ConfirmedOptIn", "CompanyName", "Address", "City", "StateCode", "CountryCode", "Zip", "PostalAddress", "ConfirmationSetting", "CustomConfirmationPage", "CustomUnsubscribePage", "Webhook", "EmailNotification");
		foreach($fields as $field) {
			if($field == "EmailNotification") {
				$json[$field]['SubscriptionEmailAddresses'] = $this->$field;
			}
			else {
				$json[$field] = $this->$field;
			}
		}
		return json_encode($json);
	}
}

class INinboxListStats extends INinboxList
{
	/**
	 * @var int
	 */
	public $NewActiveSubscribersToday;

	/**
	 * @var int
	 */
	public $NewActiveSubscribersYesterday;

	/**
	 * @var int
	 */
	public $NewActiveSubscribersThisWeek;

	/**
	 * @var int
	 */
	public $TotalActiveSubscribers;

	/**
	 * @var int
	 */
	public $TotalUnsubscribes;

	/**
	 * @var int
	 */
	public $TotalUsedInBroadcasts;

	/**
	 * @var int
	 */
	public $TotalCreatedAutoresponders;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setNewActiveSubscribersToday($obj->{'NewActiveSubscribersToday'});
		$this->setNewActiveSubscribersYesterday($obj->{'NewActiveSubscribersYesterday'});
		$this->setNewActiveSubscribersThisWeek($obj->{'NewActiveSubscribersThisWeek'});
		$this->setTotalActiveSubscribers($obj->{'TotalActiveSubscribers'});
		$this->setTotalUnsubscribes($obj->{'TotalUnsubscribes'});
		$this->setTotalUsedInBroadcasts($obj->{'TotalUsedInBroadcasts'});
		$this->setTotalCreatedAutoresponders($obj->{'TotalCreatedAutoresponders'});
		return true;
	}
	
	/**
	 * @param int $NewActiveSubscribersToday NewActiveSubscribersToday
	 */
	public function setNewActiveSubscribersToday($NewActiveSubscribersToday)
	{
	  $this->NewActiveSubscribersToday = (int)$NewActiveSubscribersToday;
	}
	
	/**
	 * @return int
	 */
	public function getNewActiveSubscribersToday()
	{
	  return $this->NewActiveSubscribersToday;
	}
	
	/**
	 * @param int $NewActiveSubscribersYesterday NewActiveSubscribersYesterday
	 */
	public function setNewActiveSubscribersYesterday($NewActiveSubscribersYesterday)
	{
	  $this->NewActiveSubscribersYesterday = (int)$NewActiveSubscribersYesterday;
	}
	
	/**
	 * @return int
	 */
	public function getNewActiveSubscribersYesterday()
	{
	  return $this->NewActiveSubscribersYesterday;
	}
	
	/**
	 * @param int $NewActiveSubscribersThisWeek NewActiveSubscribersThisWeek
	 */
	public function setNewActiveSubscribersThisWeek($NewActiveSubscribersThisWeek)
	{
	  $this->NewActiveSubscribersThisWeek = (int)$NewActiveSubscribersThisWeek;
	}
	
	/**
	 * @return int
	 */
	public function getNewActiveSubscribersThisWeek()
	{
	  return $this->NewActiveSubscribersThisWeek;
	}
	
	/**
	 * @param int $TotalActiveSubscribers TotalActiveSubscribers
	 */
	public function setTotalActiveSubscribers($TotalActiveSubscribers)
	{
	  $this->TotalActiveSubscribers = (int)$TotalActiveSubscribers;
	}
	
	/**
	 * @return int
	 */
	public function getTotalActiveSubscribers()
	{
	  return $this->TotalActiveSubscribers;
	}

	/**
	 * @param int $TotalUnsubscribes TotalUnsubscribes
	 */
	public function setTotalUnsubscribes($TotalUnsubscribes)
	{
	  $this->TotalUnsubscribes = (int)$TotalUnsubscribes;
	}
	
	/**
	 * @return int
	 */
	public function getTotalUnsubscribes()
	{
	  return $this->TotalUnsubscribes;
	}
	
	/**
	 * @param int $TotalUsedInBroadcasts TotalUsedInBroadcasts
	 */
	public function setTotalUsedInBroadcasts($TotalUsedInBroadcasts)
	{
	  $this->TotalUsedInBroadcasts = (int)$TotalUsedInBroadcasts;
	}
	
	/**
	 * @return int
	 */
	public function getTotalUsedInBroadcasts()
	{
	  return $this->TotalUsedInBroadcasts;
	}

	/**
	 * @param int $TotalCreatedAutoresponders TotalCreatedAutoresponders
	 */
	public function setTotalCreatedAutoresponders($TotalCreatedAutoresponders)
	{
	  $this->TotalCreatedAutoresponders = (int)$TotalCreatedAutoresponders;
	}
	
	/**
	 * @return int
	 */
	public function getTotalCreatedAutoresponders()
	{
	  return $this->TotalCreatedAutoresponders;
	}
}

?>