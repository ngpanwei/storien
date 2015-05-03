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

class CSVDataTable {
	var $keys ;
	var $rows ;
	public function __construct($rawData,$primaryKey) {
		$rows = explode(";",$rawData) ;
		$this->rows = array() ;
		$prIndex = 0 ;
		foreach($rows as $i=>$row) {
			if(strlen(trim($row))<1) continue ;
			if($i==0) {
				$this->keys = explode(",",$row) ;
				foreach($this->keys as $j=>$key) {
					$this->keys[trim($key)] = $j ;
					if($key==$primaryKey) {
						$prIndex = $j ;
					}
				}
			} else {
				$cols = explode(",",$row) ;
				foreach($cols as $j=>$value) {
					$cols[$j] = trim($value) ;
				}
				$primaryKeyValue = trim($cols[$prIndex]) ;
				$this->rows[$primaryKeyValue] = $cols ;
// 				Logger::log(__FILE__,__LINE__,__FUNCTION__."==[".$primaryKeyValue."]") ;
			}
		}
	}
	public function getRow($key) {
		foreach($this->rows as $myKey=>$row) {
			if($key==$myKey) {
				return $row ;
			}
		}
		return null ;
	}
	public function getHTMLTable($header,$tableId,$sumHeader,$sumCol) {
		$html = "<table data-role='table' id='$tableId' " .
		        "data-column-btn-text='选择显示列' " .
		        "data-mode='columntoggle' class='ui-responsive table-stroke'>" ;
		$html = $html . PHP_EOL ;
		$html = $html . "<thead><tr>" ;
		foreach($header as $index => $column) {
			$html = $html . "<th data-priority='$index'>$column</th>" ; 
		}
		if($sumHeader!=null) {
			$index = $index + 1 ;
			$html = $html . "<th data-priority='$index'>$sumHeader</th>" ; 
		}
		$html = $html . PHP_EOL . "</tr></thead>" . PHP_EOL ;
		$html = $html . "<tbody>" ;
		$index = 1 ;
		foreach($this->rows as $myKey=>$row) {
			$sum = "" ;
			$html = $html . "<tr>" ;
// 			$html = $html . "<th>" . $index . "</th>" . PHP_EOL ;
			foreach($header as $column) {
				$keyIndex = $this->keys[$column] ;
				$value = $row[$keyIndex] ;
				if(isset($sumCol[$column])) {
					$sum = $sum + $value ;	
				}
				$html = $html . "<td>" . $value . "</td>" ;
			}
			if($sumHeader!=null) {
				$html = $html . "<td>" . $sum . "</td>" ;
			}
			$html = $html . "</tr>" . PHP_EOL ;
			$index = $index + 1 ;
		}
		$html = $html . "</tbody></table>" ;
		return $html ;
	}
}

