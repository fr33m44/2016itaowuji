<?php
define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');
require('wxch_lg.php');
$act = trim($_REQUEST['act']);
$wxch_lang['ur_here'] = '砸金蛋';
if($act == 'view') 
{
	$wxid = $_GET['wxid'];
	$id = $_GET['id'];
	if(!empty($wxid)) 
	{
		$sql = "SELECT wxch_prize_users.*,wxch_prize_register.phone,wxch_prize_register.name FROM `wxch_prize_users`
       LEFT JOIN `wxch_prize_register` ON `wxch_prize_users`.wxid = `wxch_prize_register`.wxid
       WHERE `wxch_prize_register`.`wxid` = '$wxid' AND `wxch_prize_users`.`id` = '$id'
       ";
		$res = $db->getRow($sql);
		$res['dateline'] = date("Y-m-d H:i:s",$res['dateline']);
	}
	$smarty->assign('data', $res);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_zjd_info.html');
}
?>