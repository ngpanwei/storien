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
require_once(dirname(dirname(__FILE__))."/util/ValueObject.php");
require_once(dirname(dirname(__FILE__))."/activity/ActivityAPI.php");

Logger::enable(true) ;
Logger::setFilename(dirname(__FILE__)."/log.txt") ;
Logger::setMode("file") ;

header('Content-type: text/html; charset=UTF-8');

class ContentRequest {
	var $userGuid ;
	var $activityVO ;
}

class ContentResponse {
	var $html ;
}
class FetchContentHandler {
	public function __construct() {
	}
	function validateFormData() {
		if(isset($_POST["userGuid"])==false)
			return false ;
		if(isset($_POST["activity"])==false)
			return false ;
		return true ;
	}
	function getRequest() {
		if($this->validateFormData()==false) {
			return null ;
		}
		$request = new ContentRequest() ;
		$request->userGuid  = $_POST["userGuid"] ;
		$request->activity  = $_POST["activity"] ;
		return $request ;
	}
	function processForm() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$request = $this->getRequest() ;
		if($request==null) {
			$vo = new ResultVO() ;
			$vo->resultCode = "failed" ;
			$vo->message = "提交的讯息不完整" ;
			echo json_encode($vo);
			return ;
		}
		$this->process($request) ;
	}
	function process($request) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$response = $this->handle($request) ;
		echo $response;
	}
	function handle($request) {
		$userGuid = $request->userGuid ;
		$activityDetail = $request->activity ;
		$creation = $activityDetail['creation'] ;
		Logger::log(__FILE__,__LINE__,$userGuid) ;
		Logger::log(__FILE__,__LINE__,$creation) ;
		$controller = new ActivityAPI() ;
		$activityDAO = $controller->getActivityByCreation($userGuid,$creation) ;
		if($activityDAO==null) {
			throw new Exception("Cannot find find activity") ;
		}
		$activityVO = $activityDAO->getVO() ;
		$contentDAO = new ContentDAO($activityVO->path) ; 
		$contentDAO->load() ;
		$html = $contentDAO->getContentXML() ;
		Logger::log(__FILE__,__LINE__,$userGuid) ;
		return 	$html ;
	}
}

$handler = new FetchContentHandler() ;
try {
	$response = $handler->processForm() ;
	echo $response ;
} catch(Exception $e) {
	echo $e->getMessage() ;
}

