<?php
require_once("CohortDAO.php");

class CohortAPI {
	public function getCohortByName($cohortName) {
		$cohortDb = new CohortDb() ;
		$cohortDb->loadAll() ;
		$cohortDAO = $cohortDb->getCohortByName($cohortName) ;
		if($cohortDAO==null) {
			return null ;
		}
		return $cohortDAO ;
	}
	public function getCohortEventContents($cohortName,$eventName) {
		$cohortDb = new CohortDb() ;
		$cohortDb->loadAll() ;
		$cohortDAO = $cohortDb->getCohortByName($cohortName) ;
		if($cohortDAO==null) {
			return null ;
		}
		$eventPath = "events." . $eventName ;
		$contentElements = $cohortDAO->getContentElements($eventPath) ;
		if($contentElements==null) {
			return null ;
		}
		return $contentElements ;
	}
}

// $cohortAPI = new CohortAPI() ;
// $contentElements = $cohortAPI->getCohortEventContents("BIPT-20150425", "register") ;
// var_dump($contentElements) ;

