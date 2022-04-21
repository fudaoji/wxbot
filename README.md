# [Wxbot](http://kyphp.kuryun.com/home/guide/bot.html)

#### 介绍
基于thinkphp的多商户多微信号的私域社群web系统。
系统的功能设计架构为：通用功能+插件应用，因此开发者可以基于此系统进行满足自身业务需求的应用开发。

体验链接（请在PC端打开）：
[http://wxbot.oudewa.cn/](http://wxbot.oudewa.cn/)（账号：test， 密码：123456）


- 主要功能：

![输入图片说明](%E5%8A%9F%E8%83%BD%E6%9E%B6%E6%9E%84.png)

- 界面截图：
![输入图片说明](1.png)
![输入图片说明](image.png)
![输入图片说明](3.png)
![输入图片说明](4.png)
![输入图片说明](5.png)
![输入图片说明](6.png)

#### 软件架构
- [ThinkPHP5.1](https://www.kancloud.cn/manual/thinkphp5_1/)
- Mysql
- Memcached & Redis
- [Layui](https://www.layui.com/)
- [Vlw](http://a.vlwai.cn/)
- [Vlw配套接口Xyo](https://www.yuque.com/httpapi/)

#### 安装教程

1.  拉取项目
2.  在项目根目录下`cp env .env`, 修改.env对应的配置信息
3.  将项目目录下的install/install.sql导入数据库
4.  修改项目目录、runtime、public/uploads的读写权限
5.  默认超管账号：admin 密码：123456

#### 使用文档

开发文档：[http://kyphp.kuryun.com/home/guide/bot](http://kyphp.kuryun.com/home/guide/bot.html)

#### 参与贡献

1.  Fork 本仓库
2.  新建 dev 分支
3.  提交代码
4.  新建 Pull Request

#### 交流
QQ交流群：
726177820

![输入图片说明](https://zyx.images.huihuiba.net/1-5f8afb8796b2f.png "KyPHP微信开发框架QQ群聊二维码.png")

微信交流群：

![输入图片说明](group.png)

#### 声明
本项目仅供技术研究，请勿用于任何商业用途，请勿用于非法用途，如有任何人凭此做何非法事情，均于作者无关，特此声明。