<?php

require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/util/Logger.php");

Logger::setPrefix(dirname(dirname(dirname(__FILE__)))) ;

class Deployer {
	var $username ;
	var $password ;
	var $hostname ;
	var $connection ;
	
	public function __construct() {
		$this->hostname = "www.storien.com" ;
	}
	
	public function getCredentials() {
		$handle = fopen ("php://stdin","r");
		echo "username :" ;
		$this->username = fgets($handle);
		$this->username = trim($this->username) ;
		echo "password :" ;
		$this->password = fgets($handle);
		$this->password = trim($this->password);
		Logger::log(__FILE__,__LINE__,$this->username) ;
		Logger::log(__FILE__,__LINE__,$this->password) ;
	}
	public function deploy() {
		$this->getCredentials() ;
		$this->loginToServer() ;
		echo ftp_pwd($this->connection);
		$contents = ftp_nlist($this->connection, ".");
		foreach($contents as $content) {
			echo $content . PHP_EOL ;
		}
	}
	public function loginToServer() {
		$this->connection = ftp_connect($this->hostname);
		// login with username and password
		$login_result = ftp_login($this->connection, 
						$this->username, $this->password);
		
		// check connection
		if ((!$this->connection) || (!$login_result)) {
			echo "FTP connection has failed!" . PHP_EOL ;
			echo "Attempted to connect to ". $this->hostname  . PHP_EOL ;
			exit;
		} else {
			echo "Connected to " . $this->hostname . PHP_EOL ;
		}		
	}
	public function upload($filename) {
		
	}
}


$deployer = new Deployer() ;
$deployer->deploy() ;
