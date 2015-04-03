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
 /**
 * Logger class for PHP.
 * @author ngpanwei
 */
class Logger {
	const LEVEL_MESSAGE = 0;
	const LEVEL_WARNING = 1;
	const LEVEL_ERROR = 2;
	private static $enable = true;
	private static $mode = "console" ;
	private static $dirPrefix = "" ;
	private static $filename ;
	
	public function __construct() {
	}
	
	public static function setPrefix($prefix) {
		self::$dirPrefix = $prefix ;
	}
	public static function setFilename($filename) {
		self::$filename = $filename ;
	}
	public static function enable($flag) {
		self::$enable = $flag ;
	}
	public static function setMode($mode) {
		self::$mode = $mode ;
	}	
 	public static function log($file,$line,$output) {
 		self::__log($file,$line,$output,self::LEVEL_MESSAGE) ;
 	}
	public static function __log($file,$line,$output, $level) {
		if (self::$enable == false)
			return;
		$filename = str_replace(self::$dirPrefix,"",$file) ;
		$message = $filename . "::" . $line . "-->" . $output ;
		if(self::$mode=="web") {
			self::__web($message) ;
		} else if(self::$mode=="file") {
			self::__file($message) ;
		} else {
			self::__console($message) ;
		}
	}
	public static function __file($message) {
		$format = 'Y-m-d-H-i-s' ;
		$line = "[" . date($format) . "][" . self::remoteAddr() . "][" . $message . "]" . PHP_EOL ;
		file_put_contents(self::$filename, $line, FILE_APPEND | LOCK_EX);
	}
	protected static function remoteAddr() {
		$str = "" ;
		try {
			$str = $_SERVER['REMOTE_ADDR'] ;
		} catch(Exception $e) {
			$str = "local" ;
		}
		return $str ;
	}
	public static function __web($message) {
		echo "<p>" . $message . "</p> " . PHP_EOL ;
	}
	public static function __console($message) {
		echo $message . PHP_EOL ;
	}
}
?>