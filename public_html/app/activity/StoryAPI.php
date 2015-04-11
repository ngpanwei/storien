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
require_once(dirname(dirname(__FILE__))."/activity/ActivityAPI.php");

class StoryAPI {
	public function postStory($userGuid,$storyText) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$activityAPI = new ActivityAPI() ;
		$activityDAO = $activityAPI->createActivityFromContentPath($userGuid,"story/postStory.xml") ;
		$activityDAO->setText("story", $storyText) ;
		Logger::log(__FILE__,__LINE__,$activityDAO->getText("story")) ;
		$activityDAO->setProperty("status","done") ;
		$activityDAO->flush() ;
		return $activityDAO ;
	}
	public function updateStory($userGuid,$activityGuid,$storyText) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$activityAPI = new ActivityAPI() ;
		$activityDAO = $activityAPI->getActivityByCreation($userGuid,$activityGuid) ;
		$activityDAO->setText("story", $storyText) ;
		Logger::log(__FILE__,__LINE__,$activityDAO->getText("story")) ;
		$activityDAO->setProperty("status","done") ;
		$activityDAO->flush() ;
		return $activityDAO ;
	}
}