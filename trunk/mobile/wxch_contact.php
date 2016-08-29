<?php
define('IN_ECTOUCH', true);
require (dirname(__FILE__) . '/include/init.php');

session_start();

if(!empty($_SESSION['wxid']))
{
  $wxid = $_SESSION['wxid'];
  $wxch_message = '';
}
if(!empty($_GET['wxid']))
{
    $wxid = $_GET['wxid'];
    $wxch_message = '';
}
else
{
    $wxch_message = '抽奖信息无效';
}

if($_POST)
{
    $wxid = $_POST['wxid'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $nickname = $db->getOne("SELECT `nickname` FROM `wxch_user` WHERE `wxid` = '$wxid' ");
    $time = time();

    if(!empty($name) and !empty($phone)){
        $pid_sql = "SELECT `id` FROM `wxch_prize_users` WHERE `wxid` = '$wxid' ORDER BY `dateline` DESC LIMIT 0 , 1";
        $pid = $db->getOne($pid_sql);

        $sql = "INSERT INTO `wxch_prize_register` (`pid`, `wxid`, `nickname`, `name`, `phone`, `dateline` ) VALUES ('$pid','$wxid','$nickname' ,'$name' , '$phone', $time);";

        $db->query($sql);

        $update_sql = "UPDATE `wxch_prize_users` SET `register` = '1' WHERE `id` ='$pid';";
        $db->query($update_sql);
    }
    $wxch_message = '联系方式保存成功，我们会尽快联系您领取奖品';

}
else
{
    $smarty->assign('wxid', $wxid);
}
$smarty->assign('page_title','中奖联系方式填写');
$smarty->assign('wxch_message', $wxch_message);
$smarty->display('wxch_contact.dwt');