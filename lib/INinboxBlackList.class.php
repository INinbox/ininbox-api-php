<?php
class INinboxBlackList extends INinboxAPI
{
	/**
	 * @var int
	 */
	private $BlacklistID;

	/**
	 * @var string
	 */
	private $Email;

	/**
	 * @var array
	 */
	private $ListIDs;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setBlacklistID($obj->{'BlacklistID'});
		$this->setEmail($obj->{'Email'});
		$this->setListIDs($obj->{'ListIDs'});

		$ListAct = array();
		$xx = (array)$obj->{'ListIDs'};
		//
		if($this->getFormat() == "xml") {
			if(count($xx['ListID'])==1)
				$ListAct[] = $xx['ListID'];
			else
				$ListAct = $xx['ListID'];
		}
		else {
			$ListAct = $xx;
		}
		//echo "<pre>";print_r($ListAct);//exit;
		$this->setListIDs($ListAct);
		return true;
	}
	
	/**
	 * @param array $ListIDs ListIDs
	 */
	public function setListIDs($ListIDs) {
	  $this->ListIDs = $ListIDs;
	}
	
	/**
	 * @return array
	 */
	public function getListIDs()
	{
	  return $this->ListIDs;
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
	
	/**
	 * @param int $BlacklistID BlacklistID
	 */
	public function setBlacklistID($BlacklistID)
	{
	  $this->BlacklistID = (int)$BlacklistID;
	}
	
	/**
	 * @return int
	 */
	public function getBlacklistID()
	{
	  return $this->BlacklistID;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function get()
	{
		$return = array();
		$final_url = "/blacklist/list." . $this->getFormat();
		$result = $this->getUrl($final_url);
		$this->checkForErrors("Blacklist");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_blacklists = $object->Blacklist;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_blacklists = $object->Blacklists;
		}
		
		foreach($all_blacklists as $blacklists)
		{
			$blacklists_obj = new INinboxBlackList($this);
			$blacklists_obj->setFormat($this->getFormat());
			$blacklists_obj->loadFromObject($blacklists); # Response data
			$return[] = $blacklists_obj;
		}
		return $return;
	}
	
	/**
	 * @return boolean
	 * @throws Exception
	 */
	public function save()
	{
		if($this->getFormat() == "xml") {
			$this->setListIDs(array('ListID'=>$this->getListIDs()));
			$person_xml = $this->toXML();
		}
		else
			$person_xml = $this->toJSON();
		if($this->getBlacklistID() != "")
			$new = $this->postDataWithVerb("/blacklist/".$this->getBlacklistID()."/update." . $this->getFormat(), $person_xml, "PUT");
		else
			$new = $this->postDataWithVerb("/blacklist/create." . $this->getFormat(), $person_xml, "POST");
		
		$this->checkForErrors("Blacklist", 201);
		return true;
	}
	
	/**
	 * @return xml data
	 */
	public function toXML()
	{
		$xml[] = "<?xml version='1.0' encoding='utf-8'?><Blacklist>";
		$fields = array("Email", "ListIDs");
		if ($this->getBlacklistID() != null)
			$xml[] = '<BlacklistID>' . $this->getBlacklistID() . '</BlacklistID>';
		foreach($fields as $field) {
			if($field == "ListIDs") {
				if( isset($this->ListIDs) && is_array( $this->ListIDs) ) {
					$xml_sub1=array();
					foreach( $this->ListIDs as $key => $val ) {
						if($this->getFormat() == "xml") {
							foreach( $val as $k1 => $v1) {
								$xml_str='<ListID>' . $v1 . '</ListID>';
								$xml_sub1[] = $xml_str;
							}
						}
						else {
							$xml_sub1[] = '<ListID>' . $val . '</ListID>';
						}
					}
					$xml_str = implode("\n", $xml_sub1);
					$xml[] = "<ListIDs>" . $xml_str . "</ListIDs>";
				}
			}
			else {
				$xml_field_name = str_replace("_", "-", $field);
				$xml[] = "<" . $xml_field_name . ">" . $this->$field . "</" . $xml_field_name . ">";
			}
		}
		//echo "<pre>";print_r($xml);exit;
		$xml[] = "</Blacklist>";
		return implode("\n", $xml);
	}

	/**
	 * @return json data
	 */
	public function toJSON() {
		$fields = array("Email", "ListIDs");
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
		$this->postDataWithVerb("/blacklist/" . $this->getBlacklistID() . "/delete.". $this->getFormat(), "", "DELETE");
		$this->checkForErrors("Blacklist", 200);	
	}
}