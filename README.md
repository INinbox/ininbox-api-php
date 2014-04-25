#PHP INinbox API Library
----------------------------------
# Table of Contents #
1. Requirements
2. Usage
3. Examples

# 1. Requirements: #
### PHP with cURL ###

### API key ###
You can generate your API key from the Account Settings >> API page in your INinbox member account.


# 2. Usage: #
### Setup API Key ###
Open config.php and set your API key

### Load the API Client library ###
	require_once("config.php");
	require_once("lib/INinboxAPI.class.php");
	require_once("lib/INinboxList.class.php");

### Instantiate Class ###
	$list_obj = new INinboxList();

### Set Request Format (optional) ###
	$list_obj->setFormat("xml");

* format: The format of your request. Either 'xml' (default), 'json'

### Set API ###
	$list_obj->setToken($api_key);

### Do a Get request to INinbox ###
	$list = $list_obj->get($param);

* $param: It is an associate array which contains extra Get parameters. eg. Page, PageSize, OrderField, OrderDirection.


## Documentation ##
See INinbox's [API Docs](http://www.ininbox.com/api) for more information about the available method calls.


# 3. Examples: #
### Get All List Example ###

	try {
		$list_obj = new INinboxList();
		$list_obj->debug = false;
		$list_obj->setFormat("xml");
		$list_obj->setToken($api_key);
		$param = array();
		$param['Page']=1;
		$param['PageSize']=100;
		$param['Status']='active'; // 
		$list = $list_obj->get($param);
		print_r($list);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}

### Details of a List Example ###

	$list_obj = new INinboxListDetail();
	$list_obj->debug = false;
	$list_obj->setFormat("xml");
	$list_obj->setToken($api_key);
	try {
		// Set the ListId to get details
		$list_id = 160;
		$list = $list_obj->findById($list_id);
		print_r($list);
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}

### Create a List Example ###
Add your authentication tokens to make this example work:

	$list_obj = new INinboxListDetail();
	$list_obj->debug = true;
	$list_obj->setFormat("xml");
	$list_obj->setToken($api_key);
	try {
		$list_obj->setTitle("Testing Started JSON");
		$list_obj->setConfirmedOptIn(true);
		$list_obj->setCompanyName("TST");
		$list_obj->setAddress("AAA XXX");
		$list_obj->setCity("CT");
		$list_obj->setStateCode("GU");
		$list_obj->setCountryCode("IN");
		$list_obj->setZip("12QW5S");
		$list_obj->setPostalAddress("Address: ##user_address##
	City: ##user_city##
	State: ##user_state##
	Postal Code: ##user_zip##
	Country: ##user_country##");
		$res = $list_obj->save();
		echo $res['Message'];exit;
	}
	catch (Exception $e)
	{
		echo "Error Message: ".$e->getMessage();
		echo "<br />Error Code: ".$e->getCode();
	}



## More information about available API methods
INinbox API documentation:  http://www.ininbox.com/api