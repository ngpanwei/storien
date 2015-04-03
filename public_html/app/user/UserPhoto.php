<?php
require_once(dirname(dirname(__FILE__))."/util/Logger.php");
require_once(dirname(dirname(__FILE__))."/util/Config.php");

class UserPhoto {
	public function __construct() {
	}
	public function generateDefaultPhoto(&$userDAO,&$userVO) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		// copy initial photo
		$sourcePath = Config::getTemplatePath() ."/photo/photo1.png" ;
		$photoPath = "users/" . $userVO->guid . ".png" ;
		$destPath = Config::getPublicPath()  . "/". $photoPath ;
		Logger::log(__FILE__,__LINE__,$sourcePath) ;
		Logger::log(__FILE__,__LINE__,$destPath) ;
		// add photo path to userDAO
		$userDAO->setProperty("photoPath",$photoPath) ;		
		// add photo path to userVO
		$userVO->photoPath = $photoPath ;
		copy($sourcePath,$destPath) ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
	}
}