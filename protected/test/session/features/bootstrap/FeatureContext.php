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
require_once ("../../../public_html/app/personify/Register.php");

Logger::log(__FILE__,__LINE__,"registration") ;
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
		$handler = new RegistrationHandler() ;
		$handler->username = $this->intention ['username'] ;
		$user = $handler->process() ;
	}
	
    /**
     * @Then /^登入 "([^"]*)"$/
     */
	public function signInResult($result)
	{
	}
}
