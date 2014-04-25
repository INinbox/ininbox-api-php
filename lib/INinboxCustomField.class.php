<?php
class INinboxPredefinedSystemField extends INinboxAPI
{
	/**
	 * @var string
	 */
	public $FieldLabel;
	
	/**
	 * @var string
	 */
	public $FieldName;

	/**
	 * @param object $obj Data Object
	 *
	 * @return boolean
	 */
	public function loadFromObject($obj)
	{
		$this->setFieldLabel($obj->{'FieldLabel'});
		$this->setFieldName($obj->{'FieldName'});
		return true;
	}

	/**
	 * @param string $FieldLabel FieldLabel
	 */
	public function setFieldLabel($FieldLabel)
	{
	  $this->FieldLabel = (string)$FieldLabel;
	}
	
	/**
	 * @return string
	 */
	public function getFieldLabel()
	{
	  return $this->FieldLabel;
	}
	
	/**
	 * @param string $FieldName FieldName
	 */
	public function setFieldName($FieldName)
	{
	  $this->FieldName = (string)$FieldName;
	}

	/**
	 * @return string
	 */
	public function getFieldName()
	{
	  return $this->FieldName;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function get() {
		$return = array();
		$result = $this->getUrl("/customfields/systemdefined.".$this->getFormat());
		$this->checkForErrors("CustomField");
		if($this->getFormat() == "xml") {
			$object = simplexml_load_string($result);
			$all_fields = $object->SystemField;
		}
		else if($this->getFormat() == "json") {
			$object = json_decode($result);
			$all_fields = $object->SystemFields;
		}
		foreach($all_fields as $fields)
		{
			$field_obj = new INinboxPredefinedSystemField();
			$field_obj->setFormat($this->getFormat());
			$field_obj->loadFromObject($fields); # Response data
			$return[] = $field_obj;
		}
		return $return;
	}
}
?>