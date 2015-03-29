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

class ContentDAO {
	var $contentName ;
	var $xmlFileDb ;
	public function __construct($contentName) {
		$this->contentName = $contentName ;
	}
	public function load() {
		$filename = $this->getContentRoot() . "/" . $this->contentName ;
		Logger::log(__FILE__,__LINE__,$filename) ;
		$this->xmlFileDb = new XMLFileDb($filename) ;
		$this->xmlFileDb->load() ;
	}
	public function getContentRoot() {
		return dirname(dirname(dirname(dirname(__FILE__))))."/protected/content" ;
	}
	public function getProperty($key) {
		return $this->xmlFileDb->getRoot($key) ;
	}
	public function getContentXML() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$node = $this->xmlFileDb->get("content") ;
		$content = $this->xmlFileDb->xmlDoc->saveXML($node->xmlElement) ;
		Logger::log(__FILE__,__LINE__,$content) ;
		$index = strpos($content,PHP_EOL) ;
		$content = substr($content,$index) ;
		return $content ;
	}
	public function getContentText() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$node = $this->xmlFileDb->get("content") ;
		return $this->getTextFromNode($node->xmlElement) ;
	}
	public function getTextFromNode($Node, $Text = "") {
		if($Node==null)
			return $Text;
		Logger::log(__FILE__,__LINE__,trim($Node->textContent)) ;
		$txt = trim($Node->textContent) ;
		$txt = str_replace(array("\n","\r",PHP_EOL)," ",$txt) ;
		$Text = $Text.$txt." ";
		foreach($Node->childNodes as $childNode) {
			if($Node==$childNode)
				continue ;
			$Text = $this->getTextFromNode($childNode, $Text);
		}
		return $Text;
	}
	
}