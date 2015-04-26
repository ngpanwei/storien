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

Logger::setPrefix(dirname(dirname(dirname(__FILE__)))) ;

class CohortVO {
	var $guid ;
	var $teamname ;
}

class CohortDAO {
	var $xmlFileDb ;
	public function __construct($xmlFileDb) {
		$this->xmlFileDb = $xmlFileDb ;
	}
	public function get($key) {
		return $this->xmlFileDb->get($key) ;
	}
	public function getContentElements($eventPath) {
		$eventElement = $this->get($eventPath) ;
		if($eventElement==null) {
			return null ;
		}
		$contentElements = $eventElement->getChildElements() ;
		return $contentElements;
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
		$vo = new CohortVO ;
		$vo->guid = $this->getProperty("guid") ;
		$vo->name = $this->getProperty("name") ;
		return $vo ;
	}
}

class CohortDb {
	var $dir ;
	var $db ;
	public function __construct() {
		$this->dir = dirname(dirname(dirname(dirname(__FILE__))))."/protected/data/cohorts" ;
		$this->db = new XMLDirDb($this->dir) ;
	}
	public function loadAll() {
		$this->db->loadAll("name") ;
	}
	public function getCohortByName($name) {
		$fileDb = $this->db->getFileDbByKey($name) ;
		if($fileDb==null) {
			return null ;
		}
		return new CohortDAO($fileDb) ;
	}
	public function getCohortById($guid) { // by guid
		$fileDb = $this->db->getFileByGuid($guid) ;
		if($fileDb==null) {
			return null ;
		}
		return new CohortDAO($fileDb) ;
	}
	public function createCohort($name) {
		$fileDb = $this->db->createFileDb("name",$name) ;
		return new Cohort($fileDb) ;
	}
}

?>
