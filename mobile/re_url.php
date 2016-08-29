<?php

/**
 * ECSHOP 支付响应页面
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: respond.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/include/init.php');
/* 支付方式代码 */
$user_id = !empty($_REQUEST['user_id']) ? trim($_REQUEST['user_id']) : '';
$type = !empty($_REQUEST['type']) ? trim($_REQUEST['type']) : '';
/* 参数是否为空 */
if (empty($user_id))
{
    $msg = "非法请求";
}
else
{	
	$fromUsername = $db -> getOne("SELECT `wxid` FROM ".$GLOBALS['ecs']->table('users')." WHERE `user_id` = '$user_id'");
	if(empty($fromUsername)){
		$msg = "非法操作";
	}else{
			if($type==1){
			
				if(record_point("share_dfpoint",$fromUsername)){
				$jf_state = $db -> getOne("SELECT `autoload` FROM `wxch_point` WHERE `point_name` = 'share_dfpoint'");
				if ($jf_state == 'yes'){
					$info="分享给朋友返积分".$qd_jf;
					$qd_jf = $db -> getOne("SELECT `point_value` FROM `wxch_point` WHERE `point_name` = 'share_dfpoint'");
				if($qd_jf>0){
					log_account_change($user_id, 0, 0, 0, $qd_jf, $info);
					$msg = "分享成功！积分+".$qd_jf;
				}					
				}else{
				
					$msg = "此功能微开启";	
				}
			  }else{
				$msg = "今天的分享次数已经用完";	
			}
			}
			elseif($type==2){
			
				if(record_point("share_fpoint",$fromUsername)){			
				$jf_state = $db -> getOne("SELECT `autoload` FROM `wxch_point` WHERE `point_name` = 'share_fpoint'");
				if ($jf_state == 'yes'){
					$info="分享朋友圈返积分".$qd_jf;
					$qd_jf = $db -> getOne("SELECT `point_value` FROM `wxch_point` WHERE `point_name` = 'share_fpoint'");
				if($qd_jf>0){
					log_account_change($user_id, 0, 0, 0, $qd_jf, $info);
					$msg = "分享成功！积分+".$qd_jf;
				}
				}else{
					$msg = "此功能微开启";	
				}				
			}else{
				$msg = "今天的分享次数已经用完";	
			}
			
			
		}else{
			
				$msg = "非法请求";
			}		
	}		
}

assign_template();
$position = assign_ur_here();
$smarty->assign('page_title', $position['title']);   // 页面标题
$smarty->assign('ur_here',    $position['ur_here']); // 当前位置
$smarty->assign('page_title', $position['title']);   // 页面标题
$smarty->assign('ur_here',    $position['ur_here']); // 当前位置
$smarty->assign('helps',      get_shop_help());      // 网店帮助

$smarty->assign('message',    $msg);
$smarty->assign('shop_url',   $ecs->url());

$smarty->display('respond.dwt');

//记录分享积分记录  by  tianxin100
function  record_point($keyword,$fromUsername){
		
		$db=$GLOBALS['db'];
		$sql = "SELECT * FROM `wxch_point_record` WHERE `point_name` = '$keyword' AND `wxid` = '$fromUsername'";
		$record = $db -> getRow($sql);
		$num = $db -> getOne("SELECT `point_num` FROM `wxch_point` WHERE `point_name` = '$keyword'");
		$lasttime = time();
		if (empty($record)) {
			$dateline = time();
			$insert_sql = "INSERT INTO `wxch_point_record` (`wxid`, `point_name`, `num`, `lasttime`, `datelinie`) VALUES
('$fromUsername', '$keyword' , 1, $lasttime, $dateline);";
			$potin_name = $db -> getOne("SELECT `point_name` FROM `wxch_point` WHERE `point_name` = '$keyword'");
			if (!empty($potin_name)) {
				$db -> query($insert_sql);
				return true;
			}
			
		} else {

			$time = time();
			$lasttime_sql = "SELECT `lasttime` FROM `wxch_point_record` WHERE `point_name` = '$keyword' AND `wxid` = '$fromUsername'";
			$db_lasttime = $db -> getOne($lasttime_sql);
			if (($time - $db_lasttime) > (60 * 60 * 24)) {
				$update_sql = "UPDATE `wxch_point_record` SET `num` = 0,`lasttime` = '$lasttime' WHERE `wxid` ='$fromUsername';";
				$db -> query($update_sql);
			} 
			$record_num = $db -> getOne("SELECT `num` FROM `wxch_point_record` WHERE `point_name` = '$keyword' AND `wxid` = '$fromUsername'");
			if ($record_num < $num) {
				$update_sql = "UPDATE `wxch_point_record` SET `num` = `num`+1,`lasttime` = '$lasttime' WHERE `point_name` = '$keyword' AND `wxid` ='$fromUsername';";
				$db -> query($update_sql);
				return true;
			} else {
				return false;
			} 
		}
}
?>