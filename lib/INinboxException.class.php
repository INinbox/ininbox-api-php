<?php
class INinboxException extends Exception {}

class INinboxConnectionException extends INinboxException {}

class INinboxValidationException extends INinboxException {
	var $errors;
	var $http_code;
	
	public function INinboxValidationException($http_code, $error) {
		$this->http_code = $http_code;

		$message = '';
		$this->errors = array();
		foreach ($error as $key=>$value) {
			if ($key == 'error') {
				$this->errors[] = $value;
				$message .= $value . ' ';
			}
		}
		echo $message;exit;
		parent::__construct($message, intval($http_code));
	}
}

class INinboxNotFoundException extends INinboxException {
	var $errors;
	var $http_code;
	
	public function INinboxNotFoundException($http_code, $error) {
		$this->http_code = $http_code;		

		$message = '';
		$this->errors = array();
		foreach ($error as $key=>$value) {
			if ($key == 'error') {
				$this->errors[] = $value;
				$message .= $value . ' ';
			}
		}

		parent::__construct($message, intval($http_code));
	}	
}

class INinboxError
{
	var $field;
	var $message;
}
?>