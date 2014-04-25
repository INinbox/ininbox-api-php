<?
class INinboxCustomConfirmation
{
	public $CustomConfirmationLogo;
	public $CustomConfirmationLogoURL;
	public $CustomConfirmationWebsiteURL;
	public $CustomConfirmationDescription;
	public $CustomConfirmationPageURL;
	
	public function __construct($CustomConfirmationLogo = null, $CustomConfirmationLogoURL = null, $CustomConfirmationWebsiteURL = null, $CustomConfirmationDescription = null, $CustomConfirmationPageURL = null)
	{
		$this->setCustomConfirmationLogo($CustomConfirmationLogo);
		$this->setCustomConfirmationLogoURL($CustomConfirmationLogoURL);
		$this->setCustomConfirmationWebsiteURL($CustomConfirmationWebsiteURL);
		$this->setCustomConfirmationDescription($CustomConfirmationDescription);
		$this->setCustomConfirmationPageURL($CustomConfirmationPageURL);
	}
	
	public function toXML()
	{
		$xml  = "<ConfirmationSettings>\n";
		if ($this->getAccessModulesTitle() != null)
			$xml .= '<CustomConfirmationLogo>' . $this->getCustomConfirmationLogo() . "</CustomConfirmationLogo>\n";
		$xml .= '<CustomConfirmationLogoURL>' . $this->getCustomConfirmationLogoURL() . "</CustomConfirmationLogoURL>\n";
		$xml .= '<CustomConfirmationWebsiteURL>' . $this->getCustomConfirmationWebsiteURL() . "</CustomConfirmationWebsiteURL>\n";
		$xml .= '<CustomConfirmationDescription>' . $this->getCustomConfirmationDescription() . "</CustomConfirmationDescription>\n";
		$xml .= '<CustomConfirmationPageURL>' . $this->getCustomConfirmationPageURL() . "</CustomConfirmationPageURL>\n";
		$xml .= "</ConfirmationSettings>\n";
		return $xml;
	}
	
	public function __toString()
	{
		return $this->CustomConfirmationDescription;
	}
		
	public function setCustomConfirmationLogo($CustomConfirmationLogo)
	{
	  $this->CustomConfirmationLogo = (string)$CustomConfirmationLogo;
	}

	public function getCustomConfirmationLogo()
	{
	  return $this->CustomConfirmationLogo;
	}

	public function setCustomConfirmationLogoURL($CustomConfirmationLogoURL)
	{
	  $this->CustomConfirmationLogoURL = (string)$CustomConfirmationLogoURL;
	}

	public function getCustomConfirmationLogoURL()
	{
	  return $this->CustomConfirmationLogoURL;
	}

	public function setCustomConfirmationWebsiteURL($CustomConfirmationWebsiteURL)
	{
	  $this->CustomConfirmationWebsiteURL = (string)$CustomConfirmationWebsiteURL;
	}

	public function getCustomConfirmationWebsiteURL()
	{
	  return $this->CustomConfirmationWebsiteURL;
	}

	public function setCustomConfirmationDescription($CustomConfirmationDescription)
	{
	  $this->CustomConfirmationDescription = (string)$CustomConfirmationDescription;
	}

	public function getCustomConfirmationDescription()
	{
	  return $this->CustomConfirmationDescription;
	}

	public function setCustomConfirmationPageURL($CustomConfirmationPageURL)
	{
	  $this->CustomConfirmationPageURL = (string)$CustomConfirmationPageURL;
	}

	public function getCustomConfirmationPageURL()
	{
	  return $this->CustomConfirmationPageURL;
	}
}
?>