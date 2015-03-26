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
require_once ("../../../public_html/app/activity/ActivityController.php");
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
	}
	/**
	 * @Given /^用户 "([^"]*)" 刚 "([^"]*)"$/
	 */
	public function userPerformsEvent($email, $event)
	{
		$this->intention['email'] = $email ;
		$this->intention['event'] = $event ;
		$controller = new ActivityController() ;
		$user = $controller->getUserByEmail($email) ;
		$this->intention['userGuid'] = $user->getProperty("guid") ;
		$this->intention['activities'] = $controller->handleUserEvent($user, $event) ;
	}	
	protected function getActivityVO($activityTitle) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$activityList = $this->intention['activities'] ;
		foreach($activityList as $activity) {
			if($activity->title==$activityTitle) {
				return $activity;
			}
		}
		return null ;
	}
	/**
	 * @When /^用户查看活动 "([^"]*)"$/
	 */
	public function userViewsActivity($activityTitle) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$activityVO = $this->getActivityVO($activityTitle) ;
		if($activityVO==null) {
			throw new TestException("cannot find activity") ;
		}
		$creation = $activityVO->creation ;
		$userGuid = $this->intention['userGuid'] ;
		Logger::log(__FILE__,__LINE__,$creation) ;
		$controller = new ActivityController() ;
		$activityDAO = $controller->getActivityByCreation($userGuid,$creation) ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$title = $activityDAO->getProperty("title") ;
		if($title!=$activityTitle) {
			Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
			throw new TestException("cannot find activity with title") ;
		}
		$this->intention['activity'] = $activityDAO ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
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
