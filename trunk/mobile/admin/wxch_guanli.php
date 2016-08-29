<?php
define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');
require('wxch_lg.php');
$wxch_lang['ur_here'] = '添加提醒';
if($_POST) 
{
	$type = $_POST['type'];
	$wxid = $_POST['wxid'];
	$name = $_POST['name'];
	$autoload = $_POST['autoload'];
	$db->query("INSERT INTO `wxch_admin` (`type`, `wxid`, `name`, `autoload`) VALUES ('$type', '$wxid', '$name', '$autoload');");
	$link[] = array('href' =>'wxch-ent.php?act=guanli', 'text' => $wxch_lang['ur_here']);
	sys_msg('添加成功',0,$link);
}
$aid = $_GET['aid'];
if($aid) 
{
	$data = $db->getRow("SELECT * FROM `wxch_admin` WHERE `aid` = '$aid'");
	$smarty->assign('data',$data);
}
$smarty->assign('wxch_lang',$wxch_lang);
$smarty->display('wxch_guanli_add.html');
?>