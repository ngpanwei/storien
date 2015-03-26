Feature: 用户注册和管理

Background:
	Given 团队 "storien"
#	Given 系统没有 用户 "testx@storien.com"
#	Given 系统有 用户名 "阿猫" 邮箱 "testy@storien.com" 密码 "12345678" 
	
Scenario: 注册新用户
	Given 团队 "storien"
	Given 用户名 "阿花" 邮箱 "testx@storien.com"
	When 用户 提供 密码 "12345678" 
	When 用户 提供 确认密码 "12345678"
	Then  注册结果 "success" 

Scenario: 已有用户注册
	Given 用户名 "阿猫" 邮箱 "testy@storien.com"
	When 用户 提供 密码 "12345678" 
	When 用户 提供 确认密码 "12345678"
	Then  注册结果 "failed" 

Scenario: 已有用户登入
	Given 用户名 "阿猫" 邮箱 "testy@storien.com"
	When 用户 提供 密码 "12345678"
	Then 登入 "success" 

