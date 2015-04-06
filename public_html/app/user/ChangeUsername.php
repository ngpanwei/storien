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
require_once(dirname(dirname(__FILE__))."/util/Logger.php");
require_once(dirname(dirname(__FILE__))."/util/Exception.php");
require_once(dirname(dirname(__FILE__))."/util/ValueObject.php");
header('Content-type: text/html; charset=UTF-8');

Logger::enable(true) ;
Logger::setFilename(dirname(__FILE__)."/log.txt") ;
Logger::setMode("file") ;

class ChangeUsernameHandler
{
	public $vo;
	public $userDb;
    public $userDAO;
    public $username;
    public $userGuid;

    public function __construct(){
		$this->vo = new ResultVO() ;
		$this->userDb = new UserDb() ;
	}

	/**
	 * 处理表单
	 * @return 
	 */
	public function processForm(){
        $getForm = $this->getFormData();
		if(!$getForm) {
			$this->vo->resultCode = "failed" ;
			$this->vo->message = "提交的讯息不完整" ;
			echo json_encode($this->vo);
			return ;
		}
		
		$this->process() ;
	}

	/**
	 * 获取表单数据
	 * @return bool 
	 */
	public function getFormData() {
        $validateForm = $this->validateFormData();
		if(!$validateForm) {
			return false ;
		}
		$this->username     = $_POST["username"] ;
        $this->userGuid     = $_POST["userGuid"] ;
		return true ;
	}

	/**
	 * 验证表单数据
	 * @return bool 
	 */
	public function validateFormData() {
		if(empty($_POST["username"])){
			return false ;
        }
        if(empty($_POST["userGuid"])){
			return false ;
        }
		return true ;
	}

	/**
	 * 通过guid获取用户
	 * @return obj
	 */
	public function getUserbyGuid() {
		$this->userDb->loadAll() ;
		$user = $this->userDb->getUserById($this->userGuid) ;
		return $user ;
	}

	/**
	 * 处理过程
	 */
	public function process() {
		$this->userDAO = $this->getUserbyGuid() ;
		if(empty($this->userDAO)) {			
			$this->vo->resultCode = "failed" ;
			$this->vo->message = $this->username . "未曾被注册" ;
			echo json_encode($this->vo);
			return ;	
		}
        
        $username = $this->userDAO->getProperty("username") ;
        if($username == $this->username) {
			$this->vo->resultCode = "failed" ;
			$this->vo->message = "用户名称已存在" ;
			echo json_encode($this->vo);
			return ;
		}
                
        //设置username
        $this->userDAO->setProperty("username",  $this->username);
        $userVO = $this->userDAO->getVO() ;
        $this->userDAO->flush() ;   //回存储数据到文件
        
        $this->vo->resultCode = "success" ;
        $this->vo->message = "个人名称修改成功" ;
        $this->vo->data = $userVO;
        echo json_encode($this->vo);

	}	
}

$handler = new ChangeUsernameHandler() ;
$handler->processForm() ;

?>
