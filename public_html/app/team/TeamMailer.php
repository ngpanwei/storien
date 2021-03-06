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
require_once (dirname(dirname(__FILE__))."/util/Logger.php");
require_once (dirname(dirname(__FILE__))."/util/Exception.php");
require_once (dirname(dirname(__FILE__))."/util/Mailer.php");


class TeamMailer {
	var $mailer;
	public function __construct() {
		$this->mailer = new PHPMailer();
	}
	public function getMailer() {
		$mailer = new PHPMailer();
		$mailer->CharSet = 'utf-8';
		$mailer->From = "happy@storien.com" ;
		return $mailer ;
	}
	function GetAbsoluteURLFolder()
	{
		$scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$scriptFolder .= $_SERVER['HTTP_HOST'] . dirname(dirname(dirname($_SERVER['REQUEST_URI'])));
		return $scriptFolder;
	}	
	public function sendTestMail() {
		$mailer = getMailer() ;
		$mailer->Subject = "测试邮件" ;
		$mailer->Body ="这是个测试邮件." ;
		if(!$mailer->Send()) {
			Logger::log(__FILE__,__LINE__,"发邮件失败.") ;
			return false;
		}
		Logger::log(__FILE__,__LINE__,"发邮件成功.") ;
		return true;		
	}
	public function sendRegistrationConfirmationEmail($userObject) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$mailer = $this->getMailer() ;
		$mailer->AddAddress($userObject->email);
		$mailer->Subject = "谢谢! 点击链接姐能够完成邮箱确认了" ;
		$username = $userobject->username ;
		$confirmCode = $userObject->verification ;
		$confirmUrl = $this->GetAbsoluteURLFolder().'/teamify.html#pgConfirmation?code='.$userObject->guid;
		
		$body .= "你好， $username," . PHP_EOL . PHP_EOL ;
		$body .= "感谢的支持. 只剩下一个步骤就注册完成了." . PHP_EOL ;
		$body .= "请点击此链接 $confirmUrl ". PHP_EOL ;
		$body .= PHP_EOL . PHP_EOL ;
		$body .= "谢谢". PHP_EOL ;
		$body .= " -- " . $mailer->From ;
				
		$mailer->Body = $body ;
		if(!$mailer->Send()) {
			Logger::log(__FILE__,__LINE__,"Registration Mailer Failed.") ;
			return false;
		}
		Logger::log(__FILE__,__LINE__,"Registration Succeeded.") ;
		return true;		
	}
}

?>