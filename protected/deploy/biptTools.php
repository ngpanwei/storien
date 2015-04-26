<?php
require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/util/Logger.php");
require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/user/UserAPI.php");

Logger::setPrefix(dirname(dirname(dirname(__FILE__)))) ;


$api = new UserAPI() ;
$userVOList = $api->getAllUsers() ;
$index = 1 ;
foreach($userVOList as $userVO) {
	if($userVO->email!="fanzhuang@bipt.edu.cn")
		continue ;
	echo $index . "--" . $userVO->email . "--" .
		 $userVO->username . "--" .
		 $userVO->getFirstTeam() . PHP_EOL ;
	$index = $index + 1 ;
}
