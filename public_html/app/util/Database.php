<?php
/** 
  *  Copyright (c) 2015  Ng Pan Wei
  *  
  *  Permission is hereby granted, free of charge, to any person 
  *  obtaining a copy of this software and associated documentation files 
  *  (the "Software"), to deal in the Software without restriction, 
  *  including without limitation the rights to use, copy, modify, merge, 
  *  publish, distribute, sublicense, and/or sell copies of the Software, 
  *  and to permit persons to whom the Software is furnished to do so, 
  *  subject to the following conditions: 
  *  
  *  The above copyright notice and this permission notice shall be 
  *  included in all copies or substantial portions of the Software. 
  *  
  *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
  *  EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
  *  MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
  *  NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS 
  *  BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN 
  *  ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN 
  *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
  *  SOFTWARE. 
  */ 
require_once ("Logger.php");


class Database {
	var $host;
	var $username;
	var $pwd;
	var $database;
	var $tablename;
	var $pdo;
	
	function InitDB($host, $uname, $pwd, $database) {
		$this->hostname = $host;
		$this->username = $uname;
		$this->password = $pwd;
		$this->database = $database;
		$this->pdo = null;
	}
	function connect() {
		$this->pdo = new PDO ( "mysql:host=$this->hostname;dbname=$this->database", 
							$this->username, $this->password,
							array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$this->pdo->exec("set names utf8") ;
		$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
		return $this->pdo;
	}
}
?>