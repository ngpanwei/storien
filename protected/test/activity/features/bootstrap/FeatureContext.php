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
Logger::log(__FILE__,__LINE__,"activity") ;
require_once ("../../../public_html/app/activity/ActivityController.php");
Logger::log(__FILE__,__LINE__,"activity") ;
/**
 * Features context.
 */

class FeatureContext extends BehatContext {
	var $intention ;
	var $controller ;
	/**
	 * Initializes context.
	 * Every scenario gets it's own context object.
	 * @param array $parameters
	 *        	context parameters (set them up through behat.yml)
	 */
	public function __construct(array $parameters) {
		$this->intention = array ();
		$this->controller = new ActivityController() ;
	}
	/**
	 * @Given /^用户 "([^"]*)" 刚 "([^"]*)"$/
	 */
	public function userPerformsEvent($email, $event)
	{
		$this->intention['email'] = $email ;
		$this->intention['event'] = $event ;
	}	
	/**
	 * @When /^用户查看活动 "([^"]*)"$/
	 */
	public function userViewsActivity($activityName)
	{
		$this->intention['activityName'] = $activityName ;
	}
    /**
     * @When /^用户完成 "([^"]*)" 活动$/
     */
    public function completeActivity($activityName)
    {
        throw new PendingException();
    }
    /**
     * @Then /^"([^"]*)" 已完成$/
     */
    public function activityIsCompleted($activityName)
    {
    	    throw new PendingException();
    }    	
}
