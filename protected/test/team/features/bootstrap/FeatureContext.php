<?php
use Behat\Behat\Context\ClosuredContextInterface, Behat\Behat\Context\TranslatedContextInterface, Behat\Behat\Context\BehatContext, Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode, Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
// require_once 'PHPUnit/Autoload.php';
// require_once 'PHPUnit/Framework/Assert/Functions.php';
//
require_once ("../../../public_html/app/util/Logger.php");
require_once ("../../../public_html/app/team/TeamDAO.php");
require_once ("../../../public_html/app/team/TeamAPI.php");

Logger::setPrefix(dirname(dirname(dirname(dirname(__FILE__))))) ;
/**
 * Features context.
 */

class FeatureContext extends BehatContext {
	var $intention ;
	/**
	 * Initializes context.
	 * Every scenario gets it's own context object.
	 * @param array $parameters
	 *        	context parameters (set them up through behat.yml)
	 */
	public function __construct(array $parameters) {
		$this->intention = array ();
	}
	/**
	 * @Given /^团队名称 "([^"]*)"$/
	 */
	public function teamname($teamname) {
		$this->intention['teamname'] = $teamname ;
	}
	
	/**
	 * @When /^成员 "([^"]*)"$/
	 */
	public function whenUserPerformsEvent($eventName) {
		$this->intention['eventName'] = $eventName ;
	}
	
	/**
	 * @Then /^系统产生活动$/
	 */
	public function getContentList() {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$teamname = $this->intention['teamname'] ;
		$eventName = $this->intention['eventName'] ;
		$controller = new TeamAPI() ;
		$contentElements = $controller->getTeamContentElements($teamname,$eventName) ;
		Logger::log(__FILE__,__LINE__,count($contentElements)) ;
		foreach($contentElements as $contentElement) {
			$path = 	$contentElement->get("path") ;
			Logger::log(__FILE__,__LINE__,$path) ;
		}
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
	}	

}
