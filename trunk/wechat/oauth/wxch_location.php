<?php
define('IN_ECTOUCH', true);
$uri = !empty($_GET['uri']) ? $_GET['uri'] : '';
if(!empty($uri)) 
{
	$uri = $_GET['uri'];
}
if(empty($uri)) 
{
	exit('uri is empty locaion');
}
require (dirname(__FILE__) . '/../../mobile/include/init.php');
$wxch_config = $db->getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
$appid = $wxch_config['appid'];
$appsecret = $wxch_config['appsecret'];
$code = !empty($_GET['code']) ? $_GET['code'] : '';
$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
$ret_json = curl_get_contents($url);
$ret = json_decode($ret_json);
$openid = !empty($ret->openid) ? $ret->openid : '';
$access_token = !empty($ret->access_token) ? $ret->access_token : '';
$cfg_baseurl = $db->getOne("SELECT cfg_value FROM wxch_cfg WHERE cfg_name = 'baseurl'");
$cfg_murl = $db->getOne("SELECT cfg_value FROM wxch_cfg WHERE cfg_name = 'murl'");
$back_url = $uri;
if(strlen($openid) == 28)
{
	$oauth_step = $db->getOne("SELECT `setp` FROM `wxch_user` WHERE `wxid` = '$openid'");
	$wxch_ecs = $ecs->table('users');
	if($oauth_step == 3) 
	{
		$query_sql = "SELECT `user_name`,`password` FROM  ".$wxch_ecs." WHERE `wxch_bd` = 'ok' AND `wxid` = '$openid'";
	}
	else 
	{
		$query_sql = "SELECT `user_name`,`password` FROM  ".$wxch_ecs." WHERE `wxch_bd` = 'no' AND `wxid` = '$openid'";
	}
	$w_res = $db->getRow($query_sql);
	if(empty($w_res)) 
	{
		$query_sql = "SELECT `user_name`,`password` FROM  ".$wxch_ecs." WHERE `wxid` = '$openid'";
		$w_res = $db->getRow($query_sql);
	}
	if ($user->login($w_res['user_name'], null, true)) 
	{
		update_user_info();
		recalculate_price();
	}
}
header("HTTP/1.1 301 Moved Permanently");
header("Location: $back_url");
exit;
function curl_get_contents($url) 
{
	if(isset($_SERVER['HTTP_USER_AGENT'])) {
		$agent = $_SERVER['HTTP_USER_AGENT'];
	} else {
		$agent = '';
	}
	
	if(isset($_SERVER['HTTP_REFERER'])) {
		$referer = $_SERVER['HTTP_REFERER'];
	} else {
		$referer = '';
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_REFERER,$referer);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$r = curl_exec($ch);
	curl_close($ch);
	return $r;
}
?>