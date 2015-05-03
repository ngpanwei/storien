<?php

require_once(dirname(dirname(__FILE__))."/util/Logger.php");
require_once(dirname(dirname(__FILE__))."/util/Exception.php");
require_once(dirname(dirname(__FILE__))."/util/ValueObject.php");
header('Content-type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");

Logger::enable(true) ;
Logger::setFilename(dirname(__FILE__)."/log.txt") ;
Logger::setMode("file") ;
Logger::log(__FILE__,__LINE__,"score") ;

class ScoreTrackingRequest {
	var $score ;
}
class ScoreTracker {

	public function __construct(){
		$this->vo = new ResultVO() ;
	}
	function validateFormData() {
		if(isset($_POST["score"])==false)
			return false ;
		return true ;
	}
	function getFormData() {
		if($this->validateFormData()==false) {
			return null ;
		}
		$request = new ScoreTrackingRequest() ;
		$request->score  = $_POST["score"] ;
		return $request ;
	}
	public function process() {
		$request = $this->getFormData() ;
		if($request==null) {
			Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		}
		$vo = new ResultVO() ;
		$vo->resultCode = "failed" ;
		$vo->message = "提交的讯息不完整" ;
		$vo->data = $request->score ;
		echo json_encode($vo);
	}
}
Logger::log(__FILE__,__LINE__,"score") ;

$handler = new ScoreTracker() ;
$handler->process();
