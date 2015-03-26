Feature: 个人活动
为了 投入改进和提升目标
作为 个人 
我要 时时刻刻跟踪活动动态和完成活动项

Scenario: 建立活动
	Given 用户 "panwei@storien.com" 刚 "register"
	When 用户查看活动 "欢迎"
	When 用户完成 "欢迎" 活动
	Then "欢迎" 已完成

