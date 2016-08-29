<?php
define('IN_ECS', true);
error_reporting(0);
require(dirname(__FILE__) . '/../includes/init.php');
$pid = $_GET['pid'];
$sql = "SELECT * FROM `wxch_prize_append` WHERE `prize_id` = '$pid'";
$prize_arr = $db->getAll($sql);
foreach ($prize_arr as $key => $val) 
{
	if(empty($val['prize_value'])) 
	{
		unset($prize_arr[$key]);
	}
}
$temp_count = count($prize_arr) -1;
foreach($prize_arr as $k => $v) 
{
	$ck_cnum = $db->getOne("SELECT `pid` FROM `wxch_prize_cnum` WHERE `pid` = '$v[prize_id]' ");
	if ($temp_count == $k) 
	{
		if (empty($ck_cnum)) 
		{
			$insert_sql = "INSERT INTO `wxch_prize_cnum` (`paid`, `pid`, `prize_name`, `prize_value` , `user_count`) VALUES ('$v[id]', '$v[prize_id]', '$v[prize_name]', '$v[prize_value]' ,'1');";
		}
		else 
		{
			$insert_sql = "UPDATE `wxch_prize_cnum` SET `user_count` = `user_count` +1 WHERE `wxch_prize_cnum`.`paid` ='$v[id]';";
		}
		$prize_arr[$k]['state'] = 'no';
	}
	else 
	{
		$prize_arr[$k]['state'] = 'yes';
	}
	if ($insert_sql) 
	{
		$db->query($insert_sql);
	}
	$ck_prize_num = $db->getOne("SELECT `user_count` FROM `wxch_prize_cnum` WHERE `paid` = '$v[id]'");
	if ($ck_prize_num >= $v['prize_value']) 
	{
		if($prize_arr[$k]['state'] == 'yes') 
		{
			unset($prize_arr[$k]);
		}
	}
}
foreach ($prize_arr as $key => $val) 
{
	if(!empty($val['prize_value'])) 
	{
		$arr[$val['id']] = $val['prize_value'];
	}
}
$fun = 'egg';
$rid = getRand($arr);
$wxid = $_GET['wxid'];
$query_sql = "SELECT `count` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$pid'";
$res['num'] = $db->getOne("SELECT `num` FROM `wxch_prize` WHERE `pid` = '$pid' ");
$prize_count = $db->getOne($query_sql);
//用户剩余积分
$query_sql1 = "SELECT `pay_points` FROM `ecs_users` WHERE `wxid` = '$wxid' ";
$user_point = $db->getOne($query_sql1);
//每次金蛋扣除积分
$prize['point'] = $db->getOne("SELECT `point` FROM `wxch_prize` WHERE `pid` = '$pid' ");


if(( $prize_count >= $res['num'])) 
{
	$res['msg'] = 2;
	$res['prize'] = '您抽奖的机会用完了';
	$res['num'] = 0;
	echo json_encode($res);
	exit;
}
elseif($prize['point']>$user_point)
{
	$res['msg'] = 4;
	$res['prize'] = '您的积分用完了';
	$res['num'] = 0;
	echo json_encode($res);
	exit;

}
else 
{
	foreach($prize_arr as $k=>$v) 
	{
		$prize_rand[$v['id']] = $v;
	}
	$res['msg'] = ($rid==6)?0:1;
	$res['pid'] = $prize_rand[$rid]['prize_id'];
	$res['prize'] = $prize_rand[$rid]['prize_name'];
	if($prize_rand[$rid]['state'] == 'no') 
	{
		$res['msg'] = 0;
	}
	else 
	{
		$res['msg'] = 1;
	}
	if($res['num'] <= 0) 
	{
		$res['num'] = 0;
	}
	else 
	{
		$res['num'] = $res['num'] - $prize_count;
		
	}
	$res['point']=$user_point-$prize['point'];
	$point=$user_point-$prize['point'];
	$res['pid'] = $pid;
	$res['pzfun'] = $fun;
	$res['yn'] = $prize_rand[$rid]['state'];
	$res['paid'] = $prize_rand[$rid]['id'];
	$res['prize_name'] = $res['prize'];
	$res['prize_value'] = $prize_rand[$rid]['prize_value'];
	$lasttime = time();
	$db->query("UPDATE `wxch_prize` SET `count` = `count`+1 WHERE `pid` = '$pid';");
	$db->query("UPDATE `ecs_users` SET `pay_points` ='$point' WHERE `wxid` = '$wxid';");
	echo json_encode($res);
	exit;
}
function getRand($proArr) 
{
	$result = '';
	$proSum = array_sum($proArr);
	foreach ($proArr as $key => $proCur) 
	{
		$randNum = mt_rand(1, $proSum);
		if ($randNum <= $proCur) 
		{
			$result = $key;
			break;
		}
		else 
		{
			$proSum -= $proCur;
		}
	}
	unset ($proArr);
	return $result;
}
?>