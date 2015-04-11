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
require_once ("../../../public_html/app/activity/ActivityAPI.php");
require_once ("../../../public_html/app/activity/StoryAPI.php");

Logger::setPrefix(dirname(dirname(dirname(dirname(__FILE__))))) ;

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
		$controller = new ActivityAPI() ;
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
     * @When /^用户刷新活动列表$/
     */
    public function userRefreshActivityList() {
    		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userGuid = $this->intention['userGuid'] ;
    		Logger::log(__FILE__,__LINE__,$userGuid) ;
		$controller = new ActivityAPI() ;
		$activityVOList = $controller->getActivityVOList($userGuid) ;
    		Logger::log(__FILE__,__LINE__,$userGuid) ;
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
		$controller = new ActivityAPI() ;
		$activityDAO = $controller->getActivityByCreation($userGuid,$creation) ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$title = $activityDAO->getProperty("title") ;
		if($title!=$activityTitle) {
			Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
			throw new TestException("cannot find activity with title") ;
		}
		$this->intention['activity'] = $activityDAO ;
		$this->intention['activityCreation'] = $activityDAO->getProperty("creation") ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
	}
    /**
     * @When /^用户把 "([^"]*)" 活动 "([^"]*)"$/
     */
    public function completeActivity($activityName,$status)
    {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
    		$activityDAO = $this->intention['activity'] ;
    		$activityDAO->setProperty("status",$status) ;
    		$activityDAO->flush() ;
    }
    /**
     * @Then /^"([^"]*)" 活动状态是  "([^"]*)"$/
     */
    public function activityIsCompleted($activityName, $status)  {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
    		$creation = $this->intention['activityCreation'] ;
		$userGuid = $this->intention['userGuid'] ;
    		Logger::log(__FILE__,__LINE__,$creation) ;
		$controller = new ActivityAPI() ;
		$activityDAO = $controller->getActivityByCreation($userGuid, $creation) ;
		$actualStatus = $activityDAO->getProperty("status") ;
		if($status!=$actualStatus) {
			Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
			throw new TestException("actual status is ".$actualStatus) ;
		}
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
    }   

    /**
     * @Given /^用户 "([^"]*)"$/
     */
    public function userWithEmail($email) {
		$this->intention['email'] = $email ;
		$controller = new ActivityAPI() ;
		$userDAO = $controller->getUserByEmail($email) ;
		$userGuid = $userDAO->getProperty("guid") ;
		$controller = new ActivityAPI() ;
		$activityVOList = $controller->getActivityVOList($userGuid) ;
		$this->intention['userGuid'] = $userGuid ;
		$this->intention['activities'] = $activityVOList ;
    }
    
    /**
     * @When /^用户分享经历 "([^"]*)"$/
     */
    public function userSharesStory($storyText) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
    		$userGuid = $this->intention['userGuid'] ;
    		if(isset($this->intention['activity'])==true) {
			$activityDAO = $this->intention['activity'] ;
    			$activityGuid = $activityDAO->getProperty("creation") ;
    			$storyAPI = new StoryAPI() ;
    			$activityDAO = $storyAPI->updateStory($userGuid, $activityGuid, $storyText) ;
    		} else {
    			Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
    			$storyAPI = new StoryAPI() ;
    			$activityDAO = $storyAPI->postStory($userGuid,$storyText) ;
    			$creation = $activityDAO->getProperty("creation") ;
    			$this->intention['activityCreation'] = $creation ;
    		}
    }   
}
