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
require_once("TeamifyAPI.php");
require_once("../util/ValueObject.php");
header('Content-type: text/html; charset=UTF-8');

Logger::enable(true) ;
Logger::setFilename(dirname(__FILE__)."/log.txt") ;
Logger::setMode("file") ;

Logger::log(__FILE__,__LINE__,"register") ;
class RegistrationHandler {
	var $teamname ;
	var $username ;
	var $email ;
	var $password ;
	var $cpassword ;
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
			return false ;
		}
		$this->teamname  = $_POST["teamname"] ;
		$this->username  = $_POST["username"] ;
		$this->email     = $_POST["email"] ;
		$this->password  = $_POST["password"] ;
		$this->cpassword = $_POST["cpassword"] ;
		return true ;
	}
	function getUserbyEmail() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$this->userDb = new UserDb() ;
		$this->userDb->loadAll() ;
		$user = $this->userDb->getUserByEmail($this->email) ;
		return $user ;
	}
	function registerNewUser() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$user = $this->userDb->createUser($this->email) ;
		$user->setProperty("username", $this->username) ;
		$user->setProperty("password", $this->password) ;
		$user->setList("teams",array($this->teamname)) ;
		$user->flush() ;
		return $user ;
	}
	function processForm() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		if($this->getFormData()==false) {
			$vo = new ResultVO() ;
			$vo->resultCode = "failed" ;
			$vo->message = "提交的讯息不完整" ;
			echo json_encode($vo);
			return ;
		}
		$this->process() ;
	}
	function process() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$user = $this->getUserbyEmail() ;
		if($user!=null) {
			$vo = new ResultVO() ;
			$vo->resultCode = "failed" ;
			$vo->message = $this->email . "已经被注册了" ;
			echo json_encode($vo);
			return ;	
		}
		$user = $this->registerNewUser() ;
		$guid = $user->getProperty("guid") ;
		$vo = new ResultVO() ;
		$vo->resultCode = "success" ;
		$vo->message = "注册成功！" ;
		$vo->data = $user->getVO() ;
		setcookie('userId', $guid, time() + (86400 * 30), "/");
		setcookie('username', $user->getProperty("username"), time() + (86400 * 30), "/");
		echo json_encode($vo);		
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
