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
require_once("TeamDAO.php");
require_once (dirname(dirname(__FILE__))."/user/UserAPI.php");
require_once (dirname(dirname(__FILE__))."/cohort/CohortAPI.php");

class TeamAPI {
	public function getTeam($teamName) {
		
	}
	public function createTeam($teamName,$cohortName) {
		$cohortAPI = new CohortAPI() ;
		$cohortDAO = $cohortAPI->getCohortByName($cohortName) ;
		if($cohortDAO==null) {
			throw new Exception("期届 ".$cohortName." 不存在。") ;
		}
		$teamDb = new TeamDb() ;
		$teamDb->loadAll() ;
		$teamDAO = $teamDb->getTeamByName($teamName) ;
		if($teamDAO==null) {
			$teamDAO = $teamDb->createTeam($teamName) ;
		}
		$teamDAO->setProperty("cohort",$cohortName) ;
		$teamDAO->flush() ;
		return $teamDAO ;
	}
	public function addUserEmailToTeam($userEmail,$teamname) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userDb = new UserDb() ;
		$userDb->loadAll() ;
		$userDAO = $userDb->getUserByEmail($userEmail) ;
		if($userDAO==null) {
			throw new Exception("用户 ".$userEmail." 不存在。") ;
		}
		$teamDb = new TeamDb() ;
		$teamDb->loadAll() ;
		$teamDAO = $teamDb->getTeamByName($teamname) ;
		if($teamDAO==null) {
			throw new Exception("团队 ".$teamname." 不存在。") ;
		}
		$userGuid = $userDAO->getProperty("guid") ;
		$username = $userDAO->getProperty("username") ;
		$teamGuid = $teamDAO->getProperty("guid") ;
		$teamName = $teamDAO->getProperty("teamname") ;
		$teamDAO->addMember($userGuid,$username) ;
		$userDAO->flush() ;
		$teamDAO->flush() ;
	}
	public function setEmailRole($teamName,$userEmail,$role) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$teamDb = new TeamDb() ;
		$teamDb->loadAll() ;
		$teamDAO = $teamDb->getTeamByName($teamName) ;
		if($teamDAO==null) {
			throw new Exception("团队 ".$teamName." 不存在。") ;
		}
		$userDb = new UserDb() ;
		$userDb->loadAll() ;
		$userDAO = $userDb->getUserByEmail($userEmail) ;
		if($userDAO==null) {
			throw new Exception("用户 ".$userEmail." 不存在。") ;
		}
		$userGuid = $userDAO->getProperty("guid") ;
		$member = $teamDAO->getMember($userGuid) ;
		$member->set("roles",$role) ;
		$teamDAO->flush() ;
	}
}

// try {
// 	Logger::log(__FILE__,__LINE__,"teamAPI") ;
// 	$teamAPI = new TeamAPI() ;
// 	$teamDAO = $teamAPI->createTeam("BIPT-01组","BIPT-20150425") ;
// 	$teamAPI->addUserEmailToTeam("fanzhuang@bipt.edu.cn","BIPT-01组") ;
// 	$teamAPI->setEmailRole("BIPT-01组","fanzhuang@bipt.edu.cn","leader") ;
// 	Logger::log(__FILE__,__LINE__,"teamAPI") ;
// } catch (Exception $e) {
// 	Logger::log(__FILE__,__LINE__,$e->getMessage()) ;
// }

