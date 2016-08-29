<?php

/**
 * wxch_check.php  UTF8
 * @author djks
 * @date 2014-03-14
 * @copyright http://www.weixincaihong.com
 */
define('IN_ECS', true);
require(dirname(__FILE__) . '/../../includes/init.php');
session_start();
$wxid = !empty($_GET['wxid']) ? $_GET['wxid'] : '';


    if (empty($wxid))
    {
        exit('请从微信进入');
    }
    $_SESSION['wxid'] = $wxid;
    $pid  = $_GET['pid'];
    if(empty($pid))
    {
        exit('请从微信进入');
    }




//loop
$loop = $db->getRow("SELECT * FROM `wxch_prize` WHERE `pid` = '$pid' ");

if($loop['loop'] >=1)
{
    $lasttime = $db->getOne("SELECT `lasttime` FROM `wxch_prize_count` WHERE `pid` = '$pid'");
    $uptime = $lasttime+60*60*24*$loop['loop'];

    if(time()>=$uptime)
    {
        $cid = $db->getOne("SELECT `cid` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$pid';");
        $db->query("UPDATE `wxch_prize_count` SET `count` = '0' WHERE `cid` = '$cid';");
    }

}
//检测
$egg_times = $db->getRow("SELECT * FROM `wxch_prize` WHERE `pid` = '$pid'");


$status = $egg_times['status'];

if($status == 0)
{
    exit('活动尚未开始');
}


$time = time();
//echo date("Y-m-d",$egg_times['starttime']);
//echo date("Y-m-d",$time);
//echo $time - $egg_times['starttime'];
//exit;
if($time <= $egg_times['starttime'])
{
    exit('活动尚未开始');
}
elseif($time >= $egg_times['endtime'])
{
    exit('活动已结束');
}

//活动规则
$wxch_lang['prize_dzp'] = $db->getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'prize_dzp'");


//加入抽奖数量记录
$prize['num'] = $db->getOne("SELECT `num` FROM `wxch_prize` WHERE `pid` = '$pid' ");
//检测抽奖次数
$query_sql = "SELECT `count` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$pid'";
//echo $query_sql;exit;
$prize_count = $db->getOne($query_sql);



//砸金蛋扣除多少积分
$prize['point'] = $db->getOne("SELECT `point` FROM `wxch_prize` WHERE `pid` = '$pid' ");
$query_sql1 = "SELECT `pay_points` FROM `ecs_users` WHERE `wxid` = '$wxid' ";
$user_point = $db->getOne($query_sql1);
//echo $prize_count.'--'.$prize['num'];exit;

//
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

$go_contact = $base_url . $m_url . 'wxch_contact.php'.'?wxid='.$wxid;

//prize 中奖记录
$prize_sql = "SELECT * FROM `wxch_prize_users` WHERE `yn` = 'yes' AND `prize_id` = '$pid' ORDER BY `dateline` DESC LIMIT 0,6";
$prize_users = $db->getAll($prize_sql);


//prize 奖品 数量

$sql = "SELECT * FROM `wxch_prize_append` WHERE `prize_id` = $pid";
$ret = $db->getAll($sql);
$i = 1;


foreach ($ret as $k => $v) {

    $wxchdata[$k] = $v;
    switch ($i) {
        case 1:
            $wxchdata[$k]['level'] = '一等奖';
            break;
        case 2:
            $wxchdata[$k]['level'] = '二等奖';
            break;
        case 3:
            $wxchdata[$k]['level'] = '三等奖';
            break;
        case 4:
            unset($wxchdata[$k]);
            break;
    }
    $i++;

    if (empty($wxchdata[$k]['prize_value'])) {
        unset($wxchdata[$k]);
    }

}

foreach($wxchdata as $k => $v)
{
    $wxchdata[$k]['state'] = 'yes';
}




