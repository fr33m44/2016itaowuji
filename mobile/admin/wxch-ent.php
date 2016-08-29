<?php
define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'include/cls_image.php');
require('wxch_lg.php');
$_REQUEST['act'] = trim($_REQUEST['act']);
$act = $_REQUEST['act'];
if($_REQUEST['act'] == 'wxconfig') 
{
	admin_priv('wx_api');
	
	$wxch_lang['ur_here'] = '微信接口';
	if(!empty($_POST['token']))
	{
		$token = $_POST['token'];
		$appid = $_POST['appid'];
		$appsecret = $_POST['appsecret'];
		$ret = $db->query("UPDATE `wxch_config` SET `token`='$token',`appid`='$appid',`appsecret`='$appsecret' WHERE `id`=1;");
		$link[] = array('href' =>'wxch-ent.php?act=wxconfig', 'text' => '微信接口');
		if($ret)
		{
			sys_msg('甜心100提示您设置成功',0,$link);
		}
		else
		{
			sys_msg('甜心100提示您设置失败',0,$link);
		}
	}
	else
	{
		$ret = $db->getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
		$smarty->assign('token',$ret['token']);
		$smarty->assign('appid',$ret['appid']);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('appsecret',$ret['appsecret']);
		$smarty->display('wxch_wxconfig.html');
	}
}
elseif($_REQUEST['act'] == 'wxpay') 
{
	$wxch_lang['ur_here'] = '微信支付配置';
	if(!empty($_POST['appid']))
	{
		$appid = $_POST['appid'];
		$appsecret = $_POST['appsecret'];
		$paysignkey = $_POST['paysignkey'];
		$partnerkey = $_POST['partnerkey'];
		$update_sql = "UPDATE `wxch_pay` SET `appid`='$appid',`appsecret`='$appsecret',`paysignkey`='$paysignkey',`partnerkey`='$partnerkey' WHERE `id`=1;";
		$ret = $db->query($update_sql);
		$link[] = array('href' =>'wxch-ent.php?act=wxpay', 'text' => $wxch_lang['ur_here']);
		if($ret)
		{
			sys_msg('设置成功',0,$link);
		}
		else
		{
			sys_msg('设置失败，请重新设置',0,$link);
		}
	}
	else
	{
		$ret = $db->getRow("SELECT * FROM `wxch_pay` WHERE `id` = 1");
		$smarty->assign('data',$ret);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_wxpay.html');
	}
}
elseif($_REQUEST['act'] == 'autoreg') 
{	
	admin_priv('wx_autoreg');
	$wxch_lang['ur_here'] = '自动注册';
	if($_POST) 
	{
		$auto_reg = $_POST['username'];
		$state = $_POST['state'];
		$content = $_POST['content'];
		$content=serialize($content);
		$rand = $_POST['rand'];
		$userpwd = $_POST['userpwd'];
		$cfg_sql = "UPDATE `wxch_cfg` SET `cfg_value` = '$userpwd' WHERE `cfg_name` = 'userpwd';";
		$update_sql = "UPDATE `wxch_autoreg` SET `autoreg_name` = '$auto_reg',`autoreg_rand` = '$rand',`autoreg_content`='$content',`state`=$state WHERE `autoreg_id` =1;";
		$db->query($cfg_sql);
		$ret = $db->query($update_sql);
		$link[] = array('href' =>'wxch-ent.php?act=autoreg', 'text' => $wxch_lang['ur_here']);
		if($ret)
		{
			sys_msg('设置成功',0,$link);
		}
		else
		{
			sys_msg('设置失败，请重新设置',0,$link);
		}
	}
	else 
	{
		$cfg = $db->getRow("SELECT * FROM `wxch_cfg` WHERE `cfg_name` = 'userpwd'");
		$data = $db->getRow("SELECT * FROM `wxch_autoreg` WHERE `autoreg_id` = 1");
	}
	$smarty->assign('cfg',$cfg);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->assign('data',$data);
	$smarty->assign('full_page', 1);
	$smarty->display('wxch_autoreg.html');
}
elseif($_REQUEST['act'] == 'menu') 
{
	admin_priv('wx_menu');
	
	$wxch_lang['ur_here'] = '微信菜单设置';
	if($_POST) 
	{
		$level = 1;
		$aid = 0;
		$db->query('TRUNCATE TABLE `wxch_menu`');
		$first_type = $_POST['first_type'];
		$first = $_POST['first'];
		$first_value = $_POST['first_value'];
		foreach($first as $k=>$v)
		{
			$sql = "INSERT INTO `wxch_menu` (`menu_type`, `level`, `name`, `value`, `aid`) VALUES ( '$first_type[$k]', $level, '$first[$k]', '$first_value[$k]', $aid)";
			$db->query($sql);
		}
		$level = 2;
		$aid = 1;
		$menu_type1 = $_POST['menu_type1'];
		$second1 = $_POST['second1'];
		$value1 = $_POST['value1'];
		foreach($second1 as $k=>$v)
		{
			$sql = "INSERT INTO `wxch_menu` (`menu_type`, `level`, `name`, `value`, `aid`) VALUES ( '$menu_type1[$k]', $level, '$second1[$k]', '$value1[$k]', $aid)";
			$db->query($sql);
		}
		$aid = 2;
		$menu_type2 = $_POST['menu_type2'];
		$second2 = $_POST['second2'];
		$value2 = $_POST['value2'];
		foreach($second2 as $k=>$v)
		{
			$sql = "INSERT INTO `wxch_menu` (`menu_type`, `level`, `name`, `value`, `aid`) VALUES ( '$menu_type2[$k]', $level, '$second2[$k]', '$value2[$k]', $aid)";
			$db->query($sql);
		}
		$aid = 3;
		$menu_type3 = $_POST['menu_type3'];
		$second3 = $_POST['second3'];
		$value3 = $_POST['value3'];
		foreach($second2 as $k=>$v)
		{
			$sql = "INSERT INTO `wxch_menu` (`menu_type`, `level`, `name`, `value`, `aid`) VALUES ( '$menu_type3[$k]', $level, '$second3[$k]', '$value3[$k]', $aid)";
			$db->query($sql);
		}
		$link[] = array('href' =>'wxch-ent.php?act=menu', 'text' => '微信菜单设置');

			$ret_msg = create_menu($db);
		

		if($ret_msg->errmsg == 'ok')
		{
			sys_msg('设置成功',0,$link);
		}
		else
		{
			if($ret_msg) 
			{
				print_r($ret_msg);
				echo '<br>';
				echo '请将以上错误内容发送给'.$wxch_lang['kefu'];
			}
			else 
			{
				echo '生成菜单失败，请重新生成一次，如多次仍然不行，请联系'.$wxch_lang['kefu'];
			}
		}
	}
	else 
	{
		$data = array();
		$sql = "SELECT * FROM  `wxch_menu` WHERE  `aid` =0";
		$data['first'] = $db->getAll($sql);
		$sql = "SELECT * FROM  `wxch_menu` WHERE  `aid` =1";
		$data['second1'] = $db->getAll($sql);
		$i = 0;
		foreach($data['second1'] as $k=>$v)
		{
			$i++;
			$data['second1'][$k]['num'] = $i;
		}
		$sql = "SELECT * FROM  `wxch_menu` WHERE  `aid` =2";
		$data['second2'] = $db->getAll($sql);
		$i = 0;
		foreach($data['second2'] as $k=>$v)
		{
			$i++;
			$data['second2'][$k]['num'] = $i;
		}
		$sql = "SELECT * FROM  `wxch_menu` WHERE  `aid` =3";
		$data['second3'] = $db->getAll($sql);
		$i = 0;
		foreach($data['second3'] as $k=>$v)
		{
			$i++;
			$data['second3'][$k]['num'] = $i;
		}
		$smarty->assign('data',$data);
		$smarty->assign('form_act','menu');
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_menu.html');
	}
}
elseif($_REQUEST['act'] == 'config') 
{	
	admin_priv('wx_menu');
	$wxch_lang['ur_here'] = $wxch_lang['config'];
	if($_POST)
	{
		$data = array();
		$data['murl'] = $_POST['murl'];
		$data['baseurl'] = $_POST['baseurl'];
		$data['imgpath'] = $_POST['imgpath'];
		$data['plustj'] = $_POST['plustj'];
		$data['userpwd'] = $_POST['userpwd'];
		$data['cxbd'] = $_POST['cxbd'];
		$data['bd'] = $_POST['bd'];
		$data['oauth'] = $_POST['oauth'];
		$data['goods'] = $_POST['goods'];
		$data['article'] = $_POST['article'];
		$data['q_name'] = $_POST['q_name'];
		$data['tianxin_url'] = $_POST['tianxin_url'];

		

		foreach($data as $k=>$v) 
		{
			$sql = "UPDATE  `wxch_cfg` SET  `cfg_value` =  '$v' WHERE  `cfg_name` = '$k';";
			$db->query($sql);
		}
		$link[] = array('href' =>'wxch-ent.php?act=config', 'text' => $wxch_lang['ur_here']);
		sys_msg('修改成功',0,$link);
	}
	else
	{
		$ret = $db->getAll("SELECT * FROM  `wxch_cfg` ");
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			$wxchdata[$k] = $v;
			switch ($v['cfg_name']) 
			{
				case 'murl':$wxchdata[$k]['title'] = '手机站路径';
				break;
				case 'baseurl':$wxchdata[$k]['title'] = '电脑版网址';
				break;
				case 'imgpath':$wxchdata[$k]['title'] = '图片路径';
				break;
				case 'plustj':$wxchdata[$k]['title'] = '搜索推荐';
				break;
				case 'userpwd':$wxchdata[$k]['title'] = '默认密码';
				break;
				case 'cxbd':$wxchdata[$k]['title'] = '重新绑定';
				break;
				case 'bd':$wxchdata[$k]['title'] = '绑定会员模式';
				break;
				case 'oauth':$wxchdata[$k]['title'] = '微信OAuth';
				break;
				case 'goods':$wxchdata[$k]['title'] = '显示下架商品';
				break;
				case 'article':$wxchdata[$k]['title'] = '文章路径';
				break;
				case 'q_name':$wxchdata[$k]['title'] = '自定义会员名前缀';
				break;
				case 'tianxin_url':$wxchdata[$k]['title'] = '分销引导关注URL';
				break;
			
			}
		}
		$smarty->assign('form_act','config');
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->display('wxch_config.html');
	}
}
elseif($_REQUEST['act'] == 'bonus') 
{
	admin_priv('wx_bonus');
	
	$wxch_lang['ur_here'] = '关注送红包设置';
	if($_POST) 
	{
		$type_id = $_POST['bonus'];
		$sql = "UPDATE `wxch_coupon` SET `type_id`='$type_id'";
		$ret = $db->query($sql);
		$link[] = array('href' =>'wxch-ent.php?act=bonus', 'text' => '关注送红包');
		if($ret)
		{
			sys_msg('设置成功',0,$link);
		}
		else
		{
			sys_msg('设置失败，请重新设置',0,$link);
		}
	}
	else 
	{
		$thistable = $ecs->prefix.'bonus_type';
		$sql = "SELECT * FROM  `$thistable` WHERE `send_type` = 3";
		$w_data = $db->getAll($sql);
		$ret = $db->getRow("SELECT `type_id` FROM `wxch_coupon` WHERE `id` = 1");
		$type_id = $ret['type_id'];
		$smarty->assign('w_data',$w_data);
		$smarty->assign('type_id',$type_id);
		$smarty->assign('form_act','bonus');
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_bonus.html');
	}
}
elseif($_REQUEST['act'] == 'regmsg') 
{	
	admin_priv('wx_regmsg');
	
	if($_POST)
	{	
		$id=$_POST['id'];
		if($id==3){
			$update_sql = "UPDATE  `wxch_keywords1` SET  `is_start` = 0 WHERE  `type` =4;";
			$db->query($update_sql);				
		}else{
		
			$update_sql = "UPDATE  `wxch_keywords1` SET  `is_start` = 0 WHERE  `type` =3;";
			$db->query($update_sql);
		}
		$update_sql = "UPDATE  `wxch_keywords1` SET  `is_start` = 1 WHERE  `type` ='$id';";
		$db->query($update_sql);
		$link[] = array('href' =>'wxch-ent.php?act=regmsg', 'text' => '消息自动回复');
		sys_msg('切换成功',0,$link);
	}else{
	$wxch_lang['ur_here'] = '关注回复设置';
	$del_sql = "SELECT * FROM `wxch_keywords` ORDER BY `wxch_keywords`.`id` DESC LIMIT 0,19";
	$ret = $db->getAll($del_sql);
	
	$sql1 = "SELECT type FROM `wxch_keywords1` where is_start=1";
	$ret1 = $db->getOne($sql1);
	$smarty->assign('ret1',$ret1);
	foreach($ret as $k)
	{
		if(empty($k['name']) and empty($k['keyword']))
		{
			$db->query("DELETE FROM `wxch_keywords` WHERE `wxch_keywords`.`id` = $k[id]");
		}
	}
	$record_count = $db->getOne("SELECT count( id ) FROM `wxch_keywords` ");
	$full_page = 1;
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
	$filter['type'] = 'list';
	$ret = $db->getAll("SELECT * FROM `wxch_keywords` LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		if($v['type'] == 1)
		{
			$v['type_name'] = '文字';
		}
		elseif($v['type'] == 2)
		{
			$v['type_name'] = '图文';
		}
		$wxchdata[$k] = $v;
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('full_page',$full_page);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_keywords_gz.html');
	}
}
elseif($_REQUEST['act'] == 'lang') 
{
	admin_priv('wx_lang');
	
	$wxch_lang['ur_here'] = '自定义语言设置';
	$ret = $db->getAll("SELECT * FROM `wxch_lang`");
	$record_count = $db->getOne("SELECT count( lang_id ) FROM `wxch_lang` ");
	$filter['page'] = 1;
	$filter['page_size'] = 30;
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
	$sql = "SELECT * FROM `wxch_lang` LIMIT $start , $filter[page_size]";
	$ret = $db->getAll($sql);
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		$wxchdata[$k] = $v;
		switch ($v['lang_name']) 
		{
			case 'regmsg':$wxchdata[$k]['lang_name'] = '关注自动回复';
			break;
			case 'help':$wxchdata[$k]['lang_name'] = '帮忙说明';
			break;
			case 'coupon':$wxchdata[$k]['lang_name'] = '已经领取优惠卷';
			break;
			case 'coupon1':$wxchdata[$k]['lang_name'] = '优惠卷前言';
			break;
			case 'coupon2':$wxchdata[$k]['lang_name'] = '优惠卷送完';
			break;
			case 'coupon3':$wxchdata[$k]['lang_name'] = '优惠卷尾部';
			break;
			case 'qdok':$wxchdata[$k]['lang_name'] = '签到成功';
			break;
			case 'qdno':$wxchdata[$k]['lang_name'] = '签到数用完';
			break;
			case 'qdstop':$wxchdata[$k]['lang_name'] = '签到关闭';
			break;
			case 'bd':$wxchdata[$k]['lang_name'] = 'web模式绑定会员';
			break;
			case 'goods':$wxchdata[$k]['lang_name'] = '显示下架商品';
			break;
			case 'prize_egg':$wxchdata[$k]['lang_name'] = '砸金蛋抽奖规则';
			break;
			case 'prize_dzp':$wxchdata[$k]['lang_name'] = '大转盘抽奖规则';
			break;
		}
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('full_page',$full_page);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_lang.html');
}
elseif($_REQUEST['act'] == 'keywords') 
{
	admin_priv('wx_keywords');
	
	$wxch_lang['ur_here'] = '关键词自动回复';
	$del_sql = "SELECT * FROM `wxch_keywords` ORDER BY `wxch_keywords`.`id` DESC LIMIT 0,19";
	$ret = $db->getAll($del_sql);
	foreach($ret as $k)
	{
		if(empty($k['name']) and empty($k['keyword']))
		{
			$db->query("DELETE FROM `wxch_keywords` WHERE `wxch_keywords`.`id` = $k[id]");
		}
	}
	$record_count = $db->getOne("SELECT count( id ) FROM `wxch_keywords` ");
	$full_page = 1;
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
	$filter['type'] = 'list';
	$ret = $db->getAll("SELECT * FROM `wxch_keywords` LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		if($v['type'] == 1)
		{
			$v['type_name'] = '文字';
		}
		elseif($v['type'] == 2)
		{
			$v['type_name'] = '图文';
		}
		$wxchdata[$k] = $v;
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('full_page',$full_page);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_keywords.html');
}
elseif($_REQUEST['act'] == 'oauth') 
{
	admin_priv('wx_oauth');
	
	$wxch_lang['ur_here'] = 'OAuth管理';
	$record_count = $db->getOne("SELECT count( oid ) FROM `wxch_oauth` ");
	$full_page = 1;
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
	$filter['type'] = 'oauth';
	$cfg_baseurl = $db->getOne("SELECT cfg_value FROM wxch_cfg WHERE cfg_name = 'baseurl'");
	$oauth_url = $cfg_baseurl.'wechat/oauth/wxch_oauth.php?oid=';
	$ret = $db->getAll("SELECT * FROM `wxch_oauth` LIMIT $filter[start] , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		$v['oauthurl'] = $oauth_url.$v['oid'];
		$wxchdata[$k] = $v;
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('full_page',$full_page);
	$smarty->display('wxch_oauth.html');
}
elseif($_REQUEST['act'] == 'point') 
{
	admin_priv('wx_point');
	
	$wxch_lang['ur_here'] = '积分增加设置';
	if($_POST) 
	{
		$autoload = $_POST['autoload'];
		$point_value = $_POST['point_value'];
		$point_name = $_POST['point_name'];
		$point_num = $_POST['point_num'];
		foreach($point_name as $k=>$v)
		{
			if($autoload[$v] == 1)
			{
				$autoload[$v] = 'yes';
			}
			else
			{
				$autoload[$v] = 'no';
			}
			$sql = "UPDATE `wxch_point` SET  `point_value` = $point_value[$v],`point_num` = '$point_num[$v]',`autoload` =  '$autoload[$v]' WHERE  `point_name` ='$point_name[$k]';";
			$db->query($sql);
		}
		$link[] = array('href' =>'wxch-ent.php?act=point', 'text' => $wxch_lang['ur_here']);
		sys_msg('设置成功',0,$link);
	}
	else 
	{
		$sql = "SELECT * FROM `wxch_point`";
		$ret = $db->getAll($sql);
		foreach ($ret as $k => $v) 
		{
			switch ($v['point_name']) 
			{
				case 'news':$ret[$k]['name'] = '新品查看';
				break;
				case 'best':$ret[$k]['name'] = '精品查看';
				break;
				case 'hot':$ret[$k]['name'] = '热销查看';
				break;
				case 'bd':$ret[$k]['name'] = '绑定会员';
				break;
				case 'cxbd':$ret[$k]['name'] = '重新绑定';
				break;
				case 'ddcx':$ret[$k]['name'] = '订单查询';
				break;
				case 'kdcx':$ret[$k]['name'] = '快递查询';
				break;
				case 'ggl':$ret[$k]['name'] = '刮刮乐';
				break;
				case 'zjd':$ret[$k]['name'] = '砸金蛋';
				break;
				case 'qiandao':$ret[$k]['name'] = '签到送积分';
				break;
				case 'dzp':$ret[$k]['name'] = '大转盘';
				break;
				case 'share_fpoint':$ret[$k]['name'] = '分享朋友圈送积分';
				break;
				case 'share_dfpoint':$ret[$k]['name'] = '分享给朋友送积分';
				break;
				case 'g_point':$ret[$k]['name'] = '关注赠送积分';
				break;				
			}
		}
		$form_act = 'point';
		$smarty->assign('data',$ret);
		$smarty->assign('form_act',$form_act);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_point.html');
	}
}
elseif($_REQUEST['act'] == 'prize') 
{
	admin_priv('wx_prize');
	
	$wxch_lang['ur_here'] = '抽奖规则';
	$record_count = $db->getOne("SELECT count( pid ) FROM `wxch_prize` ");
	$filter['page'] = 1;
	$filter['page_size'] = 15;
	$full_page = 1;
	if ($filter['page'] <= 1) 
	{
		$start = 0;
	}
	else 
	{
		$start = ($filter['page'] - 1) * $filter['page_size'];
	}
	$page_count = ceil($record_count / $filter['page_size']);
	$filter['page_count'] = $page_count;
	$filter['start'] = $start;
	$filter['type'] = 'prize';
	$ret = $db->getAll("SELECT * FROM `wxch_prize` ORDER BY `dateline` DESC LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach ($ret as $k => $v) 
	{
		$v['starttime'] = date("Y-m-d", $v['starttime']);
		$v['endtime'] = date("Y-m-d", $v['endtime']);
		$wxchdata[$k] = $v;
		switch($v['fun']) 
		{
			case 'egg':$wxchdata[$k]['fun_title'] = '砸金蛋';
			break;
			case 'dzp':$wxchdata[$k]['fun_title'] = '大转盘';
			break;
		}
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('filter', $filter);
	$form_act = 'prize';
	$smarty->assign('wxchdata', $wxchdata);
	$smarty->assign('full_page', $full_page);
	$smarty->assign('form_act', $form_act);
	$smarty->assign('wxch_lang', $wxch_lang);
	$smarty->display('wxch_prize.html');
}
elseif($_REQUEST['act'] == 'zjd') 
{
	admin_priv('wx_zjd');
	
	$wxch_lang['ur_here'] = '砸金蛋';
	$record_count = $db->getOne("SELECT count( wxid ) FROM `wxch_prize_users` WHERE `fun` = 'egg' AND `yn` = 'yes' ");
	$full_page = 1;
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
	$filter['type'] = 'zjd';
	$ret = $db->getAll("SELECT * FROM `wxch_prize_users` WHERE `fun` = 'egg' AND `yn` = 'yes' ORDER BY `dateline` ASC LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		$v['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
		if(empty($v['nickname'])) 
		{
			$v['name'] = $v['wxid'];
		}
		else 
		{
			$v['name'] = $v['nickname'];
		}
		$wxchdata[$k] = $v;
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxch_ver',1);
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->assign('full_page',$full_page);
	$smarty->display('wxch_zjd.html');
}
elseif($_REQUEST['act'] == 'dzp') 
{
	admin_priv('wx_dzp');
	
	$wxch_lang['ur_here'] = '大转盘';
	$record_count = $db->getOne("SELECT count( wxid ) FROM `wxch_prize_users` WHERE `fun` = 'dzp' AND `yn` = 'yes' ");
	$full_page = 1;
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
	$filter['type'] = 'dzp';
	$ret = $db->getAll("SELECT * FROM `wxch_prize_users` WHERE `fun` = 'dzp' AND `yn` = 'yes' ORDER BY `dateline` ASC LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		$v['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
		if(empty($v['nickname'])) 
		{
			$v['name'] = $v['wxid'];
		}
		else 
		{
			$v['name'] = $v['nickname'];
		}
		$wxchdata[$k] = $v;
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxch_ver',1);
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->assign('full_page',$full_page);
	$smarty->display('wxch_dzp.html');
}
elseif($_REQUEST['act'] == 'ggk') 
{
	$wxch_lang['ur_here'] = '大转盘';
	$record_count = $db->getOne("SELECT count( wxid ) FROM `wxch_prize_users` WHERE `fun` = 'ggk' AND `yn` = 'yes' ");
	$full_page = 1;
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
	$page_count = ceil($record_count / $filter['page_size']);
	$filter['page_count'] = $page_count;
	$filter['start'] = $start;
	$filter['type'] = 'dzp';
	$ret = $db->getAll("SELECT * FROM `wxch_prize_users` WHERE `fun` = 'ggk' AND `yn` = 'yes' ORDER BY `dateline` ASC LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		$v['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
		if(empty($v['nickname'])) 
		{
			$v['name'] = $v['wxid'];
		}
		else 
		{
			$v['name'] = $v['nickname'];
		}
		$wxchdata[$k] = $v;
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxch_ver',1);
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->assign('full_page',$full_page);
	$smarty->display('wxch_ggl.html');
}
elseif($_REQUEST['act'] == 'fun') 
{
	admin_priv('wx_fun');
	
	$wxch_lang['ur_here'] = '功能变量';
	$ret = $db->getAll("SELECT * FROM `wxch_msg`");
	$record_count = $db->getOne("SELECT count( id ) FROM `wxch_msg` ");
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
	$sql = "SELECT * FROM `wxch_msg` LIMIT $start , $filter[page_size]";
	$ret = $db->getAll($sql);
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		$wxchdata[$k] = $v;
		if(empty($v['command']))
		{
			$wxchdata[$k]['count'] = 0;
		}
		else
		{
			$t_ret = explode(' ',$v['command']);
			$wxchdata[$k]['count'] = count($t_ret);
		}
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('msgkey',$msgkey);
	$smarty->assign('filter',$filter);
	$smarty->assign('wxch_msg',$ret);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->assign('full_page',$full_page);
	$smarty->display('wxch_fun.html');
}
elseif($_REQUEST['act'] == 'qr') 
{
	admin_priv('wx_qr');
	
	$wxch_lang['ur_here'] = '多功能二维码';
	$_SESSION['act_uri'] = 'qr';
	$record_count = $db->getOne("SELECT count( qid ) FROM `wxch_qr` WHERE `type` = 'qr'");
	$full_page = 1;
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
	$filter['type'] = 'qr';
	$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE `type` = 'qr' ORDER BY `qid` DESC LIMIT $filter[start] , $filter[page_size]");
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
		if(empty($v['qr_path'])) 
		{
			$v['qr_path'] = 'http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$v['ticket'];
		}
		else 
		{
			$v['qr_path'] = '../'.$v['qr_path'];
		}
		$wxchdata[$k] = $v;
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('full_page',$full_page);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_qr.html');
}
elseif($_REQUEST['act'] == 'order') 
{
	admin_priv('wx_order');
	
	$wxch_lang['ur_here'] = '发货提醒';
	if($_POST) 
	{
		$title = $_POST['title'];
		$image = wxch_upload_file($_FILES['image']);
		$img_url = $_POST['img_url'];
		$http_ret1 = stristr($img_url,'http://');
		$http_ret2 = stristr($img_url, 'http:\\');
		$autoload = $_POST['autoload'];
		if($image) 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`image` = '$image',`autoload` = '$autoload' WHERE `id` = 1;";
		}
		elseif($http_ret1 or $http_ret2) 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`image` = '$img_url',`autoload` = '$autoload' WHERE `id` = 1;";
		}
		else 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`autoload` = '$autoload' WHERE `id` = 1;";
		}
		$db->query($sql);
		$href = 'wxch-ent.php?act='.$act;
		$link[] = array('href' => $href, 'text' => $wxch_lang['ur_here']);
		sys_msg('设置成功',0,$link);
	}
	else 
	{
		$sql = "SELECT * FROM `wxch_order` WHERE `id` = 1";
		$ret =$db->getRow($sql);
		$smarty->assign('order',$ret);
		$smarty->assign('form_act','order');
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_order.html');
	}
}
elseif($_REQUEST['act'] == 'pay') 
{
	admin_priv('wx_pay');
	
	$wxch_lang['ur_here'] = '成功支付提醒';
	if($_POST) 
	{
		$title = $_POST['title'];
		$image = wxch_upload_file($_FILES['image']);
		$img_url = $_POST['img_url'];
		$http_ret1 = stristr($img_url,'http://');
		$http_ret2 = stristr($img_url, 'http:\\');
		$autoload = $_POST['autoload'];
		if($image) 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`image` = '$image',`autoload` = '$autoload' WHERE `id` = 3;";
		}
		elseif($http_ret1 or $http_ret2) 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`image` = '$img_url',`autoload` = '$autoload' WHERE `id` = 3;";
		}
		else 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`autoload` = '$autoload' WHERE `id` = 3;";
		}
		$db->query($sql);
		$href = 'wxch-ent.php?act='.$act;
		$link[] = array('href' => $href, 'text' => '成功付款');
		sys_msg('设置成功',0,$link);
	}
	else 
	{
		$sql = "SELECT * FROM `wxch_order` WHERE `id` = 3";
		$ret =$db->getRow($sql);
		$ret['order_title'] = '成功付款';
		$smarty->assign('order',$ret);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('form_act','pay');
		$smarty->display('wxch_pay.html');
	}
}
elseif($_REQUEST['act'] == 'reorder') 
{
	admin_priv('wx_reorder');
	
	$wxch_lang['ur_here'] = '订单提醒';
	if($_POST) 
	{
		$title = $_POST['title'];
		$image = wxch_upload_file($_FILES['image']);
		$img_url = $_POST['img_url'];
		$http_ret1 = stristr($img_url,'http://');
		$http_ret2 = stristr($img_url, 'http:\\');
		$autoload = $_POST['autoload'];
		if($image) 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`image` = '$image',`autoload` = '$autoload' WHERE `id` = 2;";
		}
		elseif($http_ret1 or $http_ret2) 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`image` = '$img_url',`autoload` = '$autoload' WHERE `id` = 2;";
		}
		else 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`autoload` = '$autoload' WHERE `id` = 2;";
		}
		$db->query($sql);
		$href = 'wxch-ent.php?act='.$act;
		$link[] = array('href' => $href, 'text' => $wxch_lang['ur_here']);
		sys_msg('设置成功',0,$link);
	}
	else 
	{
		$sql = "SELECT * FROM `wxch_order` WHERE `id` = 2";
		$ret =$db->getRow($sql);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('order',$ret);
		$smarty->assign('form_act','reorder');
		$smarty->display('wxch_reorder.html');
	}
}
elseif($_REQUEST['act'] == 'tuijian_reply') 
{
	//admin_priv('wx_reorder');
	
	$wxch_lang['ur_here'] = '发展会员关注提醒';
	if($_POST) 
	{
		$title = $_POST['title'];
		$image = wxch_upload_file($_FILES['image']);
		$img_url = $_POST['img_url'];
		$http_ret1 = stristr($img_url,'http://');
		$http_ret2 = stristr($img_url, 'http:\\');
		$autoload = $_POST['autoload'];
		if($image) 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`image` = '$image',`autoload` = '$autoload' WHERE `id` = 4";
		}
		elseif($http_ret1 or $http_ret2) 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`image` = '$img_url',`autoload` = '$autoload' WHERE `id` = 4";
		}
		else 
		{
			$sql = "UPDATE `wxch_order` SET `title` = '$title',`autoload` = '$autoload' WHERE `id` = 4";
		}
		$db->query($sql);
		$href = 'wxch-ent.php?act='.$act;
		$link[] = array('href' => $href, 'text' => $wxch_lang['ur_here']);
		sys_msg('设置成功',0,$link);
	}
	else 
	{
		$sql = "SELECT * FROM `wxch_order` WHERE `id` = 4";
		$ret =$db->getRow($sql);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('order',$ret);
		$smarty->assign('form_act','tuijian_reply');
		$smarty->display('wxch_tuijian_reply.html');
	}
}
elseif($_REQUEST['act'] == 'guanli') 
{
	$wxch_lang['ur_here'] = '管理员提醒';
	$record_count = $db->getOne("SELECT count( aid ) FROM `wxch_admin`");
	$full_page = 1;
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
	$filter['type'] = 'guanli';
	$ret = $db->getAll("SELECT * FROM `wxch_admin` ORDER BY `aid` DESC LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		$v['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
		if($v['type'] == 'reorder') 
		{
			$v['type_name'] = '订单提醒';
		}
		elseif($v['type'] == 'pay') 
		{
			$v['type_name'] = '支付提醒';
		}
		$v['nickname'] = $db->getOne("SELECT `nickname` FROM `wxch_user` WHERE `wxid` = '$v[wxid]'");
		$wxchdata[$k] = $v;
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxch_ver',1);
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->assign('full_page',$full_page);
	$smarty->display('wxch_guanli.html');
}
elseif($_REQUEST['act'] == 'tuijian') 
{
	admin_priv('wx_tuijian');
	
	$wxch_lang['ur_here'] = '扫码引荐';
	$_SESSION['act_uri'] = 'tuijian';
	$record_count = $db->getOne("SELECT count( qid ) FROM `wxch_qr` WHERE `affiliate` !=0 ");
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
	$filter['type'] = 'qr';
	$ret = $db->getAll("SELECT * FROM `wxch_qr`  WHERE `affiliate` !=0 ORDER BY `qid` DESC LIMIT $start , $filter[page_size]");
	$wxchdata = array();
	foreach($ret as $k=>$v)
	{
		if($v['action_name'] == 'QR_LIMIT_SCENE')
		{
			$v['type_name'] = '永久';
		}
		if(empty($v['qr_path'])) 
		{
			$v['qr_path'] = 'http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$v['ticket'];
		}
		else 
		{

		
			$v['qr_path'] =$v['qr_path'];
			
		}
		if($v['endtime']) 
		{
			$v['endtime'] = date("YmdH",$v['endtime'])-date("YmdH",$v['dateline']).'小时';
		}
		else 
		{
			$v['endtime'] = '无限制';
		}
		$af_table = $ecs->prefix.'affiliate_log';
		$af_query = "SELECT * FROM `$af_table` WHERE `user_id` = $v[affiliate] AND `separate_type` = 1";
		$af_ret = $db->getAll($af_query);
		$af_money = 0;
		foreach($af_ret as $kk=>$vv) 
		{
			$af_order = $ecs->prefix.'order_info';
			$af_order_id = $vv['order_id'];
			$af_order_query = "SELECT `money_paid`,`surplus` FROM `$af_order` WHERE `order_id` = $af_order_id";
			$af_ms = $db->getRow($af_order_query);
			$af_money = $af_money+$af_ms['money_paid']+$af_ms['surplus'];
		}
		$v['money'] += $af_money;
		$v['scan_count'] = $v['scan']+$v['subscribe'];
		$wxchdata[$k] = $v;
	}
	$filter['record_count'] = $record_count;
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->assign('filter',$filter);
	$smarty->assign('full_page',$full_page);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_tuijian.html');
}
//多用户服务号推广
elseif($_REQUEST['act'] == 'muli_user') 
{
	$wxch_lang['ur_here'] = '推广服务号列表';
	$_SESSION['act_uri'] = 'mul_user';
	$record_count = $db->getOne("SELECT count( * ) FROM `wxch_muli_user` ");
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
	$filter['type'] = 'qr';
	$ret = $db->getAll("SELECT * FROM `wxch_muli_user`  ORDER BY `id` DESC LIMIT $start , $filter[page_size]");
	$filter['record_count'] = $record_count;
	$smarty->assign('wxchdata',$ret);
	$smarty->assign('filter',$filter);
	$smarty->assign('full_page',$full_page);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_muli_user.html');
}
elseif($_REQUEST['act'] == 'messages') 
{
	if(!empty($_POST['regmsg']))
	{
		$lang_name = $_POST['act'];
		$lang_value = $_POST['regmsg'];
		$sql = "SELECT * FROM `wxch_lang` WHERE `lang_name` = 'regmsg'";
		$ret =$db->getOne($sql);
		if($ret)
		{
			$sql = "UPDATE `wxch_lang` SET `lang_value` = '$lang_value' WHERE `lang_name` = '$lang_name'";
			$ret = $db->query($sql);
			$link[] = array('href' =>'wxch-ent.php?act=regmsg', 'text' => '关注回复设置');
			if($ret)
			{
				sys_msg('修改成功',0,$link);
			}
			else
			{
				sys_msg('修改成功，请重新修改',0,$link);
			}
		}
		else
		{
			$sql = "INSERT INTO `wxch_lang` (`lang_name` ,`lang_value`) VALUES ( '$lang_name', '$lang_value')";
			$ret = $db->query($sql);
			$link[] = array('href' =>'wxch-ent.php?act=regmsg', 'text' => '关注回复设置');
			if($ret)
			{
				sys_msg('添加成功',0,$link);
			}
			else
			{
				sys_msg('添加成功，请重新添加',0,$link);
			}
		}
	}
	else
	{
		$type = !empty($_GET['type']) ? $_GET['type'] : '';
		require(ROOT_PATH . 'include/fckeditor/fckeditor.php');
		$editor = new FCKeditor('regmsg');
		$editor->BasePath = '../include/fckeditor/';
		$editor->ToolbarSet = 'Normal';
		$editor->Width = '80%';
		$editor->Height = '320';
		$smarty->assign('FCKeditor', $FCKeditor);
		$sql = "SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'regmsg'";
		$ret =$db->getOne($sql);
		$smarty->assign('regmsg',$ret);
		$editor->Value = $ret;
		$FCKeditor = $editor->CreateHtml();
		$smarty->assign('FCKeditor', $FCKeditor);
		$smarty->assign('form_act','text');
		if($type == 'text') 
		{
			$smarty->display('wxch_message_text.html');
		}
		elseif($type == 'image') 
		{
			$smarty->display('wxch_message_image.html');
		}
	}
}
elseif($_REQUEST['act'] == 'service') 
{
	$smarty->display('wxch_service.html');
}
elseif($_REQUEST['act'] == 'edit_title') 
{
	$title = json_str_iconv(trim($_POST['val']));
	make_json_result(stripslashes($title));
}
elseif ($_REQUEST['act'] == 'toggle_show') 
{
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);
	$db->query("UPDATE `wxch_keywords` SET `status` = '$val' WHERE `id` =$id;");
	clear_cache_files();
	make_json_result($val);
}
elseif ($_REQUEST['act'] == 'prize_show') 
{
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);
	$db->query("UPDATE `wxch_prize` SET `status` = '$val' WHERE `id` =$id;");
	clear_cache_files();
	make_json_result($val);
}
elseif ($_REQUEST['act'] == 'zjd_show') 
{
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);
	$db->query("UPDATE `wxch_prize_users` SET `status` = '$val' WHERE `id` =$id;");
	clear_cache_files();
	make_json_result($val);
}
elseif ($_REQUEST['act'] == 'zjd_state') 
{
	$pid = intval($_POST['id']);
	$val = intval($_POST['val']);
	$db->query("UPDATE `wxch_prize` SET `status` = '$val' WHERE `pid` =$pid;");
	clear_cache_files();
	make_json_result($val);
}
elseif ($_REQUEST['act'] == 'toggle_guanli') 
{
	$id = intval($_POST['id']);
	$val = intval($_POST['val']);
	$db->query("UPDATE `wxch_admin` SET `autoload` = '$val' WHERE `aid` =$id;");
	clear_cache_files();
	make_json_result($val);
}
elseif ($_REQUEST['act'] == 'remove') 
{
	$type = $_REQUEST['type'];
	if($type == 'list') 
	{
		$kws_id = intval($_REQUEST['id']);
		$del_sql = "DELETE FROM `wxch_keywords` WHERE `id` = '$kws_id';";
		$db->query($del_sql);
		$url = 'wxch-ent.php?act=keywords&act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header("Location: $url\n");
		exit;
	}
	elseif($type == 'zjd') 
	{
		$id = intval($_REQUEST['id']);
		$del_sql = "DELETE FROM `wxch_prize_users` WHERE `fun` = 'egg' AND `id` = '$id';";
		$db->query($del_sql);
		$url = 'wxch-ent.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header("Location: $url\n");
		exit;
	}
	elseif($type == 'dzp') 
	{
		$id = intval($_REQUEST['id']);
		$del_sql = "DELETE FROM `wxch_prize_users` WHERE `fun` = 'dzp' AND `id` = '$id';";
		$db->query($del_sql);
		$url = 'wxch-ent.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header("Location: $url\n");
		exit;
	}
	elseif($type == 'prize') 
	{
		$id = intval($_REQUEST['id']);
		$del_sql = "DELETE FROM `wxch_prize` WHERE `pid` = '$id';";
		$db->query($del_sql);
		$url = 'wxch-ent.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header("Location: $url\n");
		exit;
	}
	elseif($type == 'qr') 
	{
		$qid = intval($_REQUEST['id']);
		$act_uri = $_SESSION['act_uri'];
		$del_sql = "DELETE FROM `wxch_qr` WHERE `qid` = '$qid';";
		$db->query($del_sql);
		$url = 'wxch-ent.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header("Location: $url\n");
		exit;
	}
	elseif($type == 'oauth') 
	{
		$id = intval($_REQUEST['id']);
		$del_sql = "DELETE FROM `wxch_oauth` WHERE `oid` = '$id';";
		$db->query($del_sql);
		$url = 'wxch-ent.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header("Location: $url\n");
		exit;
	}
	elseif($type == 'guanli') 
	{
		$id = intval($_REQUEST['id']);
		$del_sql = "DELETE FROM `wxch_admin` WHERE `aid` = '$id';";
		$db->query($del_sql);
		$url = 'wxch-ent.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header("Location: $url\n");
		exit;
	}
}
elseif ($_REQUEST['act'] == 'query') 
{
	if($_POST) 
	{
		$filter['page'] = $_POST['page'];
		$filter['page_size'] = $_POST['pagesize'];
		$filter['start'] = $_POST['start'];
		$filter['page_count'] = $_POST['page_count'];
		$filter['record_count'] = $_POST['record_count'];
	}
	elseif($_GET) 
	{
		$filter['page'] = $_GET['page'];
		$filter['page_size'] = $_GET['pagesize'];
		$filter['start'] = $_GET['start'];
		$filter['page_count'] = $_GET['page_count'];
		$filter['record_count'] = $_GET['record_count'];
	}
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
	$start = $filter['start'];
	$type = $_REQUEST['type'];
	$filter['type'] = $type;
	$keyword = $_POST['keyword'];
	$goto = $_REQUEST['goto'];
	;
	if($goto == 'search') 
	{
		if($type == 'guanli') 
		{
			$filter['type'] = 'guanli';
			$ret = $db->getAll("SELECT * FROM `wxch_admin` WHERE `name` LIKE '%$keyword%' ORDER BY `aid` DESC LIMIT $filter[start] , $filter[page_size]");
			$wxchdata = array();
			foreach($ret as $k=>$v)
			{
				$v['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
				if($v['type'] == 'reorder') 
				{
					$v['type_name'] = '订单提醒';
				}
				elseif($v['type'] == 'pay') 
				{
					$v['type_name'] = '支付提醒';
				}
				$v['nickname'] = $db->getOne("SELECT `nickname` FROM `wxch_user` WHERE `wxid` = '$v[wxid]'");
				$wxchdata[$k] = $v;
			}
			$smarty->assign('wxchdata',$wxchdata);
			$smarty->assign('filter',$filter);
			$smarty->assign('page_count',$filter['page_count']);
			$smarty->assign('record_count',$filter['record_count']);
			$smarty->assign('wxch_lang',$wxch_lang);
			make_json_result($smarty->fetch('wxch_guanli.html'), '',array('filter' => $filter,'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
			exit;
		}
	}
	if(!empty($keyword)) 
	{
		if($type == 'oauth') 
		{
			$record_count = $db->getOne("SELECT count( oid ) FROM `wxch_oauth` WHERE `name` LIKE '%$keyword%' ");
			$page_count = ceil($record_count / $filter['page_size']);
			$filter['page_count'] = $page_count;
			$filter['record_count'] = $record_count;
			if(empty($keyword))
			{
				$ret = $db->getAll("SELECT * FROM `wxch_oauth` LIMIT $filter[start] , $filter[page_size]");
			}
			else
			{
				$ret = $db->getAll("SELECT * FROM `wxch_oauth` WHERE `name` LIKE '%$keyword%' LIMIT $filter[start] , $filter[page_size]");
			}
			$cfg_baseurl = $db->getOne("SELECT cfg_value FROM wxch_cfg WHERE cfg_name = 'baseurl'");
			$oauth_url = $cfg_baseurl.'wechat/oauth/wxch_oauth.php?oid=';
			$wxchdata = array();
			foreach($ret as $k=>$v)
			{
				$v['oauthurl'] = $oauth_url.$v['oid'];
				$wxchdata[$k] = $v;
			}
			$smarty->assign('wxchdata',$wxchdata);
			$smarty->assign('wxch_lang',$wxch_lang);
			$smarty->assign('filter',$filter);
			make_json_result($smarty->fetch('wxch_oauth.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
		}
		elseif($type=='prize') 
		{
			$record_count = $db->getOne("SELECT count( pid ) FROM `wxch_prize` WHERE `title` LIKE '%$keyword%' ");
			$page_count = ceil($record_count / $filter['page_size']);
			$filter['page_count'] = $page_count;
			$filter['record_count'] = $record_count;
			if(empty($keyword))
			{
				$search_sql = "SELECT * FROM `wxch_prize` ORDER BY `dateline` DESC LIMIT $filter[start] , $filter[page_size]";
			}
			else
			{
				$search_sql = "SELECT * FROM `wxch_prize` WHERE `title` LIKE '%$keyword%' ORDER BY `dateline` DESC LIMIT $filter[start] , $filter[page_size]";
			}
			$ret = $db->getAll($search_sql);
			$wxchdata = array();
			foreach($ret as $k=>$v)
			{
				$v['starttime'] = date("Y-m-d", $v['starttime']);
				$v['endtime'] = date("Y-m-d", $v['endtime']);
				$wxchdata[$k] = $v;
				switch($v['fun']) 
				{
					case 'egg':$wxchdata[$k]['fun_title'] = '砸金蛋';
					break;
					case 'dzp':$wxchdata[$k]['fun_title'] = '大转盘';
					break;
				}
			}
			$smarty->assign('wxch_lang',$wxch_lang);
			$smarty->assign('wxchdata',$wxchdata);
			$smarty->assign('filter',$filter);
			$smarty->assign('page_count',$filter['page_count']);
			$smarty->assign('record_count',$filter['record_count']);
			make_json_result($smarty->fetch('wxch_prize.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
		}
		elseif($type == 'qr') 
		{
			$act_uri = $_SESSION['act_uri'];
			$keyword = $_POST['keyword'];
			if($act_uri == 'qr') 
			{
				$filter['record_count'] = $db->getOne("SELECT count( qid ) FROM `wxch_qr` WHERE `type` = 'qr'");
				$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
				if(empty($keyword))
				{
					$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE `type` = 'qr' ORDER BY `qid` DESC LIMIT $filter[start] , $filter[page_size] ");
				}
				else
				{
					$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE `type` = 'qr' AND `scene` LIKE '%$keyword%' AND `type` = 'qr' ORDER BY `qid` DESC LIMIT $filter[start] , $filter[page_size]");
				}
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
					if(empty($v['qr_path'])) 
					{
						$v['qr_path'] = 'http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$v['ticket'];
					}
					$wxchdata[$k] = $v;
				}
				$smarty->assign('wxchdata',$wxchdata);
				$smarty->assign('filter',$filter);
				make_json_result($smarty->fetch('wxch_qr.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
			}
			elseif($act_uri == 'tuijian') 
			{
				$filter['record_count'] = $db->getOne("SELECT count( qid ) FROM `wxch_qr` WHERE `type` = 'tj'");
				$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
				if(empty($keyword))
				{
					$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE `type` = 'tj' ORDER BY `qid` DESC LIMIT $filter[start] , $filter[page_size] ");
				}
				else
				{
					$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE `type` = 'tj' AND `scene` LIKE '%$keyword%' ORDER BY `qid` DESC LIMIT $filter[start] , $filter[page_size]");
				}
				$wxchdata = array();
				foreach($ret as $k=>$v)
				{
					if($v['action_name'] == 'QR_LIMIT_SCENE')
					{
						$v['type_name'] = '永久';
					}
					if(empty($v['qr_path'])) 
					{
						$v['qr_path'] = 'http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$v['ticket'];
					}
					else 
					{
						$v['qr_path'] = '../'.$v['qr_path'];
					}
					if($v['endtime']) 
					{
						$v['endtime'] = date("YmdH",$v['endtime'])-date("YmdH",$v['dateline']);
					}
					else 
					{
						$v['endtime'] = '无限制';
					}
					$af_table = $ecs->prefix.'affiliate_log';
					$af_query = "SELECT * FROM `$af_table` WHERE `user_id` = $v[affiliate] AND `separate_type` = 1";
					$af_ret = $db->getAll($af_query);
					$af_money = 0;
					foreach($af_ret as $kk=>$vv) 
					{
						$af_order = $ecs->prefix.'order_info';
						$af_order_id = $vv['order_id'];
						$af_order_query = "SELECT `money_paid`,`surplus` FROM `$af_order` WHERE `order_id` = $af_order_id";
						$af_ms = $db->getRow($af_order_query);
						$af_money = $af_money+$af_ms['money_paid']+$af_ms['surplus'];
					}
					$v['money'] += $af_money;
					$v['scan_count'] = $v['scan']+$v['subscribe'];
					$wxchdata[$k] = $v;
				}
				$smarty->assign('wxchdata',$wxchdata);
				$smarty->assign('filter',$filter);
				make_json_result($smarty->fetch('wxch_tuijian.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
			}
		}
		else 
		{
			$record_count = $db->getOne("SELECT count( id ) FROM `wxch_keywords` WHERE `name` LIKE '%$keyword%' ");
			$page_count = ceil($record_count / $filter['page_size']);
			$filter['page_count'] = $page_count;
			$filter['record_count'] = $record_count;
			if(empty($keyword))
			{
				$ret = $db->getAll("SELECT * FROM `wxch_keywords` LIMIT $filter[start] , $filter[page_size]");
			}
			else
			{
				$ret = $db->getAll("SELECT * FROM `wxch_keywords` WHERE `name` LIKE '%$keyword%' LIMIT $filter[start] , $filter[page_size]");
			}
			$wxchdata = array();
			foreach($ret as $k=>$v)
			{
				if($v['type'] == 1)
				{
					$v['type_name'] = '文字';
				}
				elseif($v['type'] == 2)
				{
					$v['type_name'] = '图文';
				}
				$wxchdata[$k] = $v;
			}
			$smarty->assign('wxchdata',$wxchdata);
			$smarty->assign('filter',$filter);
			$smarty->assign('wxch_lang',$wxch_lang);
			make_json_result($smarty->fetch('wxch_keywords.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
		}
	}
	elseif($type == 'list') 
	{
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['type'] = 'list';
		$filter['record_count'] = $db->getOne("SELECT count( id ) FROM `wxch_keywords` WHERE `type` = 'qr'");
		$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
		$ret = $db->getAll("SELECT * FROM `wxch_keywords` LIMIT $filter[start] , $filter[page_size]");
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			if($v['type'] == 1)
			{
				$v['type_name'] = '文字';
			}
			elseif($v['type'] == 2)
			{
				$v['type_name'] = '图文';
			}
			$wxchdata[$k] = $v;
		}
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->assign('filter',$filter);
		$smarty->assign('page_count',$filter['page_count']);
		$smarty->assign('record_count',$filter['record_count']);
		$smarty->assign('wxch_lang',$wxch_lang);
		make_json_result($smarty->fetch('wxch_keywords.html'), '',array('filter' => $filter,'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
	}
	elseif($type == 'qr') 
	{
		$act_uri = $_SESSION['act_uri'];
		$keyword = $_POST['keyword'];
		if($act_uri == 'qr') 
		{
			$filter['record_count'] = $db->getOne("SELECT count( qid ) FROM `wxch_qr` WHERE `type` = 'qr'");
			$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
			if(empty($keyword))
			{
				$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE `type` = 'qr' ORDER BY `qid` DESC LIMIT $filter[start] , $filter[page_size] ");
			}
			else
			{
				$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE `scene` LIKE '%$keyword%' AND `type` = 'qr' ORDER BY `qid` DESC LIMIT $filter[start] , $filter[page_size]");
			}
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
				if(empty($v['qr_path'])) 
				{
					$v['qr_path'] = 'http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$v['ticket'];
				}
				$wxchdata[$k] = $v;
			}
			$smarty->assign('wxchdata',$wxchdata);
			$smarty->assign('filter',$filter);
			make_json_result($smarty->fetch('wxch_qr.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
		}
		elseif($act_uri == 'tuijian') 
		{
			$filter['record_count'] = $db->getOne("SELECT count( qid ) FROM `wxch_qr` WHERE `type` = 'tj'");
			$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
			if(empty($keyword))
			{
				$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE `type` = 'tj' ORDER BY `qid` DESC LIMIT $filter[start] , $filter[page_size] ");
			}
			else
			{
				$ret = $db->getAll("SELECT * FROM `wxch_qr` WHERE WHERE `type` = 'tj' `scene` LIKE '%$keyword%' ORDER BY `qid` DESC LIMIT $filter[start] , $filter[page_size]");
			}
			$wxchdata = array();
			foreach($ret as $k=>$v)
			{
				if($v['action_name'] == 'QR_LIMIT_SCENE')
				{
					$v['type_name'] = '永久';
				}
				if(empty($v['qr_path'])) 
				{
					$v['qr_path'] = 'http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$v['ticket'];
				}
				else 
				{
					$v['qr_path'] = '../'.$v['qr_path'];
				}
				if($v['endtime']) 
				{
					$v['endtime'] = date("YmdH",$v['endtime'])-date("YmdH",$v['dateline']);
				}
				else 
				{
					$v['endtime'] = '无限制';
				}
				$af_table = $ecs->prefix.'affiliate_log';
				$af_query = "SELECT * FROM `$af_table` WHERE `user_id` = $v[affiliate] AND `separate_type` = 1";
				$af_ret = $db->getAll($af_query);
				$af_money = 0;
				foreach($af_ret as $kk=>$vv) 
				{
					$af_order = $ecs->prefix.'order_info';
					$af_order_id = $vv['order_id'];
					$af_order_query = "SELECT `money_paid`,`surplus` FROM `$af_order` WHERE `order_id` = $af_order_id";
					$af_ms = $db->getRow($af_order_query);
					$af_money = $af_money+$af_ms['money_paid']+$af_ms['surplus'];
				}
				$v['money'] += $af_money;
				$v['scan_count'] = $v['scan']+$v['subscribe'];
				$wxchdata[$k] = $v;
			}
			$smarty->assign('wxchdata',$wxchdata);
			$smarty->assign('filter',$filter);
			make_json_result($smarty->fetch('wxch_tuijian.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
		}
	}
	elseif($type == 'prize') 
	{
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['type'] = 'prize';
		$filter['record_count'] = $db->getOne("SELECT count( pid ) FROM `wxch_prize`");
		$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
		$keyword = $_POST['keyword'];
		if(empty($keyword))
		{
			$ret = $db->getAll("SELECT * FROM `wxch_prize` LIMIT $filter[start] , $filter[page_size]");
		}
		else
		{
			$ret = $db->getAll("SELECT * FROM `wxch_prize` WHERE `title` LIKE '%$keyword%' LIMIT $filter[start] , $filter[page_size]");
		}
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			$v['starttime'] = date("Y-m-d", $v['starttime']);
			$v['endtime'] = date("Y-m-d", $v['endtime']);
			$wxchdata[$k] = $v;
			switch($v['fun']) 
			{
				case 'egg':$wxchdata[$k]['fun_title'] = '砸金蛋';
				break;
				case 'dzp':$wxchdata[$k]['fun_title'] = '大转盘';
				break;
			}
		}
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->assign('filter',$filter);
		$smarty->assign('page_count',$filter['page_count']);
		$smarty->assign('record_count',$filter['record_count']);
		make_json_result($smarty->fetch('wxch_prize.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
	}
	elseif($type == 'oauth') 
	{
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
		$filter['type'] = 'oauth';
		$filter['record_count'] = $db->getOne("SELECT count( oid ) FROM `wxch_oauth`");
		$ret = $db->getAll("SELECT * FROM `wxch_oauth` LIMIT $filter[start] , $filter[page_size]");
		$cfg_baseurl = $db->getOne("SELECT cfg_value FROM wxch_cfg WHERE cfg_name = 'baseurl'");
		$oauth_url = $cfg_baseurl.'wechat/oauth/wxch_oauth.php?oid=';
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			$v['oauthurl'] = $oauth_url.$v['oid'];
			$wxchdata[$k] = $v;
		}
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->assign('filter',$filter);
		$smarty->assign('page_count',$filter['page_count']);
		$smarty->assign('record_count',$filter['record_count']);
		make_json_result($smarty->fetch('wxch_oauth.html'), '',array('filter' => $filter,'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
	}
	elseif($type == 'guanli') 
	{
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['type'] = 'guanli';
		$filter['record_count'] = $db->getOne("SELECT count( aid ) FROM `wxch_admin`");
		$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
		$ret = $db->getAll("SELECT * FROM `wxch_admin` ORDER BY `aid` DESC LIMIT $filter[start] , $filter[page_size]");
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			$v['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
			if($v['type'] == 'reorder') 
			{
				$v['type_name'] = '订单提醒';
			}
			elseif($v['type'] == 'pay') 
			{
				$v['type_name'] = '支付提醒';
			}
			$v['nickname'] = $db->getOne("SELECT `nickname` FROM `wxch_user` WHERE `wxid` = '$v[wxid]'");
			$wxchdata[$k] = $v;
		}
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->assign('filter',$filter);
		$smarty->assign('page_count',$filter['page_count']);
		$smarty->assign('record_count',$filter['record_count']);
		$smarty->assign('wxch_lang',$wxch_lang);
		make_json_result($smarty->fetch('wxch_guanli.html'), '',array('filter' => $filter,'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
	}
	elseif($type == 'zjd') 
	{
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['type'] = 'zjd';
		$filter['record_count'] = $db->getOne("SELECT count( wxid ) FROM `wxch_prize_users` WHERE `fun` = 'egg' AND `yn` = 'yes' ");
		$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
		if(empty($keyword))
		{
			$ret = $db->getAll("SELECT * FROM `wxch_prize_users` WHERE `fun` = 'egg' AND `yn` = 'yes' ORDER BY `dateline` ASC LIMIT $filter[start] , $filter[page_size]");
		}
		else
		{
			$ret = $db->getAll("SELECT * FROM `wxch_prize_users` WHERE `fun` = 'egg' AND `prize_name` AND `yn` = 'yes' LIKE '%$keyword%' ORDER BY `dateline` ASC LIMIT $filter[start] , $filter[page_size]");
		}
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			$v['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
			if(empty($v['nickname'])) 
			{
				$v['name'] = $v['wxid'];
			}
			else 
			{
				$v['name'] = $v['nickname'];
			}
			$wxchdata[$k] = $v;
		}
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->assign('filter',$filter);
		$smarty->assign('page_count',$filter['page_count']);
		$smarty->assign('record_count',$filter['record_count']);
		make_json_result($smarty->fetch('wxch_zjd.html'), '',array('filter' => $filter,'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
	}
	elseif($type == 'dzp') 
	{
		$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
		$filter['type'] = 'dzp';
		$filter['record_count'] = $db->getOne("SELECT count( wxid ) FROM `wxch_prize_users` WHERE `fun` = 'dzp' AND `yn` = 'yes' ");
		$filter['page_count'] = ceil($filter['record_count'] / $filter['page_size']);
		if(empty($keyword))
		{
			$ret = $db->getAll("SELECT * FROM `wxch_prize_users` WHERE `fun` = 'dzp' AND `yn` = 'yes' ORDER BY `dateline` ASC LIMIT $filter[start] , $filter[page_size]");
		}
		else
		{
			$ret = $db->getAll("SELECT * FROM `wxch_prize_users` WHERE `fun` = 'dzp' AND `prize_name` AND `yn` = 'yes' LIKE '%$keyword%' ORDER BY `dateline` ASC LIMIT $filter[start] , $filter[page_size]");
		}
		$wxchdata = array();
		foreach($ret as $k=>$v)
		{
			$v['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
			if(empty($v['nickname'])) 
			{
				$v['name'] = $v['wxid'];
			}
			else 
			{
				$v['name'] = $v['nickname'];
			}
			$wxchdata[$k] = $v;
		}
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('wxchdata',$wxchdata);
		$smarty->assign('filter',$filter);
		$smarty->assign('page_count',$filter['page_count']);
		$smarty->assign('record_count',$filter['record_count']);
		make_json_result($smarty->fetch('wxch_dzp.html'), '',array('filter' => $filter,'page_count' => $filter['page_count'],'record_count' => $filter['record_count']));
	}
}
function create_menu($db) 
{
	access_token($db);
	$ret = $db->getRow("SELECT `access_token` FROM `wxch_config`");
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
			$access_token = new_access_token($db);
			$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
			$ret_json = curl_grab_page($url,$menu);
			$ret = json_decode($ret_json);
		}
		return $ret;
	}
	else
	{
		$access_token = new_access_token($db);
		return FALSE;
	}
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
			$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` =1;");
		}
	}
	elseif(empty($access_token)) 
	{
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
			$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` =1;");
		}
	}
}
function new_access_token($db) 
{
	$time = time();
	$ret = $db->getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
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
		$db->query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `id` =1;");
	}
	return $ret->access_token;
}
function wxch_upload_file($upload) 
{
	$image = new cls_image();
	$res = $image->upload_image($upload);
	if($res) 
	{
		return $res;
	}
	else 
	{
		return false;
	}
}
function curl_redir_exec($ch) 
{
	static $curl_loops = 0;
	static $curl_max_loops = 20;
	if ($curl_loops++ >= $curl_max_loops) 
	{
		$curl_loops = 0;
		return FALSE;
	}
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	list($header, $data) = explode("\n\n", $data, 2);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($http_code == 301 || $http_code == 302) 
	{
		$matches = array();
		preg_match('/Location:(.*?)\n/', $header, $matches);
		$url = @parse_url(trim(array_pop($matches)));
		if (!$url) 
		{
			$curl_loops = 0;
			return $data;
		}
		$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
		if (!$url['scheme']) $url['scheme'] = $last_url['scheme'];
		if (!$url['host']) $url['host'] = $last_url['host'];
		if (!$url['path']) $url['path'] = $last_url['path'];
		$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:'');
		curl_setopt($ch, CURLOPT_URL, $new_url);
		debug('Redirecting to', $new_url);
		return curl_redir_exec($ch);
	}
	else 
	{
		$curl_loops=0;
		return $data;
	}
}
function curl_get_contents($url) 
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
	curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
	@curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
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
	curl_setopt($ch, CURLOPT_MAXREDIRS, 200);
	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	ob_start();
	return curl_exec ($ch);
	ob_end_clean();
	curl_close ($ch);
	unset($ch);
}
;
?>