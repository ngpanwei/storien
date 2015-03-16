Feature: Registration


Background:
	Given users :
	| username | email       | password |
	| abc      | abc@abc.com | 145d8    |
	| def      | xyc@def.com | hufuu    |
	Given non-existent users :
	| email    |
	| a@a      |

@done
Scenario: Register new user
	Given user "黄" with email "a@a"
	When user gives password "tfyitff"
	Then registration "succeeds" with code "0"

@wip
Scenario: Confirm registration
	Given user "黄" with email "abc@abc.com"
	Then registration confirmation "succeeds" with code "0"

@done
Scenario: Sign In as existing user
	Given user "abc" with email "abc@abc.com"
	When user gives password "145d8"
	Then sign in "succeeds" with code "0"

@done
Scenario: Change password of existing user
	Given user "test" with email "abc@abc.com"
	And user has old password "145d8"
	And user gives password "tfyitff"
	Then password change "succeeds" with code "0"

