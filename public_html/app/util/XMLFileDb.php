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
require_once("Logger.php") ;

Logger::setPrefix(dirname(__FILE__)) ;

class XMLNode {
	var $xmlElement ; // DOMElement 
	public function __construct($xmlElement) {
		$this->xmlElement = $xmlElement ;
	}
	public function setKeyValues($keyValues) {
		if($keyValues==null)
			return ;
		foreach($keyValues as $key => $value) {
			$this->xmlElement->setAttribute($key, $value) ;
		}
	}
	public function set($key,$value) {
		if($this->xmlElement==null) {
			Logger::log(__FILE__,__LINE__,"Null") ;
		} 
		$this->xmlElement->setAttribute($key, $value) ;
	}
	public function get($key) {
		$value = $this->xmlElement->getAttribute($key) ;
		return $value ;
	}
	public function getFirstElement() {
		$elementList = $this->xmlElement->getElementsByTagName("div") ;
		$domElement = $elementList->item(0) ;
		return new XMLNode($domElement) ;
	}
	public function getChildElements() {
		$elementList = $this->xmlElement->getElementsByTagName("div") ;
		$nodes = array() ;
		for($c = 0; $c<$elementList->length; $c++){
			$domElement = $elementList->item($c) ;
			$node = new XMLNode($domElement) ;
			array_push($nodes,$node) ;
		}
		return $nodes ;
	}
	public function getElementById($Id) {
		$elementList = $this->xmlElement->getElementsByTagName("div") ;
		for($c = 0; $c<$elementList->length; $c++){
			$domElement = $elementList->item($c) ;
			$elementId = $domElement->getAttribute("id") ; 
			if($Id==$elementId) {
				return new XMLNode($domElement) ;
			}
			$tempNode = new XMLNode($domElement) ;
			$node = $tempNode->getElementById($Id) ;
			if($node!=null)
				return $node;
		}
		return null ;
	}
	public function getChildElement($key) {
		$list = array() ;
		$elementList = $this->xmlElement->getElementsByTagName("div") ;
		for($c = 0; $c<$elementList->length; $c++){
			$domElement = $elementList->item($c) ;
			$value = $domElement->getAttribute("name") ;
			array_push($list,$value) ;
		}
		return $list ;
	}
}

class XMLFileDb {
	var $filename ;
	var $xmlDoc ;
	var $xmlRoot ;
	public function __construct($filename) {
		$this->filename = $filename ;
		$this->xmlDoc = null ;
	}
	public function setFilename($filename) {
		$this->filename = $filename ;
	}
	public function load() {
		if(file_exists($this->filename)==false) {
			$this->xmlDoc = new DOMDocument('1.0', 'utf-8');
			$this->xmlRoot = $this->xmlDoc->createElement("div","") ;
			$this->xmlDoc->appendChild($this->xmlRoot);
		} else {
			$this->xmlDoc = new DOMDocument();
			$this->xmlDoc->load($this->filename);
			$elementList = $this->xmlDoc->getElementsByTagName("div") ;
			$this->xmlRoot = $elementList->item(0) ;
		}
	}

	public function flush() {
		$this->writeUTF8File($this->filename,$this->xmlDoc->saveXML()) ;
	}
	
	private function writeUTF8File($filename,$content) {
		$f=fopen($filename,"w");
		# Now UTF-8 - Add byte order mark
		fwrite($f, pack("CCC",0xef,0xbb,0xbf));
		fwrite($f,$content);
		fclose($f);
	}	
	
	public function getRoot($key) {
		$rootElement = new XMLNode($this->xmlRoot) ;
		return $rootElement->get($key) ;
	}
	public function setRoot($key,$value) {
		$rootElement = new XMLNode($this->xmlRoot) ;
		$rootElement->set($key, $value) ;
	}
	public function setRecord($elementId,$keyValues) {
		$element = $this->get($elementId) ;
		if($element==null) {
			$domElement = $this->xmlDoc->createElement("div","") ;
			$domElement->setAttribute("id", $elementId) ;
			$this->xmlRoot->appendChild($domElement);
			$element = new XMLNode($domElement) ;
		}
		$element->setKeyValues($keyValues) ;
		return $element ;
	}
	public function get($elementId) {
		$rootElement = new XMLNode($this->xmlRoot) ;
		return $rootElement->getElementById($elementId) ;
	}
	public function setList($elementId,$arrayValue) {
		$element = $this->get($elementId) ;
		if($element==null) {
			$domElement = $this->xmlDoc->createElement("div","") ;
			$domElement->setAttribute("id", $elementId) ;
			$this->xmlRoot->appendChild($domElement);
			$element = new XMLNode($domElement) ;
		}
		foreach($arrayValue as $value) {
			$childDomElement = $this->xmlDoc->createElement("div","") ;
			$childDomElement->setAttribute("name", $value) ;
			$domElement->appendChild($childDomElement) ;
		}
	}
	
	public function getList($elementId) {
		$element = $this->get($elementId) ;
		if($element==null) {
			return null ;
		}	
		return $element->getChildElement("name") ;
	}
	
	public function getFirstElement() {
		$rootElement = new XMLNode($this->xmlRoot) ;
		return $rootElement->getFirstElement() ;
	}
	public function asXML() {
		return $this->xmlDoc->saveXML() ;
	}
}

class XMLDirDb {
	var $dir ;
	var $key ;
	var $dirDb ;
	public function __construct($dir) {
		$this->dirDb = array() ;
		$this->dir = $dir ;
	}
	public function deleteAll() {
		$files = scandir($this->dir);
		foreach($files as $filename) {
			if(strpos($filename,'.xml')!==false) {
				unlink($this->dir."/".$filename) ;
			}
		}
	}
	public function loadAll($key) {
		$files = scandir($this->dir);
		foreach($files as $filename) {
			if(strpos($filename,'.xml')!==false) {
				$xmlDb = new XMLFileDb($this->dir."/".$filename) ;
				$xmlDb->load() ;
				$keyValue = $xmlDb->getRoot($key) ;
				$this->dirDb[$keyValue] = $xmlDb ;
			}
		}
	}
	public function getFileDbByKey($keyValue) {
		try {
			$xmlDb = $this->dirDb[$keyValue] ;
			return $xmlDb;
		} catch (Exception $e) {
			Logger::log(__FILE__,__LINE__,$e->getMessage()) ;
			return null ;
		}
	}
	public function getFileByGuid($guid) {
		$filename = $this->dir . "/" . $guid . ".xml" ;
		$xmlDb = new XMLFileDb($filename) ;
		$xmlDb->load() ;
		return $xmlDb ;
	}
	public function createFileDb($key,$keyValue) {
		$guid = Guid::getGuid() ;
		$filename = $this->dir . "/" . $guid . ".xml" ;
		$xmlDb = new XMLFileDb($filename) ;
		$xmlDb->load() ;
		$xmlDb->setRoot($key,$keyValue) ;
		$xmlDb->setRoot("guid",$guid) ;
		$this->dirDb[$keyValue] = $xmlDb ;
		return $xmlDb ;
	}
	public function getAllFiles() {
		$fileDbArray = array() ;
		foreach($this->dirDb as $key => $fileDb) {
			array_push($fileDbArray,$fileDb) ;
		}
		return $fileDbArray;
	}
}

// try {
// 	$xmlDb = new XMLFileDb(dirname(__FILE__)."/Db.xml") ;
// 	$xmlDb->load() ;
// 	$xmlDb->set("spelling7",array("correct"=>3,"wrong"=>2)) ;
// 	Logger::log(__FILE__,__LINE__,"Log") ;
// 	$xmlDb->set("spelling9",array("correct"=>23,"wrong"=>2)) ;
// 	Logger::log(__FILE__,__LINE__,"Log") ;
// 	$xmlDb->flush() ;
// 	Logger::log(__FILE__,__LINE__,"Log") ;
// } catch(Exception $e) {
// 	Logger::log(__FILE__,__LINE__,$e->getMessage()) ;
// }

?>