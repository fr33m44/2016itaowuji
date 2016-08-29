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

/*
此文件extend.php在EcApp.class.php中默认会加载，不再需要手动加载
用户自定义的函数，建议写在这里

注意：升级项目文件时，不要直接覆盖本文件,避免自定义函数丢失
*/

/*
//模块执行结束之后，调用的接口函数
function ec_app_end()
{
	//在这里写代码实现你要实现的功能
}
*/

//自定义模板标签解析函数
function tpl_parse_ext($template)
{
	require_once(dirname(__FILE__)."/Template_ext.php");
	$template=template_ext($template);
	return $template;

}

//自定义网址解析函数
function url_parse_ext()
{
	EcApp::$module=trim($_GET['m']);
	EcApp::$action=trim($_GET['a']);
}

?>