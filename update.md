2022-07-26
v2.0.0
功能更新
* 将thinkphp5.1升级为thinkphp6.x
* 修复部分bugs

2022-07-19
v1.7.0
功能更新
* 新增“我的”机器人驱动（hook方式，而且免费，强烈推荐）
* 修复部分bugs

2022-07-15
v1.6.0
功能更新
* 新增web机器人

数据库表更新
* bot_member修改wxid为varchar(100)
* bot_groupmember修改wxid为varchar(100)

2022-07-11
v1.5.1
功能更新
* 关键词回复增加选择全体好友或所有群聊
* 修复系统更新bug、素材选择翻页bug
* 新增修改用户备注、删除好友
* 新增退出群聊
* 新增基础数据统计

数据库表更新
* keyword表增加user_type字段
* 新增tj_group表

2022-07-08
v1.5.0
功能更新
* 系统支持在线安装和升级
* 修复bugs
* 新增修改群名功能

数据库表更新
* 无

2022-07-06
v1.4.0
功能更新
* 重构机器人类库架构
* 新增可爱猫驱动支持
* 新增群发间隔时间可配置

数据库表更新
* 无

2022-06-23
v1.3.0
功能更新
* 修复素材单选中文本素材选中后的回调bug
* 群发消息增加发送类型选项：单次发送和每天发送
* 新增消息转播功能
* 新增优惠券助手应用

数据库表更新
* task表新增circle、plan_hour字段
* 新增forward、yhq_config、yhq_coupon、yhq_code、yhq_reply表

2022-06-16
v1.2.2
功能更新
* 群发消息支持多素材

数据库表更新
* 表task新增medias字段，删除media_type、media_id字段

2022-05-30
v1.2.1
功能更新
* 优化推品助手的采集群功能

数据库表更新
* 表tpzs_gather新增wxids、universal 字段
* 表tpzs_grouppos新增bot_id 字段

2022-05-26
v1.2.0
功能更新
* 被动回复和关键词回复支持多个
* 增加汉字小工具

2022-04-26
v1.1.0
功能更新
* 新增群规则之移除群规则
* 新增群成员白名单

数据库表更新
* 新增whiteid、group_rule表

2022-04-20
v1.0.0
功能更新
* 新增群发消息

数据库表更新
* 新增task表

2022-04-18

功能更新
* 新增关键词回复
* 优化接口设计

数据库表更新
* 新增keyword表

2022-04-15

功能更新
* 新增被动回复

数据库表更新
* 新增reply、link表

2022-04-01

功能更新
* 新增关键词查询

数据库表更新
* 新增tpzs_channel
* 表tpzs_grouppos增加channel_id字段

2022-03-29

功能更新
* 新增京东联盟品类
* 调整采品群设置

数据库表更新
* 新增tpzs_union、tpzs_position、tpzs_gather
* 修改bot_grouppos为tpzs_grouppos表

2022-03-28

功能更新
* 将发单功能调整为推品助手应用

数据库表更新
* ky_bot_task 增加type、title字段
* ky_bot_config 改为ky_tpzs_config
* ky_bot_task 改为ky_tpzs_task
* ky_bot_team 改为ky_tpzs_team


  
