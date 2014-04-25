<?php
# -------------------------------------------------------------
# Created by PN as on 6 Mar 2014
# -------------------------------------------------------------
class INinboxContact extends INinboxAPI
{
	/**
	 * @var int
	 */
	public $ContactID;

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
	public $FullName;

	/**
	 * @var string
	 */
	public $Email;

	/**
	 * @var string
	 */
	public $Gender;

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
	public $RegisterThrough;

	/**
	 * @var array
	 */
	public $CustomFields;

	/**
	 * @var string
	 */
	public $Status;
	

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj) {
		if(isset($obj->{'ContactID'}))
			$this->setContactID($obj->{'ContactID'});
		$this->setFirstName($obj->{'FirstName'});
		$this->setLastName($obj->{'LastName'});
		$this->setFullName($obj->{'FullName'});
		$this->setEmail($obj->{'Email'});
		$this->setStateCode($obj->{'StateCode'});
		$this->setCountryCode($obj->{'CountryCode'});
		$this->setRegisterThrough($obj->{'RegisterThrough'});
		$this->setGender($obj->{'Gender'});
		$CFields = array();
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
		$this->setStatus($obj->{'Status'});
		return true;
	}
	

	/**
	 * @param int $ContactID ContactID
	 */
	public function setContactID($ContactID) {
	  $this->ContactID = (int)$ContactID;
	}

	/**
	 * @return int
	 */
	public function getContactID() {
	  return $this->ContactID;
	}

	/**
	 * @param string $FirstName FirstName
	 */
	public function setFirstName($FirstName) {
	  $this->FirstName = (string)$FirstName;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
	  return $this->FirstName;
	}

	/**
	 * @param string $LastName LastName
	 */
	public function setLastName($LastName) {
	  $this->LastName = (string)$LastName;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
	  return $this->LastName;
	}

	/**
	 * @param string $FullName FullName
	 */
	public function setFullName($FullName) {
		$this->FullName = (string)$FullName;
	}


	/**
	 * @return string
	 */
	public function getFullName() {
	  return $this->FullName;
	}
	
	/**
	 * @param string $Email Email
	 */
	public function setEmail($Email) {
	  $this->Email = (string)$Email;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
	  return $this->Email;
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
	public function getStateCode() {
	  return $this->StateCode;
	}
	
	/**
	 * @param string $CountryCode CountryCode
	 */
	public function setCountryCode($CountryCode) {
	  $this->CountryCode = (string)$CountryCode;
	}

	/**
	 * @return string
	 */
	public function getCountryCode() {
	  return $this->CountryCode;
	}
	
	/**
	 * @param string $RegisterThrough RegisterThrough
	 */
	public function setRegisterThrough($RegisterThrough) {
	  $this->RegisterThrough = (string)$RegisterThrough;
	}

	/**
	 * @return string
	 */
	public function getRegisterThrough() {
	  return $this->RegisterThrough;
	}

	/**
	 * @param string $Gender Gender
	 */
	public function setGender($Gender) {
	  $this->Gender = (string)$Gender;
	}
	
	/**
	 * @return string
	 */
	public function getGender() {
	  return $this->Gender;
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
	public function getStatus() {
	  return $this->Status;
	}

	/**
	 * @param array $CustomFields CustomFields
	 */
	public function setCustomFields($CustomFields) {
	  $this->CustomFields = $CustomFields;
	}

	/**
	 * @return array
	 */
	public function getCustomFields() {
	  return $this->CustomFields;
	}

	/**
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get($param=array())
	{
		$return = array();

		$final_url = "/contacts/list.".$this->getFormat() ;
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url.= $sep.http_build_query($param);
		}

		$result = $this->getUrl($final_url);
		$this->checkForErrors("Contact");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_contacts = $object->Results->Contact;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_contacts = $object->Results;
		}

		$this->setAllPagingParameter($object->TotalNumberOfRecords, $object->RecordsOnThisPage, $object->PageNumber, $object->PageSize);

		foreach($all_contacts as $contact)
		{
			$contact_obj = new INinboxContact();
			$contact_obj->setFormat($this->getFormat());
			$contact_obj->loadFromObject($contact); # Response data
			$return[] = $contact_obj;
		}
		return $return;
	}

	/**
	 * @param int $id id
	 *
	 * @return object
	 * @throws Exception
	 */
	public function findById($id)
	{
		$result = $this->getURL("/contacts/$id/details.".$this->getFormat());
		$this->checkForErrors("Contact");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
		}
		$contact_obj = new INinboxContactDetail();
		$contact_obj->setFormat($this->getFormat());
		$contact_obj->loadFromObject($object); # Response data
		return $contact_obj;
	}

	/**
	 * @return object
	 * @throws Exception
	 */
	public function delete()
	{
		$result = $this->postDataWithVerb("/contacts/" . $this->getContactID() . "/delete." . $this->getFormat(), "", "DELETE");
		$this->checkForErrors("Contact", 200);
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
		}
		return $object;
	}

	/**
	 * @return object
	 * @throws Exception
	 */
	public function getStatistics()
	{
		$array = $this->getURL("/contacts/stats." . $this->getFormat());
		$this->checkForErrors("Contact");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($array);
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($array);
		}
		$resData = new INinboxContactStatistics();
		$resData->setFormat($this->getFormat());
		$resData->loadFromObject($object);
		return $resData;
	}
	
}

class INinboxContactDetail extends INinboxContact
{
	/**
	 * @var string
	 */
	public $Company;
	
	/**
	 * @var string
	 */
	public $ProfileImageURL;
	
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
	public $PostalCode;

	/**
	 * @var string
	 */
	public $HomePhone;
	
	/**
	 * @var string
	 */
	public $MobilePhone;
	
	/**
	 * @var string
	 */
	public $WorkPhone;
	
	/**
	 * @var string
	 */
	public $Fax;
	
	/**
	 * @var array
	 */
	public $Opt_In;
	
	/**
	 * @var array
	 */
	public $ActiveLists;
	
	/**
	 * @var array
	 */
	public $UnsubscribedLists;
	
	/**
	 * @var array
	 */
	public $DeletedLists;
	
	/**
	 * @var array
	 */
	public $UnconfirmedLists;
	
	/**
	 * @var array
	 */
	public $BouncedLists;
	
	/**
	 * @var array
	 */
	public $ListIDs;
	
	/**
	 * @var boolean
	 */
	public $Resubscribe;

	/**
	 * @var boolean
	 */
	public $SendConfirmationEmail;
	
	/**
	 * @var boolean
	 */
	public $AddContactToAutoresponderCycle;

	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		parent::loadFromObject($obj);
		$this->setName($obj->{'Name'});
		$this->setCompany($obj->{'Company'});
		$this->setProfileImageURL($obj->{'ProfileImageURL'});
		$this->setAddress($obj->{'Address'});
		$this->setCity($obj->{'City'});
		$this->setStateCode($obj->{'StateCode'});
		$this->setState($obj->{'State'});
		$this->setCountryCode($obj->{'CountryCode'});
		$this->setCountry($obj->{'Country'});
		$this->setPostalCode($obj->{'PostalCode'});
		$this->setHomePhone($obj->{'Phone'});
		$this->setMobilePhone($obj->{'MobilePhone'});
		$this->setWorkPhone($obj->{'WorkPhone'});
		$this->setFax($obj->{'Fax'});
		$this->setOptIn((array)$obj->{'Opt-In'});
		$ListAct = array();
		$xx = (array)$obj->{'ActiveLists'};
		if($this->getFormat() == "xml") {
			if(count($obj->{'ActiveLists'}->{'List'})==1)
				$ListAct[] = $xx['List'];
			else
				$ListAct = $xx['List'];
		}
		else {
			$ListAct = $xx;
		}

		$this->setActiveLists($ListAct);
		
		$ListUnSub = array();
		$xx1 = (array)$obj->{'UnsubscribedLists'};
		if($this->getFormat() == "xml") {
			if(count($xx1['List'])==1)
				$ListUnSub[] = $xx1['List'];
			else
				$ListUnSub = $xx1['List'];
		}
		else {
			$ListUnSub = $xx1;
		}
		$this->setUnsubscribedLists($ListUnSub);

		$ListDel = array();
		$xx2 = (array)$obj->{'DeletedLists'};
		if($this->getFormat() == "xml") {
			if(count($xx2['List'])==1)
				$ListDel[] = $xx2['List'];
			else
				$ListDel = $xx2['List'];
		}
		else {
			$ListDel = $xx2;
		}
		$this->setDeletedLists($ListDel);

		$ListUConf = array();
		$xx3 = (array)$obj->{'UnconfirmedLists'};
		if($this->getFormat() == "xml") {
			if(count($xx3['List'])==1)
				$ListUConf[] = $xx3['List'];
			else
				$ListUConf = $xx3['List'];
		}
		else {
			$ListUConf = $xx3;
		}
		$this->setUnconfirmedLists($ListUConf);

		$ListBounce = array();
		$xx4 = (array)$obj->{'BouncedLists'};
		if($this->getFormat() == "xml") {
			if(count($xx4['List'])==1)
				$ListBounce[] = $xx4['List'];
			else
				$ListBounce = $xx4['List'];
		}
		else {
			$ListBounce = $xx4;
		}
		$this->setBouncedLists($ListBounce);
		return true;
	}
	
	/**
	 * @param string $Name Name
	 */
	public function setName($Name) {
		$br_point = stripos($Name, " ");
		$f_name = substr($Name, 0, $br_point);
		$l_name = substr($Name, $br_point+1);
		$this->setFirstName($f_name);
		$this->setLastName($l_name);
		$this->setFullName($Name);
	}

	/**
	 * @param string $Company Company
	 */
	public function setCompany($Company) {
		$this->Company = (string)$Company;
	}
	
	/**
	 * @param string $ProfileImageURL ProfileImageURL
	 */
	public function setProfileImageURL($ProfileImageURL) {
	  $this->ProfileImageURL = (string)$ProfileImageURL;
	}

	/**
	 * @param string $Address Address
	 */
	public function setAddress($Address) {
		$this->Address = (string)$Address;
	}

	/**
	 * @param string $City City
	 */
	public function setCity($City) {
	  $this->City = (string)$City;
	}

	/**
	 * @param string $StateCode StateCode
	 */
	public function setStateCode($StateCode) {
	  $this->StateCode = (string)$StateCode;
	}

	/**
	 * @param string $State State
	 */
	public function setState($State) {
	  $this->State = (string)$State;
	}

	/**
	 * @param string $CountryCode CountryCode
	 */
	public function setCountryCode($CountryCode) {
	  $this->CountryCode = (string)$CountryCode;
	}

	/**
	 * @param string $Country Country
	 */
	public function setCountry($Country) {
	  $this->Country = (string)$Country;
	}

	/**
	 * @param string $PostalCode PostalCode
	 */
	public function setPostalCode($PostalCode) {
	  $this->PostalCode = (string)$PostalCode;
	}
	
	/**
	 * @param string $HomePhone HomePhone
	 */
	public function setHomePhone($HomePhone) {
	  $this->HomePhone = (string)$HomePhone;
	}

	/**
	 * @param string $MobilePhone MobilePhone
	 */
	public function setMobilePhone($MobilePhone) {
	  $this->MobilePhone = (string)$MobilePhone;
	}

	/**
	 * @param string $WorkPhone WorkPhone
	 */
	public function setWorkPhone($WorkPhone) {
	  $this->WorkPhone = (string)$WorkPhone;
	}

	/**
	 * @param string $Fax Fax
	 */
	public function setFax($Fax) {
	  $this->Fax = (string)$Fax;
	}

	/**
	 * @param array $OptIn OptIn
	 */
	public function setOptIn($OptIn) {
	  $this->Opt_In = $OptIn;
	}

	/**
	 * @param array $ActiveLists ActiveLists
	 */
	public function setActiveLists($ActiveLists) {
	  $this->ActiveLists = $ActiveLists;
	}

	/**
	 * @param array $UnsubscribedLists UnsubscribedLists
	 */
	public function setUnsubscribedLists($UnsubscribedLists) {
	  $this->UnsubscribedLists = $UnsubscribedLists;
	}

	/**
	 * @param array $DeletedLists DeletedLists
	 */
	public function setDeletedLists($DeletedLists) {
	  $this->DeletedLists = $DeletedLists;
	}
	
	/**
	 * @param array $UnconfirmedLists UnconfirmedLists
	 */
	public function setUnconfirmedLists($UnconfirmedLists) {
	  $this->UnconfirmedLists = $UnconfirmedLists;
	}

	/**
	 * @param array $BouncedLists BouncedLists
	 */
	public function setBouncedLists($BouncedLists) {
	  $this->BouncedLists = $BouncedLists;
	}

	/**
	 * @param array $ListIDs ListIDs
	 */
	public function setListIDs($ListIDs) {
	  $this->ListIDs = $ListIDs;
	}

	/**
	 * @param boolean $Resubscribe Resubscribe
	 */
	public function setResubscribe($Resubscribe) {
	  $this->Resubscribe = $Resubscribe;
	}

	/**
	 * @param boolean $SendConfirmationEmail SendConfirmationEmail
	 */
	public function setSendConfirmationEmail($SendConfirmationEmail) {
	  $this->SendConfirmationEmail = $SendConfirmationEmail;
	}

	/**
	 * @param boolean $AddContactToAutoresponderCycle AddContactToAutoresponderCycle
	 */
	public function setAddContactToAutoresponderCycle($AddContactToAutoresponderCycle) {
	  $this->AddContactToAutoresponderCycle = $AddContactToAutoresponderCycle;
	}

	/**
	 * @return string
	 */
	public function getCompany() {
		return $this->Company;
	}

	/**
	 * @return string
	 */
	public function getProfileImageURL() {
		return $this->ProfileImageURL;
	}

	/**
	 * @return string
	 */
	public function getAddress() {
	  return $this->Address;
	}

	/**
	 * @return string
	 */
	public function getCity() {
	  return $this->City;
	}

	/**
	 * @return string
	 */
	public function getStateCode() {
	  return $this->StateCode;
	}

	/**
	 * @return string
	 */
	public function getState() {
	  return $this->State;
	}

	/**
	 * @return string
	 */
	public function getCountryCode() {
	  return $this->CountryCode;
	}

	/**
	 * @return string
	 */
	public function getCountry() {
	  return $this->Country;
	}

	/**
	 * @return string
	 */
	public function getPostalCode() {
	  return $this->PostalCode;
	}

	/**
	 * @return string
	 */
	public function getHomePhone() {
	  return $this->HomePhone;
	}
	
	/**
	 * @return string
	 */
	public function getMobilePhone() {
	  return $this->MobilePhone;
	}

	/**
	 * @return string
	 */
	public function getWorkPhone() {
	  return $this->WorkPhone;
	}

	/**
	 * @return string
	 */
	public function getFax() {
	  return $this->Fax;
	}

	/**
	 * @return array
	 */
	public function getOptIn() {
	  return $this->Opt_In;
	}
	
	/**
	 * @return array
	 */
	public function getActiveLists() {
	  return $this->ActiveLists;
	}

	/**
	 * @return array
	 */
	public function getUnsubscribedLists() {
	  return $this->UnsubscribedLists;
	}

	/**
	 * @return array
	 */
	public function getDeletedLists() {
	  return $this->DeletedLists;
	}

	/**
	 * @return array
	 */
	public function getUnconfirmedLists() {
	  return $this->UnconfirmedLists;
	}

	/**
	 * @return array
	 */
	public function getBouncedLists() {
	  return $this->BouncedLists;
	}

	/**
	 * @return array
	 */
	public function getListIDs() {
	  return $this->ListIDs;
	}

	/**
	 * @return boolean
	 */
	public function getResubscribe() {
	  return $this->Resubscribe;
	}

	/**
	 * @return boolean
	 */
	public function getSendConfirmationEmail() {
	  return $this->SendConfirmationEmail;
	}

	/**
	 * @return boolean
	 */
	public function getAddContactToAutoresponderCycle() {
	  return $this->AddContactToAutoresponderCycle;
	}

	/**
	 * @return array
	 */
	private function contactFieldArr() {
		return array("FirstName", "LastName", "Email", "Gender", "StateCode", "CountryCode", "Company", "ProfileImageURL", "Address", "City", "PostalCode", "HomePhone", "MobilePhone", "WorkPhone", "Fax", "CustomFields", "ListIDs", "Resubscribe", "SendConfirmationEmail", "AddContactToAutoresponderCycle");
	}
	
	/**
	 * @return array
	 * @throws Exception
	 */
	public function save() {
		if($this->getFormat()=="xml"){
			# Creating seperate tag of ListID
			$this->setListIDs(array('ListID'=>$this->getListIDs()));

			# Creating seperate tag of CustomField
			if(count($this->getCustomFields())>0){
				$cf_arr = array();
				foreach($this->getCustomFields() as $ind=>$data){
					$cf_arr['CustomField'][] = $data;
				}
				$this->setCustomFields($cf_arr);

			}
			$reqDataXML = $this->toXML();
		}
		else{
			$reqDataXML = $this->toJSON();
		}
		if ($this->getContactID() != null) {
			$new_xml = $this->postDataWithVerb("/contacts/" . $this->getContactID() . "/update.".$this->getFormat(), $reqDataXML, "PUT");
			$this->checkForErrors("Contact");
		}
		else {
			$new_xml = $this->postDataWithVerb("/contacts/create.".$this->getFormat(), $reqDataXML, "POST");
			$this->checkForErrors("Contact", 201);
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
	 * @return json data
	 */
	public function toJSON() { 
		$fields = $this->contactFieldArr();
		foreach($fields as $field) {
			if($field == "PostalCode") {
				$json["Zip"] = $this->$field;
			}
			else
				$json[$field] = $this->$field;
		}
		return json_encode($json);
	}
	
	
	/**
	 * @return xml data
	 */
	public function toXML() { 
		$xml[] = "<?xml version='1.0' encoding='utf-8'?><Contact>";
		$fields = $this->contactFieldArr();
		
		if ($this->getContactID() != null)
			$xml[] = '<ContactID>' . $this->getContactID() . '</ContactID>';
		
		foreach($fields as $field) {
			if($field == "CustomFields") {
				if( isset($this->CustomFields) && is_array( $this->CustomFields) ) {
					foreach( $this->CustomFields as $cf => $value ) {
						$xml_sub1=array();
						foreach( $value as $cf1 => $value1 ) {
							
							$xml_str='<CustomField>';
							foreach( $value1 as $kk => $vv ) {
								$xml_str .= '<' . str_replace( '_', '-', $kk) . '>' . $vv . '</' . str_replace( '_', '-', $kk) . '>';
							}
							$xml_str.='</CustomField>';
							$xml_sub1[] = $xml_str; 
						}
					}
					$xml_str = implode("\n", $xml_sub1);
					$xml[] = "<CustomFields>" . $xml_str . "</CustomFields>";
				}
			}
			else if($field == "ListIDs") {
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
			else if($field == "PostalCode") {
				$xml[] = "<Zip>" . $this->$field . "</Zip>";
			}
			else {
				$xml_field_name = str_replace("_", "-", $field);
				$xml[] = "<" . $xml_field_name . ">" . $this->$field . "</" . $xml_field_name . ">";
			}
		}
		$xml[] = "</Contact>";
		return implode("\n", $xml);
	}

}

class INinboxContactStatistics
{
	/**
	 * @var int
	 */
	public $TotalActiveContacts;
	
	/**
	 * @var int
	 */
	public $TotalInactiveContacts;
	
	/**
	 * @var int
	 */
	public $TotalTrashContacts;
	
	/**
	 * @var int
	 */
	public $TotalBlockedContacts;
	
	/**
	 * @var int
	 */
	public $TotalImportedContacts;
	
	/**
	 * @var int
	 */
	public $AllContacts;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj) {
		$this->setTotalActiveContacts($obj->{'TotalActiveContacts'});
		$this->setTotalInactiveContacts($obj->{'TotalInactiveContacts'});
		$this->setTotalTrashContacts($obj->{'TotalTrashContacts'});
		$this->setTotalBlockedContacts($obj->{'TotalBlockedContacts'});
		$this->setTotalImportedContacts($obj->{'TotalImportedContacts'});
		$this->setAllContacts($obj->{'AllContacts'});
		return true;
	}

	/**
	 * @param int $TotalActiveContacts TotalActiveContacts
	 */
	public function setTotalActiveContacts($TotalActiveContacts) {
		$this->TotalActiveContacts = (int)$TotalActiveContacts;
	}
	
	/**
	 * @param int $TotalInactiveContacts TotalInactiveContacts
	 */
	public function setTotalInactiveContacts($TotalInactiveContacts) {
		$this->TotalInactiveContacts = (int)$TotalInactiveContacts;
	}
	
	/**
	 * @param int $TotalTrashContacts TotalTrashContacts
	 */
	public function setTotalTrashContacts($TotalTrashContacts) {
		$this->TotalTrashContacts = (int)$TotalTrashContacts;
	}
	
	/**
	 * @param int $TotalBlockedContacts TotalBlockedContacts
	 */
	public function setTotalBlockedContacts($TotalBlockedContacts) {
		$this->TotalBlockedContacts = (int)$TotalBlockedContacts;
	}
	
	/**
	 * @param int $TotalImportedContacts TotalImportedContacts
	 */
	public function setTotalImportedContacts($TotalImportedContacts) {
		$this->TotalImportedContacts = (int)$TotalImportedContacts;
	}
	
	/**
	 * @param int $AllContacts AllContacts
	 */
	public function setAllContacts($AllContacts) {
		$this->AllContacts = (int)$AllContacts;
	}

	/**
	 * @return int
	 */
	public function getTotalActiveContacts() {
	  return $this->TotalActiveContacts;
	}
	
	/**
	 * @return int
	 */
	public function getTotalInactiveContacts() {
	  return $this->TotalInactiveContacts;
	}
	
	/**
	 * @return int
	 */
	public function getTotalTrashContacts() {
	  return $this->TotalTrashContacts;
	}
	
	/**
	 * @return int
	 */
	public function getTotalBlockedContacts() {
	  return $this->TotalBlockedContacts;
	}
	
	/**
	 * @return int
	 */
	public function getTotalImportedContacts() {
	  return $this->TotalImportedContacts;
	}
	
	/**
	 * @return int
	 */
	public function getAllContacts() {
	  return $this->AllContacts;
	}
}
?>