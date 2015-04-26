<?php
require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/util/Logger.php");
Logger::setPrefix(dirname(dirname(dirname(__FILE__)))) ;

require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/user/UserAPI.php");


$teams = array() ;
$teams['BIPT-01组'] = "fanzhuang@bipt.edu.cn" ;
$teams['BIPT-02组'] = "744298827@qq.com" ;
$teams['BIPT-03组'] = "hmf2014@sina.com" ;
$teams['BIPT-05组'] = "jzw0726@qq.com" ;
$teams['BIPT-06组'] = "zhaojialun12345@163.com" ;
$teams['BIPT-07组'] = "254582669@qq.com" ;
$teams['BIPT-08组'] = "973476942@qq.com" ;
$teams['BIPT-09组'] = "1454018358@qq.com" ;
$teams['BIPT-10组'] = "892372685@qq.com" ;
$teams['BIPT-11组'] = "chenyianne@126.com" ;
$teams['BIPT-12组'] = "653424884@qq.com" ;

class CohortTool {
	public function processUsers() {
		$userAPI = new UserAPI() ;
		$userVOList = $userAPI->getAllUsers() ;
		$index = 1 ;
		$count = 0 ;
		foreach($userVOList as $userVO) {
			$userEmail = $userVO->email ;
			$studentCode = $this->getStudentCode($userVO->email,"提供学好") ;
			if(strlen($studentCode)>1) {
				$count = $count + 1; 
			}
			echo $index . "--" .
				 $userVO->guid . "--" . 
				 $userVO->email . "--" .
				 $userVO->username . "--" .
				 $userVO->getFirstTeam() . "--" .
				 $studentCode . 
				 PHP_EOL ;
			$index = $index + 1 ;
		}
		Logger::log(__FILE__,__LINE__,$count) ;
	}
	public function getStudentCode($email) {
		$userAPI = new UserAPI() ;
		$userDAO = $userAPI->getUserbyEmail($email) ;
		$userGuid = $userDAO->getProperty("guid") ;
		$activityAPI = new ActivityAPI() ;
		$activityDAO = $activityAPI->getActivityByTitle($userGuid, "提供学好") ;
		if($activityDAO==null) {
			return "" ;
		}
		$studentCode = "" ;
		try {
			$studentCode = $activityDAO->getText("story") ;
		} catch (Exception $e) {
			$studenCode = "" ;
		}
		if(strlen($studentCode)>6) {
			$studentCode = substr($studentCode,-6) ;
		}
		$userDAO->xmlFileDb->setKeyText("BIPT-StudentCode",$studentCode) ;
		$userDAO->flush() ;
		return $studentCode ;
	}
}

try {
	Logger::log(__FILE__,__LINE__,"BIPT-Tools") ;
	$toolAPI = new CohortTool() ;
	Logger::log(__FILE__,__LINE__,"BIPT-Tools") ;
	$toolAPI->processUsers() ;
	Logger::log(__FILE__,__LINE__,"BIPT-Tools") ;
} catch (Exception $e) {
	Logger::log(__FILE__,__LINE__,$e->getMessage()) ;
}
