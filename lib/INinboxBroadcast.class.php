<?php
class INinboxBroadcast extends INinboxAPI 
{
	/**
	 * @var int
	 */
	public $BroadcastID;
	
	/**
	 * @var string
	 */
	public $BroadcastType;
	
	/**
	 * @var string
	 */
	public $Subject;
	
	/**
	 * @var string
	 */
	public $ContentType;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setBroadcastID($obj->{'BroadcastID'});
		$this->setBroadcastType($obj->{'BroadcastType'});
		$this->setSubject($obj->{'Subject'});
		$this->setContentType($obj->{'ContentType'});
		return true;
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
	 * @param int $BroadcastID BroadcastID
	 */
	public function setBroadcastID($BroadcastID)
	{
	  $this->BroadcastID = (int)$BroadcastID;
	}

	/**
	 * @return int
	 */
	public function getBroadcastID()
	{
	  return $this->BroadcastID;
	}

	/**
	 * @param string $BroadcastType BroadcastType
	 */
	public function setBroadcastType($BroadcastType)
	{
	  $this->BroadcastType = (string)$BroadcastType;
	}

	/**
	 * @return string
	 */
	public function getBroadcastType()
	{
	  return $this->BroadcastType;
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
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function getDraftList($param = array())
	{
		return $this->parseList("/broadcasts/draft-list." . $this->getFormat(), "Draft", $param);
	}
	
	/**
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function getSendList($param = array())
	{
		return $this->parseList("/broadcasts/sent-list." . $this->getFormat(), "Send", $param);
	}
	
	/**
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function getInprocessList($param = array())
	{
		return $this->parseList("/broadcasts/inprocess-list." . $this->getFormat(), "Inprocess", $param);
	}
	
	/**
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function getScheduledList($param = array())
	{
		return $this->parseList("/broadcasts/scheduled-list." . $this->getFormat(), "Scheduled", $param);
	}

	/**
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function getAwaitingForApprovalList($param = array())
	{
		return $this->parseList("/broadcasts/awaiting-for-approval-list." . $this->getFormat(), "AwaitingForApproval", $param);
	}

	/**
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function getPausedList($param = array())
	{
		return $this->parseList("/broadcasts/paused." . $this->getFormat(), "Paused", $param);
	}

	/**
	 * @param string $url URL
	 * @param object $classObj Class Object
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function parseList($url, $classObj, $param = array())
	{
		$return = array();
		$final_url = $url;
		$className = 'INinboxBroadcast'.$classObj;
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url = $url.$sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);
		$this->checkForErrors("Broadcast");
		
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_broadcasts = $object->Results->Broadcast;

		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_broadcasts = $object->Results;
		}
		$this->setAllPagingParameter($object->TotalNumberOfRecords, $object->RecordsOnThisPage, $object->PageNumber, $object->PageSize);

		foreach($all_broadcasts as $broadcasts)
		{
			$broadcast_obj = new $className();
			$broadcast_obj->setFormat($this->getFormat());
			$broadcast_obj->loadFromObject($broadcasts);
			$return[] = $broadcast_obj;
		}
			
		return $return;
	}

	/**
	 * @param int $broadcast_id Broadcast Id
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function findClicksRecipients($broadcast_id, $param = array())
	{
		return $this->parseRecipients("/broadcasts/$broadcast_id/clicks." . $this->getFormat(), "Clicks", $param);
	}
	
	/**
	 * @param int $broadcast_id Broadcast Id
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function findOpensRecipients($broadcast_id, $param = array())
	{
		return $this->parseRecipients("/broadcasts/$broadcast_id/opens." . $this->getFormat(), "Opens", $param);
	}
	
	/**
	 * @param int $broadcast_id Broadcast Id
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function findBouncesRecipients($broadcast_id, $param = array())
	{
		return $this->parseRecipients("/broadcasts/$broadcast_id/bounces." . $this->getFormat(), "Bounces", $param);
	}
	
	/**
	 * @param int $broadcast_id Broadcast Id
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function findSpamRecipients($broadcast_id, $param = array())
	{
		return $this->parseRecipients("/broadcasts/$broadcast_id/spam." . $this->getFormat(), "Spam", $param);
	}
	
	/**
	 * @param int $broadcast_id Broadcast Id
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function findRecipients($broadcast_id, $param = array())
	{
		return $this->parseRecipients("/broadcasts/$broadcast_id/recipient." . $this->getFormat(), "Recipients", $param);
	}

	/**
	 * @param int $broadcast_id Broadcast Id
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 */
	public function findUnsubscribeRecipients($broadcast_id, $param = array())
	{
		return $this->parseRecipients("/broadcasts/$broadcast_id/unsubscribe." . $this->getFormat(), "Unsubscribe", $param);
	}
	
	/**
	 * @param string $url URL
	 * @param object $classObj Class Object
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function parseRecipients($url, $classObj, $param=array())
	{
		$return = array();
		$final_url = $url;
		$className = 'INinboxBroadcast'.$classObj;
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url = $url.$sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);
		$this->checkForErrors("Broadcast");
		
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_broadcasts = $object->Results->Recipient;

		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_broadcasts = $object->Results;
		}
		$this->setAllPagingParameter($object->TotalNumberOfRecords, $object->RecordsOnThisPage, $object->PageNumber, $object->PageSize);

		foreach($all_broadcasts as $broadcast)
		{
			$broadcast_obj = new $className();
			$broadcast_obj->setFormat($this->getFormat());
			$broadcast_obj->loadFromObject($broadcast);
			$return[] = $broadcast_obj;
		}
			
		return $return;
	}
	
	/**
	 * @param int $id BroadcastID
	 *
	 * @return object
	 * @throws Exception
	 */
	public function findDetailById($id) {
		$result = $this->getURL("/broadcasts/$id/detail.".$this->getFormat());
		$this->checkForErrors("Broadcast");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
		}
		$broadcast = new INinboxBroadcastDetail();
		$broadcast->setFormat($this->getFormat());
		$broadcast->loadFromObject($object);
		return $broadcast;
	}

	/**
	 * return array
	 * @throws Exception
	 */
	public function delete()
	{
		$result = $this->postDataWithVerb("/broadcasts/" . $this->getBroadcastID() . "/delete." . $this->getFormat(), "", "DELETE");
		$this->checkForErrors("Broadcast", 200);	
		if($this->getFormat() == "xml") {
			$object = (array)simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$object = (array)json_decode($result);
		}
		return $object;
	}
}

class INinboxBroadcastList extends INinboxBroadcast
{
	/**
	 * @var int
	 */
	public $FromFieldID;

	/**
	 * @var string
	 */
	public $FromName;
	
	/**
	 * @var string
	 */
	public $FromEmail;
	
	/**
	 * @var int
	 */
	public $ReplyToFieldID;
	
	/**
	 * @var string
	 */
	public $ReplyTo;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setFromFieldID($obj->{'FromFieldID'});
		$this->setFromName($obj->{'FromName'});
		$this->setFromEmail($obj->{'FromEmail'});
		$this->setReplyToFieldID($obj->{'ReplyToFieldID'});
		$this->setReplyTo($obj->{'ReplyTo'});
		return true;
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
}


////////////////////////////////////////////////////////////////////////////////
/////                INinboxBroadcastList Child Class                   ////////
///////////////////////////////////////////////////////////////////////////////

class INinboxBroadcastDraft extends INinboxBroadcastList
{
	/**
	 * @var string
	 */
	public $CreatedDate;


	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$ret = parent::loadFromObject($obj);
		$ret['CreatedDate'] = (string)$obj->{'CreatedDate'};
		return $ret;
	}
	
	/**
	 * @param string $CreatedDate CreatedDate
	 */
	public function setCreatedDate($CreatedDate)
	{
	  $this->CreatedDate = (string)$CreatedDate;
	}

	/**
	 * @return string
	 */
	public function getCreatedDate()
	{
	  return $this->CreatedDate;
	}
}

class INinboxBroadcastSend extends INinboxBroadcastList
{
	/**
	 * @var int
	 */
	public $TotalRecipients;
	
	/**
	 * @var string
	 */
	public $CreatedDate;
	
	/**
	 * @var string
	 */
	public $SentDate;
	
	/**
	 * @var string
	 */
	public $WebVersionURL;
	
	/**
	 * @var string
	 */
	public $WebVersionTextURL;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setTotalRecipients($obj->{'TotalRecipients'});
		$this->setCreatedDate($obj->{'CreatedDate'});
		$this->setSentDate($obj->{'SentDate'});
		$this->setWebVersionURL($obj->{'WebVersionURL'});
		$this->setWebVersionTextURL($obj->{'WebVersionTextURL'});
		return true;
	}

	/**
	 * @param string $SentDate SentDate
	 */
	public function setSentDate($SentDate)
	{
	  $this->SentDate = (string)$SentDate;
	}

	/**
	 * @return string
	 */
	public function getSentDate()
	{
	  return $this->SentDate;
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
	 * @param string $CreatedDate CreatedDate
	 */
	public function setCreatedDate($CreatedDate)
	{
	  $this->CreatedDate = (string)$CreatedDate;
	}

	/**
	 * @return string
	 */
	public function getCreatedDate()
	{
	  return $this->CreatedDate;
	}
}

class INinboxBroadcastInprocess extends INinboxBroadcastList
{
	/**
	 * @var int
	 */
	public $TotalRecipients;
	
	/**
	 * @var int
	 */
	public $TotalSent;
	
	/**
	 * @var string
	 */
	public $CreatedDate;
	
	/**
	 * @var string
	 */
	public $SendDate;
	
	/**
	 * @var string
	 */
	public $WebVersionURL;
	
	/**
	 * @var string
	 */
	public $WebVersionTextURL;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setTotalRecipients($obj->{'TotalRecipients'});
		$this->setTotalSent($obj->{'TotalSent'});
		$this->setCreatedDate($obj->{'CreatedDate'});
		$this->setSendDate($obj->{'SendDate'});
		$this->setWebVersionURL($obj->{'WebVersionURL'});
		$this->setWebVersionTextURL($obj->{'WebVersionTextURL'});
		return true;
	}
	
	/**
	 * @param int $TotalSent TotalSent
	 */
	public function setTotalSent($TotalSent)
	{
	  $this->TotalSent = (int)$TotalSent;
	}

	/**
	 * @return int
	 */
	public function getTotalSent()
	{
	  return $this->TotalSent;
	}

	/**
	 * @param string $SendDate SendDate
	 */
	public function setSendDate($SendDate)
	{
	  $this->SendDate = (string)$SendDate;
	}
	
	/**
	 * @return string
	 */
	public function getSendDate()
	{
	  return $this->SendDate;
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
	 * @param string $CreatedDate CreatedDate
	 */
	public function setCreatedDate($CreatedDate)
	{
	  $this->CreatedDate = (string)$CreatedDate;
	}

	/**
	 * @return string
	 */
	public function getCreatedDate()
	{
	  return $this->CreatedDate;
	}
}

class INinboxBroadcastScheduled extends INinboxBroadcastList
{
	/**
	 * @var string
	 */
	public $CreatedDate;
	
	/**
	 * @var string
	 */
	public $ScheduledTimeZone;
	
	/**
	 * @var string
	 */
	public $DateScheduled;
	
	/**
	 * @var string
	 */
	public $PreviewURL;
	
	/**
	 * @var string
	 */
	public $PreviewTextURL;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setCreatedDate($obj->{'CreatedDate'});
		$this->setScheduledTimeZone($obj->{'ScheduledTimeZone'});
		$this->setDateScheduled($obj->{'DateScheduled'});
		$this->setPreviewURL($obj->{'PreviewURL'});
		$this->setPreviewTextURL($obj->{'PreviewTextURL'});
		return true;
	}

	/**
	 * @param string $ScheduledTimeZone ScheduledTimeZone
	 */
	public function setScheduledTimeZone($ScheduledTimeZone)
	{
	  $this->ScheduledTimeZone = (string)$ScheduledTimeZone;
	}

	/**
	 * @return string
	 */
	public function getScheduledTimeZone()
	{
	  return $this->ScheduledTimeZone;
	}

	/**
	 * @param string $DateScheduled DateScheduled
	 */
	public function setDateScheduled($DateScheduled)
	{
	  $this->DateScheduled = (string)$DateScheduled;
	}

	/**
	 * @return string
	 */
	public function getDateScheduled()
	{
	  return $this->DateScheduled;
	}

	/**
	 * @param string $PreviewURL PreviewURL
	 */
	public function setPreviewURL($PreviewURL)
	{
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
	 * @param string $PreviewTextURL PreviewTextURL
	 */
	public function setPreviewTextURL($PreviewTextURL)
	{
	  $this->PreviewTextURL = (string)$PreviewTextURL;
	}

	/**
	 * @return string
	 */
	public function getPreviewTextURL()
	{
	  return $this->PreviewTextURL;
	}

	/**
	 * @param string $CreatedDate CreatedDate
	 */
	public function setCreatedDate($CreatedDate)
	{
	  $this->CreatedDate = (string)$CreatedDate;
	}

	/**
	 * @return string
	 */
	public function getCreatedDate()
	{
	  return $this->CreatedDate;
	}
}

class INinboxBroadcastAwaitingForApproval extends INinboxBroadcastList
{
	/**
	 * @var string
	 */
	public $DeliverMethod;
	
	/**
	 * @var int
	 */
	public $TotalRecipients;
	
	/**
	 * @var string
	 */
	public $DateScheduled;
	
	/**
	 * @var string
	 */
	public $PreviewURL;
	
	/**
	 * @var string
	 */
	public $PreviewTextURL;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setDeliverMethod($obj->{'DeliverMethod'});
		$this->setTotalRecipients($obj->{'TotalRecipients'});
		$this->setDateScheduled($obj->{'DateScheduled'});
		$this->setPreviewURL($obj->{'PreviewURL'});
		$this->setPreviewTextURL($obj->{'PreviewTextURL'});
		return true;
	}

	/**
	 * @param int $TotalRecipients TotalRecipients
	 */
	public function setTotalRecipients($TotalRecipients)
	{
	  $this->TotalRecipients = (int)$TotalRecipients;
	}

	/**
	 * @param string $DateScheduled DateScheduled
	 */
	public function setDateScheduled($DateScheduled)
	{
	  $this->DateScheduled = (string)$DateScheduled;
	}
	
	/**
	 * @return string
	 */
	public function getDateScheduled()
	{
	  return $this->DateScheduled;
	}

	/**
	 * @param string $PreviewURL PreviewURL
	 */
	public function setPreviewURL($PreviewURL)
	{
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
	 * @param string $PreviewTextURL PreviewTextURL
	 */
	public function setPreviewTextURL($PreviewTextURL)
	{
	  $this->PreviewTextURL = (string)$PreviewTextURL;
	}

	/**
	 * @return string
	 */
	public function getPreviewTextURL()
	{
	  return $this->PreviewTextURL;
	}

	/**
	 * @param string $DeliverMethod DeliverMethod
	 */
	public function setDeliverMethod($DeliverMethod)
	{
	  $this->DeliverMethod = (string)$DeliverMethod;
	}
}

class INinboxBroadcastPaused extends INinboxBroadcastList
{
	/**
	 * @var int
	 */
	public $TotalSent;
	
	/**
	 * @var int
	 */
	public $TotalRecipients;
	
	/**
	 * @var string
	 */
	public $CreatedDate;
	
	/**
	 * @var string
	 */
	public $SentDate;
	
	/**
	 * @var string
	 */
	public $WebVersionURL;
	
	/**
	 * @var string
	 */
	public $WebVersionTextURL;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setTotalSent($obj->{'TotalSent'});
		$this->setTotalRecipients($obj->{'TotalRecipients'});
		$this->setCreatedDate($obj->{'CreatedDate'});
		$this->setSentDate($obj->{'SentDate'});
		$this->setWebVersionURL($obj->{'WebVersionURL'});
		$this->setWebVersionTextURL($obj->{'WebVersionTextURL'});
		return true;
	}
	
	/**
	 * @param int $TotalSent TotalSent
	 */
	public function setTotalSent($TotalSent)
	{
	  $this->TotalSent = (int)$TotalSent;
	}

	/**
	 * @return int
	 */
	public function getTotalSent()
	{
	  return $this->TotalSent;
	}

	/**
	 * @param string $SentDate SentDate
	 */
	public function setSentDate($SentDate)
	{
	  $this->SentDate = (string)$SentDate;
	}

	/**
	 * @return string
	 */
	public function getSentDate()
	{
	  return $this->SentDate;
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
	 * @param string $CreatedDate CreatedDate
	 */
	public function setCreatedDate($CreatedDate)
	{
	  $this->CreatedDate = (string)$CreatedDate;
	}

	/**
	 * @return string
	 */
	public function getCreatedDate()
	{
	  return $this->CreatedDate;
	}
}

class INinboxBroadcastSummary extends INinboxBroadcast
{
	/**
	 * @var string
	 */
	private $SentDate;
	
	/**
	 * @var string
	 */
	private $Status;
	
	/**
	 * @var int
	 */
	private $TotalRecipients;
	
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
	private $TotalUnsubscribed;
	
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
		parent::loadFromObject($obj);
		$this->setSentDate($obj->{'SentDate'});
		$this->setStatus($obj->{'Status'});
		$this->setTotalRecipients($obj->{'TotalRecipients'});
		$this->setTotalOpened($obj->{'TotalOpened'});
		$this->setUniqueOpened($obj->{'UniqueOpened'});
		$this->setTotalClicks($obj->{'TotalClicks'});
		$this->setTotalBounces($obj->{'TotalBounces'});
		$this->setTotalSoftBounces($obj->{'TotalSoftBounces'});
		$this->setTotalHardBounces($obj->{'TotalHardBounces'});
		$this->setTotalBlockedBounces($obj->{'TotalBlockedBounces'});
		$this->setTotalUnsubscribed($obj->{'TotalUnsubscribed'});
		$this->setTotalDelivered($obj->{'TotalDelivered'});
		$this->setTotalSpam($obj->{'TotalSpam'});
		$this->setWebVersionURL($obj->{'WebVersionURL'});
		$this->setWebVersionTextURL($obj->{'WebVersionTextURL'});
		return true;
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
	 * @param string $SentDate SentDate
	 */
	public function setSentDate($SentDate)
	{
	  $this->SentDate = (string)$SentDate;
	}

	/**
	 * @return string
	 */
	public function getSentDate()
	{
	  return $this->SentDate;
	}

	/**
	 * @return object
	 * @throws Exception
	 */
	public function get()
	{
		$return = array();

		$final_url = "/broadcasts/" . $this->getBroadcastID() . "/summary.".$this->getFormat() ;
		
		$result = $this->getUrl($final_url);
		$this->checkForErrors("Broadcast");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
		}
		$broadcast = new INinboxBroadcastSummary();
		$broadcast->loadFromObject($object);
		return $broadcast;
	}
}

abstract class INinboxBroadcastRecepientsCommon extends INinboxAPI
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

class INinboxBroadcastOpens extends INinboxBroadcastRecepientsCommon
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

class INinboxBroadcastClicks extends INinboxBroadcastOpens
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

class INinboxBroadcastBounces extends INinboxBroadcastRecepientsCommon
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

class INinboxBroadcastSpam extends INinboxBroadcastRecepientsCommon
{
}

class INinboxBroadcastRecipients extends INinboxBroadcastRecepientsCommon
{
}

class INinboxBroadcastUnsubscribe extends INinboxBroadcastRecepientsCommon
{
}

class INinboxBroadcastAction extends INinboxAPI 
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
	 * @var string
	 */
	public $FromEmailID;
	
	/**
	 * @var string
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
	 * @var string
	 */
	public $TrackClickThrough;
	
	/**
	 * @var boolean
	 */
	public $TrackOpenRate;
	
	/**
	 * @var string
	 */
	public $RemoveFooter;

	/**
	 * @var array
	 */
	public $IncludeListIDs;
	
	/**
	 * @var array
	 */
	public $ExcludeListIDs;
	
	/**
	 * @var array
	 */
	public $IncludeSegmentIDs;
	
	/**
	 * @var array
	 */
	public $ExcludeSegmentIDs;
	
	/**
	 * @var array
	 */
	public $ExcludeSuppressionIDs;
	

	/**
	 * Constructor
	 */
	public function INinboxBroadcastAction()
	{
		parent::__construct();

		$this->IncludeListIDs = array();
		$this->ExcludeListIDs = array();
		$this->IncludeSegmentIDs = array();
		$this->ExcludeSegmentIDs = array();
		$this->ExcludeSuppressionIDs = array();
		$this->setTrackOpenRate(True);
		$this->setRemoveFooter(False);
	}

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		//echo "<pre>";print_r($obj);exit;
		$this->setMailType($obj->{'Type'});
		$this->setFromEmailID($obj->{'FromEmailID'});
		$this->setReplyToID($obj->{'ReplyToID'});
		$this->setSubject($obj->{'Subject'});
		$this->setBody($obj->{'Body'});
		$this->setBodyText($obj->{'BodyText'});
		$this->setTrackClickThrough($obj->{'TrackClickThrough'});
		if($this->getFormat() == "xml") {
			$this->setTrackOpenRate($obj->{'TrackOpenRate'}=="True"?true:false);
			$this->setRemoveFooter($obj->{'RemoveFooter'}=="True"?true:false);
		}
		else {
			$this->setTrackOpenRate($obj->{'TrackOpenRate'});
			$this->setRemoveFooter($obj->{'RemoveFooter'});
		}
		return true;
	}

	/**
	 * @param boolean $TrackOpenRate TrackOpenRate
	 */
	public function setTrackOpenRate($TrackOpenRate)
	{
	  $this->TrackOpenRate = (bool)$TrackOpenRate;
	}

	/**
	 * @param string $Type Type
	 */
	public function setMailType($Type)
	{
	  $this->Type = (string)$Type;
	}

	/**
	 * @param string $FromEmailID FromEmailID
	 */
	public function setFromEmailID($FromEmailID)
	{
	  $this->FromEmailID = (string)$FromEmailID;
	}

	/**
	 * @param string $ReplyToID ReplyToID
	 */
	public function setReplyToID($ReplyToID)
	{
	  $this->ReplyToID = (string)$ReplyToID;
	}

	/**
	 * @param string $Subject Subject
	 */
	public function setSubject($Subject)
	{
	  $this->Subject = (string)$Subject;
	}

	/**
	 * @param string $Body Body
	 */
	public function setBody($Body)
	{
	  $this->Body = (string)$Body;
	}

	/**
	 * @param string $BodyText BodyText
	 */
	public function setBodyText($BodyText)
	{
	  $this->BodyText = (string)$BodyText;
	}

	/**
	 * @param string $TrackClickThrough TrackClickThrough
	 */
	public function setTrackClickThrough($TrackClickThrough)
	{
	  $this->TrackClickThrough = (string)$TrackClickThrough;
	}

	/**
	 * @param string $RemoveFooter RemoveFooter
	 */
	public function setRemoveFooter($RemoveFooter)
	{
	  $this->RemoveFooter = (string)$RemoveFooter;
	}

	/**
	 * @return string
	 */
	public function getRemoveFooter()
	{
	  return $this->RemoveFooter;
	}

	/**
	 * @param array $IncludeListIDs IncludeListIDs
	 */
	public function setIncludeListIDs($IncludeListIDs)
	{
	  $this->IncludeListIDs = $IncludeListIDs;
	}

	/**
	 * @return array
	 */
	public function getIncludeListIDs()
	{
	  return $this->IncludeListIDs;
	}

	/**
	 * @param array $ExcludeListIDs ExcludeListIDs
	 */
	public function setExcludeListIDs($ExcludeListIDs)
	{
	  $this->ExcludeListIDs = $ExcludeListIDs;
	}

	/**
	 * @return array
	 */
	public function getExcludeListIDs()
	{
	  return $this->ExcludeListIDs;
	}

	/**
	 * @param array $IncludeSegmentIDs IncludeSegmentIDs
	 */
	public function setIncludeSegmentIDs($IncludeSegmentIDs)
	{
	  $this->IncludeSegmentIDs = $IncludeSegmentIDs;
	}

	/**
	 * @return array
	 */
	public function getIncludeSegmentIDs()
	{
	  return $this->IncludeSegmentIDs;
	}

	/**
	 * @param array $ExcludeSegmentIDs ExcludeSegmentIDs
	 */
	public function setExcludeSegmentIDs($ExcludeSegmentIDs)
	{
	  $this->ExcludeSegmentIDs = $ExcludeSegmentIDs;
	}

	/**
	 * @return array
	 */
	public function getExcludeSegmentIDs()
	{
	  return $this->ExcludeSegmentIDs;
	}

	/**
	 * @param array $ExcludeSuppressionIDs ExcludeSuppressionIDs
	 */
	public function setExcludeSuppressionIDs($ExcludeSuppressionIDs)
	{
	  $this->ExcludeSuppressionIDs = $ExcludeSuppressionIDs;
	}

	/**
	 * @return array
	 */
	public function getExcludeSuppressionIDs()
	{
	  return $this->ExcludeSuppressionIDs;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function save()
	{
		if($this->getFormat() == "xml") {
			# Creating tag of ListID inside IncludeListIDs
			if(is_array($this->IncludeListIDs) && count($this->IncludeListIDs))
				$this->setIncludeListIDs(array('ListID'=>$this->IncludeListIDs));

			# Creating tag of ListID inside ExcludeListIDs
			if(is_array($this->ExcludeListIDs) && count($this->ExcludeListIDs))
				$this->setExcludeListIDs(array('ListID'=>$this->ExcludeListIDs));
			
			# Creating tag of ListID inside IncludeSegmentIDs
			if(is_array($this->IncludeSegmentIDs) && count($this->IncludeSegmentIDs))
				$this->setIncludeSegmentIDs(array('SegmentID'=>$this->IncludeSegmentIDs));
			
			# Creating tag of ListID inside ExcludeSegmentIDs
			if(is_array($this->ExcludeSegmentIDs) && count($this->ExcludeSegmentIDs))
				$this->setExcludeSegmentIDs(array('SegmentID'=>$this->ExcludeSegmentIDs));
			
			# Creating tag of ListID inside ExcludeSuppressionIDs
			if(is_array($this->ExcludeSuppressionIDs) && count($this->ExcludeSuppressionIDs))
				$this->setExcludeSuppressionIDs(array('SuppressionID'=>$this->ExcludeSuppressionIDs));

			$person_xml = $this->toXML();
		}
		else
			$person_xml = $this->toJSON();
		$new_data = $this->postDataWithVerb("/broadcasts/draft." . $this->getFormat(), $person_xml, "POST");

		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($new_data);
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($new_data);
		}
		return (array)$object;
	}
	
	/**
	 * @return xml data
	 */
	public function toXML()
	{
		$xml[] = "<?xml version='1.0' encoding='utf-8'?><Broadcast>";
		$fields = array("Type", "Body", "BodyText", "TrackOpenRate", "FromEmailID", "ReplyToID", "Subject", "TrackClickThrough", "RemoveFooter", "IncludeListIDs", "ExcludeListIDs", "IncludeSegmentIDs", "ExcludeSegmentIDs", "ExcludeSuppressionIDs");
		
		foreach($fields as $field) {
			if($field == "IncludeListIDs") {
				if(is_array( $this->IncludeListIDs) &&  count($this->IncludeListIDs)) {
					$xml_sub1=array();
					foreach( $this->IncludeListIDs as $key => $val ) {
						foreach( $val as $k1 => $v1) {
							$xml_str1 = '<ListID>' . $v1 . '</ListID>';
							$xml_sub1[] = $xml_str1;
						}
					}
					$xml_str1 = implode("\n", $xml_sub1);
					$xml[] = "<IncludeListIDs>" . $xml_str1 . "</IncludeListIDs>";
				}
			}
			else if($field == "ExcludeListIDs") {
				if( is_array( $this->ExcludeListIDs) && count($this->ExcludeListIDs) ) {
					$xml_sub2=array();
					foreach( $this->ExcludeListIDs as $key => $val ) {
						foreach( $val as $k1 => $v1) {
							$xml_str2='<ListID>' . $v1 . '</ListID>';
							$xml_sub2[] = $xml_str2;
						}
					}
					$xml_str2 = implode("\n", $xml_sub2);
					$xml[] = "<ExcludeListIDs>" . $xml_str2 . "</ExcludeListIDs>";
				}
			}
			else if($field == "IncludeSegmentIDs") {
				if(is_array( $this->IncludeSegmentIDs) && count( $this->IncludeSegmentIDs)) {
					$xml_sub3=array();
					foreach( $this->IncludeSegmentIDs as $key => $val ) {
						foreach( $val as $k1 => $v1) {
							$xml_str3='<SegmentID>' . $v1 . '</SegmentID>';
							$xml_sub3[] = $xml_str3;
						}
					}
					$xml_str3 = implode("\n", $xml_sub3);
					$xml[] = "<IncludeSegmentIDs>" . $xml_str3 . "</IncludeSegmentIDs>";
				}
			}
			else if($field == "ExcludeSegmentIDs") {
				if(is_array( $this->ExcludeSegmentIDs) && count( $this->ExcludeSegmentIDs)) {
					$xml_sub4=array();
					foreach( $this->ExcludeSegmentIDs as $key => $val ) {
						foreach( $val as $k1 => $v1) {
							$xml_str4='<SegmentID>' . $v1 . '</SegmentID>';
							$xml_sub4[] = $xml_str4;
						}
					}
					$xml_str4 = implode("\n", $xml_sub4);
					$xml[] = "<ExcludeSegmentIDs>" . $xml_str4 . "</ExcludeSegmentIDs>";
				}
			}
			else if($field == "ExcludeSuppressionIDs") {
				if(is_array( $this->ExcludeSuppressionIDs) &&  count($this->ExcludeSuppressionIDs)) {
					$xml_sub5=array();
					foreach( $this->ExcludeSuppressionIDs as $key => $val ) {
						foreach( $val as $k1 => $v1) {
							$xml_str5='<SuppressionID>' . $v1 . '</SuppressionID>';
							$xml_sub5[] = $xml_str5;
						}
					}
					$xml_str5 = implode("\n", $xml_sub5);
					$xml[] = "<ExcludeSuppressionIDs>" . $xml_str5 . "</ExcludeSuppressionIDs>";
				}
			}
			else {
				$xml_field_name = str_replace("_", "-", $field);
				$xml[] = "<" . $xml_field_name . ">" . $this->$field . "</" . $xml_field_name . ">";
			}
		}

		$xml[] = "</Broadcast>";
		return implode("\n", $xml);
	}

	/**
	 * @return json data
	 */
	public function toJSON() {
		$fields = array("Type", "Body", "BodyText", "TrackOpenRate", "FromEmailID", "ReplyToID", "Subject", "TrackClickThrough", "RemoveFooter", "IncludeListIDs", "ExcludeListIDs", "IncludeSegmentIDs", "ExcludeSegmentIDs", "ExcludeSuppressionIDs");
		foreach($fields as $field) {
			$json[$field] = $this->$field;
		}
		return json_encode($json);
	}
}

class INinboxBroadcastDetail extends INinboxBroadcastList
{
	/**
	 * @var string
	 */
	public $Type;

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
	public $TrackOpenRate;

	/**
	 * @var boolean
	 */
	public $TrackClickThrough;

	/**
	 * @var boolean
	 */
	public $RemoveFooter;

	/**
	 * @var string
	 */
	public $CreatedDate;

	/**
	 * @var string
	 */
	public $ModifiedDate;

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
		parent::loadFromObject($obj);
		$this->setType($obj->{'Type'});
		$this->setBody($obj->{'Body'});
		$this->setBodyText($obj->{'BodyText'});
		if($this->getFormat() == "xml") {
			$this->setTrackOpenRate($obj->{'TrackOpenRate'}=="True"?true:false);
			$this->setTrackClickThrough($obj->{'TrackClickThrough'}=="True"?true:false);
			$this->setRemoveFooter($obj->{'RemoveFooter'}=="True"?true:false);
		}
		else {
			$this->setTrackOpenRate($obj->{'TrackOpenRate'});
			$this->setTrackClickThrough($obj->{'TrackClickThrough'});
			$this->setRemoveFooter($obj->{'RemoveFooter'});
		}
		$this->setCreatedDate($obj->{'CreatedDate'});
		$this->setModifiedDate($obj->{'ModifiedDate'});
		$this->setStatus($obj->{'Status'});
		return true;
	}
	
	/**
	 * @param string $Type Type
	 */
	public function setType($Type)
	{
	  $this->Type = (string)$Type;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
	  return $this->Type;
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
	 * @param string $CreatedDate CreatedDate
	 */
	public function setCreatedDate($CreatedDate)
	{
	  $this->CreatedDate = (string)$CreatedDate;
	}

	/**
	 * @return string
	 */
	public function getCreatedDate()
	{
	  return $this->CreatedDate;
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
}
?>