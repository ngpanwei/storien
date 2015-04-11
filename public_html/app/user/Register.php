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
require_once("UserAPI.php");
require_once("UserPhoto.php");
require_once("PersonifyMailer.php");
require_once(dirname(dirname(__FILE__))."/activity/ActivityAPI.php");
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
    public $vo;
}

class RegistrationHandler {
	public function __construct() {
        $this->vo = new ResultVO() ;
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
        
        try {
            $handler = new UserController() ;
            $userVO = $handler->register($request) ;
            
            $this->vo->resultCode = "success" ;
            $this->vo->message = "注册成功！" ;
            $this->vo->data = $userVO ;
            echo json_encode($this->vo);
            
        } catch(Exception $e) {
            $this->vo->resultCode = "failed" ;
            $this->vo->message = $e->getMessage() ;
            echo json_encode($this->vo);
        }        
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
