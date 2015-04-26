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

/**
 * XMLNode is a wrapper around a DOM Element. 
 * @author ngpanwei
 */
class XMLNode {
	var $xmlElement ; // DOMElement 
	/**
	 * Constructor
	 * @param unknown $xmlElement
	 */
	public function __construct($xmlElement) {
		$this->xmlElement = $xmlElement ;
	}
	/**
	 * set multiple key values
	 * @param array $keyValues
	 */
	public function setKeyValues($keyValues) {
		if($keyValues==null)
			return ;
		foreach($keyValues as $key => $value) {
			$this->xmlElement->setAttribute($key, $value) ;
		}
	}
	/**
	 * Set an attribute
	 * @param unknown $key
	 * @param unknown $value
	 */
	public function set($key,$value) {
		if($this->xmlElement==null) {
			throw new Exception("Unknown XML Element.") ;
		} 
		$this->xmlElement->setAttribute($key, $value) ;
	}
	/**
	 * Get attribute
	 * @param unknown $key
	 * @return unknown
	 */
	public function get($key) {
		$value = $this->xmlElement->getAttribute($key) ;
		return $value ;
	}
	/**
	 * Get the text value of this node.
	 */
	public function getText() {
		return $this->xmlElement->nodeValue ;
	}
	/**
	 * Set the text value of this node.
	 * @param string $text
	 */
	public function setText($text) {
		$this->xmlElement->nodeValue = $text ;
	}
	/**
	 * Get first child of this node.
	 * @return XMLNode
	 */
	public function getFirstElement() {
		$elementList = $this->xmlElement->getElementsByTagName("div") ;
		$domElement = $elementList->item(0) ;
		return new XMLNode($domElement) ;
	}
	/**
	 * Get child nodes.
	 * @return array of XMLNodes:
	 */
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
	/**
	 * Get child node by name.
	 * @param unknown $name
	 * @return XMLNode|NULL
	 */
	public function getNamedChild($name) {
		$elementList = $this->xmlElement->getElementsByTagName("div") ;
		for($c = 0; $c<$elementList->length; $c++){
			$domElement = $elementList->item($c) ;
			$value = $domElement->getAttribute("name") ;
			if($value==$name) {
			    $node = new XMLNode($domElement) ;
				return $node ;
			}
		}
		return null ;
	}
	/**
	 * Get an element by it. 
	 * This function is ecursive, but the id is a string not an xpath
	 * @param string $Id
	 * @return XMLNode|Ambigous <NULL, XMLNode>|NULL
	 */
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
	/**
	 * Get child div element based on the id. 
	 * This function does not recursively search the div elements in the file.
	 * @param string $key
	 * @return multitype:
	 */
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

/**
 * Wrapper for an XML Document (File).
 * @author ngpanwei
 */
class XMLFileDb {
	var $filename ;
	var $xmlDoc ;
	var $xmlRoot ;
	/**
	 * Constructor.
	 * @param string $filename fullpath
	 */
	public function __construct($filename) {
		$this->filename = $filename ;
		$this->xmlDoc = null ;
	}
	/**
	 * Set the file name.
	 * @param string $filename fullpath
	 */
	public function setFilename($filename) {
		$this->filename = $filename ;
	}
	/**
	 * Load from file or create an empty DOMElement.
	 */
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
	/**
	 * Save the XML File as utf-8.
	 */
	public function flush() {
		$this->writeUTF8File($this->filename,$this->xmlDoc->saveXML()) ;
	}
	/**
	 * Erase this file.
	 */
	public function erase() {
		return ulink($this->filename) ;
	}
	/**
	 * Private function to write XML file as utf-8.
	 * @param unknown $filename
	 * @param unknown $content
	 */
	private function writeUTF8File($filename,$content) {
		$f=fopen($filename,"w");
		# Now UTF-8 - Add byte order mark
		fwrite($f, pack("CCC",0xef,0xbb,0xbf));
		fwrite($f,$content);
		fclose($f);
	}	
	/**
	 * Return the root attribute
	 * @param String $key
	 * @return unknown
	 */
	public function getRoot($key) {
		$rootElement = new XMLNode($this->xmlRoot) ;
		return $rootElement->get($key) ;
	}
	/**
	 * Set a root attribute.
	 * @param unknown $key
	 * @param unknown $value
	 */
	public function setRoot($key,$value) {
		$rootElement = new XMLNode($this->xmlRoot) ;
		$rootElement->set($key, $value) ;
	}
	/**
	 * Create an element with an Id.
	 * @param unknown $elementId
	 * @return Ambigous <XMLNode, Ambigous>
	 */
	public function createElement($elementId) {
		$element = $this->get($elementId) ;
		if($element==null) {
			$domElement = $this->xmlDoc->createElement("div","") ;
			$domElement->setAttribute("id", $elementId) ;
			$this->xmlRoot->appendChild($domElement);
			$element = new XMLNode($domElement) ;
		}
		return $element ;
	}
	/**
	 * Get the XML string of an element given its id.
	 * @param string $elementId
	 * @return string
	 */
	public function getXML($elementId) {
		$element = $this->get($elementId) ;
		$xml = $this->xmlDoc->saveXML($element->xmlElement) ;
		return $xml ;
	}
	/**
	 * Replace the XML contents of an element.
	 * @param string $elementId
	 * @param string $xml
	 */
	public function setXML($elementId,$xml) {
		$element = $this->get($elementId) ;
		if($element!=null) {
			$domElement = $element->xmlElement ;
			$parentNode = $domElement->parentNode ;
			$parentNode->removeChild($domElement) ;
		}
		if($this->xmlDoc==null) {
			Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		}
		$fragment = $this->xmlDoc->createDocumentFragment();
		$fragment->appendXML($xml) ;
		$this->xmlRoot->appendChild($fragment);
	}
	public function setKeyText($elementId,$text) {
		$element = $this->createElement($elementId) ;
		$element->setText($text) ;
	}
	public function getKeyText($elementId) {
		$element = $this->get($elementId) ;
		if($element==null) {
			return null ;
		}
		$text = $element->getText() ;
		return $text ;
	}
	
	public function setRecord($elementId,$keyValues) {
		$element = $this->createElement($elementId) ;
		$element->setKeyValues($keyValues) ;
		return $element ;
	}
	public function get($elementId) {
		$rootElement = new XMLNode($this->xmlRoot) ;
		return $rootElement->getElementById($elementId) ;
	}
	/**
	 * Create a list of elements
	 * @param unknown $elementId
	 * @param unknown $arrayValue
	 */
	public function setList($elementId,$arrayValue) {
		$element = $this->createElement($elementId) ;
		$domElement = $element->xmlElement ;
// 		if($element==null) {
// 			$domElement = $this->xmlDoc->createElement("div","") ;
// 			$domElement->setAttribute("id", $elementId) ;
// 			$this->xmlRoot->appendChild($domElement);
// 			$element = new XMLNode($domElement) ;
// 		}
		foreach($arrayValue as $value) {
			$this->createNamedElement($domElement,$value) ;
// 			$childDomElement = $this->xmlDoc->createElement("div","") ;
// 			$childDomElement->setAttribute("name", $value) ;
// 			$domElement->appendChild($childDomElement) ;
		}
	}
	private function createNamedElement($domElement,$name) {
		$childDomElement = $this->xmlDoc->createElement("div","") ;
		$childDomElement->setAttribute("name", $name) ;
		$domElement->appendChild($childDomElement) ;
		return $childDomElement ;
	}
	public function getListItem($elementId,$name) {
		$node = $this->createElement($elementId) ;
		$namedNode = $node->getNamedChild($name) ;
		return $namedNode ;
	}
	public function addListItem($elementId,$name) {
		$node = $this->createElement($elementId) ;
		$namedNode = $node->getNamedChild($name) ;
		if($namedNode==null) {
			$domElement = $node->xmlElement ;
			$childElement = $this->createNamedElement($domElement,$name) ;
			return new XMLNode($childElement) ;
		}
		return $namedNode ;
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
	/**
	 * load all files from the directory.
	 * @param unknown $key
	 * @param string $extension is a callback function. 
	 *     If the return value from this call back is false, 
	 *     the file is not added to dirDb hashmap.
	 */
	public function loadAll($key,$extension=NULL) {
		$files = scandir($this->dir);
		foreach($files as $filename) {
			if(strpos($filename,'.xml')!==false) {
				$xmlDb = new XMLFileDb($this->dir."/".$filename) ;
				$xmlDb->load() ;
				$keyValue = $xmlDb->getRoot($key) ;
				if($extension!=NULL) {
					$result = call_user_func($extension,$xmlDb) ;
					if($result==false) {
						continue ;
					}
				}
				$this->dirDb[$keyValue] = $xmlDb ;
			}
		}
	}
	public function erase() {
		$files = scandir($this->dir);
		foreach($this->dirDb as $fileDb) {
			$fileDb->erase() ;
		}
		rmdir($this->dir) ;
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

?>