<?php

// +----------------------------------------------------------------------
// | EcTouch [ 专注移动电商: 商创网络科技 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://ectouch.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: EcTouch Team <zhong@ecmoban.com> (QQ: 2880175560)
// +----------------------------------------------------------------------

define('IN_ECTOUCH', true);

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.2.0','<')) die('require PHP > 5.2.0 !');
// 核心框架目录，注意目录后面加"/"
define('CORE_PATH', dirname(__FILE__) . '/../include/');
// 加载系统配置
require(CORE_PATH . '/../data/config.php');
// 加载应用控制类
require(CORE_PATH . 'kernel/EcApp.class.php');
// 实例化单一入口应用控制类
$app = new EcApp($config);
// 执行项目
$app->run();
// 亲^_^ 后面不需要任何代码了 就是如此简单