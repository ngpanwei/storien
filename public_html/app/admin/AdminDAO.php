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

class AdminVO {
	var $teamname ;
	var $username ;
	var $email ;
	var $password ;
}
class AdminDAO {
	var $xmlFileDb ;
	public function __construct() {
	}
	public function load() {
		$filename = $this->getConfigPath() . "/admin.xml" ;
		$this->xmlFileDb = new XMLFileDb($filename) ;
		$this->xmlFileDb->load() ;
	}
	public function getConfigPath() {
		$root = dirname(dirname(dirname(dirname(__FILE__))))."/protected/config" ;
		return $root ;
	}
	public function getAdminList() {
		$adminElement = $this->xmlFileDb->get("administrators") ;
		$elements = $adminElement->getChildElements() ;
		$adminVOList = array() ;
		foreach($elements as $element) {
			$adminVO = new AdminVO() ;
			$adminVO->teamname = $element->get("teamname") ;
			$adminVO->username = $element->get("username") ;
			$adminVO->email = $element->get("email") ;
			$adminVO->password = $element->get("password") ;
			array_push($adminVOList,$adminVO) ;
		}
		return $adminVOList ;
	}
}
// try {
// 	$adminDAO = new AdminDAO() ;
// 	$adminDAO->load() ;
// 	$list = $adminDAO->getAdminList() ;
// 	var_dump($list) ;
// }  catch (Exception $e) {
// 	Logger::log(__FILE__,__LINE__,$e->getMessage()) ;
// }
?>
