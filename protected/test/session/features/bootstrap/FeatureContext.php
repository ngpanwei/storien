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
		$request->teamname  = $this->intention ['teamname'] ;
		$request->username  = $this->intention ['username'] ;
		$request->email     = $this->intention ['email'] ;
		$request->password  = $this->intention ['password'] ;
		$request->cpassword = $this->intention ['cpassword'] ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$handler = new RegistrationHandler() ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		$vo = $handler->handle($request) ;
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		if($vo==null) {
			throw new Exception("no result") ;
		}
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		if($vo->resultCode!=$result) {
			throw new Exception("registration should be ".$result) ;
		}
		var_dump($vo) ;
	}
	
    /**
     * @Then /^登入 "([^"]*)"$/
     */
	public function signInResult($result) {
		Logger::log(__FILE__,__LINE__,__FUNCTION__) ;
		throw new Exception("Not implemented") ;
	}
}
