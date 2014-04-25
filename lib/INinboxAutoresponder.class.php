<?php
# Created by MS as on Saturday, March 08, 2014
class INinboxAutoresponders extends INinboxAPI
{
	/**
	 * @var int
	 */
	private $AutoresponderID;

	/**
	 * @var string
	 */
	private $Subject;

	/**
	 * @var string
	 */
	private $ContentType;

	/**
	 * @var string
	 */
	private $CreatedDate;

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
		//echo "<pre>";print_r($obj);
		if(isset($obj->{'AutoresponderID'}))
			$this->setAutoresponderID($obj->{'AutoresponderID'});
		$this->setSubject($obj->{'Subject'});
		$this->setContentType($obj->{'ContentType'});
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
	 * @param string $Subject Subject
	 */
	public function setSubject($Subject)
	{
	  $this->Subject = (string)$Subject;
	}
	
	/**
	 * @return string
	 */
	public function getSubject()
	{
	  return $this->Subject;
	}
	
	/**
	 * @param string $ContentType ContentType
	 */
	public function setContentType($ContentType)
	{
	  $this->ContentType = (string)$ContentType;
	}
	
	/**
	 * @return string
	 */
	public function getContentType()
	{
	  return $this->ContentType;
	}
	
	/**
	 * @param int $AutoresponderID AutoresponderID
	 */
	public function setAutoresponderID($AutoresponderID)
	{
	  $this->AutoresponderID = (int)$AutoresponderID;
	}
	
	/**
	 * @return int
	 */
	public function getAutoresponderID()
	{
	  return $this->AutoresponderID;
	}

	/**
	 * @param int $list_id ListID
	 * @param int $auto_id AutoresponderID
	 *
	 * @return object
	 * @throws Exception
	 */
	public function findDetailById($list_id, $auto_id)
	{
		$array = $this->getURL("/lists/$list_id/autoresponders/$auto_id/detail." . $this->getFormat());
		$this->checkForErrors("Autoreponder");
		if($this->getFormat() == "xml")
			$object = simplexml_load_string($array);
		else
			$object = json_decode($array);
		$resData = new INinboxAutoresponderDetails($list_id, $auto_id);
		$resData->setFormat($this->getFormat());
		$resData->loadFromObject($object);
		return $resData;
	}
	
	/**
	 * @param int $id ListID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 * @throws Exception
	 */
	public function get($id, $param = array())
	{
		$return = array();

		$final_url = "/lists/$id/autoresponders/list." . $this->getFormat();
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url.= $sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);
		//echo $result;exit;
		$this->checkForErrors("Autoresponder");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_autoresponders = $object->Results->Autoresponder;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_autoresponders = $object->Results;
		}
		
		$this->setAllPagingParameter($object->TotalNumberOfRecords, $object->RecordsOnThisPage, $object->PageNumber, $object->PageSize);

		foreach($all_autoresponders as $autoresponder)
		{
			$autoresponder_obj = new INinboxAutoresponderLists($this);
			$autoresponder_obj->loadFromObject($autoresponder); # Response data
			$return[] = $autoresponder_obj;
		}
		//echo "<pre>";print_r($return);exit;
		return $return;
	}

	/**
	 * @param int $list_id ListID
	 * @param int $auto_id AutoresponderID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findClicksRecipients($list_id, $auto_id, $param = array())
	{
		return $this->parseRecipients("/lists/$list_id/autoresponders/$auto_id/clicks." . $this->getFormat(), "Clicks", $param);
	}
	
	/**
	 * @param int $list_id ListID
	 * @param int $auto_id AutoresponderID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findOpensRecipients($list_id, $auto_id, $param = array())
	{
		return $this->parseRecipients("/lists/$list_id/autoresponders/$auto_id/opens." . $this->getFormat(), "Opens", $param);
	}
	
	/**
	 * @param int $list_id ListID
	 * @param int $auto_id AutoresponderID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findBouncesRecipients($list_id, $auto_id, $param = array())
	{
		return $this->parseRecipients("/lists/$list_id/autoresponders/$auto_id/bounces." . $this->getFormat(), "Bounces", $param);
	}
	
	/**
	 * @param int $list_id ListID
	 * @param int $auto_id AutoresponderID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findSpamRecipients($list_id, $auto_id, $param = array())
	{
		return $this->parseRecipients("/lists/$list_id/autoresponders/$auto_id/spam." . $this->getFormat(), "Spam", $param);
	}
	
	/**
	 * @param int $list_id ListID
	 * @param int $auto_id AutoresponderID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findRecipients($list_id, $auto_id, $param = array())
	{
		return $this->parseRecipients("/lists/$list_id/autoresponders/$auto_id/recipients." . $this->getFormat(), "Recipients", $param);
	}

	/**
	 * @param int $list_id ListID
	 * @param int $auto_id AutoresponderID
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return object
	 */
	public function findUnsubscribeRecipients($list_id, $auto_id, $param = array())
	{
		return $this->parseRecipients("/lists/$list_id/autoresponders/$auto_id/unsubscribe." . $this->getFormat(), "Unsubscribe", $param);
	}
	
	/**
	 * @param string $url
	 * @param string $type (Type for making classname dynamic)
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function parseRecipients($url, $type, $param=array())
	{
		$return = array();
		$final_url = $url;
		$className = 'INinboxAutoresponder'.$type;
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url = $url.$sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);
		$this->checkForErrors("Autoresponder");
		
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_autoresponders = $object->Results->Recipient;

		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_autoresponders = $object->Results;
		}
		$this->setAllPagingParameter($object->TotalNumberOfRecords, $object->RecordsOnThisPage, $object->PageNumber, $object->PageSize);

		foreach($all_autoresponders as $autoresponder)
		{
			$autoresponder_obj = new $className();
			$autoresponder_obj->loadFromObject($autoresponder);
			$return[] = $autoresponder_obj;
		}
			
		return $return;
	}
}
class INinboxAutoresponderLists extends INinboxAutoresponders
{
	/**
	 * @var string
	 */
	private $Interval;

	/**
	 * @var int
	 */
	private $TotalRecipients;

	/**
	 * @var int
	 */
	private $FromFieldID;

	/**
	 * @var string
	 */
	private $FromName;

	/**
	 * @var string
	 */
	private $FromEmail;

	/**
	 * @var int
	 */
	private $ReplyToFieldID;

	/**
	 * @var string
	 */
	private $ReplyTo;

	/**
	 * @var string
	 */
	private $WebVersionURL;

	/**
	 * @var string
	 */
	private $WebVersionTextURL;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		//echo "<pre>";print_r($obj);
		parent::loadFromObject($obj);
		$this->setInterval($obj->{'Interval'});
		$this->setTotalRecipients($obj->{'TotalRecipients'});
		$this->setFromFieldID($obj->{'FromFieldID'});
		$this->setFromName($obj->{'FromName'});
		$this->setFromEmail($obj->{'FromEmail'});
		$this->setReplyToFieldID($obj->{'ReplyToFieldID'});
		$this->setReplyTo($obj->{'ReplyTo'});
		$this->setWebVersionURL($obj->{'WebVersionURL'});
		$this->setWebVersionTextURL($obj->{'WebVersionTextURL'});
		return true;
	}
	
	/**
	 * @param string $Status Status
	 */
	public function setWebVersionTextURL($WebVersionTextURL)
	{
	  $this->WebVersionTextURL = (string)$WebVersionTextURL;
	}
	
	/**
	 * @return string
	 */
	public function getWebVersionTextURL()
	{
	  return $this->WebVersionTextURL;
	}
	
	/**
	 * @param string $WebVersionURL WebVersionURL
	 */
	public function setWebVersionURL($WebVersionURL)
	{
	  $this->WebVersionURL = (string)$WebVersionURL;
	}
	
	/**
	 * @return string
	 */
	public function getWebVersionURL()
	{
	  return $this->WebVersionURL;
	}
	
	/**
	 * @param string $ReplyTo ReplyTo
	 */
	public function setReplyTo($ReplyTo)
	{
	  $this->ReplyTo = (string)$ReplyTo;
	}
	
	/**
	 * @return string
	 */
	public function getReplyTo()
	{
	  return $this->ReplyTo;
	}
	
	/**
	 * @param string $ReplyToFieldID ReplyToFieldID
	 */
	public function setReplyToFieldID($ReplyToFieldID)
	{
	  $this->ReplyToFieldID = (int)$ReplyToFieldID;
	}
	
	/**
	 * @return int
	 */
	public function getReplyToFieldID()
	{
	  return $this->ReplyToFieldID;
	}
	
	/**
	 * @param string $FromEmail FromEmail
	 */
	public function setFromEmail($FromEmail)
	{
	  $this->FromEmail = (string)$FromEmail;
	}
	
	/**
	 * @return string
	 */
	public function getFromEmail()
	{
	  return $this->FromEmail;
	}
	
	/**
	 * @param string $FromName FromName
	 */
	public function setFromName($FromName)
	{
	  $this->FromName = (string)$FromName;
	}
	
	/**
	 * @return string
	 */
	public function getFromName()
	{
	  return $this->FromName;
	}
	
	/**
	 * @param string $FromFieldID FromFieldID
	 */
	public function setFromFieldID($FromFieldID)
	{
	  $this->FromFieldID = (int)$FromFieldID;
	}
	
	/**
	 * @return int
	 */
	public function getFromFieldID()
	{
	  return $this->FromFieldID;
	}
	
	/**
	 * @param string $TotalRecipients TotalRecipients
	 */
	public function setTotalRecipients($TotalRecipients)
	{
	  $this->TotalRecipients = (int)$TotalRecipients;
	}
	
	/**
	 * @return int
	 */
	public function getTotalRecipients()
	{
	  return $this->TotalRecipients;
	}
	
	/**
	 * @param string $Interval TimeInterval
	 */
	public function setInterval($Interval)
	{
	  $this->Interval = (string)$Interval;
	}
	
	/**
	 * @return string
	 */
	public function getInterval()
	{
	  return $this->Interval;
	}
}

class INinboxAutoresponderAction extends INinboxAutoresponders
{
	/**
	 * @var int
	 */
	public $ListID;

	/**
	 * @var string
	 */
	public $Type;

	/**
	 * @var int
	 */
	public $SendAfterSingupDays;

	/**
	 * @var int
	 */
	public $FromEmailID;

	/**
	 * @var int
	 */
	public $ReplyToID;

	/**
	 * @var string
	 */
	public $Subject;

	/**
	 * @var string
	 */
	public $Body;
	
	/**
	 * @var string
	 */
	public $BodyText;

	/**
	 * @var boolean
	 */
	public $TrackClickThrough;

	/**
	 * @var boolean
	 */
	public $TrackOpenRate;
	
	/**
	 * @var boolean
	 */
	public $RemoveFooter;
	
	public function loadFromObject($obj)
	{
		//echo "<pre>";print_r($obj);exit;
		$this->setMailType($obj->{'Type'});
		$this->setSendAfterSingupDays($obj->{'SendAfterSingupDays'});
		$this->setFromEmailID($obj->{'FromEmailID'});
		$this->setReplyToID($obj->{'ReplyToID'});
		$this->setSubject($obj->{'Subject'});
		$this->setBody($obj->{'Body'});
		$this->setBodyText($obj->{'BodyText'});
		if($this->getFormat() == "xml") {
			$this->setTrackClickThrough($obj->{'TrackClickThrough'}=="True"?true:false);
			$this->setRemoveFooter($obj->{'RemoveFooter'}=="True"?true:false);
			$this->setTrackOpenRate($obj->{'TrackOpenRate'}=="True"?true:false);
		}
		else {
			$this->setTrackClickThrough($obj->{'TrackClickThrough'});
			$this->setRemoveFooter($obj->{'RemoveFooter'});
			$this->setTrackOpenRate($obj->{'TrackOpenRate'});
		}
		return true;
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
	 * @param string $Type Type
	 */
	public function setMailType($Type)
	{
	  $this->Type = (string)$Type;
	}
	
	/**
	 * @return string
	 */
	public function getMailType()
	{
	  return $this->Type;
	}
	
	/**
	 * @param int $SendAfterSingupDays SendAfterSingupDays
	 */
	public function setSendAfterSingupDays($SendAfterSingupDays)
	{
	  $this->SendAfterSingupDays = (int)$SendAfterSingupDays;
	}
	
	/**
	 * @return int
	 */
	public function getSendAfterSingupDays()
	{
	  return $this->SendAfterSingupDays;
	}
	
	/**
	 * @param int $FromEmailID FromEmailID
	 */
	public function setFromEmailID($FromEmailID)
	{
	  $this->FromEmailID = (int)$FromEmailID;
	}
	
	/**
	 * @return int
	 */
	public function getFromEmailID()
	{
	  return $this->FromEmailID;
	}
	
	/**
	 * @param int $ReplyToID ReplyToID
	 */
	public function setReplyToID($ReplyToID)
	{
	  $this->ReplyToID = (int)$ReplyToID;
	}
	
	/**
	 * @return int
	 */
	public function getReplyToID()
	{
	  return $this->ReplyToID;
	}
	
	/**
	 * @param string $Subject Subject
	 */
	public function setSubject($Subject)
	{
	  $this->Subject = (string)$Subject;
	}
	
	/**
	 * @return string
	 */
	public function getSubject()
	{
	  return $this->Subject;
	}
	
	/**
	 * @param string $Body Body
	 */
	public function setBody($Body)
	{
	  $this->Body = (string)$Body;
	}
	
	/**
	 * @return string
	 */
	public function getBody()
	{
	  return $this->Body;
	}
	
	/**
	 * @param string $BodyText BodyText
	 */
	public function setBodyText($BodyText)
	{
	  $this->BodyText = (string)$BodyText;
	}
	
	/**
	 * @return string
	 */
	public function getBodyText()
	{
	  return $this->BodyText;
	}
	
	/**
	 * @param boolean $TrackClickThrough TrackClickThrough
	 */
	public function setTrackClickThrough($TrackClickThrough)
	{
	  $this->TrackClickThrough = (bool)$TrackClickThrough;
	}
	
	/**
	 * @return boolean
	 */
	public function getTrackClickThrough()
	{
	  return $this->TrackClickThrough;
	}

	/**
	 * @param boolean $TrackOpenRate TrackOpenRate
	 */
	public function setTrackOpenRate($TrackOpenRate)
	{
	  $this->TrackOpenRate = (bool)$TrackOpenRate;
	}
	
	/**
	 * @return boolean
	 */
	public function getTrackOpenRate()
	{
	  return $this->TrackOpenRate;
	}
	
	/**
	 * @param boolean $RemoveFooter RemoveFooter
	 */
	public function setRemoveFooter($RemoveFooter)
	{
	  $this->RemoveFooter = (bool)$RemoveFooter;
	}
	
	/**
	 * @return boolean
	 */
	public function getRemoveFooter()
	{
	  return $this->RemoveFooter;
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
		if ($this->getAutoresponderID() != null && $this->getListID() != null)
		{
			$new_xml = $this->postDataWithVerb("/lists/" . $this->getListID() . "/autoresponders/" . $this->getAutoresponderID() ."/update." . $this->getFormat(), $person_xml, "PUT");
			$this->checkForErrors("Autoresponder");
		}
		else if ($this->getListID() != null)
		{
			$new_xml = $this->postDataWithVerb("/lists/" . $this->getListID() . "/autoresponders/create." . $this->getFormat(), $person_xml, "POST");
			$this->checkForErrors("Autoresponder", 201);
		}
		return true;
	}
	
	/**
	 * @return xml data
	 */
	public function toXML()
	{
		$xml[] = "<?xml version='1.0' encoding='utf-8'?><Autoresponder>";
		$fields = array("Type", "Body", "BodyText", "SendAfterSingupDays", "FromEmailID", "ReplyToID", "Subject", "TrackClickThrough", "RemoveFooter");
		
		if ($this->getListID() != null)
			$xml[] = '<ListID>' . $this->getListID() . '</ListID>';
		
		foreach($fields as $field) {
			$xml_field_name = str_replace("_", "-", $field);
			$xml[] = "<" . $xml_field_name . ">" . $this->$field . "</" . $xml_field_name . ">";
		}

		$xml[] = "</Autoresponder>";
		//echo "<pre>";print_R($xml);exit;
		return implode("\n", $xml);
	}

	/**
	 * @return json data
	 */
	public function toJSON() {
		$fields = array("Type", "Body", "BodyText", "SendAfterSingupDays", "FromEmailID", "ReplyToID", "Subject", "TrackClickThrough", "RemoveFooter");
		foreach($fields as $field) {
			$json[$field] = $this->$field;
		}
		//echo json_encode($json);exit;
		return json_encode($json);
	}
		
	/**
	 * @return array
	 * @throws Exception
	 */
	public function delete()
	{
		$this->postDataWithVerb("/lists/" . $this->getListID() . "/autoresponders/" . $this->getAutoresponderID() ."/delete.xml", "", "DELETE");
		$this->checkForErrors("List", 200);	
	}
}

class INinboxAutoresponderSummary extends INinboxAPI
{
	/**
	 * @var string
	 */
	private $ContentType;

	/**
	 * @var int
	 */
	private $TotalOpened;

	/**
	 * @var int
	 */
	private $UniqueOpened;

	/**
	 * @var int
	 */
	private $TotalClicks;

	/**
	 * @var int
	 */
	private $TotalBounces;

	/**
	 * @var int
	 */
	private $TotalUnsubscribed;

	/**
	 * @var int
	 */
	private $TotalRecipients;

	/**
	 * @var int
	 */
	private $TotalSoftBounces;

	/**
	 * @var int
	 */
	private $TotalHardBounces;

	/**
	 * @var int
	 */
	private $TotalBlockedBounces;

	/**
	 * @var int
	 */
	private $TotalDelivered;

	/**
	 * @var int
	 */
	private $TotalSpam;

	/**
	 * @var string
	 */
	private $Status;

	/**
	 * @var string
	 */
	private $WebVersionURL;

	/**
	 * @var string
	 */
	private $WebVersionTextURL;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		//echo "<pre>";print_r($obj);exit;
		$this->setContentType($obj->{'ContentType'});
		$this->setTotalOpened($obj->{'TotalOpened'});
		$this->setUniqueOpened($obj->{'UniqueOpened'});
		$this->setTotalClicks($obj->{'TotalClicks'});
		$this->setTotalBounces($obj->{'TotalBounces'});
		$this->setTotalUnsubscribed($obj->{'TotalUnsubscribed'});
		$this->setTotalRecipients($obj->{'TotalRecipients'});
		$this->setTotalSoftBounces($obj->{'TotalSoftBounces'});
		$this->setTotalHardBounces($obj->{'TotalHardBounces'});
		$this->setTotalBlockedBounces($obj->{'TotalBlockedBounces'});
		$this->setTotalDelivered($obj->{'TotalDelivered'});
		$this->setTotalSpam($obj->{'TotalSpam'});
		$this->setStatus($obj->{'Status'});
		$this->setWebVersionURL($obj->{'WebVersionURL'});
		$this->setWebVersionTextURL($obj->{'WebVersionTextURL'});
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
	 * @param string $WebVersionURL WebVersionURL
	 */
	public function setWebVersionURL($WebVersionURL)
	{
	  $this->WebVersionURL = (string)$WebVersionURL;
	}
	
	/**
	 * @return string
	 */
	public function getWebVersionURL()
	{
	  return $this->WebVersionURL;
	}
	

	/**
	 * @param string $WebVersionTextURL WebVersionTextURL
	 */
	public function setWebVersionTextURL($WebVersionTextURL)
	{
	  $this->WebVersionTextURL = (string)$WebVersionTextURL;
	}
	
	/**
	 * @return string
	 */
	public function getWebVersionTextURL()
	{
	  return $this->WebVersionTextURL;
	}
	
	/**
	 * @param string $ContentType ContentType
	 */
	public function setContentType($ContentType)
	{
	  $this->ContentType = (string)$ContentType;
	}
	
	/**
	 * @return string
	 */
	public function getContentType()
	{
	  return $this->ContentType;
	}
	
	/**
	 * @param int $TotalSpam TotalSpam
	 */
	public function setTotalSpam($TotalSpam)
	{
	  $this->TotalSpam = (int)$TotalSpam;
	}
	
	/**
	 * @return int
	 */
	public function getTotalSpam()
	{
	  return $this->TotalSpam;
	}
	
	/**
	 * @param int $TotalRecipients TotalRecipients
	 */
	public function setTotalRecipients($TotalRecipients)
	{
	  $this->TotalRecipients = (int)$TotalRecipients;
	}
	
	/**
	 * @return int
	 */
	public function getTotalRecipients()
	{
	  return $this->TotalRecipients;
	}
	
	/**
	 * @param int $TotalUnsubscribed TotalUnsubscribed
	 */
	public function setTotalUnsubscribed($TotalUnsubscribed)
	{
	  $this->TotalUnsubscribed = (int)$TotalUnsubscribed;
	}
	
	/**
	 * @return int
	 */
	public function getTotalUnsubscribed()
	{
	  return $this->TotalUnsubscribed;
	}
	
	/**
	 * @param int $TotalBounces TotalBounces
	 */
	public function setTotalBounces($TotalBounces)
	{
	  $this->TotalBounces = (int)$TotalBounces;
	}
	
	/**
	 * @return int
	 */
	public function getTotalBounces()
	{
	  return $this->TotalBounces;
	}
	
	/**
	 * @param int $TotalClicks TotalClicks
	 */
	public function setTotalClicks($TotalClicks)
	{
	  $this->TotalClicks = (int)$TotalClicks;
	}
	
	/**
	 * @return int
	 */
	public function getTotalClicks()
	{
	  return $this->TotalClicks;
	}
	
	/**
	 * @param int $UniqueOpened UniqueOpened
	 */
	public function setUniqueOpened($UniqueOpened)
	{
	  $this->UniqueOpened = (int)$UniqueOpened;
	}
	
	/**
	 * @return int
	 */
	public function getUniqueOpened()
	{
	  return $this->UniqueOpened;
	}
	
	/**
	 * @param int $TotalOpened TotalOpened
	 */
	public function setTotalOpened($TotalOpened)
	{
	  $this->TotalOpened = (int)$TotalOpened;
	}
	
	/**
	 * @return int
	 */
	public function getTotalOpened()
	{
	  return $this->TotalOpened;
	}
	
	/**
	 * @param int $TotalDelivered TotalDelivered
	 */
	public function setTotalDelivered($TotalDelivered)
	{
	  $this->TotalDelivered = (int)$TotalDelivered;
	}
	
	/**
	 * @return int
	 */
	public function getTotalDelivered()
	{
	  return $this->TotalDelivered;
	}
	
	/**
	 * @param int $TotalSoftBounces TotalSoftBounces
	 */
	public function setTotalSoftBounces($TotalSoftBounces)
	{
	  $this->TotalSoftBounces = (int)$TotalSoftBounces;
	}
	
	/**
	 * @return int
	 */
	public function getTotalSoftBounces()
	{
	  return $this->TotalSoftBounces;
	}
	
	/**
	 * @param int $TotalHardBounces TotalHardBounces
	 */
	public function setTotalHardBounces($TotalHardBounces)
	{
	  $this->TotalHardBounces = (int)$TotalHardBounces;
	}

	/**
	 * @return int
	 */
	public function getTotalHardBounces()
	{
	  return $this->TotalHardBounces;
	}
	
	/**
	 * @param int $TotalBlockedBounces TotalBlockedBounces
	 */
	public function setTotalBlockedBounces($TotalBlockedBounces)
	{
	  $this->TotalBlockedBounces = (int)$TotalBlockedBounces;
	}
	
	/**
	 * @return int
	 */
	public function getTotalBlockedBounces()
	{
	  return $this->TotalBlockedBounces;
	}

	/**
	 * @param int $list_id ListID
	 * @param int $auto_id AutoresponderID
	 *
	 * @return object
	 * @throws Exception
	 */
	public function findSummaryById($list_id, $auto_id)
	{
		$array = $this->getURL("/lists/$list_id/autoresponders/$auto_id/summary." . $this->getFormat());
		$this->checkForErrors("Autoreponder");
		if($this->getFormat() == "xml")
			$object = simplexml_load_string($array);
		else
			$object = json_decode($array);
		$resData = new INinboxAutoresponderSummary($list_id, $auto_id);
		$resData->loadFromObject($object);
		return $resData;
	}
}

abstract class INinboxAutoresponderRecepientsCommon extends INinboxAPI
{
	/**
	 * @var int
	 */
	private $ContactID;

	/**
	 * @var string
	 */
	private $Name;

	/**
	 * @var string
	 */
	private $Email;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		//echo "<pre>";print_r($obj);
		$this->setContactID($obj->{'ContactID'});
		$this->setName($obj->{'Name'});
		$this->setEmail($obj->{'Email'});
		return true;
	}
	
	/**
	 * @param int $ContactID ContactID
	 */
	public function setContactID($ContactID)
	{
	  $this->ContactID = (int)$ContactID;
	}
	
	/**
	 * @return int
	 */
	public function getContactID()
	{
	  return $this->ContactID;
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
	 * @param string $Email Email
	 */
	public function setEmail($Email)
	{
	  $this->Email = (string)$Email;
	}
	
	/**
	 * @return string
	 */
	public function getEmail()
	{
	  return $this->Email;
	}
}

class INinboxAutoresponderOpens extends INinboxAutoresponderRecepientsCommon
{
	/**
	 * @var string
	 */
	public $Date;

	/**
	 * @var string
	 */
	public $IP;

	/**
	 * @var string
	 */
	public $CountryCode;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		//echo "<pre>";print_r($obj);
		$this->setDate($obj->{'Date'});
		$this->setIP($obj->{'IP'});
		$this->setCountryCode($obj->{'CountryCode'});
		return true;
	}
	
	/**
	 * @param string $Date Date
	 */
	public function setDate($Date)
	{
	  $this->Date = (string)$Date;
	}
	
	/**
	 * @return string
	 */
	public function getDate()
	{
	  return $this->Date;
	}
	
	/**
	 * @param string $IP IP
	 */
	public function setIP($IP)
	{
	  $this->IP = (string)$IP;
	}
	
	/**
	 * @return string
	 */
	public function getIP()
	{
	  return $this->IP;
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
}

class INinboxAutoresponderClicks extends INinboxAutoresponderOpens
{
	/**
	 * @var string
	 */
	public $URL;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		//echo "<pre>";print_r($obj);//exit;
		$this->setURL($obj->{'URL'});
		return true;
	}
	
	/**
	 * @param string $URL URL
	 */
	public function setURL($URL)
	{
	  $this->URL = (string)$URL;
	}
	
	/**
	 * @return string
	 */
	public function getURL()
	{
	  return $this->URL;
	}
}

class INinboxAutoresponderBounces extends INinboxAutoresponderRecepientsCommon
{
	/**
	 * @var string
	 */
	public $BounceType;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		//echo "<pre>";print_r($obj);//exit;
		$this->setBounceType($obj->{'BounceType'});
		return true;
	}
	
	/**
	 * @param string $BounceType BounceType
	 */
	public function setBounceType($BounceType)
	{
	  $this->BounceType = (string)$BounceType;
	}
	
	/**
	 * @return string
	 */
	public function getBounceType()
	{
	  return $this->BounceType;
	}
}

class INinboxAutoresponderSpam extends INinboxAutoresponderRecepientsCommon
{
}

class INinboxAutoresponderRecipients extends INinboxAutoresponderRecepientsCommon
{
}

class INinboxAutoresponderUnsubscribe extends INinboxAutoresponderRecepientsCommon
{
}

class INinboxAutoresponderDetails extends INinboxAutoresponders
{	
	/**
	 * @var int
	 */
	private $FromFieldID;

	/**
	 * @var string
	 */
	private $FromName;

	/**
	 * @var string
	 */
	private $FromEmail;

	/**
	 * @var int
	 */
	private $ReplyToFieldID;

	/**
	 * @var string
	 */
	private $ReplyToEmail;

	/**
	 * @var string
	 */
	private $ReplyTo;

	/**
	 * @var string
	 */
	private $Body;

	/**
	 * @var boolean
	 */
	private $TrackOpenRate;

	/**
	 * @var boolean
	 */
	private $TrackClickThrough;

	/**
	 * @var boolean
	 */
	private $RemoveFooter;

	/**
	 * @var string
	 */
	private $ModifiedDate;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setFromName($obj->{'FromName'});
		$this->setFromEmail($obj->{'FromEmail'});
		$this->setFromFieldID($obj->{'FromFieldID'});
		$this->setReplyToEmail($obj->{'ReplyToEmail'});
		$this->setReplyTo($obj->{'ReplyTo'});
		$this->setReplyToFieldID($obj->{'ReplyToFieldID'});
		$this->setBody($obj->{'Body'});
		if($this->getFormat() == "xml") {
			$this->setTrackClickThrough($obj->{'TrackClickThrough'}=="True"?true:false);
			$this->setRemoveFooter($obj->{'RemoveFooter'}=="True"?true:false);
			$this->setTrackOpenRate($obj->{'TrackOpenRate'}=="True"?true:false);
		}
		else {
			$this->setTrackClickThrough($obj->{'TrackClickThrough'});
			$this->setRemoveFooter($obj->{'RemoveFooter'});
			$this->setTrackOpenRate($obj->{'TrackOpenRate'});
		}
		$this->setModifiedDate($obj->{'ModifiedDate'});
		return true;
	}

	/**
	 * @param string $ModifiedDate ModifiedDate
	 */
	public function setModifiedDate($ModifiedDate)
	{
	  $this->ModifiedDate = (string)$ModifiedDate;
	}
	
	/**
	 * @return string
	 */
	public function getModifiedDate()
	{
	  return $this->ModifiedDate;
	}
	
	/**
	 * @param boolean $RemoveFooter RemoveFooter
	 */
	public function setRemoveFooter($RemoveFooter)
	{
	  $this->RemoveFooter = (boolean)$RemoveFooter;
	}

	/**
	 * @return boolean
	 */
	public function getRemoveFooter()
	{
	  return $this->RemoveFooter;
	}
	
	/**
	 * @param boolean $TrackClickThrough TrackClickThrough
	 */
	public function setTrackClickThrough($TrackClickThrough)
	{
	  $this->TrackClickThrough = (boolean)$TrackClickThrough;
	}

	/**
	 * @return boolean
	 */
	public function getTrackClickThrough()
	{
	  return $this->TrackClickThrough;
	}
	
	/**
	 * @param boolean $TrackOpenRate TrackOpenRate
	 */
	public function setTrackOpenRate($TrackOpenRate)
	{
	  $this->TrackOpenRate = (boolean)$TrackOpenRate;
	}
	
	/**
	 * @return boolean
	 */
	public function getTrackOpenRate()
	{
	  return $this->TrackOpenRate;
	}
	
	/**
	 * @param string $Body Body
	 */
	public function setBody($Body)
	{
	  $this->Body = (string)$Body;
	}
	
	/**
	 * @return string
	 */
	public function getBody()
	{
	  return $this->Body;
	}
	
	/**
	 * @param string $ReplyToEmail ReplyToEmail
	 */
	public function setReplyToEmail($ReplyToEmail)
	{
	  $this->ReplyToEmail = (string)$ReplyToEmail;
	}
	
	/**
	 * @return string
	 */
	public function getReplyToEmail()
	{
	  return $this->ReplyToEmail;
	}
	
	/**
	 * @param string $ReplyTo ReplyTo
	 */
	public function setReplyTo($ReplyTo)
	{
	  $this->ReplyTo = (string)$ReplyTo;
	}
	
	/**
	 * @return string
	 */
	public function getReplyTo()
	{
	  return $this->ReplyTo;
	}
	
	/**
	 * @param int $ReplyToFieldID ReplyToFieldID
	 */
	public function setReplyToFieldID($ReplyToFieldID)
	{
	  $this->ReplyToFieldID = (int)$ReplyToFieldID;
	}
	
	/**
	 * @return int
	 */
	public function getReplyToFieldID()
	{
	  return $this->ReplyToFieldID;
	}
	
	/**
	 * @param int $FromFieldID FromFieldID
	 */
	public function setFromFieldID($FromFieldID)
	{
	  $this->FromFieldID = (int)$FromFieldID;
	}
	
	/**
	 * @return int
	 */
	public function getFromFieldID()
	{
	  return $this->FromFieldID;
	}
	
	/**
	 * @param string $FromName FromName
	 */
	public function setFromName($FromName)
	{
	  $this->FromName = (string)$FromName;
	}

	/**
	 * @return string
	 */
	public function getFromName()
	{
	  return $this->FromName;
	}
	
	/**
	 * @param string $FromEmail FromEmail
	 */
	public function setFromEmail($FromEmail)
	{
	  $this->FromEmail = (string)$FromEmail;
	}
	
	/**
	 * @return string
	 */
	public function getFromEmail()
	{
	  return $this->FromEmail;
	}
}
?>