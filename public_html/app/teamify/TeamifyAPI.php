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
require_once("../util/Logger.php");
require_once("../util/Exception.php");
require_once("../util/XMLFileDb.php");
require_once("../util/Guid.php");

Logger::setPrefix(dirname(dirname(dirname(__FILE__)))) ;

class TeamVO {
	var $guid ;
	var $teamname ;
}
class Team {
	var $xmlFileDb ;
	public function __construct($xmlFileDb) {
		$this->xmlFileDb = $xmlFileDb ;
	}
	public function setRecord($key,$keyValues) {
	    $this->xmlFileDb->set($key,$keyValues) ;
	}
	public function get($key) {
		return $this->xmlFileDb->get($key) ;
	}
	public function setList($key,$values) {
		$this->xmlFileDb->setList($key,$values) ;
	}
	public function setProperty($key,$value) {
	    $this->xmlFileDb->setRoot($key,$value) ;
	}
	public function getProperty($key) {
		return $this->xmlFileDb->getRoot($key) ;
	}
	public function flush() {
		$this->xmlFileDb->flush() ;
	}
	public function getVO() {
		$vo = new UserVO ;
		$vo->guid = $this->getProperty("guid") ;
		$vo->username = $this->getProperty("username") ;
		$vo->email = $this->getProperty("email") ;
		$vo->teams = $this->xmlFileDb->getList("teams") ;
		return $vo ;
	}
}
class TeamDb {
	var $dir ;
	var $db ;
	public function __construct() {
		$this->dir = dirname(dirname(dirname(dirname(__FILE__))))."/protected/data/users" ;
		$this->db = new XMLDirDb($this->dir) ;
	}
	public function loadAll() {
		$this->db->loadAll("email") ;
	}
	public function getUserByEmail($email) {
		$fileDb = $this->db->getFileDbByKey($email) ;
		if($fileDb==null) {
			return null ;
		}
		return new User($fileDb) ;
	}
	public function getUserById($guid) { // by guid
		$fileDb = $this->db->getFileByGuid($guid) ;
		if($fileDb==null) {
			return null ;
		}
		return new User($fileDb) ;
	}
	public function createUser($email) {
		$fileDb = $this->db->createFileDb("email",$email) ;
		return new User($fileDb) ;
	}
}

// Logger::log(__FILE__,__LINE__,"Teamify") ;
// $userDb = new UserDb() ;
// Logger::log(__FILE__,__LINE__,"Teamify") ;
// $userDb->loadAll() ;
// $user = $userDb->getUserByEmail("panwei@storien.com") ;
// if($user==null) {
// 	Logger::log(__FILE__,__LINE__,"create new user") ;
// 	$user = $userDb->createUser("panwei@storien.com") ;	
// } else {
// 	Logger::log(__FILE__,__LINE__,"existing user") ;
// }
// Logger::log(__FILE__,__LINE__,"Teamify") ;
?>
