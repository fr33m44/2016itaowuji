<?php
define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');
require('wxch_lg.php');
$_REQUEST['act'] = trim($_REQUEST['act']);
if($_REQUEST['act'] == 'list') 
{
	$smarty->display('wxch_qr.html');
}
elseif($_REQUEST['act'] == 'add') 
{
	$wxch_lang['ur_here'] = '多功能二维码';
	if($_POST)
	{
		$action_name = $_POST['action_name'];
		$scene = $_POST['scene'];
		$function = $_POST['function'];
		$type = 'qr';
		$expire_seconds = $_POST['expire_seconds'];
		$ret = $db->getRow("SELECT * FROM `wxch_qr` WHERE `action_name` = 'QR_LIMIT_SCENE' ORDER BY `scene_id` DESC");
		$scene_id = $ret['scene_id']+1;
		$json_arr = array('action_name'=>$action_name,'action_info'=>array('scene'=>array('scene_id'=>$scene_id)));
		$data = json_encode($json_arr);
		access_token($db);
		$ret = $db->getRow("SELECT `access_token` FROM `wxch_config`");
		$access_token = $ret['access_token'];
		if(strlen($access_token) >= 64) 
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
			$res_json = curl_grab_page($url, $data);
			$json = json_decode($res_json);
		}
		$ticket = $json->ticket;
		if($ticket)
		{
			$ticket_url = urlencode($ticket);
			$ticket_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket_url;
			$qrimg = curl_get_contents($ticket_url);
			$time = time();
			$path = '../images/upload/'.$time.'.jpg';
			@file_put_contents($path, $qrimg);
			$qr_path = '/images/upload/'.$time.'.jpg';
			if(@filesize(ROOT_PATH.$qr_path)<10240) 
			{
				$qr_path = '';
			}
			$insert_sql = "INSERT INTO `wxch_qr` (`type`,`expire_seconds`,`action_name`,`ticket`, `scene_id`, `scene` ,`qr_path`,`function`) VALUES
   ('$type','$expire_seconds','$action_name', '$ticket',$scene_id, '$scene' ,'$qr_path','$function')";
			$db->query($insert_sql);
			$link[] = array('href' =>'wxch-ent.php?act=qr', 'text' => $wxch_lang['ur_here']);
			sys_msg('添加成功',0,$link);
		}
		else
		{
			$link[] = array('href' =>'wxch-ent.php?act=qr', 'text' => $wxch_lang['ur_here']);
			sys_msg('添加失败',0,$link);
		}
	}
	else
	{
		$smarty->assign('wxch_lang',$wxch_lang);
		if($_GET['type'] == 'scene')
		{
			$smarty->display('wxch_qr_scene.html');
		}
		elseif($_GET['type'] == 'limitscene')
		{
			$smarty->display('wxch_qr_limitscene.html');
		}
	}
}
elseif($_REQUEST['act'] == 'edit') 
{
	if($_POST)
	{
		$id = $_POST['id'];
		$scene = $_POST['scene'];
		$expire_seconds = $_POST['expire_seconds'];
		$function = $_POST['function'];
		$ticket = $db->getOne("SELECT `ticket` FROM `wxch_qr` WHERE `qid` = '$id' ");
		if($ticket)
		{
			$ticket_url = urlencode($ticket);
			$ticket_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket_url;
			$qrimg = curl_get_contents($ticket_url);
			$time = time();
			$path = '../images/upload/'.$time.'.jpg';
			@file_put_contents($path, $qrimg);
			$qr_path = '/images/upload/'.$time.'.jpg';
			if(@filesize(ROOT_PATH.$qr_path)<10240) 
			{
				$qr_path = '';
			}
			$update_sql = "UPDATE  `wxch_qr` SET  `scene` =  '$scene',`function` = '$function',`expire_seconds` = '$expire_seconds',`qr_path` = '$qr_path' WHERE  `qid` ='$id';";
			$db->query($update_sql);
			$link[] = array('href' =>'wxch-ent.php?act=qr', 'text' => $wxch_lang['ur_here']);
			sys_msg('修改成功',0,$link);
		}
		else
		{
			$link[] = array('href' =>'wxch-ent.php?act=qr', 'text' => $wxch_lang['ur_here']);
			sys_msg('修改',0,$link);
		}
	}
	$id = $_GET['id'];
	$data = $db->getRow("SELECT * FROM `wxch_qr` WHERE `qid` = $id");
	$lang = array();
	$lang['tab_general'] = '主要信息';
	$smarty->assign('data', $data);
	$smarty->assign('lang',$lang);
	$smarty->assign('data',$data);
	if($_GET['type'] == 'scene')
	{
		$smarty->display('wxch_qr_scene.html');
	}
	elseif($_GET['type'] == 'limit')
	{
		$smarty->display('wxch_qr_limitscene.html');
	}
}
elseif($_REQUEST['act'] == 'remove') 
{
	$id = $_GET['id'];
	$filter['page'] = $_GET['page'];
	$filter['page_size'] = $_GET['page_size'];
	if(empty($filter['page_size']))
	{
		$filter['page_size'] = 15;
	}
	$filter['page_count'] = $_GET['page_count'];
	$filter['record_count'] = $_GET['record_count'];
	if($filter['page'] <=1)
	{
		$start = 0;
	}
	else
	{
		$start = ($filter['page']-1) * $filter['page_size'];
	}
	$filter['start'] = $start;
	$ret = $db->getAll("SELECT * FROM `wxch_qr` LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		if($v['action_name'] == 'QR_LIMIT_SCENE')
		{
			$v['type_name'] = '永久';
		}
		elseif($v['action_name'] == 'QR_SCENE')
		{
			$v['type_name'] = '临时';
		}
		$wxchdata[$k] = $v;
	}
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	make_json_result($smarty->fetch('wxch_qr.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
}
elseif($_REQUEST['act'] == 'edit_title') 
{
	$title = json_str_iconv(trim($_POST['val']));
	make_json_result(stripslashes($title));
}
elseif($_REQUEST['act'] == 'query') 
{
	if(!empty($_POST['keyword']))
	{
		$keyword = $_POST['keyword'];
		$filter['page'] = $_POST['page'];
		$filter['page_size'] = $_POST['page_size'];
		if(empty($filter['page_size']))
		{
			$filter['page_size'] = 15;
		}
		$filter['page_count'] = ceil($_POST['page_count']/$filter['page_size']);
		$filter['record_count'] = $_POST['record_count'];
		if($filter['page'] <=1)
		{
			$start = 0;
		}
		else
		{
			$start = ($filter['page']-1) * $filter['page_size'];
		}
		$filter['start'] = $start;
		$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE `name` LIKE '%$keyword%' LIMIT $start , $filter[page_size]");
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			if($v['action_name'] == 'QR_LIMIT_SCENE')
			{
				$v['type_name'] = '永久';
			}
			elseif($v['action_name'] == 'QR_SCENE')
			{
				$v['type_name'] = '临时';
			}
			$wxchdata[$k] = $v;
		}
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->assign('filter',$filter);
		make_json_result($smarty->fetch('wxch_qr.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
	}
}
function htmltowei($contents) 
{
	$contents = strip_tags($contents,'<br>');
	$contents = str_replace('<br />',"\r\n",$contents);
	$contents = str_replace('"','"',$contents);
	$contents = str_replace(' ','',$contents);
	return $contents;
}
function access_token($db) 
{
	$ret = $db->getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
	$appid = $ret['appid'];
	$appsecret = $ret['appsecret'];
	$dateline = $ret['dateline'];
	$time = time();
	if(($time - $dateline) > 7200) 
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		$ret_json = curl_get_contents($url);
		$ret = json_decode($ret_json);
		if($ret->access_token)
		{
			$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `wxch_config`.`id` =1;");
		}
	}
}
function curl_get_contents($url) 
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 2);
	curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
	curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
	@curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	$r = curl_exec($ch);
	curl_close($ch);
	return $r;
}
function curl_grab_page($url,$data,$proxy='',$proxystatus='',$ref_url='') 
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	if ($proxystatus == 'true') 
	{
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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
?>