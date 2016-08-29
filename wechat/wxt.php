<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/../includes/init.php');
require ('callback.php');
$wechatObj = new wechatCallbackapi();
$base_url = 'http://' . $_SERVER['SERVER_NAME'] . '/';
$db -> prefix = $ecs -> prefix;
$wechatObj -> valid($db);
$wechatObj -> responseMsg($db, $user, $base_url);
echo 'ok';
?>