### 架构
* 借助addon模块作为路由入口；
* addons文件夹须可写，以达到应用在线安装、升级的目的；

### 目录结构
* admin       //业务功能逻辑主模块（名称可自定义，只要和路由定义能对应就行），至少1个模块
* crontab     //定时任务模块，非必须
* platform    //消息处理器模块，必须
* 其他模块
* public      //对外开放访问入口，一般放logo和静态资源文件。此目录安装时会被移动到框架public/addons/下，文件夹名称改为对应的应用名。非必须
* vendor      //此应用依赖的三方composer包，非必须
* common.php  //应用公共函数文件，非必须
* composer.json  //composer配置文件，非必须
* info.php    //应用信息文件，必须
* Install.php  //应用安装类文件，必须
* install.sql  //应用安装文件
* menu.php    //应用的菜单文件, 必须
* route.php  //应用路由文件，必须
* upgrade.md  //当前版本升级说明，非必须
* upgrade.sql  //升级版本的SQL文件，非必须


### 路由
* 应用的路由文件需要返回函数，函数内部实现可完全参考tp6的路由定义规则

### 自定义函数
* 应用文件夹下的common.php，建议函数名称加上应用名称_作为前缀：例如demo_，防止函数名冲突