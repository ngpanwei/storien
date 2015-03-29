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
require_once(dirname(dirname(__FILE__))."/util/XMLFileDb.php");
header('Content-type: text/html; charset=UTF-8');

/**
* 忘记密码
* @author xucheng
* @version 2015.03.17
*/
class ResetPasswordHandler
{
	public $email ;
	public $password ;
	public $cpassword; 
	public $vo;
	public $userDb;

	public function __construct(){
		$this->vo = new ResultVO() ;
		$this->userDb = new UserDb() ;
		$this->xmlDirDb = new XMLDirDb() ;
	}

	/**
	 * 处理表单
	 * @return 
	 */
	public function processForm(){
		if($this->getFormData()==false) {
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
		if($this->validateFormData()==false) {
			return false ;
		}
		$this->email     = $_POST["email"] ;
		$this->password  = $_POST["password"] ;
		$this->cpassword  = $_POST["cpassword"] ;
		return true ;
	}

	/**
	 * 验证表单数据
	 * @return bool 
	 */
	public function validateFormData() {
		if(isset($_POST["email"])==false)
			return false ;
		if(isset($_POST["password"])==false)
			return false ;
		if(isset($_POST["cpassword"])==false)
			return false ;
		return true ;
	}

	/**
	 * 通过邮箱获取用户
	 * @return obj
	 */
	public function getUserbyEmail() {
		$this->userDb->loadAll() ;
		$user = $this->userDb->getUserByEmail($this->email) ;
		return $user ;
	}

	/**
	 * 处理过程
	 * @return [type] [description]
	 */
	public function process() {
		$user = $this->getUserbyEmail() ;
		// var_dump($user);
		if(empty($user)) {			
			$this->vo->resultCode = "failed" ;
			$this->vo->message = $this->email . "未曾被注册" ;
			echo json_encode($this->vo);
			return ;	
		}

		// if($user->getProperty("password")!=$this->password) {
		// 	$this->vo->resultCode = "failed" ;
		// 	$this->vo->message = "密码不正确" ;
		// 	echo json_encode($this->vo);
		// 	return ;
		// }

		if($this->password != $this->cpassword){
			$this->vo->resultCode = "failed" ;
			$this->vo->message = "两次输入密码不一致" ;
			echo json_encode($this->vo);
			return ;
		}

		$guid = $user->getProperty("guid") ;
		echo $guid;

		$this->xmlDirDb->loadAll();
		$xmlFile = $this->xmlDirDb->getFileByGuid($guid);
		echo "<pre>";
		print_r($xmlFile);

		$new_password = $xmlFile->setKeyValues("password",$this->cpassword);
		var_dump($new_password);die;
		// echo $this->cpassword;die;
		$new_password = $this->user->setRecord("password",$this->cpassword) ;	//更新密码
		var_dump($new_password);die;
		$this->vo->resultCode = "success" ;
		$this->vo->message = "修改密码成功！" ;
		$this->vo->data = $user->getVO() ;
// 		setcookie('userId', $guid, time() + (86400 * 30), "/");
// 		setcookie('username', $user->getProperty("username"), time() + (86400 * 30), "/");
		echo json_encode($this->vo);
	}	

}

// 类实例化
$handler = new ResetPasswordHandler() ;
$handler->processForm() ;

// $vo = new ResultVO() ;
// var_dump($_POST);
// $vo->resultCode = "failed" ;
// $vo->message = "The forget password form you submit is incomplete" ;
// echo json_encode($vo);

?>