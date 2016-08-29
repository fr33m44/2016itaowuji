<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/../includes/init.php');
$wxid = !empty($_GET['wxid']) ? $_GET['wxid'] : '';
if(!empty($wxid)) 
{
	access_token($db);
	$ret = $db->getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
	$access_token = $ret['access_token'];
	$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$wxid";
	$res_json = curl_get_contents($url);
	$w_user = json_decode($res_json,TRUE);
	if($w_user['errcode'] == '40001') 
	{
		$access_token = new_access_token($db);
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$wxid";
		$res_json = curl_get_contents($url);
		$w_user = json_decode($res_json,TRUE);
	}
	if(empty($w_user['nickname'])) 
	{
		if($wxid == 'oo1v-tir7oHXTL42WpwAlNsLTZlc') 
		{
			$db->query( "UPDATE  `wxch_user` SET  `nickname` =  'empty' WHERE `wxid` = '$wxid';");
		}
		exit('nickname is empty');
	}
	$ecs_users = $ecs->prefix.'users';
	$w_sql = "UPDATE  `wxch_user` SET  `nickname` =  '$w_user[nickname]',`sex` =  '$w_user[sex]',`city` =  '$w_user[city]',`country` =  '$w_user[country]',`province` =  '$w_user[province]',`language` =  '$w_user[language]',`headimgurl` =  '$w_user[headimgurl]',`localimgurl` = '$localimgurl', `subscribe_time` =  '$w_user[subscribe_time]' WHERE `wxid` = '$wxid';";
	$db->query($w_sql);
	$user_query = $db->getOne("SELECT `wxid` FROM ".$ecs_users." WHERE `wxid` = `user_name` AND wxid = '$wxid';");
	$w_users = "UPDATE  ".$ecs_users." SET  `user_name` =  '$w_user[nickname]'  WHERE `wxid` = `user_name` AND `wxch_bd` = 'no' AND `wxid` = '$wxid';";
	$users_sql = "SELECT `wxid` FROM ".$ecs_users." WHERE `user_name` = '$w_user[nickname]';";
	$users_q = $db->getOne($users_sql);
	if(empty($users_q)) 
	{
		if(strlen($user_query) == 28) 
		{
			$db->query($w_users);
		}
	}
	else 
	{
		$users_sql = "SELECT `wxid` FROM ".$ecs_users." WHERE `user_name` = '$w_user[nickname]' AND `wxid` = '$wxid';";
		$users_q = $db->getOne($users_sql);
		if(empty($users_q)) 
		{
			$w_user1 = $w_user[nickname]. mt_rand(1, 999);
			$users_sql = "SELECT `wxid` FROM ".$ecs_users." WHERE `user_name` =  '$w_user1' AND `wxid` = '$wxid';";
			$users_q = $db->getOne($users_sql);
			if(empty($users_q)) 
			{
				$w_users = "UPDATE  ".$ecs_users." SET  `user_name` =  '$w_user1'  WHERE `wxch_bd` = 'no' AND `wxid` = '$wxid';";
				$db->query($w_users);
			}
		}
	}
	echo $w_users;
}
function wxch_file($upload) 
{
	$dir = date('Ymdh');
	$img_path = 'images'. '/' . $dir . '/';
	$dir = ROOT_PATH . 'images'. '/' . $dir . '/';
	if (!file_exists($dir)) 
	{
		if (!make_dir($dir)) 
		{
			$this->error_msg = sprintf($GLOBALS['_LANG']['directory_readonly'], $dir);
			$this->error_no = ERR_DIRECTORY_READONLY;
			return false;
		}
	}
	$file_name = random_filename();
	$path_name = $dir.$file_name;
	file_put_contents($path_name,$upload);
	$img_name = $img_path.$file_name;
	return $img_name;
}
function random_filename() 
{
	$str = '';
	for($i = 0; $i < 9; $i++) 
	{
		$str .= mt_rand(0, 9);
	}
	return gmtime() . $str.'.jpg';
}
function access_token($db) 
{
	$ret = $db->getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
	$appid = $ret['appid'];
	$appsecret = $ret['appsecret'];
	$access_token = $ret['access_token'];
	$dateline = $ret['dateline'];
	$time = time();
	if(($time - $dateline) >= 7200) 
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		$ret_json = curl_get_contents($url);
		echo 1;
		$ret = json_decode($ret_json);
		if($ret->access_token)
		{
			$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` =1;");
		}
	}
	elseif(empty($access_token)) 
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		echo 2;
		$ret_json = curl_get_contents($url);
		$ret = json_decode($ret_json);
		if($ret->access_token)
		{
			$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` =1;");
		}
	}
}
function new_access_token($db) 
{
	$ret = $db->getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
	$appid = $ret['appid'];
	$appsecret = $ret['appsecret'];
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
	$ret_json = curl_get_contents($url);
	$ret = json_decode($ret_json);
	if($ret->access_token)
	{
		$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` =1;");
	}
	return $ret->access_token;
}
function curl_get_contents($url) 
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:26.0) Gecko/20100101 Firefox/26.0");
	curl_setopt($ch, CURLOPT_REFERER,$url);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$r = curl_exec($ch);
	curl_close($ch);
	return $r;
}
?>