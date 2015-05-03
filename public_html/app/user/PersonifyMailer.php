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
require_once (dirname(dirname(__FILE__))."/util/MailDAO.php");

class MailerAPI {
	var $mailer;
	public function __construct() {
		$this->mailer = new PHPMailer();
	}
	public function getMailer() {
		$mailer = new PHPMailer();
		$mailer->CharSet = 'utf-8';
		$mailer->From = "panwei@storien.com" ;
		return $mailer ;
	}
	function GetAbsoluteURLFolder() {
		$scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$scriptFolder .= $this->getHost() . dirname(dirname(dirname($this->getURI())));
		return $scriptFolder;
	}
	protected function getURI() {
		try {
			return $_SERVER['REQUEST_URI'] ;
		} catch (Exception $e) {
			return "localhost" ;
		}
	}
	protected function getHost() {
		try {
			return $_SERVER['HTTP_HOST'] ;
		} catch (Exception $e) {
			return "localhost" ;
		}
	}	
	public function sendTestMail() {
		$mailer = $this->getMailer() ;
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
		$mailDAO = new MailDAO("email/registration.xml") ;
		$mailDAO->load() ;
		$mailer->AddAddress($userObject->email);
		$mailer->Subject = $mailDAO->getText("subject") ;
		$username = $userObject->username ;
		$confirmUrl = $this->GetAbsoluteURLFolder().'experience.php#pgWelcomeConfirmation?code='.$userObject->guid;
		$body = $mailDAO->getText("body") ;
		$body = str_replace("@username",$username,$body) ;
		$body = str_replace("@confirmUrl",$confirmUrl,$body) ;
		$body = str_replace("@sender",$mailer->From,$body) ;
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