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
require_once(dirname(dirname(__FILE__))."/util/Logger.php");
require_once(dirname(dirname(__FILE__))."/util/Exception.php");
require_once(dirname(dirname(__FILE__))."/util/XMLFileDb.php");

class MailDAO {
	var $templatePath ;
	var $xmlFileDb ;
	public function __construct($templatePath) {
		$this->templatePath = $templatePath ;
	}
	public function load() {
		$filename = $this->getMailRoot() . "/" . $this->templatePath ;
		$this->xmlFileDb = new XMLFileDb($filename) ;
		$this->xmlFileDb->load() ;
	}
	public function getMailRoot() {
		$root = dirname(dirname(dirname(dirname(__FILE__))))."/protected/template" ;
		return $root ;
	}
	public function getText($key) {
		$text = $this->xmlFileDb->getKeyText($key) ;
		return trim($text);
	}
}

// Logger::log(__FILE__,__LINE__,"MailDAO") ;
// $mailDAO = new MailDAO("email/registration.xml") ;
// $mailDAO->load() ;
// Logger::log(__FILE__,__LINE__,$mailDAO->getText("subject")) ;
// Logger::log(__FILE__,__LINE__,$mailDAO->getText("body")) ;

