<?php
class INinboxContactCustomFields extends INinboxAPI
{
	/**
	 * @var int
	 */
	public $CustomFieldID;

	/**
	 * @var string
	 */
	public $FieldName;

	/**
	 * @var string
	 */
	public $FieldDataType;

	/**
	 * @var string
	 */
	public $FieldUsage;

	/**
	 * @var array
	 */
	public $FieldOptions; //=array()

	/**
	 * @var boolean
	 */
	public $VisibleInWebform;

	/**
	 * @var boolean
	 */
	public $KeepExistingOptions;
	
	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setCustomFieldID($obj->{'CustomFieldID'});
		$this->setFieldName($obj->{'FieldName'});
		$this->setFieldDataType($obj->{'FieldDataType'});
		$this->setFieldUsage($obj->{'FieldUsage'});
		$FOptions = array();

		if($this->getFormat() == "xml") {
			if($obj->{'FieldDataType'} == "text" || $obj->{'FieldDataType'} == "textarea") {
				$xx = (array)$obj->{'FieldOptions'};
			}
			else {
				$xx = (array)$obj->{'FieldOptions'};
				$xx = $xx['FieldOption']; 
			}
		}
		else {
			$xx = (array)$obj->{'FieldOptions'};
		}
		if(!is_array($xx) && count($xx)==1)
			$FOptions[] = $xx;
		else
			$FOptions = $xx;
		//echo "<pre>";print_r($FOptions);//exit;

		$this->setFieldOption($FOptions);
		if($this->getFormat() == "xml") 
			$this->setVisibleInWebform($obj->{'VisibleInWebform'}=="True"?true:false);
		else
			$this->setVisibleInWebform($obj->{'VisibleInWebform'});
		return true;
	}
	
	/**
	 * @param int $CustomFieldID CustomFieldID
	 */
	public function setCustomFieldID($CustomFieldID) {
		$this->CustomFieldID = (int)$CustomFieldID;
	}
	
	/**
	 * @param string $FieldName FieldName
	 */
	public function setFieldName($FieldName) {
		$this->FieldName = (string)$FieldName;
	}
	
	/**
	 * @param string $FieldDataType FieldDataType
	 */
	public function setFieldDataType($FieldDataType) {
		$this->FieldDataType = (string)$FieldDataType;
	}
	
	/**
	 * @param string $FieldUsage FieldUsage
	 */
	public function setFieldUsage($FieldUsage) {
		$this->FieldUsage = (string)$FieldUsage;
	}

	/**
	 * @param boolean $VisibleInWebform VisibleInWebform
	 */
	public function setVisibleInWebform($VisibleInWebform) {
		$this->VisibleInWebform = (bool)$VisibleInWebform;
	}

	/**
	 * @param boolean $KeepExistingOptions KeepExistingOptions
	 */
	public function setKeepExistingOptions($KeepExistingOptions) {
		$this->KeepExistingOptions = (bool)$KeepExistingOptions;
	}

	/**
	 * @param array $FieldOptions FieldOptions
	 */
	public function setFieldOption($FieldOptions) {
		$this->FieldOptions = $FieldOptions;
	}
	
	/**
	 * @return int
	 */
	public function getCustomFieldID() {
		return $this->CustomFieldID;
	}
	
	/**
	 * @return string
	 */
	public function getFieldName() {
		return $this->FieldName;
	}

	/**
	 * @return string
	 */
	public function getFieldDataType() {
		return $this->FieldDataType;
	}

	/**
	 * @return string
	 */
	public function getFieldUsage() {
		return $this->FieldUsage;
	}

	/**
	 * @return booelan
	 */
	public function getVisibleInWebform() {
		return $this->VisibleInWebform;
	}

	/**
	 * @return booelan
	 */
	public function getKeepExistingOptions() {
		return $this->KeepExistingOptions;
	}

	/**
	 * @return array
	 */
	public function getFieldOption() {
		return $this->FieldOptions;
	}
	
	/**
	 * @return boolean
	 * @throws Exception
	 */
	public function saveFieldOption() {
		if ($this->getCustomFieldID() != null) { // Update Customfield Case
			if(count($this->getFieldOption())==0) {
				throw new INinboxValidationException('1002', array("error"=>"Missing value for Field Options."));
			}
			else {
				$KeepExistingOptions = strtolower($this->getKeepExistingOptions());
				if(isset($KeepExistingOptions) && !($KeepExistingOptions==1 || $KeepExistingOptions=="" || $KeepExistingOptions=="true" || $KeepExistingOptions=="false")) {
					throw new INinboxValidationException('1012', array("error"=>"Invalid value for Keep Exising Value. It should be either True or False."));
				}
				else {
					if($this->getFormat() == "xml")
						$reqDataXML = $this->toXML();
					else
						$reqDataXML = $this->toJSON();
					$new_xml = $this->postDataWithVerb("/customfields/" . $this->getCustomFieldID() . "/options.".$this->getFormat(), $reqDataXML, "PUT");
					$this->checkForErrors("Customfields");
					return true;
				}
			}
		}
		else {
			throw new INinboxValidationException('1002', array("error"=>'Missing value for CustomField ID'));
		}
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function get($param = array()) {
		return $this->parseAll("/customfields/list.".$this->getFormat(), $param);
	}
	
	/**
	 * @param string $url
	 * @param array $param Paging, Search, Sorting Paramters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function parseAll($url, $param=array()) {
		$return = array();
		$final_url = $url;
		if(count($param)){
			$sep = (strstr($url, "?")?"&":"?");
			$final_url = $url.$sep.http_build_query($param);
		}
		$result = $this->getUrl($final_url);
		$this->checkForErrors("CustomField");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_customfields = $object->Results->CustomField;

		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_customfields = $object->Results;
		}
		$this->setAllPagingParameter($object->TotalNumberOfRecords, $object->RecordsOnThisPage, $object->PageNumber, $object->PageSize);

		foreach($all_customfields as $customfield)
		{
			$list_obj = new INinboxContactCustomFields($this);
			$list_obj->loadFromObject($customfield);
			$return[] = $list_obj;
		}
			
		return $return;
	}

	/**
	 * @return boolean
	 * @throws Exception
	 */
	public function save() {
		if ($this->getCustomFieldID() != null) { // Update Customfield Case
			if(trim($this->getFieldName())=="") {
				throw new INinboxValidationException('1002', array("error"=>'Missing value for Field Name'));
			}
			else {
				$VisibleInWebform = strtolower($this->getVisibleInWebform());
				if(isset($VisibleInWebform) && !($VisibleInWebform==1 || $VisibleInWebform=="" || $VisibleInWebform=="true" || $VisibleInWebform=="false")) {
					throw new INinboxValidationException('1012', array("error"=>printf("Invalid value for Visible in Webform. It should be either True or False.")));
				}
				else {
					if($this->getFormat() == "xml")
						$reqDataXML = $this->toXML();
					else
						$reqDataXML = $this->toJSON();
					$new_xml = $this->postDataWithVerb("/customfields/" . $this->getCustomFieldID() . "/update.".$this->getFormat(), $reqDataXML, "PUT");
					$this->checkForErrors("Customfields");
					return true;
				}
			}
		}
		else { // Add Customfield Case
			if(trim($this->getFieldName())=="") {
				throw new INinboxValidationException('1002', array("error"=>'Missing value for Field Name'));
			}
			else if(trim($this->getFieldDataType())=="") {
				throw new INinboxValidationException('1002', array("error"=>'Missing value for Field Data type'));
			}
			else if(!in_array($this->getFieldDataType(), array("text", "textarea", "checkbox", "radio", "multi_select", "single_select"))) {
				throw new INinboxValidationException('3504', array("error"=>'Invalid field data type. Valid data types are text, textarea, radio, checkbox, single_select and multi_select.'));
			}
			else if(count($this->getFieldOption())==0) {
				throw new INinboxValidationException('1002', array("error"=>"Missing value for Field Options."));
			}
			else if(($this->getFieldDataType()=='text' || $this->getFieldDataType()=='textarea') && count($this->getFieldOption())>1) {
				//echo printf("You can not add multiple fields in %s", $this->getFieldDataType());
				throw new INinboxValidationException('3603', array("error"=>printf("Custom Field having data type %s can hold only single value.", $this->getFieldDataType())));
			}
			else {
				$VisibleInWebform = strtolower($this->getVisibleInWebform());
				if(isset($VisibleInWebform) && !($VisibleInWebform==1 || $VisibleInWebform=="" || $VisibleInWebform=="true" || $VisibleInWebform=="false")) {
					throw new INinboxValidationException('1012', "Invalid value for Visible in Webform. It should be either True or False.");
				}
				else {
					if($this->getFormat() == "xml")
						$reqDataXML = $this->toXML();
					else
						$reqDataXML = $this->toJSON();
					$new_xml = $this->postDataWithVerb("/customfields/create.".$this->getFormat(), $reqDataXML, "POST");
					$this->checkForErrors("Customfields", 201);
					return true;
				}
			}
		}
	}
	
	/**
	 * @return xml data
	 */
	public function toXML() {
		$xml[] = "<?xml version='1.0' encoding='utf-8'?><CustomField>";
		$fields = array("FieldName", "FieldDataType", "FieldOptions", "VisibleInWebform", "KeepExistingOptions");
		
		if ($this->getCustomFieldID() != null)
			$xml[] = '<CustomFieldID>' . $this->getCustomFieldID() . '</CustomFieldID>';
		
		foreach($fields as $field) {
			$xml_field_name = str_replace("_", "-", $field);
			if($field == "FieldOptions") {
				$fieldoption = "<FieldOption>".implode("</FieldOption><FieldOption>", $this->getFieldOption())."</FieldOption>";
				$xml[] = "<" . $xml_field_name . ">" . $fieldoption . "</" . $xml_field_name . ">";
			
			}
			else 
				$xml[] = "<" . $xml_field_name . ">" . $this->$field . "</" . $xml_field_name . ">";

	
		}
		$xml[] = "</CustomField>";
		return implode("\n", $xml);
	}
	
	/**
	 * @return json data
	 */
	public function toJSON() {
		$fields = array("FieldName", "FieldDataType", "FieldOptions", "VisibleInWebform", "KeepExistingOptions");
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
		$result = $this->postDataWithVerb("/customfields/" . $this->getCustomFieldID() . "/delete." . $this->getFormat(), "", "DELETE");
		$this->checkForErrors("Customfield", 200);
		if($this->getFormat() == "xml") {
			$object = (array)simplexml_load_string($result);
		}
		else if($this->getFormat() == "json") {
			$object = (array)json_decode($result);
		}
		return $object;
	}
}
?>