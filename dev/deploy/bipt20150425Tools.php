<?php
require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/util/Logger.php");
require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/util/CSV.php");
require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/user/UserAPI.php");

Logger::log(__FILE__,__LINE__,"cohort") ;
Logger::setPrefix(dirname(dirname(dirname(__FILE__)))) ;

class BIPT20150425DAO {
	var $filename ;
	var $xmlFileDb ;
	public function __construct($filename) {
		$this->filename = $filename ;
		$this->xmlFileDb = new XMLFileDb($filename) ;
		$this->xmlFileDb->load() ;
	}
	public function extractTable($id,$primaryKey) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$string = $this->xmlFileDb->getKeyText($id) ;
		return new CSVDataTable($string,$primaryKey) ;
	}
	
}
class CohortTool {
	public function processUsers() {
		$userAPI = new UserAPI() ;
		$userVOList = $userAPI->getAllUsers() ;
		$index = 1 ;
		$studentCount = 0 ; $gitCount = 0 ;
		foreach($userVOList as $userVO) {
			$userEmail = $userVO->email ;
			$studentCode = $this->getActivityData($userVO->email,"提供学好") ;
			$gitRepo = $this->getActivityData($userVO->email,"git repository链接") ;
			if(strlen($studentCode)>1) {
				$studentCount = $studentCount + 1; 
			}
			if(strlen($gitRepo)>1) {
				$gitCount = $gitCount + 1; 
			}
			echo $index . "--" .
				 $userVO->guid . "--" . 
				 $userVO->email . "--" .
				 $userVO->username . "--" .
				 $userVO->getFirstTeam() . "--" .
				 $studentCode . "--" .
				 $gitRepo .
				 PHP_EOL ;
			$index = $index + 1 ;
		}
		Logger::log(__FILE__,__LINE__,$studentCount) ;
		Logger::log(__FILE__,__LINE__,$gitCount) ;
	}
	public function getActivityData($email,$key) {
		$userAPI = new UserAPI() ;
		$userDAO = $userAPI->getUserbyEmail($email) ;
		$userGuid = $userDAO->getProperty("guid") ;
		$activityAPI = new ActivityAPI() ;
		$activityDAO = $activityAPI->getActivityByTitle($userGuid, $key) ;
		if($activityDAO==null) {
			return "" ;
		}
		$studentCode = "" ;
		try {
			$studentCode = $activityDAO->getText("story") ;
		} catch (Exception $e) {
			$studenCode = "" ;
		}
		if($key=="提供学好") {
			if(strlen($studentCode)>6) {
				$studentCode = substr($studentCode,-6) ;
			}
			$userDAO->xmlFileDb->setKeyText("BIPT-StudentCode",$studentCode) ;
		}
		if($key=="git repository链接") {
			$userDAO->xmlFileDb->setKeyText("BIPT-StudentGit",$studentCode) ;
		}
		$userDAO->flush() ;
		return $studentCode ;
	}
}

try {
// 	$toolAPI = new CohortTool() ;
	Logger::log(__FILE__,__LINE__,"BIPT") ;
	$filename = dirname(__FILE__) . "/bipt20150425.xml" ;
	$biptDAO = new BIPT20150425DAO($filename) ;
	$dataTable = $biptDAO->extractTable("students", "Email") ;
	$rows = $dataTable->rows ;
	$filename = dirname(__FILE__) . "/bipt20150425.xml" ;
	foreach($rows as $key=>$row) {
		Logger::log(__FILE__,__LINE__,$key) ;
		foreach($row as $index=>$col) {
			echo "[" .$dataTable->keys[$index] . "==". trim($col) . "]"  ;
		}
		echo PHP_EOL ;
	}
	
	// $toolAPI->processUsers() ;
} catch (Exception $e) {
	Logger::log(__FILE__,__LINE__,$e->getMessage()) ;
}
