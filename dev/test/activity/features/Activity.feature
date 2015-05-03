Feature: 个人活动
为了 投入改进和提升目标
作为 个人 
我要 时时刻刻跟踪活动动态和完成活动项

Scenario: 建立活动
	Given 用户 "panwei@storien.com" 刚 "register"
	When 用户查看活动 "欢迎"
	When 用户把 "欢迎" 活动 "done"
	Then "欢迎" 活动状态是  "done"

Scenario: 刷新活动列表
	Given 用户 "panwei@storien.com" 刚 "signin"
	When 用户刷新活动列表


