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
require_once("PersonifyAPI.php");
require_once("../util/ValueObject.php");
header('Content-type: text/html; charset=UTF-8');

class SignInHandler {
	var $email ;
	var $password ;
	public function __construct() {
	}
	function validateFormData() {
		if(isset($_POST["email"])==false)
			return false ;
		if(isset($_POST["password"])==false)
			return false ;
		return true ;
	}
	function getFormData() {
		if($this->validateFormData()==false) {
			return false ;
		}
		$this->email     = $_POST["email"] ;
		$this->password  = $_POST["password"] ;
		return true ;
	}
	function processForm() {
		if($this->getFormData()==false) {
			$vo = new ResultVO() ;
			$vo->resultCode = "failed" ;
			$vo->message = "提交的讯息不完整" ;
			echo json_encode($vo);
			return ;
		}
		$this->process() ;
	}	
	function getUserbyEmail() {
		$this->userDb = new UserDb() ;
		$this->userDb->loadAll() ;
		$user = $this->userDb->getUserByEmail($this->email) ;
		return $user ;
	}
	function process() {
		$user = $this->getUserbyEmail() ;
		$vo = new ResultVO() ;
		if($user==null) {
			$vo->resultCode = "failed" ;
			$vo->message = $this->email . "未曾被注册" ;
			echo json_encode($vo);
			return ;	
		}
		if($user->getProperty("password")!=$this->password) {
			$vo->resultCode = "failed" ;
			$vo->message = "密码不正确" ;
			echo json_encode($vo);
			return ;
		}
		$guid = $user->getProperty("guid") ;
		$vo->resultCode = "success" ;
		$vo->message = "登录成功！" ;
		$vo->data = $user->getVO() ;
		setcookie('userId', $guid, time() + (86400 * 30), "/");
		setcookie('username', $user->getProperty("username"), time() + (86400 * 30), "/");
		echo json_encode($vo);
	}
}

$handler = new SignInHandler() ;
$handler->processForm() ;

// $handler = new SignInHandler() ;
// $handler->email = "panwei@storien.com" ;
// $handler->password = "ABCDEFG" ;
// $handler->process() ;
?>
