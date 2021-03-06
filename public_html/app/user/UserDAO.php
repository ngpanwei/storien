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
require_once(dirname(dirname(__FILE__))."/util/Logger.php");
require_once(dirname(dirname(__FILE__))."/util/Exception.php");
require_once(dirname(dirname(__FILE__))."/util/XMLFileDb.php");
require_once(dirname(dirname(__FILE__))."/util/Guid.php");

class UserVO {
	var $guid ;
	var $username ;
	var $email ;
	var $teams ;
	var $creation;
    var $photoPath;
    var $password;
    var $confirmation;
	public function getFirstTeam() {
		foreach($this->teams as $teamname) {
			return $teamname ;			
		} 
		return null ;
	}
}

class User extends BaseFileDAO {

	public function getVO() {
		$vo = new UserVO ;
		$vo->guid = $this->getProperty("guid") ;
		$vo->username = $this->getProperty("username") ;
		$vo->email = $this->getProperty("email") ;
		$vo->chohorts = $this->getCohorts() ;
		$vo->teams = $this->getTeams() ;
		$vo->creation = $this->getProperty("creation") ;
        $vo->confirmation = $this->getProperty("confirmation") ;
        $vo->photoPath = $this->getProperty("photoPath") ;
        $vo->password = $this->getProperty("password") ;
		return $vo ;
	}
	public function getTeams() {
		return $this->xmlFileDb->getList("teams") ;
	}
	public function getCohorts() {
		return $this->xmlFileDb->getList("cohorts") ;
	}
}

class UserDb {
	var $dir ;
	var $db ;
	var $format ;
	public function __construct() {
		$this->format = 'Y-m-d-H-i-s' ;
		$this->dir = dirname(dirname(dirname(dirname(__FILE__))))."/protected/data/users" ;
		$this->db = new XMLDirDb($this->dir) ;
	}
	public function loadAll() {
		$this->db->loadAll("email") ;
	}
	public function getAllUsers($option) {
		$userVOList = array() ;
		$fileArray = $this->db->getAllFiles() ;
		foreach($fileArray as $fileDb) {
			$userDAO = new User($fileDb) ;
			$userVO = $userDAO->getVO() ;
			array_push($userVOList,$userVO) ;
		}
		return $userVOList ;
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
		Logger::log(__FILE__,__LINE__,$email) ;
		$fileDb = $this->db->createFileDb("email",$email) ;
		$dateStr = date($this->format) ;
		$fileDb->setRoot("creation", $dateStr) ;
		return new User($fileDb) ;
	}
}

?>
