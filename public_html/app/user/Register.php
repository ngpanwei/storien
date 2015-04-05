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
require_once("UserDAO.php");
require_once("UserPhoto.php");
require_once("PersonifyMailer.php");
require_once(dirname(dirname(__FILE__))."/activity/ActivityController.php");
require_once(dirname(dirname(__FILE__))."/util/ValueObject.php");
header('Content-type: text/html; charset=UTF-8');

Logger::enable(true) ;
Logger::setFilename(dirname(__FILE__)."/log.txt") ;
Logger::setMode("file") ;

Logger::log(__FILE__,__LINE__,"register") ;

class RegistrationRequest {
	var $teamname ;
	var $username ;
	var $email ;
	var $password ;
	var $cpassword ;
}

class RegistrationHandler {
	public function __construct() {
	}
	function validateFormData() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		if(isset($_POST["teamname"])==false)
			return false ;
		if(isset($_POST["username"])==false)
			return false ;
		if(isset($_POST["email"])==false)
			return false ;
		if(isset($_POST["password"])==false)
			return false ;
		if(isset($_POST["cpassword"])==false)
			return false ;
		return true ;		
	}
	function getFormData() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		if($this->validateFormData()==false) {
			return null ;
		}
		$request = new RegistrationRequest() ;
		$request->teamname  = $_POST["teamname"] ;
		$request->username  = $_POST["username"] ;
		$request->email     = $_POST["email"] ;
		$request->password  = $_POST["password"] ;
		$request->cpassword = $_POST["cpassword"] ;
		return $request ;
	}
	function getUserbyEmail($request) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$this->userDb = new UserDb() ;
		$this->userDb->loadAll() ;
		$user = $this->userDb->getUserByEmail($request->email) ;
		return $user ;
	}
	function registerNewUser($request) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userDAO = $this->userDb->createUser($request->email) ;
		$userDAO->setProperty("username", $request->username) ;
		$userDAO->setProperty("password", $request->password) ;
		$userDAO->setList("teams",array($request->teamname)) ;
		return $userDAO ;
	}
	function processForm() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$request = $this->getFormData() ;
		if($request==null) {
			$vo = new ResultVO() ;
			$vo->resultCode = "failed" ;
			$vo->message = "提交的讯息不完整" ;
			echo json_encode($vo);
			return ;
		}
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$this->process($request) ;
	}
	function process($request) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$vo = $this->handle($request) ;
		$json = json_encode($vo) ;
		Logger::log(__FILE__,__LINE__,$json) ;
		echo $json;
	}
	function handle($request) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userDAO = $this->getUserbyEmail($request) ;
		if($userDAO!=null) {
			$vo = new ResultVO() ;
			$vo->resultCode = "failed" ;
			$vo->message = $this->email . "已经被注册了" ;
			return $vo;	
		}
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userDAO = $this->registerNewUser($request) ;
		$userVO = $userDAO->getVO() ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userPhoto = new UserPhoto() ;
		$userPhoto->generateDefaultPhoto($userDAO,$userVO) ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$activityController = new ActivityAPI() ;
		$activityController->handleUserEvent($userDAO, "register") ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$mailerAPI = new MailerAPI() ;
		$mailerAPI->sendRegistrationConfirmationEmail($userVO) ;
		$userDAO->flush() ;
		
		$vo = new ResultVO() ;
		$vo->resultCode = "success" ;
		$vo->message = "注册成功！" ;
		$vo->data = $userVO ;
		return $vo;
	}
}

$handler = new RegistrationHandler() ;
try {
	$handler->processForm() ;
} catch(Exception $e) {
	$vo->resultCode = "failed" ;
	$vo->message = $e->getMessage() ;
}
// $handler = new RegistrationHandler() ;
// $handler->teamname = "storien" ;
// $handler->username = "黄邦伟" ;
// $handler->email = "panwei@storien.com" ;
// $handler->password = "ABCDEFG" ;
// $handler->cpassword = "ABCDEFG" ;
// $handler->process() ;

?>
