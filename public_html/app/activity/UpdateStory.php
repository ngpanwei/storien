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
require_once(dirname(dirname(__FILE__))."/util/ValueObject.php");
require_once(dirname(dirname(__FILE__))."/activity/ActivityAPI.php");
require_once(dirname(dirname(__FILE__))."/activity/StoryAPI.php");

header('Content-type: text/html; charset=UTF-8');

Logger::enable(true) ;
Logger::setFilename(dirname(__FILE__)."/log.txt") ;
Logger::setMode("file") ;

class StoryRequest {
	var $userGuid ;
	var $activityGuid ;
	var $storyText ;	
}

class UpdateStoryHandler {
	public function __construct() {
	}
	function validateFormData() {
		if(isset($_POST["userGuid"])==false)
			return false ;
		if(isset($_POST["activityGuid"])==false)
			return false ;
		if(isset($_POST["storyText"])==false)
			return false ;
		return true ;
	}
	function getRequest() {
		if($this->validateFormData()==false) {
			return null ;
		}
		$request = new StoryRequest() ;
		$request->userGuid  = $_POST["userGuid"] ;
		$request->activityGuid  = $_POST["activityGuid"] ;
		$request->storyText  = $_POST["storyText"] ;
		return $request ;
	}
	function processForm() {
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
		$vo = $this->handle($request) ;
		$json = json_encode($vo);
		Logger::log(__FILE__,__LINE__,$json) ;
		echo json_encode($vo);
	}
	function handle($request) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userGuid = $request->userGuid ;
		$activityGuid = $request->activityGuid ;
		$storyText = $request->storyText ;
		$storyAPI = new StoryAPI() ;
		$activityDAO = $storyAPI->updateStory($userGuid, $activityGuid, $storyText) ;
		$vo = new ResultVO() ;
		$vo->resultCode = "success" ;
		$vo->message = "提交经历成功" ;
 		$vo->data = $activityDAO->getVO() ;
		return $vo ;		
	}
}

$handler = new UpdateStoryHandler() ;
try {
	$handler->processForm() ;
} catch(Exception $e) {
	$vo->resultCode = "failed" ;
	$vo->message = $e->getMessage() ;
}