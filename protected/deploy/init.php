<?php
require_once(dirname(dirname(dirname(__FILE__)))."/public_html/app/util/Logger.php");

Logger::setPrefix(dirname(dirname(dirname(__FILE__)))) ;

Logger::log(__FILE__,__LINE__,"init") ;

class Initializer {
	public function clearFolderFiles($folderName,$extension) {
		Logger::log(__FILE__,__LINE__,$folderName) ;
		$files = scandir($folderName);
		foreach($files as $filename) {
			if($filename==".") continue ;
			if($filename=="..") continue ;
			$path = $folderName . "/".$filename ;
			if(is_dir($path)) {
				Logger::log(__FILE__,__LINE__,$path) ;
				$this->clearFolderFiles($path,$extension) ;
				Logger::log(__FILE__,__LINE__,$path) ;
				rmdir($path) ;
				continue ;
			}
			if(strpos($filename,$extension)==false) {
				continue ;
			}
			unlink($path) ;
			Logger::log(__FILE__,__LINE__,$path) ;
		}
	}
	public function process() {
		$root = dirname(dirname(dirname(__FILE__))) ;
		Logger::log(__FILE__,__LINE__,$root) ;
		$this->clearFolderFiles($root."/protected/data/users",".xml") ;
		$this->clearFolderFiles($root."/protected/data/activities",".xml") ;
		$this->clearFolderFiles($root."/public_html/users",".jpg") ;
		$this->clearFolderFiles($root."/public_html/activities",".jpg") ;
	}
}

$object = new Initializer() ;
$object->process() ;

