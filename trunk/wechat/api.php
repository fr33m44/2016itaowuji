<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/../includes/init.php');
$_REQUEST['act'] = trim($_REQUEST['act']);
date_default_timezone_set('PRC');
$act = $_REQUEST['act'];
$time = time();
if($act == 'egg') 
{
	if($_POST)
	{
		$wxid = $_POST['wxid'];
		$_SESSION['wxid'] = $wxid;
		$prize_name = $_POST['prize'];
		$paid = $_POST['paid'];
		$yn = $_POST['yn'];
		$prize_id = $_POST['pid'];
		$prize_value = $_POST['prize_value'];
		$sn = $time.$wxid;
		$prize_sn = md5($sn);
		$nickname = $_POST['nickname'];
		$fun = $_POST['pzfun'];
		$query_sql = "SELECT `count` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$prize_id'";
		$prize_count = $db->getOne($query_sql);
		$num = $db->getOne("SELECT `num` FROM `wxch_prize` WHERE `pid` = '$prize_id' ");
		if(( $prize_count > $num)) 
		{
			$res['msg'] = 2;
			$res['prize'] = '您抽奖的机会用完了';
			echo json_encode($res);
			exit;
		}
		if(!empty($wxid))
		{
			if($yn == 'yes') 
			{
				$sql = "INSERT INTO `wxch_prize_users` (`wxid`, `nickname`, `fun`, `prize_id`, `prize_name`, `prize_sn`, `status`, `yn`, `dateline`) VALUES ('$wxid','$nickname','$fun','$prize_id','$prize_name', '$prize_sn', 0 , '$yn' ,$time);";
			}
			else 
			{
				$sql = "INSERT INTO `wxch_prize_users` (`wxid`, `nickname`, `fun`, `prize_id`, `prize_name`, `prize_sn`, `status`, `yn`, `dateline`) VALUES ('$wxid','$nickname','$fun','$prize_id','$prize_name', '$prize_sn', 1 , '$yn' ,$time);";
			}
			$db->query($sql);
		}
		if(!empty($wxid) and !empty($prize_id)) 
		{
			$temp_pid = $db->getOne("SELECT `wxid` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$prize_id'");
			if(empty($temp_pid)) 
			{
				$insert_sql = "INSERT INTO `wxch_prize_count` (`pid`, `wxid`, `num`, `count`, `lasttime`, `dateline`) VALUES ('$prize_id', '$wxid', '1','1','$time','$time');";
				$db->query($insert_sql);
			}
			else 
			{
				$update_sql = "UPDATE `wxch_prize_count` SET `num` = `num`+1,`count` = `count`+1,`lasttime` = '$time' WHERE `wxid`='$wxid' AND `pid` = '$prize_id';";
				$db->query($update_sql);
			}
		}
		$ck_cnum = $db->getOne("SELECT `paid` FROM `wxch_prize_cnum` WHERE `paid` = '$paid' ");
		if (empty($ck_cnum)) 
		{
			$insert_sql = "INSERT INTO `wxch_prize_cnum` (`paid`, `pid`, `prize_name`, `prize_value` , `user_count`) VALUES ('$paid', '$prize_id', '$prize_name', '$prize_value' ,'1');";
		}
		else 
		{
			$ck_prize_num = $db->getOne("SELECT `user_count` FROM `wxch_prize_cnum` WHERE `paid` = '$paid'");
			if ($ck_prize_num >= $prize_value) 
			{
				$insert_sql = "";
			}
			else 
			{
				$t_prize_value = $db->getOne("SELECT `prize_value` FROM `wxch_prize_cnum` WHERE `paid` ='$paid'");
				if($t_prize_value) 
				{
					$insert_sql = "UPDATE `wxch_prize_cnum` SET `prize_value` = `prize_value` - 1,`user_count` = `user_count` +1 WHERE `paid` ='$paid';";
				}
				else 
				{
					$insert_sql = "UPDATE `wxch_prize_cnum` SET `user_count` = `user_count` +1 WHERE `paid` ='$paid';";
				}
			}
		}
	}
	if ($insert_sql) 
	{
		$db->query($insert_sql);
	}
}
elseif($act == 'dzp') 
{
	if($_POST)
	{
		$wxid = $_POST['wxid'];
		$_SESSION['wxid'] = $wxid;
		$prize_name = $_POST['prize'];
		$paid = $_POST['paid'];
		$yn = $_POST['yn'];
		$prize_id = $_POST['pid'];
		$prize_value = $_POST['prize_value'];
		$sn = $time.$wxid;
		$prize_sn = md5($sn);
		$nickname = $_POST['nickname'];
		$fun = $_POST['pzfun'];
		$query_sql = "SELECT `count` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$prize_id'";
		$prize_count = $db->getOne($query_sql);
		$num = $db->getOne("SELECT `num` FROM `wxch_prize` WHERE `pid` = '$prize_id' ");
		if(( $prize_count >= $num)) 
		{
			$res['msg'] = 2;
			$res['prize'] = '您抽奖的机会用完了';
			echo json_encode($res);
			exit;
		}
		if(!empty($wxid))
		{
			if($yn == 'yes') 
			{
				$sql = "INSERT INTO `wxch_prize_users` (`wxid`, `nickname`, `fun`, `prize_id`, `prize_name`, `prize_sn`, `status`, `yn`, `dateline`) VALUES ('$wxid','$nickname','$fun','$prize_id','$prize_name', '$prize_sn', 0 , '$yn' ,$time);";
			}
			else 
			{
				$sql = "INSERT INTO `wxch_prize_users` (`wxid`, `nickname`, `fun`, `prize_id`, `prize_name`, `prize_sn`, `status`, `yn`, `dateline`) VALUES ('$wxid','$nickname','$fun','$prize_id','$prize_name', '$prize_sn', 1 , '$yn' ,$time);";
			}
			$db->query($sql);
		}
		if(!empty($wxid) and !empty($prize_id)) 
		{
			$temp_pid = $db->getOne("SELECT `wxid` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$prize_id'");
			if(empty($temp_pid)) 
			{
				$insert_sql = "INSERT INTO `wxch_prize_count` (`pid`, `wxid`, `num`, `count`, `lasttime`, `dateline`) VALUES ('$prize_id', '$wxid', '1','1','$time','$time');";
				$db->query($insert_sql);
			}
			else 
			{
				$update_sql = "UPDATE `wxch_prize_count` SET `num` = `num`+1,`count` = `count`+1,`lasttime` = '$time' WHERE `wxid`='$wxid' AND `pid` = '$prize_id';";
				$db->query($update_sql);
			}
		}
		$ck_cnum = $db->getOne("SELECT `paid` FROM `wxch_prize_cnum` WHERE `paid` = '$paid' ");
		if (empty($ck_cnum)) 
		{
			$insert_sql = "INSERT INTO `wxch_prize_cnum` (`paid`, `pid`, `prize_name`, `prize_value` , `user_count`) VALUES ('$paid', '$prize_id', '$prize_name', '$prize_value' ,'1');";
		}
		else 
		{
			$ck_prize_num = $db->getOne("SELECT `user_count` FROM `wxch_prize_cnum` WHERE `paid` = '$paid'");
			if ($ck_prize_num >= $prize_value) 
			{
				$insert_sql = "";
			}
			else 
			{
				$t_prize_value = $db->getOne("SELECT `prize_value` FROM `wxch_prize_cnum` WHERE `paid` ='$paid'");
				if($t_prize_value) 
				{
					$insert_sql = "UPDATE `wxch_prize_cnum` SET `prize_value` = `prize_value` - 1,`user_count` = `user_count` +1 WHERE `paid` ='$paid';";
				}
				else 
				{
					$insert_sql = "UPDATE `wxch_prize_cnum` SET `user_count` = `user_count` +1 WHERE `paid` ='$paid';";
				}
			}
		}
	}
	if ($insert_sql) 
	{
		$db->query($insert_sql);
	}
}
elseif($act == 'ggk') 
{
	if($_POST)
	{
		$wxid = $_POST['wxid'];
		$_SESSION['wxid'] = $wxid;
		$prize_name = $_POST['prize'];
		$paid = $_POST['paid'];
		$yn = $_POST['yn'];
		$prize_id = $_POST['pid'];
		$prize_value = $_POST['prize_value'];
		$time = time();
		$sn = $time.$wxid;
		$prize_sn = md5($sn);
		$nickname = $_POST['nickname'];
		$fun = $_POST['pzfun'];
		$query_sql = "SELECT `count` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$prize_id'";
		$prize_count = $db->getOne($query_sql);
		$num = $db->getOne("SELECT `num` FROM `wxch_prize` WHERE `pid` = '$prize_id' ");
		if(( $prize_count >= $num)) 
		{
			$res['msg'] = 2;
			$res['prize'] = '您抽奖的机会用完了';
			echo json_encode($res);
			exit;
		}
		if(!empty($wxid))
		{
			if($yn == 'yes') 
			{
				$sql = "INSERT INTO `wxch_prize_users` (`wxid`, `nickname`, `fun`, `prize_id`, `prize_name`, `prize_sn`, `status`, `yn`, `dateline`) VALUES ('$wxid','$nickname','$fun','$prize_id','$prize_name', '$prize_sn', 0 , '$yn' ,$time);";
			}
			else 
			{
				$sql = "INSERT INTO `wxch_prize_users` (`wxid`, `nickname`, `fun`, `prize_id`, `prize_name`, `prize_sn`, `status`, `yn`, `dateline`) VALUES ('$wxid','$nickname','$fun','$prize_id','$prize_name', '$prize_sn', 1 , '$yn' ,$time);";
			}
			$db->query($sql);
		}
		if(!empty($wxid) and !empty($prize_id)) 
		{
			$temp_pid = $db->getOne("SELECT `wxid` FROM `wxch_prize_count` WHERE `wxid` = '$wxid' AND `pid` = '$prize_id'");
			if(empty($temp_pid)) 
			{
				$insert_sql = "INSERT INTO `wxch_prize_count` (`pid`, `wxid`, `num`, `count`, `lasttime`, `dateline`) VALUES ('$prize_id', '$wxid', '1','1','$time','$time');";
				$db->query($insert_sql);
			}
			else 
			{
				$update_sql = "UPDATE `wxch_prize_count` SET `num` = `num`+1,`count` = `count`+1,`lasttime` = '$time' WHERE `wxid`='$wxid' AND `pid` = '$prize_id';";
				$db->query($update_sql);
			}
		}
		$ck_cnum = $db->getOne("SELECT `paid` FROM `wxch_prize_cnum` WHERE `paid` = '$paid' ");
		if (empty($ck_cnum)) 
		{
			$insert_sql = "INSERT INTO `wxch_prize_cnum` (`paid`, `pid`, `prize_name`, `prize_value` , `user_count`) VALUES ('$paid', '$prize_id', '$prize_name', '$prize_value' ,'1');";
		}
		else 
		{
			$ck_prize_num = $db->getOne("SELECT `user_count` FROM `wxch_prize_cnum` WHERE `paid` = '$paid'");
			if ($ck_prize_num >= $prize_value) 
			{
				$insert_sql = "";
			}
			else 
			{
				$t_prize_value = $db->getOne("SELECT `prize_value` FROM `wxch_prize_cnum` WHERE `paid` ='$paid'");
				if($t_prize_value) 
				{
					$insert_sql = "UPDATE `wxch_prize_cnum` SET `prize_value` = `prize_value` - 1,`user_count` = `user_count` +1 WHERE `paid` ='$paid';";
				}
				else 
				{
					$insert_sql = "UPDATE `wxch_prize_cnum` SET `user_count` = `user_count` +1 WHERE `paid` ='$paid';";
				}
			}
		}
	}
	if ($insert_sql) 
	{
		$db->query($insert_sql);
	}
}
;
?>