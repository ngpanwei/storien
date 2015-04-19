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

class ActivityVO {
	var $title    ; // name of activity to display on person's activity list
	var $creation ; // date when activity was created
	var $kind     ; // kind of activity, which achieved results in a medal
	var $path     ; // path to find the content of this activity
	var $text     ; // text
	var $content  ; // html based content
	var $story    ; // user submitted content
	var $photo    ; // user submitted photo
	public function __construct() {
		$this->story = "" ;
		$this->photo = "" ;
	}
}

class ActivityDAO {
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
	public function setContentXML($xml) {
		$this->xmlFileDb->setXML("content",$xml) ;
	}
	public function flush() {
		$this->xmlFileDb->flush() ;
	}
	public function setText($id,$text) {
		$this->xmlFileDb->setKeyText($id,$text) ;
	}
	public function getText($id) {
		return $this->xmlFileDb->getKeyText($id) ;
	}
	public function getVO() {
		$vo = new ActivityVO ;
		$vo->guid = $this->getProperty("guid") ;
		$vo->title = $this->getProperty("title") ;
		$vo->creation = $this->getProperty("creation") ;
		$vo->path = $this->getProperty("path") ;
		$vo->kind = $this->getProperty("kind") ;
		$vo->text = $this->getProperty("text") ;
		$vo->content = $this->xmlFileDb->getXML("content") ;
		$vo->story = $this->getText("story") ;
		return $vo ;
	}
}

class ActivityDb {
	var $dir ;
	var $db ;
	var $userId ;
	var $format ;
	public function __construct($userId) {
		$this->format = 'Y-m-d-H-i-s-u' ;
		$this->userId = $userId ;
		$this->dir = dirname(dirname(dirname(dirname(__FILE__))))."/protected/data/activities/" . $userId ;
		$this->db = new XMLDirDb($this->dir) ;
	}
	public function init() {
		mkdir($this->dir) ;
		$this->db->deleteAll() ;
	}
	public function loadAll() {
		$this->db->loadAll("creation","ActivityDb::loadFilter") ;
	}
	public static function loadFilter($activityFileDb) {
		$keyValue = $activityFileDb->getRoot("deleted") ;
		if($keyValue!=null) {
			if($keyValue=="true")
				return false ;
			return true ; // continue processing
		}
		return true ;
	}
	public function getActivityByCreation($creation) {
		$filename = $this->dir . "/" . $creation . ".xml" ;
		$fileDb = new XMLFileDb($filename) ;
		$fileDb->load() ;
		$activityDAO = new ActivityDAO($fileDb) ;
		$activityCreation = $activityDAO->getProperty("creation") ;
		if($activityCreation!=$creation) {
			throw new Exception("cannot find activity ".$creation) ;
		}
		return $activityDAO;
	}
	public function getDateStr() {
		$t = microtime(true);
		$micro = sprintf("%06d",($t - floor($t)) * 1000000);
		$d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
		$dateStr = $d->format($this->format) ;
		return $dateStr ;
	}
	public function createActivity($name) {
		$fileDb = $this->db->createFileDb("title",$name) ;
		$dateStr = $this->getDateStr() ;
		$filename = $this->dir . "/" . $dateStr . ".xml" ;
		$fileDb->setFilename($filename) ;
		$fileDb->setRoot("creation",$dateStr) ;
		return new ActivityDAO($fileDb) ;
	}
	public function getAllActivities() {
		$fileDbArray = $this->db->getAllFiles() ;
		$ActivityVOList = array() ;
		foreach($fileDbArray as $fileDb) {
			$activityDAO = new ActivityDAO($fileDb) ;
			$activityVO = $activityDAO->getVO() ;
			array_push($ActivityVOList,$activityVO) ;
		}
		return $ActivityVOList ;
	}
	public function deleteActivity($activityGuid) {
		$activityDAO = $this->getActivityByCreation($activityGuid) ;
		$activityDAO->setProperty("deleted", "true") ;
		$activityDAO->flush() ;
		return $activityDAO ;
	}
}

?>
