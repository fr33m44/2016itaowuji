<?php
define('IN_ECS', true);
if (file_exists('wx_init.php')) {
	require('wx_init.php');
} else {
	require(dirname(__FILE__) . '/../includes/init.php');
} 
/*
if (isset($_GET['todo'])) {
	$todo = $_GET['todo'];
} 
$dom = '12308mm.com';
$do = array();
array_push($do, 'www.' . $dom, 'test.' . $dom, 'shop.' . $dom, $dom);
$do991 = $_SERVER['HTTP_HOST'];
$do992 = explode(':', $do991);
if (count($do992) > 1) {
	$do99 = $do992[0];
} else {
	$do99 = $do991;
} 
if (!in_array($do99, $do)) {
	$todo = 'error';
} 
if ($todo == 'error') {
	exit("\r\n\r\n   ");
} */
require ('callback-ent.php');
$wechatObj = new wechatCallbackapi();
$base_url = 'http://' . $_SERVER['SERVER_NAME'] . '/';
$db -> prefix = $ecs -> prefix;
$wechatObj -> valid($db);
$wechatObj -> responseMsg($db, $user, $base_url);
echo 'ok';
