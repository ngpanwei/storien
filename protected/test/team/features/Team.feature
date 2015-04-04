Feature: 团队管理

Background:
	Given 团队名称 "storien"

Scenario: 团队配置
	When 成员 "register"
	Then 系统产生活动

