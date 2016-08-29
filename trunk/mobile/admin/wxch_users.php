<?php
define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');
require('wxch_lg.php');
if ($_REQUEST['act'] == 'list') 
{
	$wxch_lang['ur_here'] = '粉丝管理';
	$record_count = $db->getOne("SELECT count( uid ) FROM `wxch_user` ");
	$filter['page'] = 1;
	$filter['page_size'] = 15;
	$full_page = 1;
	if($filter['page'] <=1)
	{
		$start = 0;
	}
	else
	{
		$start = ($filter['page']-1) * $filter['page_size'];
	}
	$page_count = ceil($record_count / $filter['page_size']);
	$filter['page_count'] = $page_count;
	$filter['start'] = $start + 1;
	$filter['type'] = $_REQUEST['act'];
	$sql = $sql = "SELECT * FROM `wxch_user` ORDER BY `dateline` DESC LIMIT $start , $filter[page_size]";
	$ret = $db->getAll($sql);
	foreach($ret as $k=>$v)
	{
		$v['subscribe_time'] = date("Y-m-d",$v['subscribe_time']);
		$v['headimgurl'] = Getheadimgurl($v['headimgurl']);
		if($v['setp'] == 3) 
		{
			$v['user_name'] = $v['uname'];
		}
		else 
		{
			$v['user_name'] = '未绑定';
		}
		$v['ec_user_name'] = $db->getOne("SELECT `user_name` FROM ".$ecs->table('users')." WHERE `wxch_bd` = 'no' AND `wxid` = '$v[wxid]'");
		$wdata[] = $v;
		switch ($v['sex']) 
		{
			case 1 :$wdata[$k]['sex'] = '男';
			break;
			case 2:$wdata[$k]['sex'] = '女';
			break;
			case 0:$wdata[$k]['sex'] = '未知';
			break;
		}
	}
	$smarty->assign('user_list',$wdata);
	$filter['record_count'] = $record_count;
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->assign('page_count',$page_count);
	$smarty->assign('record_count',$record_count);
	$smarty->assign('w_user', $w_user);
	$smarty->assign('filter',$filter);
	$smarty->assign('full_page',$full_page);
	$smarty->display('wxch_users_list.html');
}
elseif($_REQUEST['act'] == 'send') 
{
	$lang['button_submit'] = '发送';
	$lang['button_reset'] = '重置';
	$wxch_lang['ur_here'] = '微信消息发送';
	$uid = !empty($_GET['uid']) ? $_GET['uid'] : '';
	$wxid = !empty($_GET['wxid']) ? $_GET['wxid'] : '';
	if(!empty($uid)) 
	{
		$sql = "SELECT * FROM `wxch_user` WHERE `uid` = '$uid'";
		$w_user = $db->getRow($sql);
		$sql = "SELECT * FROM `wxch_message` WHERE `wxid` = '$w_user[wxid]' ORDER BY `dateline` DESC LIMIT 0,5";
		$ret = $db->getAll($sql);
	}
	elseif(!empty($wxid)) 
	{
		$sql = "SELECT * FROM `wxch_user` WHERE `wxid` = '$wxid'";
		$w_user = $db->getRow($sql);
		$sql = "SELECT * FROM `wxch_message` WHERE `wxid` = '$wxid' ORDER BY `dateline` DESC LIMIT 0,5";
		$ret = $db->getAll($sql);
	}
	$message = array();
	if(count($ret)) 
	{
		foreach($ret as $v) 
		{
			date_default_timezone_set("Asia/Shanghai");
			$v['dateline'] = date("H:i",$v['dateline']);
			$message[] = $v;
		}
	}
	$smarty->assign('w_user', $w_user);
	$smarty->assign('message', $message);
	$smarty->assign('ur_here', '微信回复');
	$smarty->assign('uid',$uid);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_msg_info.html');
}
elseif($_REQUEST['act'] == 'view') 
{
	$wxch_lang['ur_here'] = '消息查看';
	$uid = !empty($_GET['uid']) ? $_GET['uid'] : '';
	if(!empty($uid)) 
	{
		$sql = "SELECT * FROM `wxch_user` WHERE `uid` = '$uid'";
		$w_user = $db->getRow($sql);
		$record_count = $db->getOne("SELECT count( id ) FROM `wxch_message` WHERE `wxid` = '$w_user[wxid]' ");
		$filter['page'] = 1;
		$filter['page_size'] = 15;
		$full_page = 1;
		if($filter['page'] <=1)
		{
			$start = 0;
		}
		else
		{
			$start = ($filter['page']-1) * $filter['page_size'];
		}
		$page_count = ceil($record_count / $filter['page_size']);
		$filter['page_count'] = $page_count;
		$filter['start'] = $start;
		$filter['type'] = $_REQUEST['act'];
		$ret = $db->getAll("SELECT * FROM `wxch_message` WHERE `wxid` = '$w_user[wxid]' ORDER BY `dateline` DESC LIMIT $start , $filter[page_size] ");
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			$v['dateline'] = date("m-d H:i",$v['dateline']);
			$wxchdata[$k] = $v;
		}
	}
	$filter['uid'] = $uid;
	$filter['record_count'] = $record_count;
	$smarty->assign('w_user', $w_user);
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('full_page',$full_page);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_msg_view.html');
}
elseif ($_REQUEST['act'] == 'remove') 
{
	$type = $_GET['type'];
	if($type == 'view') 
	{
		$uid = $_GET['uid'];
		$id = intval($_REQUEST['id']);
		$del_sql = "DELETE FROM `wxch_message` WHERE `id` = '$id';";
		$db->query($del_sql);
		$url = 'wxch_users.php?act=view&act=query&uid='.$uid.'&'. str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header("Location: $url\n");
		exit;
	}else{
	
		$id = intval($_REQUEST['id']);
		$del_sql = "DELETE FROM `wxch_user` WHERE `uid` = '$id';";
		$db->query($del_sql);
		$url = 'wxch_users.php?act=list';
		ecs_header("Location: $url\n");
	}
}
elseif($_REQUEST['act'] == 'query') 
{
	$type = $_POST['type'];
	$uid = $_REQUEST['uid'];
	if(!empty($uid)) 
	{
		$sql = "SELECT * FROM `wxch_user` WHERE `uid` = '$uid'";
		$w_user = $db->getRow($sql);
	}
	if(!empty($_POST['keyword']))
	{
		if(empty($uid)) 
		{
			$uid = $_POST['uid'];
		}
		$sql = "SELECT * FROM `wxch_user` WHERE `uid` = '$uid'";
		$w_user = $db->getRow($sql);
		$keyword = $_POST['keyword'];
		$filter['page'] = $_POST['page'];
		$filter['page_size'] = $_POST['page_size'];
		$filter['record_count'] = $db->getOne("SELECT count( wxid ) FROM `wxch_message` WHERE `message` LIKE '%$keyword%' ");
		if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) 
		{
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) 
		{
			$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
		}
		else 
		{
			$filter['page_size'] = 15;
		}
		$filter['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
		$filter['page_count'] = (!empty($filter['record_count']) && $filter['record_count'] > 0) ? ceil($filter['record_count'] / $filter['page_size']) : 1;
		if ($filter['page'] > $filter['page_count']) 
		{
			$filter['page'] = $filter['page_count'];
		}
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['type'] = 'view';
		$ret = $db->getAll("SELECT * FROM `wxch_message` WHERE `message` LIKE '%$keyword%' ORDER BY `dateline` DESC LIMIT $filter[start] , $filter[page_size]");
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			$v['dateline'] = date("H:i",$v['dateline']);
			$wxchdata[$k] = $v;
		}
		$filter['uid'] = $uid;
		$wxch_lang['ur_here'] = '微信消息查看';
		$smarty->assign('w_user', $w_user);
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->assign('filter',$filter);
		$smarty->assign('wxch_lang',$wxch_lang);
		make_json_result($smarty->fetch('wxch_msg_view.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
	}
	elseif(!empty($_POST['keywords'])) 
	{
		$keyword = $_POST['keywords'];
		$row_type = $_POST['type'];
		$filter['page'] = $_POST['page'];
		$filter['page_size'] = $_POST['page_size'];
		$filter['start'] = $_POST['start'];
		$filter['page_count'] = $_POST['page_count'];
		$filter['record_count'] = $_POST['record_count'];
		$filter['record_count'] = $db->getOne("SELECT count( wxid ) FROM `wxch_user` WHERE `$row_type` LIKE '%$keyword%' ");
		if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) 
		{
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) 
		{
			$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
		}
		else 
		{
			$filter['page_size'] = 15;
		}
		$filter['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
		$filter['page_count'] = (!empty($filter['record_count']) && $filter['record_count'] > 0) ? ceil($filter['record_count'] / $filter['page_size']) : 1;
		if ($filter['page'] > $filter['page_count']) 
		{
			$filter['page'] = $filter['page_count'];
		}
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['type'] = 'view';
		$sql = "SELECT * FROM `wxch_user` WHERE `$row_type` LIKE '%$keyword%' ORDER BY `dateline` DESC LIMIT $filter[start] , $filter[page_size]";
		$ret = $db->getAll($sql);
		foreach($ret as $k=>$v)
		{
			$v['subscribe_time'] = date("Y-m-d",$v['subscribe_time']);
			$v['headimgurl'] = Getheadimgurl($v['headimgurl']);
			if($v['setp'] == 3) 
			{
				$v['user_name'] = $v['uname'];
			}
			else 
			{
				$v['user_name'] = '未绑定';
			}
			$v['ec_user_name'] = $db->getOne("SELECT `user_name` FROM ".$ecs->table('users')." WHERE `wxch_bd` = 'no' AND `wxid` = '$v[wxid]'");
			$wdata[] = $v;
			switch ($v['sex']) 
			{
				case 1 :$wdata[$k]['sex'] = '男';
				break;
				case 2:$wdata[$k]['sex'] = '女';
				break;
				case 0:$wdata[$k]['sex'] = '未知';
				break;
			}
		}
		$wxch_lang['ur_here'] = '粉丝管理';
		$smarty->assign('user_list',$wdata);
		$smarty->assign('page_count',$filter['page_count']);
		$smarty->assign('record_count',$filter['record_count']);
		$smarty->assign('w_user', $w_user);
		$smarty->assign('filter',$filter);
		$smarty->assign('full_page',$full_page);
		$smarty->assign('wxch_lang',$wxch_lang);
		make_json_result($smarty->fetch('wxch_users_list.html'),'',array('filter' => $filter));
	}
	elseif($type == 'list') 
	{
		$wxch_lang['ur_here'] = '粉丝管理';
		$row_type = $_POST['type'];
		$filter['page'] = $_POST['page'];
		$filter['page_size'] = $_POST['page_size'];
		$filter['start'] = $_POST['start'];
		$filter['page_count'] = $_POST['page_count'];
		$filter['record_count'] = $_POST['record_count'];
		if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) 
		{
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) 
		{
			$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
		}
		else 
		{
			$filter['page_size'] = 15;
		}
		$filter['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
		$filter['page_count'] = (!empty($filter['record_count']) && $filter['record_count'] > 0) ? ceil($filter['record_count'] / $filter['page_size']) : 1;
		if ($filter['page'] > $filter['page_count']) 
		{
			$filter['page'] = $filter['page_count'];
		}
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['type'] = 'list';
		$sql = "SELECT * FROM `wxch_user` ORDER BY `dateline` DESC LIMIT $filter[start] , $filter[page_size]";
		$ret = $db->getAll($sql);
		foreach($ret as $k=>$v)
		{
			$v['subscribe_time'] = date("Y-m-d",$v['subscribe_time']);
			$v['headimgurl'] = Getheadimgurl($v['headimgurl']);
			if($v['setp'] == 3) 
			{
				$v['user_name'] = $v['uname'];
			}
			else 
			{
				$v['user_name'] = '未绑定';
			}
			$v['ec_user_name'] = $db->getOne("SELECT `user_name` FROM ".$ecs->table('users')." WHERE `wxch_bd` = 'no' AND `wxid` = '$v[wxid]'");
			$wdata[] = $v;
			switch ($v['sex']) 
			{
				case 1 :$wdata[$k]['sex'] = '男';
				break;
				case 2:$wdata[$k]['sex'] = '女';
				break;
				case 0:$wdata[$k]['sex'] = '未知';
				break;
			}
		}
		$smarty->assign('filter',$filter);
		$smarty->assign('user_list',$wdata);
		$smarty->assign('page_count',$filter['page_count']);
		$smarty->assign('record_count',$filter['record_count']);
		$smarty->assign('w_user', $w_user);
		$smarty->assign('wxch_lang',$wxch_lang);
		make_json_result($smarty->fetch('wxch_users_list.html'),'',array('filter' => $filter,'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
		exit;
	}
	elseif($type == 'view') 
	{
		$uid = $_POST['uid'];
		$sql = "SELECT * FROM `wxch_user` WHERE `uid` = '$uid'";
		$w_user = $db->getRow($sql);
		$filter['page'] = $_POST['page'];
		$filter['page_size'] = $_POST['page_size'];
		$filter['record_count'] = $_POST['record_count'];
		if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) 
		{
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) 
		{
			$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
		}
		else 
		{
			$filter['page_size'] = 15;
		}
		$filter['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
		$filter['page_count'] = (!empty($filter['record_count']) && $filter['record_count'] > 0) ? ceil($filter['record_count'] / $filter['page_size']) : 1;
		if ($filter['page'] > $filter['page_count']) 
		{
			$filter['page'] = $filter['page_count'];
		}
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['type'] = 'view';
		$ret = $db->getAll("SELECT * FROM `wxch_message` WHERE `wxid` = '$w_user[wxid]' ORDER BY `dateline` DESC LIMIT $filter[start] , $filter[page_size]");
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			$v['dateline'] = date("H:i",$v['dateline']);
			$wxchdata[$k] = $v;
		}
		$record_count = $filter['record_count'];
		$page_count = $filter['page_count'];
		$filter['uid'] = $uid;
		$wxch_lang['ur_here'] = '微信消息发送';
		$smarty->assign('page_count',$page_count);
		$smarty->assign('record_count',$record_count);
		$smarty->assign('w_user', $w_user);
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->assign('filter',$filter);
		$smarty->assign('wxch_lang',$wxch_lang);
		make_json_result($smarty->fetch('wxch_msg_view.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
	}
	$record_count = $db->getOne("SELECT count( id ) FROM `wxch_message` WHERE `wxid` = '$w_user[wxid]'");
	$filter['page'] = 1;
	$filter['page_size'] = 15;
	if($filter['page'] <=1)
	{
		$start = 0;
	}
	else
	{
		$start = ($filter['page']-1) * $filter['page_size'];
	}
	$page_count = ceil($record_count / $filter['page_size']);
	$filter['page_count'] = $page_count;
	$filter['start'] = $start;
	$filter['type'] = 'view';
	$ret = $db->getAll("SELECT * FROM `wxch_message` WHERE `wxid` = '$w_user[wxid]' ORDER BY `dateline` DESC LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		$v['dateline'] = date("H:i",$v['dateline']);
		$wxchdata[$k] = $v;
	}
	$filter['uid'] = $uid;
	$smarty->assign('w_user', $w_user);
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('wxch_lang',$wxch_lang);
	make_json_result($smarty->fetch('wxch_msg_view.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
}
elseif($_REQUEST['act'] == 'action') 
{
	$wxid = $_POST['wxid'];
	$content = $_POST['msg_content'];
	$uid = $_POST['uid'];
	$time = time();
	if(!empty($wxid)) 
	{
		if(empty($content)) 
		{
			$link[0] = array('href' =>'wxch_users.php?act=list', 'text' => '用户列表');
			sys_msg('消息不能为空',1,$link);
		}
		$access_token = access_token($db);
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
		$post_msg = '{
           "touser":"'.$wxid.'",
           "msgtype":"text",
           "text":
           {
                "content":"'.$content.'"
           }
       }';
		$ret_json = curl_grab_page($url, $post_msg);
		$ret = json_decode($ret_json);
		if($ret->errcode == '40001') 
		{
			$access_token = new_access_token($db);
			$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
			$ret_json = curl_grab_page($url,$menu);
			$ret = json_decode($ret_json);
		}
		elseif($ret->errcode == '45015') 
		{
			$link[0] = array('href' =>'wxch_users.php?act=list', 'text' => '用户列表');
			sys_msg('回复时间超过限制',0,$link);
		}
		elseif($ret->errcode == '48001') 
		{
			$link[0] = array('href' =>'wxch_users.php?act=list', 'text' => '用户列表');
			sys_msg('公众号不具备该功能',5,$link);
		}
		elseif($ret->errcode == '0') 
		{
			$db->query("INSERT INTO `wxch_message` (`wxid`, `message`, `dateline`) VALUES
( '$wxid', '$content', $time);");
			$href = 'wxch_users.php?act=send&uid='.$uid;
			$link[0] = array('href' =>$href, 'text' => '消息回复');
			sys_msg('回复成功',0,$link);
		}
	}
}
function new_access_token($db) 
{
	$ret = $db->getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
	$appid = $ret['appid'];
	$appsecret = $ret['appsecret'];
	$time = time();
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
	$ret_json = curl_get_contents($url);
	$ret = json_decode($ret_json);
	if($ret->access_token)
	{
		$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` =1;");
	}
	return $ret->access_token;
}
function access_token($db) 
{
	$ret = $db->getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
	$appid = $ret['appid'];
	$appsecret = $ret['appsecret'];
	$access_token = $ret['access_token'];
	$dateline = $ret['dateline'];
	$time = time();
	if(($time - $dateline) >= 7200) 
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		$ret_json = curl_get_contents($url);
		$ret = json_decode($ret_json);
		if($ret->access_token)
		{
			$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` =1;");
			return $ret->access_token;
		}
	}
	elseif(empty($access_token)) 
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		$ret_json = curl_get_contents($url);
		$ret = json_decode($ret_json);
		if($ret->access_token)
		{
			$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` =1;");
			return $ret->access_token;
		}
	}
	else 
	{
		return $access_token;
	}
}
function curl_get_contents($url) 
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 200);
	curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
	curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
	@curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$r = curl_exec($ch);
	curl_close($ch);
	return $r;
}
function curl_grab_page($url,$data,$proxy='',$proxystatus='',$ref_url='') 
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if ($proxystatus == 'true') 
	{
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_URL, $url);
	if(!empty($ref_url))
	{
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_REFERER, $ref_url);
	}
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	ob_start();
	return curl_exec ($ch);
	ob_end_clean();
	curl_close ($ch);
	unset($ch);
}
function Getheadimgurl($headimgurl,$type = '46') 
{
	if(!empty($headimgurl))
	{
		$headimgurl = substr($headimgurl,0,-1);
		$headimgurl = $headimgurl.$type;
	}
	return $headimgurl;
}
?>