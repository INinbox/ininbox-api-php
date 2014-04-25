<?php

class INinboxAccessModule
{
	public $AccessModulesTitle;
	public $Assigned;
	
	public function __construct($AccessModulesTitle = null, $Assigned = null)
	{
		$this->setAccessModulesTitle($AccessModulesTitle);
		$this->setAssigned($Assigned);
	}
	
	public function toXML()
	{
		$xml  = "<AccessModule>\n";
		if ($this->getAccessModulesTitle() != null)
			$xml .= '<AccessModulesTitle type="integer">' . $this->getAccessModulesTitle() . "</AccessModulesTitle>\n";
		$xml .= '<Assigned>' . $this->getAssigned() . "</Assigned>\n";
		$xml .= "</AccessModule>\n";
		return $xml;
	}
	
	public function __toString()
	{
		return $this->Assigned;
	}
		
	public function setAssigned($Assigned)
	{
	  $this->Assigned = (string)$Assigned;
	}

	public function getAssigned()
	{
	  return $this->Assigned;
	}

	public function setAccessModulesTitle($AccessModulesTitle)
	{
	  $this->AccessModulesTitle = (string)$AccessModulesTitle;
	}

	public function getAccessModulesTitle()
	{
	  return $this->AccessModulesTitle;
	}
}

?>