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
require_once(dirname(dirname(__FILE__))."/util/FileUtil.php");
require_once(dirname(dirname(__FILE__))."/util/Config.php");
require_once(dirname(dirname(__FILE__))."/admin/AdminDAO.php");
require_once(dirname(dirname(__FILE__))."/user/UserAPI.php");

class AdminAPI {
	
	public function __construct() {
	}
	public function wipe() {
		$root = Config::getRootPath() ;
		FileUtil::clearFolderFiles($root."/protected/data/users",".xml") ;
		FileUtil::clearFolderFiles($root."/protected/data/activities",".xml") ;
		FileUtil::clearFolderFiles($root."/public_html/users",".jpg") ;
		FileUtil::clearFolderFiles($root."/public_html/activities",".jpg") ;
	}
	public function init() {
		$this->createAdministratorUsers() ;
	}
	public function createAdministratorUsers() {
		$adminDAO = new AdminDAO() ;
		$adminDAO->load() ;
		$administrators = $adminDAO->getAdminList() ;
		$userAPI = new UserAPI() ;
		foreach($administrators as $administrator) {
			$request = new RegistrationRequest() ;
			$request->teamname  = $administrator->teamname ;
			$request->username  = $administrator->username ;
			$request->email     = $administrator->email ;
			$request->password  = $administrator->password ;
			$request->cpassword = $administrator->password ;
			try {
				$userAPI->register($registerRequest) ;
			} catch (Exception $e) {
			}
		}
	}
}

Logger::log(__FILE__,__LINE__,"AdminAPI") ;
$adminAPI = new AdminAPI() ;
$adminAPI->wipe() ;

?>
