<?php
class INinboxPaging {
	/**
	 * @var int
	 */
	static public $TotalNumberOfRecords;

	/**
	 * @var int
	 */
	static public $RecordsOnThisPage;

	/**
	 * @var int
	 */
	static public $CurrentPageNumber;

	/**
	 * @var int
	 */
	static public $PageSize;

	/**
	 * @param int $tot_records TotalRecords
	 * @param int $curr_page_records RecordsOnCurrentPage
	 * @param int $page_num PageNumber
	 * @param int $page_size TotalPages
	 */
	public function setAllPagingParameter($tot_records, $curr_page_records, $page_num, $page_size){
		self::$TotalNumberOfRecords = $tot_records;
		self::$RecordsOnThisPage = $curr_page_records;
		self::$CurrentPageNumber = $page_num;
		self::$PageSize = $page_size;
	}

}

/**
 * The INinbox API Class
 *
 * Generated at: Fri, 18 Apr 2014 10:30:35
 */
class INinboxAPI extends INinboxPaging
{
	/**
	 * @var string
	 */
	public $token;

	/**
	 * @var string
	 */
	public $url;

	/**
	 * @var string
	 */
	protected $format;
	
	/**
	 * @var string
	 */
	protected $curl;

	/**
	 * @var boolean
	 */
	public $debug;

	/**
	 * @var string
	 */
	private $response;

	public function __construct()
	{
		$this->format = "xml";
		$this->url = "http://api.ininbox.com/v1";

		$this->curl = curl_init();
		curl_setopt($this->curl,CURLOPT_RETURNTRANSFER,true);

		curl_setopt($this->curl,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($this->curl,CURLOPT_SSL_VERIFYHOST,0);	
	}
	
	/**
	 * @param string $token APIKey
	 */
	public function setToken($token)
	{
		$this->token = $token;
		curl_setopt($this->curl,CURLOPT_USERPWD,$this->token.':x');
	}
	
	/**
	 * Invoke the INinbox API.
	 *
	 * @param string $path
	 * @param array $request_body The query/post data
	 * @param string $verb The http method (default 'get')
	 *
	 * @return mixed The decoded response object
	 */
	protected function postDataWithVerb($path, $request_body, $verb = "POST")
	{
		$url = $this->url . $path;
		if ($this->debug ==  true){
			print "postDataWithVerb $verb $url ============================\n";
			curl_setopt($this->curl, CURLOPT_VERBOSE, true);
		}

		curl_setopt($this->curl, CURLOPT_URL,$url);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $request_body);

		if($this->format == "xml")
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
		else if($this->format == "json")
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($this->curl, CURLOPT_USERPWD,$this->token.':x');
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);
						
		if ($verb != "POST")
			curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $verb);
		else
			curl_setopt($this->curl, CURLOPT_POST, true);
			
		$response = curl_exec($this->curl);

		if ($this->debug == true)
			print "Begin Request Body ============================\n" . $request_body . "End Request Body ==============================\n";
		
		curl_setopt($this->curl,CURLOPT_HTTPGET, true);
		$this->response=$response;
		return $response;
	}
	

	/**
	 * Invoke the INinbox API.
	 *
	 * @param string $path
	 *
	 * @return mixed The decoded response object
	 */
	protected function getURL($path)
	{
		$url = $this->url . $path;

		if ($this->debug ==  true){
			print "getURL $url ============================\n";
			curl_setopt($this->curl, CURLOPT_VERBOSE, true);
		}
		if($this->format == "xml")
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
		else if($this->format == "json")
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));

		curl_setopt($this->curl, CURLOPT_USERPWD,$this->token.':x');
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);

		if ($this->debug == true)
			curl_setopt($this->curl, CURLOPT_VERBOSE, true);

			
		curl_setopt($this->curl,CURLOPT_URL,$url);
		$response = curl_exec($this->curl);
		if ($this->debug == true)
			print "Response: =============\n" . $response . "============\n";
		
		$this->response=$response;
		return $response;
		
	}
	
	/**
	 * @return int
	 */
	protected function getLastReturnStatus()
	{
		return curl_getinfo($this->curl, CURLINFO_HTTP_CODE); 
	}
	
	/**
	 * @param string $type
	 * @param int $expected_status_codes
	 *
	 * @throws Exception
	 */
	protected function checkForErrors($type, $expected_status_codes = 200)
	{
		if (!is_array($expected_status_codes))
			$expected_status_codes = array($expected_status_codes);

		if (!in_array($this->getLastReturnStatus(), $expected_status_codes))
		{
			switch($this->getLastReturnStatus())
			{
				case 400: // Bad Request
					$error = $this->getError();
					throw new Exception($error->Message, (int)$error->Code);
					break;
				case 401: // Unauthorized
					$error = $this->getError();
					throw new Exception($error->Message, (int)$error->Code);
					break;
				case 403: // Forbidden
					$error = $this->getError();
					throw new Exception($error->Message, (int)$error->Code);
					break;
				case 404: // Not Found 
					throw new Exception("Page/Resouce not found");
					break;
				case 405: // Method Not Allowed 
					throw new Exception("Access denied to $type resource");
					break;
				case 500: // Internal Server Error 
					throw new Exception("Internal Server Error");
					break;
				case 507:
					throw new Exception("Cannot create $type: Insufficient storage in your INinbox Account");
					break;
				default:
						throw new Exception("API for $type returned Status Code: " . $this->getLastReturnStatus() . " Expected Code: " . implode(",", $expected_status_codes));
						break;
			}				
		}
	}
	
	/**
	 * @return mixed The decoded error object
	 */
	public function getError() {
		if($this->getFormat()=="xml"){
			$error_object = simplexml_load_string($this->response);
		}
		else{
			$error_object = json_decode($this->response);
		}
		return $error_object;
	}
	
	/**
	 * @return string
	 */
	public function getFormat()	{
		return $this->format;
	}
	
	/**
	 * @param string $format Format(xml/json)
	 * @throws Exception
	 */
	public function setFormat($format)	{
		$format = strtolower(trim($format));
		if(!($format=='xml' || $format=='json')){
			throw new Exception("Request Type must be either xml or json");
		}
		$this->format=$format; // xml or json
	}
}
?>