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
require_once(dirname(dirname(__FILE__))."/content/ContentDAO.php");
require_once(dirname(dirname(__FILE__))."/content/ContentAPI.php");
require_once(dirname(dirname(__FILE__))."/activity/ActivityDAO.php");
require_once(dirname(dirname(__FILE__))."/user/UserDAO.php");
require_once(dirname(dirname(__FILE__))."/team/TeamAPI.php");

class ActivityAPI {
	public function getUserByEmail($email) {
		$userDb = new UserDb() ;
		$userDb->loadAll() ;
		$user = $userDb->getUserByEmail($email) ;
		return $user ;
	}
	public function handleEvent($email,$event) {
		$user = getUserByEmail($email) ;
		return handleUserEvent($user,$event) ;
	}
	public function createActivityFromContent($activityDb,$contentDAO) {
		$title = $contentDAO->getProperty("title") ;
		$kind = $contentDAO->getProperty("kind") ;
		$xml = $contentDAO->getContentXML() ;
		$text = $contentDAO->getContentText() ;
		$activityDAO = $activityDb->createActivity($title) ;
		$activityDAO->setProperty("path",$contentDAO->contentName) ;
		$activityDAO->setProperty("kind",$kind) ;
		$activityDAO->setProperty("text",$text) ;
		$activityDAO->setContentXML($xml) ;
		return $activityDAO ;
	}
	public function handleUserEvent($userDAO,$event) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$activityList = array() ;
		if($event!="register") {
            return $activityList ;
		}
 		$userId = $userDAO->getProperty("guid") ;
 		$activityDb = new ActivityDb($userId) ;
 		$activityDb->init() ;
 		$teamnames = $userDAO->getTeams() ;
 		$contentList = $this->generateContentList($teamnames,$event) ;
 		foreach($contentList as $contentDAO) {
 			$activityDAO = $this->createActivityFromContent($activityDb,$contentDAO) ;
 			$activityDAO->flush() ;
  			$activityVO = $activityDAO->getVO() ;
  			array_push($activityList,$activityVO) ;
 		}
 		return $activityList;
	}
	public function generateContentList($teamnames,$eventName) {
		$teamController = new TeamAPI() ;
		$contentAPI = new ContentAPI() ;
		$contentList = array() ;
		foreach($teamnames as $teamname) {
			$contentElements = $teamController->getTeamContentElements($teamname,$eventName) ;
			if($contentElements==null)
				continue ;
			foreach($contentElements as $contentElement) {
				$path = $contentElement->get("path") ;
				$contentDAO = $contentAPI->getContentFromPath($path) ;
				array_push($contentList,$contentDAO) ;
			}
		}
		return $contentList ;
	}
	public function createActivityFromContentPath($userGuid,$path) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$contentAPI = new ContentAPI() ;
		$contentDAO = $contentAPI->getContentFromPath($path) ;
		$activityDb = new ActivityDb($userGuid) ;
		$activityDAO = $this->createActivityFromContent($activityDb,$contentDAO) ;
		return $activityDAO ;
	}
	public function getActivityByCreation($userGuid,$creation) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
 		$activityDb = new ActivityDb($userGuid) ;
 		$activityDAO = $activityDb->getActivityByCreation($creation) ;
		if($activityDAO==null) {
			Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
			throw new Exception("Cannot find activity ".$creation) ;
		}
		return $activityDAO ;
	}
	public function getActivityVOList($userGuid) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$activityDb = new ActivityDb($userGuid) ;
		$activityDb->loadAll() ;
		$activityVOList = $activityDb->getAllActivities() ;
		return $activityVOList;
	}
	public function deleteActivity($userGuid,$activityGuid) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
 		$activityDb = new ActivityDb($userGuid) ;
 		$activityDAO = $activityDb->deleteActivity($activityGuid) ;
 		return $activityDAO ;
	}
}