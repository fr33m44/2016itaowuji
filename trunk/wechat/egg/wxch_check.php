<?php
//加密方式：php源码混淆类加密。免费版地址:http://www.zhaoyuanma.com/phpjm.php 免费版不能解密,可以使用VIP版本。

//发现了time,请自行验证这套程序是否有时间限制.
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (免费版）在线逆向还原，QQ：7530782 QQ群：342233194 
?>
<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/../../includes/init.php');
session_start();
$wxid = !empty($_GET['wxid']) ? $_GET['wxid'] : '';

	if (empty($wxid)) 
	{
		exit('请从微信进入');
	}
	$_SESSION['wxid'] = $wxid;
	$pid = $_GET['pid'];
	if(empty($pid)) 
	{
		exit('请从微信进入');
	}

$loop = $db->getRow("SELECT * FROM `wxch_prize` WHERE `pid` = '$pid' ");
if($loop['loop'] >=1) 
{
	$lasttime = $db->getOne("SELECT `lasttime` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$pid'");
	$uptime = $lasttime+60*60*24*$loop['loop'];
	if(time()>=$uptime) 
	{
		$cid = $db->getOne("SELECT `cid` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$pid';");
		$db->query("UPDATE `wxch_prize_count` SET `count` = '0' WHERE `cid` = '$cid';");
	}
}
$egg_times = $db->getRow("SELECT * FROM `wxch_prize` WHERE `pid` = '$pid'");
$status = $egg_times['status'];
if($status == 0) 
{
	exit('活动尚未开始');
}
$time = time();
if($time <= $egg_times['starttime']) 
{
	exit('活动尚未开始');
}
elseif($time >= $egg_times['endtime']) 
{
	exit('活动已结束');
}
$wxch_lang['prize_egg'] = $db->getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'prize_egg'");
$prize['num'] = $db->getOne("SELECT `num` FROM `wxch_prize` WHERE `pid` = '$pid' ");

//砸金蛋扣除多少积分
$prize['point'] = $db->getOne("SELECT `point` FROM `wxch_prize` WHERE `pid` = '$pid' ");

$query_sql1 = "SELECT `pay_points` FROM `ecs_users` WHERE `wxid` = '$wxid' ";
$user_point = $db->getOne($query_sql1);

$query_sql = "SELECT `count` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$pid'";
$prize_count = $db->getOne($query_sql);
if(( $prize_count >= $prize['num'])) 
{
	$prize['num'] = 0;
}
else 
{
	$prize['num'] = $prize['num'] - $prize_count;
}
$prize['pzfun'] = $db->getOne("SELECT `fun` FROM `wxch_prize` WHERE `pid` = '$pid' ");
$prize['nickname'] = $db->getOne("SELECT `nickname` FROM `wxch_user` WHERE `wxid` = '$wxid' ");
$base_url = $db->getOne("SELECT `cfg_value` FROM `wxch_cfg` WHERE `cfg_name` = 'baseurl' ");
$m_url = $db->getOne("SELECT `cfg_value` FROM `wxch_cfg` WHERE `cfg_name` = 'murl' ");
$go_contact = $base_url . $m_url . 'wxch_contact.php?wxid='.$wxid;
$prize_sql = "SELECT * FROM `wxch_prize_users` WHERE `yn` = 'yes' AND `prize_id` = '$pid' ORDER BY `dateline` DESC LIMIT 0,6";
$prize_users = $db->getAll($prize_sql);
$sql = "SELECT * FROM `wxch_prize_append` WHERE `prize_id` = $pid";
$ret = $db->getAll($sql);
$i = 1;
foreach ($ret as $k => $v) 
{
	$wxchdata[$k] = $v;
	switch ($i) 
	{
		case 1: $wxchdata[$k]['level'] = '一等奖';
		break;
		case 2: $wxchdata[$k]['level'] = '二等奖';
		break;
		case 3: $wxchdata[$k]['level'] = '三等奖';
		break;
		case 4: $wxchdata[$k]['level'] = '四等奖';
		break;
		case 5: $wxchdata[$k]['level'] = '五等奖';
		break;
		case 6: $wxchdata[$k]['level'] = '六等奖';
		break;
	}
	$i++;
	if (empty($wxchdata[$k]['prize_value'])) 
	{
		unset($wxchdata[$k]);
	}
}
$temp_count = count($wxchdata) -1;
foreach($wxchdata as $k => $v) 
{
	if ($temp_count == $k) 
	{
		$wxchdata[$k]['state'] = 'no';
	}
	else 
	{
		$wxchdata[$k]['state'] = 'yes';
	}
}
?>