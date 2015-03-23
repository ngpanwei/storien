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

/**
 * Features context.
 */

class FeatureContext extends BehatContext {
	var $intention ;
	var $sessionAPI ;
	/**
	 * Initializes context.
	 * Every scenario gets it's own context object.
	 * @param array $parameters
	 *        	context parameters (set them up through behat.yml)
	 */
	public function __construct(array $parameters) {
		$this->intention = array ();
// 		$this->sessionAPI = new SessionAPI() ;
	}
    /**
     * @Given /^users :$/
     */
    public function existingUsers(TableNode $table)
    {
    	    foreach ($table->getHash() as $row) {
        		$this->intention ['username'] = $row['username'];
        		$this->intention ['email'] = $row['email'];
        		$this->intention ['password'] = $row['password'];
        		try {
//   				$userObject = $this->sessionAPI->deleteUserByEmail($this->intention['email']) ;
        		} catch(Exception $ignore) { }
        		try {
//         			$userObject = $this->sessionAPI->registerUser($this->intention) ;
        		} catch(Exception $ignore) { }
    	    }
    }
    /**
     * @Given /^non-existent users :$/
     */
    public function nonExistentUsers(TableNode $table)
    {
        	foreach ($table->getHash() as $row) {
        		$this->intention ['email'] = $row['email'];
        		try {
//   				$userObject = $this->sessionAPI->deleteUserByEmail($this->intention['email']) ;
        		} catch(Exception $ignore) { }
    	    }
    	}    
    	/**
	 * @Given /^user "([^"]*)" with email "([^"]*)"$/
	 */
	public function userWithEmail($username, $email) {
		$this->intention ['username'] = $username;
		$this->intention ['email'] = $email;
	}
	
	/**
	 * @When /^user gives password "([^"]*)"$/
	 */
	public function userGivesPassword($password) {
		$this->intention ['password'] = $password;
	}
	/**
	 * @Given /^user has old password "([^"]*)"$/
	 */
	public function userHasOldPassword($opassword)
	{
		$this->intention ['opassword'] = $opassword;
	}	
	/**
	 * @Then /^registration "([^"]*)" with code "([^"]*)"$/
	 */
	public function registrationResultWithCode($expectedResult, $expectedCode) {
		Logger::log(__FILE__,__LINE__,"registrationResultWithCode") ;
// 		$pdo = $this->sessionAPI->connect() ;
// 		$userObject = $this->sessionAPI->registerUser($this->intention) ;
// 		Logger::log(__FILE__,__LINE__,"username ".$userObject->username) ;
	}
	/**
	 * @Then /^registration confirmation "([^"]*)" with code "([^"]*)"$/
	 */
	public function registrationConfirmationResultWithCode($expectedResult, $expectedCode)
	{
		Logger::log(__FILE__,__LINE__,"registrationConfirmationResultWithCode") ;
// 		$email = $this->intention['email'] ;
// 		Logger::log(__FILE__,__LINE__,"confirming email ".$email) ;
// 		$pdo = $this->sessionAPI->connect() ;
// 		$userDAO = new UserDAO() ;
// 		$userObject = $userDAO->fetchUserByEmail($pdo, $email) ;
// 		if($userObject==null) {
// 			throw new TestException("unknown user email ".$email) ;
// 		}
// 		$verification = $userObject->verification ;
// 		Logger::log(__FILE__,__LINE__,"confirming code ".$verification) ;
// 		$this->intention = array ();
// 		$this->intention['verification'] = $verification ;
// 		$userObject = $this->sessionAPI->confirmRegistration($this->intention) ;
// 		Logger::log(__FILE__,__LINE__,"confirmed ".$userObject->email) ;
	}	
	/**
	 * @Then /^sign in "([^"]*)" with code "([^"]*)"$/
	 */
	public function signInResultWithCode($arg1, $arg2)
	{
// 		$pdo = $this->sessionAPI->connect() ;
// 		$userObject = $this->sessionAPI->signIn($this->intention) ;
	}
	/**
	 * @Then /^password change "([^"]*)" with code "([^"]*)"$/
	 */
	public function passwordChangeWithCode($arg1, $arg2)
	{
// 		$pdo = $this->sessionAPI->connect() ;
// 		$userDAO = new UserDAO() ;
// 		$userObject = $userDAO->fetchUserByEmail($pdo, $this->intention['email']) ;
// 		$this->intention['verification'] = $userObject->verification ;		
// 		$userObject = $this->sessionAPI->changePassword($this->intention) ;
	}	
	
}
