<?php
require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/util/Logger.php");

Logger::setPrefix(dirname(dirname(dirname(__FILE__)))) ;

Logger::log(__FILE__,__LINE__,"init") ;

class Initializer {
	public function clearFolder($folderName,$extension) {
		$files = scandir($folderName);
		foreach($files as $filename) {
			if($filename==".") continue ;
			if($filename=="..") continue ;
			$path = $folderName . "/".$filename ;
			if(is_dir($path)) {
				$this->clearFolder($path,$extension) ;
				return ;
			}
			if(strpos($filename,$extension)==false) {
				continue ;
			}
			Logger::log(__FILE__,__LINE__,$path) ;
		}
	}
	public function process() {
		$root = dirname(dirname(dirname(__FILE__))) ;
		Logger::log(__FILE__,__LINE__,$root) ;
		$this->clearFolder($root."/protected/data/users",".xml") ;
		$this->clearFolder($root."/public_html/users",".jpg") ;
	}
}

$object = new Initializer() ;
$object->process() ;

