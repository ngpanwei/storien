<?php
require_once("TeamDAO.php");

class TeamController {
	public function getTeamContentElements($teamname,$eventName) {
		$teamDb = new TeamDb() ;
		$teamDb->loadAll() ;
		$teamDAO = $teamDb->getTeamByName($teamname) ;
		if($teamDAO==null) {
			return null ;
		}
		$eventPath = "events." . $eventName ;
		$contentElements = $teamDAO->getContentElements($eventPath) ;
		if($contentElements==null) {
			return null ;
		}
		return $contentElements ;
	}
	
}