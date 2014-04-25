<?php
# Created by MS as on Saturday, March 08, 2014
class INinboxReplyToFields extends INinboxAPI
{
	/**
	 * @var int
	 */
	private $ReplyToFieldID;

	/**
	 * @var string
	 */
	private $ReplyToEmail;

	/**
	 * @var boolean
	 */
	private $IsDefault;

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
		$this->setReplyToFieldID($obj->{'ReplyToFieldID'});
		$this->setReplyToEmail($obj->{'ReplyToEmail'});
		if($this->getFormat() == "xml") 
			$this->setIsDefault($obj->{'IsDefault'}=="True"?true:false);
		else
			$this->setIsDefault($obj->{'IsDefault'});
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
	 * @param boolean $IsDefault IsDefault
	 */
	public function setIsDefault($IsDefault)
	{
	  $this->IsDefault = (bool)$IsDefault;
	}
	
	/**
	 * @return boolean
	 */
	public function getIsDefault()
	{
	  return $this->IsDefault;
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
	 * @param int $if ReplyToFieldID
	 * @param int $code VerificationCode
	 *
	 * @return object
	 * @throws Exception
	 */
	public function verify($id, $code)
	{
		$result = $this->getURL("/account/replytofields/$id/verify.".$this->getFormat()."?VerificationCode=".$code);
		$this->checkForErrors("ReplyToFields");
		if($this->getFormat() == "xml")
			$object = simplexml_load_string($result);
		else
			$object = json_decode($result);
		return $object;
	}

	/**
	 * @param int $if ReplyToFieldID
	 * @param int $code VerificationCode
	 *
	 * @return object
	 * @throws Exception
	 */
	public function setDefault($id)
	{
		$result = $this->getURL("/account/replytofields/$id/setdefault.".$this->getFormat());
		$this->checkForErrors("ReplyToFields");
		if($this->getFormat() == "xml")
			$object = simplexml_load_string($result);
		else
			$object = json_decode($result);
		return $object;
	}
	
	/**
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get($param = array())
	{
		$return = array();
		$final_url = "/account/replytofields/list." . $this->getFormat();
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url.= $sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);
		$this->checkForErrors("ReplyToFields");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_replytofields = $object->ReplyToField;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_replytofields = $object->ReplyToFields;
		}
		
		foreach($all_replytofields as $replytofields)
		{
			$replytofields_obj = new INinboxReplyToFields($this);
			$replytofields_obj->setFormat($this->getFormat());
			$replytofields_obj->loadFromObject($replytofields); # Response data
			$return[] = $replytofields_obj;
		}
		return $return;
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
		$new_xml = $this->postDataWithVerb("/account/replytofields/create." . $this->getFormat(), $person_xml, "POST");
		$this->checkForErrors("ReplyToFields", 201);
		return true;
	}
	
	/**
	 * @return xml data
	 */
	public function toXML()
	{
		$xml[] = "<?xml version='1.0' encoding='utf-8'?><ReplyToField>";
		$fields = array("ReplyToEmail");
		
		foreach($fields as $field) {
			$xml_field_name = str_replace("_", "-", $field);
			$xml[] = "<" . $xml_field_name . ">" . $this->$field . "</" . $xml_field_name . ">";
		}
		$xml[] = "</ReplyToField>";
		return implode("\n", $xml);
	}

	/**
	 * @return json data
	 */
	public function toJSON() {
		$fields = array("ReplyToEmail");
		foreach($fields as $field) {
			$json[$field] = $this->$field;
		}
		return json_encode($json);
	}
		
	/**
	 * @return array
	 * @throws Exception
	 */
	public function delete()
	{
		$this->postDataWithVerb("/account/replytofields/" . $this->getReplyToFieldID() . "/delete.". $this->getFormat(), "", "DELETE");
		$this->checkForErrors("ReplyToFields", 200);	
	}
}