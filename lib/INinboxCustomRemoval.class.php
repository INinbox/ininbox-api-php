<?
class INinboxCustomRemoval
{
	public $Logo;
	public $URL;
	public $WebsiteURL;
	public $Description;
	public $UnsubscribePageURL;
	public $StayOnListPageURL;
	
	public function __construct($Logo = null, $URL = null, $WebsiteURL = null, $Description = null, $UnsubscribePageURL = null, $StayOnListPageURL =null)
	{
		$this->setLogo($Logo);
		$this->setURL($URL);
		$this->setWebsiteURL($WebsiteURL);
		$this->setDescription($Description);
		$this->setUnsubscribePageURL($UnsubscribePageURL);
		$this->setStayOnListPageURL($StayOnListPageURL);
	}
	
	public function toXML()
	{
		$xml  = "<ConfirmationSettings>\n";
		if ($this->getAccessModulesTitle() != null)
			$xml .= '<Logo>' . $this->getLogo() . "</Logo>\n";
		$xml .= '<URL>' . $this->getURL() . "</URL>\n";
		$xml .= '<WebsiteURL>' . $this->getWebsiteURL() . "</WebsiteURL>\n";
		$xml .= '<Description>' . $this->getDescription() . "</Description>\n";
		$xml .= '<UnsubscribePageURL>' . $this->getUnsubscribePageURL() . "</UnsubscribePageURL>\n";
		$xml .= '<StayOnListPageURL>' . $this->getStayOnListPageURL() . "</StayOnListPageURL>\n";
		$xml .= "</RemovalSettings>\n";
		return $xml;
	}
	
	public function __toString()
	{
		return $this->Description;
	}
		
	public function setLogo($Logo)
	{
	  $this->Logo = (string)$Logo;
	}

	public function getLogo()
	{
	  return $this->Logo;
	}

	public function setURL($URL)
	{
	  $this->URL = (string)$URL;
	}

	public function getURL()
	{
	  return $this->URL;
	}

	public function setWebsiteURL($WebsiteURL)
	{
	  $this->WebsiteURL = (string)$WebsiteURL;
	}

	public function getWebsiteURL()
	{
	  return $this->WebsiteURL;
	}

	public function setDescription($Description)
	{
	  $this->Description = (string)$Description;
	}

	public function getDescription()
	{
	  return $this->Description;
	}

	public function setUnsubscribePageURL($UnsubscribePageURL)
	{
	  $this->UnsubscribePageURL = (string)$UnsubscribePageURL;
	}

	public function getUnsubscribePageURL()
	{
	  return $this->UnsubscribePageURL;
	}

	public function setStayOnListPageURL($StayOnListPageURL)
	{
	  $this->StayOnListPageURL = (string)$StayOnListPageURL;
	}

	public function getStayOnListPageURL()
	{
	  return $this->StayOnListPageURL;
	}
}
?>