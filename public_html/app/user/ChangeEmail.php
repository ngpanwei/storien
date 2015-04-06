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

class ChangeEmailHandler
{
	public $email ;
	public $vo;
	public $userDb;
    public $userDAO;
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
		$this->email     = $_POST["email"] ;
		$this->userGuid     = $_POST["userGuid"] ;
		return true ;
	}

	/**
	 * 验证表单数据
	 * @return bool 
	 */
	public function validateFormData() {
		if(empty($_POST["email"])){
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
			$this->vo->message = $this->email . "未曾被注册" ;
			echo json_encode($this->vo);
			return ;	
		}
        
        $email = $this->userDAO->getProperty("email") ;
        if($email == $this->email) {
			$this->vo->resultCode = "failed" ;
			$this->vo->message = "用户邮箱已存在" ;
			echo json_encode($this->vo);
			return ;
		}
                
        //设置email
        $this->userDAO->setProperty("email",  $this->email);
        $userVO = $this->userDAO->getVO() ;
        
        $this->vo->resultCode = "success" ;
        $this->vo->message = "邮箱修改成功" ;
        $this->vo->data = $userVO;
        echo json_encode($this->vo);
	}	
}

$handler = new ChangeEmailHandler() ;
$handler->processForm() ;

?>
