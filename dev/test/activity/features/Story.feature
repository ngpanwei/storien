Feature: 分享经历
为了 更好的聆听成员的声音
作为 团队管理 
我要 收集成员的经历

Scenario: 建立活动
	Given 用户 "panwei@storien.com"
	When 用户查看活动 "敏捷迭代回顾会议"
	When 用户分享经历 "今天好累"
	Then "敏捷迭代回顾会议" 活动状态是  "done"

Scenario: 自主分享经历
	Given 用户 "panwei@storien.com"
	When 用户分享经历 "今天达到零缺陷"
	Then "未提供标题" 活动状态是  "done"



