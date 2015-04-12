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
require_once(dirname(dirname(__FILE__))."/activity/ActivityAPI.php");
header('Content-type: text/html; charset=UTF-8');

Logger::enable(true) ;
Logger::setFilename(dirname(__FILE__)."/log.txt") ;
Logger::setMode("file") ;

class RegistrationRequest {
	var $teamname ;
	var $username ;
	var $email ;
	var $password ;
	var $cpassword ;
}

class SignInRequest {
	var $email ;
	var $password ;
}

class UserAPI {
    public $userDb;
    public $userPhoto;

    public function __construct() {
       $this->userDb = new UserDb() ;
       $this->userPhoto = new UserPhoto() ;
    }
    
    /**
     * 注册操作
     * @param obj $registerRequest
     * @return obj
     * @throws Exception
     */
    public function register($registerRequest) {
        //通过邮箱获取user对象
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userDAO = $this->getUserbyEmail($registerRequest) ;
		if($userDAO!=null) {
            $error = $registerRequest->email . "已经被注册了" ;
            throw new Exception($error);
		}
        
        //注册新用户
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userDAO = $this->createNewUser($registerRequest) ;
		$userVO = $userDAO->getVO() ;
        
        //生成默认图像
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$this->userPhoto->generateDefaultPhoto($userDAO,$userVO) ;
        
        //活动
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$activityController = new ActivityAPI() ;
		$activityController->handleUserEvent($userDAO, "register") ;
        
        //发送确认邮件
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$mailerAPI = new MailerAPI() ;
		$mailerAPI->sendRegistrationConfirmationEmail($userVO) ;
		$userDAO->flush() ;
		
		return $userVO;
	}
    
    /**
     * 注册新用户
     * @param obj $request
     * @return obj
     */
    function createNewUser($request) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userDAO = $this->userDb->createUser($request->email) ;
		$userDAO->setProperty("username", $request->username) ;
		$userDAO->setProperty("password", $request->password) ;
		$userDAO->setList("teams",array($request->teamname)) ;
		return $userDAO ;
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
		$this->userPhoto->getPhotoPath($userDAO,$userVO) ;
        
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