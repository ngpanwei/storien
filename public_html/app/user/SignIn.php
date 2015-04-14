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
require_once(dirname(dirname(__FILE__))."/util/ValueObject.php");
header('Content-type: text/html; charset=UTF-8');

Logger::enable(true) ;
Logger::setFilename(dirname(__FILE__)."/log.txt") ;
Logger::setMode("file") ;

class SignInHandler {
	public function __construct() {
	}
	function validateFormData() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		if(isset($_POST["email"])==false)
			return false ;
		if(isset($_POST["password"])==false)
			return false ;
		return true ;
	}
	function getFormData() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		if($this->validateFormData()==false) {
			return null ;
		}
		$request = new SignInRequest() ;
		$request->email     = $_POST["email"] ;
		$request->password  = $_POST["password"] ;
		return $request ;
	}
	function processForm() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$request = $this->getFormData() ;
        $vo = new ResultVO() ;
		if($request==null) {
			$vo->resultCode = "failed" ;
			$vo->message = "提交的讯息不完整" ;
			echo json_encode($vo);
			return ;
		}
        
        try {
			Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
        		$handler = new UserAPI() ;
            $userVO = $handler->signIn($request) ;
            Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
            
            $vo->resultCode = "success" ;
            $vo->message = "登录成功！" ;
            $vo->data = $userVO ;
            echo json_encode($vo);
            
        } catch(Exception $e) {
            $vo->resultCode = "failed" ;
            $vo->message = $e->getMessage() ;
            echo json_encode($vo);
        }  
	}
}

$handler = new SignInHandler() ;
$handler->processForm() ;

?>
