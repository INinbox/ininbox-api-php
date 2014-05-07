<?php
class INinboxFromFields extends INinboxAPI
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
		$this->setFromFieldID($obj->{'FromFieldID'});
		$this->setFromName($obj->{'FromName'});
		$this->setFromEmail($obj->{'FromEmail'});
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
	 * @param int $id FromFieldID
	 * @param int $code VerificationCode
	 *
	 * @return object
	 * @throws Exception
	 */
	public function verify($id, $code)
	{
		$result = $this->getURL("/account/fromfields/$id/verify.".$this->getFormat()."?VerificationCode=".$code);
		$this->checkForErrors("FromFields");
		if($this->getFormat() == "xml")
			$object = simplexml_load_string($result);
		else
			$object = json_decode($result);
		return $object;
	}

	/**
	 * @param int $id FromFieldID
	 *
	 * @return object
	 * @throws Exception
	 */
	public function setDefault($id)
	{
		$result = $this->getURL("/account/fromfields/$id/setdefault.".$this->getFormat());
		$this->checkForErrors("FromFields");
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
		$final_url = "/account/fromfields/list." . $this->getFormat();
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url.= $sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);
		$this->checkForErrors("FromFields");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_fromfields = $object->FromField;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_fromfields = $object->FromFields;
		}
		
		foreach($all_fromfields as $fromfields)
		{
			$fromfields_obj = new INinboxFromFields($this);
			$fromfields_obj->setFormat($this->getFormat());
			$fromfields_obj->loadFromObject($fromfields); 
			$return[] = $fromfields_obj;
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
		$new_xml = $this->postDataWithVerb("/account/fromfields/create." . $this->getFormat(), $person_xml, "POST");
		$this->checkForErrors("FromFields", 201);
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
		$xml[] = "<?xml version='1.0' encoding='utf-8'?><FromField>";
		$fields = array("FromName", "FromEmail");
		
		foreach($fields as $field) {
			$xml_field_name = str_replace("_", "-", $field);
			$xml[] = "<" . $xml_field_name . ">" . $this->$field . "</" . $xml_field_name . ">";
		}
		$xml[] = "</FromField>";
		return implode("\n", $xml);
	}

	/**
	 * @return json data
	 */
	public function toJSON() {
		$fields = array("FromName", "FromEmail");
		foreach($fields as $field) {
			$json[$field] = $this->$field;
		}
		return json_encode($json);
	}
		
	/**
	 * @throws Exception
	 */
	public function delete()
	{
		$this->postDataWithVerb("/account/fromfields/" . $this->getFromFieldID() . "/delete.". $this->getFormat(), "", "DELETE");
		$this->checkForErrors("FromFields", 200);	
	}
}