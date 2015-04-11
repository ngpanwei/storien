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
require_once("./app/util/Logger.php");
$footer = file_get_contents('./page/footer.php'); 
function file_inject($filename) {
	global $footer ;
	$footerTag = "<!-- footer -->" ;
	$content = file_get_contents($filename); 
	$content = str_replace($footerTag,$footer,$content) ;
	return $content;
}
echo file_get_contents('./page/head.php'); 
echo file_inject('./page/welcome.php');
echo file_inject('./page/home.php');
echo file_inject('./page/activityTemplate.php');
echo file_inject('./page/activityInfo.php');
echo file_inject('./page/activityStory.php');
echo file_inject('./page/settings.php');
echo file_inject('./page/registration.php');
echo file_inject('./page/forgetPassword.php');
echo file_inject('./page/changeUsername.php');
echo file_inject('./page/changeEmail.php');
echo file_inject('./page/changePassword.php');
echo file_inject('./page/upload.php');
echo file_inject('./page/welcome.php');
echo file_get_contents('./page/end.php');
?>
