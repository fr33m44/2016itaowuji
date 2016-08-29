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

if (!defined('IN_ECTOUCH')){
    die('Hacking attempt');
}

//定义全局常量
define('ROOT_PATH', str_replace('data/convention.php', '', str_replace('\\', '/', __FILE__)));

//检测需要安装服务
if (!file_exists(ROOT_PATH.'data/install.lock') && !stripos(getcwd(), 'install')){
    header('location: ./install/index.php');
    exit;
}

//全局数据库配置
require(ROOT_PATH . '../data/config.php');
$db_config = explode(':', $db_host);

//时区设置
$config['TIMEZONE'] = $timezone; //时区设置

//日志和错误调试配置
$config['DEBUG'] = true; //是否开启调试模式，true开启，false关闭
$config['LOG_ON'] = false; //是否开启出错信息保存到文件，true开启，false不开启
$config['LOG_PATH'] = './data/log/'; //出错信息存放的目录，出错信息以天为单位存放，一般不需要修改
$config['ERROR_URL'] = ''; //出错信息重定向页面，为空采用默认的出错页面，一般不需要修改

//网址配置
$config['URL_REWRITE_ON'] = false; //是否开启重写，true开启重写,false关闭重写
$config['URL_MODULE_DEPR'] = '/'; //模块分隔符，一般不需要修改
$config['URL_ACTION_DEPR'] = '/'; //操作分隔符，一般不需要修改
$config['URL_PARAM_DEPR'] = '/'; //参数分隔符，一般不需要修改
$config['URL_HTML_SUFFIX'] = '.html'; //伪静态后缀设置，，例如 .html ，一般不需要修改
$config['URL_HTTP_HOST'] = ''; //设置网址域名

//模块配置
$config['MODULE_PATH'] = './module/'; //模块存放目录，一般不需要修改
$config['MODULE_SUFFIX'] = 'Mod.class.php'; //模块后缀，一般不需要修改
$config['MODULE_INIT'] = 'init.php'; //初始程序，一般不需要修改
$config['MODULE_DEFAULT'] = 'index'; //默认模块，一般不需要修改
$config['MODULE_EMPTY'] = 'empty'; //空模块，一般不需要修改	

//操作配置
$config['ACTION_DEFAULT'] = 'index'; //默认操作，一般不需要修改
$config['ACTION_EMPTY'] = '_empty'; //空操作，一般不需要修改

//模型配置
$config['MODEL_PATH'] = './model/'; //模型存放目录，一般不需要修改
$config['MODEL_SUFFIX'] = 'Model.class.php'; //模型后缀，一般不需要修改

//静态页面缓存
$config['HTML_CACHE_ON'] = false; //是否开启静态页面缓存，true开启.false关闭
$config['HTML_CACHE_PATH'] = './data/html_cache/'; //静态页面缓存目录，一般不需要修改
$config['HTML_CACHE_RULE']['index']['index'] = 3600; //缓存时间,单位：秒

//数据库配置
$config['DB_TYPE'] = 'mysql'; //数据库类型，一般不需要修改
$config['DB_HOST'] = $db_config[0];//数据库主机，一般不需要修改
$config['DB_PORT'] = $db_config[1];//数据库端口，mysql默认是3306，一般不需要修改
$config['DB_USER'] = $db_user;//数据库用户名
$config['DB_PWD'] = $db_pass;//数据库密码
$config['DB_NAME'] = $db_name;//数据库名
$config['DB_CHARSET'] = 'utf8';//数据库编码，一般不需要修改
$config['DB_PREFIX'] = $prefix;//数据库前缀
$config['DB_CACHE_ON'] = false; //是否开启数据库缓存，true开启，false不开启
$config['DB_CACHE_TYPE'] = 'Memcache'; //缓存类型，FileCache或Memcache或SaeMemcache
$config['DB_CACHE_TIME'] = 600; //缓存时间,0不缓存，-1永久缓存,单位：秒
$config['DB_RUN_QUERY'] = 0; //运行sql语句
$config['DB_BACKUP_PATH'] = './data/backups/'; //数据库备份目录
$config['DB_PCONNECT'] = false; //true表示使用永久连接，false表示不使用永久连接，一般不使用永久连接

//文件缓存配置
$config['DB_CACHE_PATH'] = './data/db_cache/'; //数据库查询内容缓存目录，地址相对于入口文件，一般不需要修改
$config['DB_CACHE_CHECK'] = false; //是否对缓存进行校验，一般不需要修改
$config['DB_CACHE_FILE'] = 'cachedata'; //缓存的数据文件名
$config['DB_CACHE_SIZE'] = '15M'; //预设的缓存大小，最小为10M，最大为1G
$config['DB_CACHE_FLOCK'] = true; //是否存在文件锁，设置为false，将模拟文件锁,，一般不需要修改

//memcache配置，可配置多台memcache服务器
$config['MEM_SERVER'] = array(array('127.0.0.1', 11211), array('localhost', 11211));
$config['MEM_GROUP'] = 'db';

// 模板目录
$config['TPL_TEMPLATE_PATH'] = './template/'; //模板目录，包含主题目录
$config['TPL_TEMPLATE_SUFFIX'] = '.html'; //模板后缀，一般不需要修改
$config['TPL_CACHE_ON'] = false; //是否开启模板缓存，true开启,false不开启
$config['TPL_CACHE_TYPE'] = ''; //数据缓存类型，为空或Memcache或SaeMemcache，其中为空为普通文件缓存

//普通文件缓存
$config['TPL_CACHE_PATH'] = './data/tpl_cache/'; //模板缓存目录，一般不需要修改
$config['TPL_CACHE_SUFFIX'] = '.php'; //模板缓存后缀,一般不需要修改

//memcache配置
$config['MEM_SERVER'] = array(array('127.0.0.1', 11211), array('localhost', 11211));
$config['MEM_GROUP'] = 'tpl';

//自动加载扩展目录
$config['AUTOLOAD_DIR'] = array(); //自动加载扩展目录
$config['CLASS_REFRESH'] = true; //后台类自动刷新模式
$config['RUN_LOG_ON'] = false; //后台运行日志

//是否缓存权限信息
$config['AUTH_POWER_CACHE'] = false; //设置false,每次从数据库读取，开发时建议设置为false