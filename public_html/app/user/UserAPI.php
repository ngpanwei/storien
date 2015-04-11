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
require_once(dirname(dirname(__FILE__))."/util/ValueObject.php");
header('Content-type: text/html; charset=UTF-8');

Logger::enable(true) ;
Logger::setFilename(dirname(__FILE__)."/log.txt") ;
Logger::setMode("file") ;

class UserController {
    public $userDb;
    public $vo;
    
    public function __construct() {
       $this->userDb = new UserDb() ;
       $this->vo = new ResultVO() ;
    }
    
    public function register($registerRequest) {
		//
		return $userVO ;
	}
    
    /**
     * 登录操作
     * @param obj $signInRequest(email,password)    
     * @return obj
     */
	public function signIn($signInRequest) {
		$userDAO = $this->getUserbyEmail($signInRequest) ;
		if($userDAO==null) {
            $error = $signInRequest->email . "未曾被注册" ;
            throw new Exception($error);
		}
        
		if($userDAO->getProperty("password")!=$signInRequest->password) {
            $error = "密码不正确" ;
            throw new Exception($error);
		}

		$userVO = $userDAO->getVO() ;
		$userPhoto = new UserPhoto() ;
		$userPhoto->getPhotoPath($userDAO,$userVO) ;
        
		return $userVO ;
	}
    
    /**
     * 通过邮箱获取user对象
     * @param obj $request
     * @return obj
     */
    function getUserbyEmail($request) {	
		$this->userDb->loadAll() ;
		$user = $this->userDb->getUserByEmail($request->email) ;
		return $user ;
	}
}