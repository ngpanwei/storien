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
require_once ("../../../public_html/app/user/Register.php");
require_once ("../../../public_html/app/user/UserAPI.php");
Logger::setMode("console") ;

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
	 * @Given /^团队 "([^"]*)"$/
	 */
	public function teamContext($teamname)
	{
		$this->intention ['teamname'] = $teamname;
	}   
    	/**
    	 * @Given /^用户名 "([^"]*)" 邮箱 "([^"]*)"$/
    	 */
	public function userWithEmail($username, $email) {
		$this->intention ['username'] = $username;
		$this->intention ['email'] = $email;
	}

    /**
     * @When /^用户 提供 密码 "([^"]*)"$/
     */
    public function userGivesPassword($password,$cpassword) {
		$this->intention ['password'] = $password;
	}
	/**
	 * @When /^用户 提供 确认密码 "([^"]*)"$/
	 */
	public function userGivesConfirmation($cpassword) {
		$this->intention ['cpassword'] = $cpassword;
	}
	/**
	 * @Then /^注册结果 "([^"]*)"$/
	 */
	public function registrationResult($result)
	{
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$request = new RegistrationRequest() ;
		$request->cohortName  = $this->intention ['teamname'] ;
		$request->username  = $this->intention ['username'] ;
		$request->email     = $this->intention ['email'] ;
		$request->password  = $this->intention ['password'] ;
		$request->cpassword = $this->intention ['cpassword'] ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$handler = new UserAPI() ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$userVO = $handler->register($request) ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		if($userVO==null) {
			throw new Exception("no result") ;
		}
		var_dump($userVO) ;
	}
	
    /**
     * @Then /^登入 "([^"]*)"$/
     */
	public function signInResult($result) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		throw new Exception("Not implemented") ;
	}
}
