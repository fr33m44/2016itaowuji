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
	$wxch_lang['ur_here'] = '公众号列表';
	if($_POST)
	{
		$name = $_POST['name'];
		$appid = $_POST['appid'];
		$appsecret =$_POST['appsecret'];
		$gh_id = $_POST['gh_id'];
		$user_id = $_POST['user_id'];
		$datetime = time();
		if(empty($appid)||empty($name)||empty($appsecret)||empty($gh_id)){
			
			sys_msg('请填写完整信息');	
	
		}
		//判断推荐ID是否为空且是否存在
		if(!empty($user_id)){
						
			$user_name = $db->getOne("SELECT user_name FROM ".$GLOBALS['ecs']->table('users')." WHERE `user_id` = '$user_id'");
			if(empty($user_name)){
				//会员不存在
					sys_msg('ID对应会员不存在，请核实ID是否正确');	
			}
					
		}else{
			//ID不能为空
			sys_msg('ID不能为空');
		}
		
		$insert_sql = "INSERT INTO `wxch_muli_user` (`name`,`token`,`appid`, `appsecret`, `gh_id` ,`user_id`,`user_name`,`datetime`) VALUES
		('$name','$token', '$appid',$appsecret, '$gh_id' ,'$user_id','$user_name','$datetime')";
		if($db->query($insert_sql))
		{
			$link[] = array('href' =>'wxch-ent.php?act=muli_user', 'text' => $wxch_lang['ur_here']);
			sys_msg('添加成功',0,$link);
		}
		else
		{
			$link[] = array('href' =>'wxch-ent.php?act=muli_user', 'text' => $wxch_lang['ur_here']);
			sys_msg('添加失败',0,$link);
		}
	}
	else
	{
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_tj_muli_user.html');
	}
}
elseif($_REQUEST['act'] == 'edit') 
{
	$wxch_lang['ur_here'] = '公众号信息';
	if($_POST)
	{
		$id = $_POST['id'];
		$name = $_POST['name'];
		$appid = $_POST['appid'];
		$appsecret =$_POST['appsecret'];
		$gh_id = $_POST['gh_id'];
		$user_id = $_POST['user_id'];
		$datetime = time();
		if(empty($appid)||empty($name)||empty($appsecret)||empty($gh_id)){
			
			sys_msg('请填写完整信息');	
	
		}
		//判断推荐ID是否为空且是否存在
		if(!empty($user_id)){
						
			$user_name = $db->getOne("SELECT user_name FROM ".$GLOBALS['ecs']->table('users')." WHERE `user_id` = '$user_id'");
			if(empty($user_name)){
				//会员不存在
					sys_msg('ID对应会员不存在，请核实ID是否正确');	
			}
					
		}else{
			//ID不能为空
			sys_msg('ID不能为空');
		}
		$update_sql = "UPDATE  `wxch_muli_user` SET  `name` =  '$name',`appid` = '$appid',`appsecret` = '$appsecret',`gh_id` = '$gh_id',`user_id` = '$user_id',`datetime` = '$datetime',`user_name` = '$user_name' WHERE  `id` ='$id';";
		
		if($db->query($update_sql))
		{
		
			$link[] = array('href' =>'wxch-ent.php?act=muli_user', 'text' => "公众号列表");
			sys_msg('修改成功',0,$link);
		}
		else
		{
			$link[] = array('href' =>'wxch-ent.php?act=muli_user', 'text' => $wxch_lang['ur_here']);
			sys_msg('修改失败',0,$link);
		}
	}
	$id = $_GET['id'];
	$data = $db->getRow("SELECT * FROM `wxch_muli_user` WHERE `id` = $id");
	$lang = array();
	$lang['tab_general'] = '主要信息';
	$smarty->assign('data', $data);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->assign('lang',$lang);
	$smarty->display('wxch_tj_muli_user.html');
}

elseif($_REQUEST['act'] == 'remove'){
	
		$id=$_GET['id'];
		if(!isset($id)){
			sys_msg("非法请求",0);
		}
		$db->query("DELETE FROM  wxch_muli_user WHERE  id = '$id' ");
		 $lnk[] = array('text' => "返回上一页", 'href' => 'wxch-ent.php?act=muli_user');
		sys_msg("删除成功", 0, $lnk);
		
}
elseif($_REQUEST['act'] == 'diymen'){

	$id=$_GET['id'];
	$ret_msg = create_menu($db,$id);

	if($ret_msg->errmsg == 'ok')
	{
	   sys_msg('设置成功',0,$link);
	}else
	{
			if($ret_msg) 
			{
				print_r($ret_msg);
				echo '<br>';
				echo '请将以上错误内容发送给'.$wxch_lang['kefu'];
			}
			else 
			{
				sys_msg('生成菜单失败，请重新生成一次，如多次仍然不行，请联系：甜心100',0,$link);
			}
	}

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
			if($v['type'] == 1)
			{
				$v['type'] = '文字';
			}
			elseif($v['type'] == 2)
			{
				$v['type'] = '图文';
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
	$contents = str_replace('&quot;','"',$contents);
	$contents = str_replace('&nbsp;','',$contents);
	return $contents;
}

function create_menu($db,$id) 
{
	access_token($db,$id);
	$ret = $db->getRow("SELECT `access_token` FROM `wxch_muli_user` where id='$id'");
	$access_token = $ret['access_token'];

	if(strlen($access_token) >= 64)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;

		$data = array();
		$sql = "SELECT * FROM  `wxch_menu` WHERE  `aid` =0";
		$data['first'] = $db->getAll($sql);
		foreach($data['first'] as $k=>$v)
		{
			if(empty($data['first'][$k]['name']))
			{
				unset($data['first'][$k]);
			}
			else
			{
				$data['first'][$k]['name'] = urlencode($v['name']);
				if($v['menu_type'] == 'click')
				{
					$data['first'][$k]['array'] = array('type'=>$v['menu_type'],'name'=>$data['first'][$k]['name'],'key'=>$v['value']);
				}
				elseif($v['menu_type'] == 'view')
				{
					$data['first'][$k]['array'] = array('type'=>$v['menu_type'],'name'=>$data['first'][$k]['name'],'url'=>$v['value']);
				}
			}
		}
		$sql = "SELECT * FROM  `wxch_menu` WHERE  `aid` =1";
		$data['second1'] = $db->getAll($sql);
		$second1 = 'no';
		foreach($data['second1'] as $k=>$v)
		{
			if(empty($data['second1'][$k]['name']))
			{
				unset($data['second1'][$k]);
			}
			else
			{
				$v['value'] = urlencode($v['value']);
				$v['name'] = urlencode($v['name']);
				if($v['menu_type'] == 'click')
				{
					$array1[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'key'=>$v['value']);
				}
				elseif($v['menu_type'] == 'view')
				{
					$array1[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'url'=>$v['value']);
				}
				$second1 = 'yes';
			}
		}
		$sql = "SELECT * FROM  `wxch_menu` WHERE  `aid` =2";
		$data['second2'] = $db->getAll($sql);
		$second2 = 'no';
		foreach($data['second2'] as $k=>$v)
		{
			if(empty($data['second2'][$k]['name']))
			{
				unset($data['second2'][$k]);
			}
			else
			{
				$v['value'] = urlencode($v['value']);
				$v['name'] = urlencode($v['name']);
				if($v['menu_type'] == 'click')
				{
					$array2[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'key'=>$v['value']);
				}
				elseif($v['menu_type'] == 'view')
				{
					$array2[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'url'=>$v['value']);
				}
				$second2 = 'yes';
			}
		}
		$sql = "SELECT * FROM  `wxch_menu` WHERE  `aid` =3";
		$data['second3'] = $db->getAll($sql);
		$second3 = 'no';
		foreach($data['second3'] as $k=>$v)
		{
			if(empty($data['second3'][$k]['name']))
			{
				unset($data['second3'][$k]);
			}
			else
			{
				$v['value'] = urlencode($v['value']);
				$v['name'] = urlencode($v['name']);
				if($v['menu_type'] == 'click')
				{
					$array3[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'key'=>$v['value']);
				}
				elseif($v['menu_type'] == 'view')
				{
					$array3[] = array('type'=>$v['menu_type'],'name'=>$v['name'],'url'=>$v['value']);
				}
				$second3 = 'yes';
			}
		}
		if($second1 == 'yes') 
		{
			$sarr1 = array('name'=>$data['first'][0]['name'],'sub_button'=>$array1);
		}
		elseif($second1 == 'no') 
		{
			$sarr1 = $data['first'][0]['array'];
		}
		if($second2 == 'yes') 
		{
			$sarr2 = array('name'=>$data['first'][1]['name'],'sub_button'=>$array2);
		}
		elseif($second2 == 'no') 
		{
			$sarr2 = $data['first'][1]['array'];
		}
		if($second3 == 'yes') 
		{
			$sarr3 = array('name'=>$data['first'][2]['name'],'sub_button'=>$array3);
		}
		elseif($second3 == 'no') 
		{
			$sarr3 = $data['first'][2]['array'];
		}
		$arr = array( 'button' => array($sarr1,$sarr2,$sarr3) );
		$menu = urldecode(json_encode($arr));
		$ret_json = curl_grab_page($url,$menu);
		$ret = json_decode($ret_json);
		if(!$ret->errcode == '0') 
		{
			$access_token = new_access_token($db,$id);
			$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
			$ret_json = curl_grab_page($url,$menu);
			$ret = json_decode($ret_json);
		}
		return $ret;
	}
	else
	{
		$access_token = new_access_token($db,$id);
		return FALSE;
	}
}
function new_access_token($db,$id) 
{
	$time = time();
	$ret = $db->getRow("SELECT * FROM `wxch_muli_user` WHERE `id` = '$id'");
	$appid = $ret['appid'];
	$appsecret = $ret['appsecret'];
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
	if(function_exists(curl_exec))
	{
		$ret_json = curl_get_contents($url);
	}
	else
	{
		echo '您的服务器不支持:curl_exec函数，无法生成菜单';
		exit;
	}
	$ret = json_decode($ret_json);
	if($ret->access_token)
	{
		$db->query("UPDATE `wxch_muli_user` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` ='$id';");
	}
	return $ret->access_token;
}


function access_token($db,$id) 
{
	$ret = $db->getRow("SELECT * FROM `wxch_muli_user` WHERE `id` ='$id'");
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
		

			$db->query("UPDATE `wxch_muli_user` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `wxch_muli_user`.`id` ='$id';");
			
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
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
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
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	ob_start();
	return curl_exec ($ch);
	ob_end_clean();
	curl_close ($ch);
	unset($ch);
}
	 function downloadimageformweixin($url) {
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_NOBODY, 0); //只取body头
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSLVERSION,3); 
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);
		return array_merge(array('body' => $package), array('header' => $httpinfo));
	 }
?>