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

class ActivityClassVO {
	var $name ; // name of activity class name
	var $displayName ; // localized name to be displayed 
}

class ActivityVO {
	var $name  ; // name of activity to display on person's activity list
	var $page  ; // page link or hash to conduct the activity
	var $creationDate  ; // date when activity was created
	var $class ; // class of activity, which achieved results in a medal
	var $point ; // points awarded for conducting the activity
}

class Activity {
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
		$vo = new ActivityVO ;
		$vo->guid = $this->getProperty("guid") ;
		$vo->name = $this->getProperty("name") ;
		return $vo ;
	}
}

class ActivityDb {
	var $dir ;
	var $db ;
	var $userId ;
	var $format ;
	public function __construct($userId) {
		$this->format = 'Y-m-d-H-i-s' ;
		$this->userId = $userId ;
		$this->dir = dirname(dirname(dirname(dirname(__FILE__))))."/protected/data/activities/" . $userId ;
		$this->db = new XMLDirDb($this->dir) ;
	}
	public function init() {
		mkdir($this->dir) ;
	}
	public function loadAll() {
		$this->db->loadAll("activityName") ;
	}
	public function getActivityByName($name) {
		$fileDb = $this->db->getFileDbByKey($name) ;
		if($fileDb==null) {
			return null ;
		}
		return new Activity($fileDb) ;
	}
	public function getActivityById($guid) { // by guid
		$fileDb = $this->db->getFileByGuid($guid) ;
		if($fileDb==null) {
			return null ;
		}
		return new Activity($fileDb) ;
	}
	public function createActivity($name) {
		$fileDb = $this->db->createFileDb("activityName",$name) ;
		$dateStr = date($this->format) ;
		$filename = $this->dir . "/" . $dateStr . ".xml" ;
		$fileDb->setFilename($filename) ;
		$fileDb->setRoot("date",$dateStr) ;
		return new Activity($fileDb) ;
	}
}

Logger::log(__FILE__,__LINE__,"Activity") ;
$activityDb = new ActivityDb("550e6bb6d4a2c") ;
Logger::log(__FILE__,__LINE__,"Activity") ;
$activityDb->init() ;
Logger::log(__FILE__,__LINE__,"Activity") ;
$activity = $activityDb->createActivity("reading") ;
Logger::log(__FILE__,__LINE__,"Activity") ;
$activity->flush() ;
Logger::log(__FILE__,__LINE__,"Activity") ;

// $team = $teamDb->getTeamByName("storien1") ;
// if($team==null) {
//  	Logger::log(__FILE__,__LINE__,"create new team") ;
//   	$team = $teamDb->createTeam("storien1") ;	
//   	$team->flush() ;
// } else {
//  	Logger::log(__FILE__,__LINE__,"existing team") ;
// }
// Logger::log(__FILE__,__LINE__,"Teamify") ;
?>
