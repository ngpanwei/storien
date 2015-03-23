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
require_once("../util/Logger.php");
require_once("../util/Exception.php");

class ActivityClassVO {
	var $name ; // name of activity class name
	var $displayName ; // localized name to be displayed 
}

class ActivityVO {
	var $name  ; // name of activity to display on person's activity list
	var $page  ; // page link or hash to conduct the activity
	var $creationDate  ; // date when activity was created
	var $class ; // class of activity, which achieved results in a medal
	var $point ; // points awarded for conducting the activity
}

class ActivityManager {
	public function createInitialActivities($userGuid) {
		// return a list of activities for a new member
		// determine if picture uploaded
		// determine if personal details filled
		// read a piece of text
		// take a simple test
		// ask user to write a story
	}
}