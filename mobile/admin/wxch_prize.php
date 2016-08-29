<?php
define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');
require('wxch_lg.php');
$_REQUEST['act'] = trim($_REQUEST['act']);
if($_REQUEST['act'] == 'add') 
{
	$wxch_lang['ur_here'] = '添加抽奖规则';
	if($_POST) 
	{
		$pid = $_REQUEST['pid'];
		$prize_id = $_POST['prize_id'];
		$prize_name = $_POST['prize_name'];
		$prize_value = $_POST['prize_value'];
		foreach($prize_id as $k=>$v)
		{
			$update_sql = "UPDATE `wxch_prize_append` SET `prize_name` = '$prize_name[$k]',`prize_value` = '$prize_value[$k]' WHERE `id` =$prize_id[$k] AND `prize_id` = '$pid';";
			$db->query($update_sql);
		}
		$url = 'wxch_prize.php?act=edit&pid='.$pid;
		$link[] = array('href' =>$url, 'text' => $wxch_lang['ur_here']);
		sys_msg('设置成功',0,$link);
	}
}
elseif($_REQUEST['act'] == 'news') 
{
	$wxch_lang['ur_here'] = '设置奖品名称、数量';
	$pid = $_GET['pid'];
	if($_POST) 
	{
		$post_pid = $_POST['pid'];
		$prize_id = $_POST['prize_id'];
		$prize_name = $_POST['prize_name'];
		$prize_value = $_POST['prize_value'];
		foreach($prize_name as $k=>$v)
		{
			$insert_sql = "INSERT INTO `wxch_prize_append` (`prize_id`, `prize_name`, `prize_value`) VALUES ('$post_pid', '$prize_name[$k]', '$prize_value[$k]')";
			$db->query($insert_sql);
		}
		$url = 'wxch_prize.php?act=edit&pid='.$pid;
		$link[] = array('href' =>$url, 'text' => $wxch_lang['ur_here']);
		sys_msg('新增奖品、数量成功',0,$link);
	}
	elseif(!empty($pid)) 
	{
		$i = 1;
		$ret = array();
		for($i;$i<=6;$i++) 
		{
			$ret[$i] = array();
		}
		$ii = 1;
		foreach($ret as $k=>$v) 
		{
			if($v['prize_value']>=1) 
			{
				$v['rand'] = round(($v['prize_value']/$count_rand),3);
			}
			else 
			{
				$v['rand'] = 0;
			}
			$wxchdata[$k] = $v;
			switch($ii) 
			{
				case 1:$wxchdata[$k]['level'] = '一等奖';
				break;
				case 2:$wxchdata[$k]['level'] = '二等奖';
				break;
				case 3:$wxchdata[$k]['level'] = '三等奖';
				break;
				case 4:$wxchdata[$k]['level'] = '四等奖';
				break;
				case 5:$wxchdata[$k]['level'] = '五等奖';
				break;
				case 6:$wxchdata[$k]['level'] = '六等奖';
				break;
			}
			$ii++;
		}
		$form_act = 'news';
		$smarty->assign('pid',$pid);
		$smarty->assign('data',$wxchdata);
		$smarty->assign('form_act',$form_act);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_prize_info.html');
	}
}
elseif($_REQUEST['act'] == 'add_prize') 
{
	$wxch_lang['ur_here'] = '新增抽奖规则';
	if($_POST) 
	{
		$name = $_POST['name'];
		$starttime = strtotime($_POST['starttime']);
		$fun = $_POST['fun'];
		$num = $_POST['num'];
		$loop = $_POST['loop'];
		$endtime = strtotime($_POST['endtime']);
		$time = time();
		$insert_sql = "INSERT INTO `wxch_prize` (`title`, `fun`, `num`, `count`, `loop`, `starttime`, `endtime`, `dateline`) VALUES
('$name', '$fun', '$num', 0, '$loop', '$starttime', '$endtime', '$time');";
		$db->query($insert_sql);
		$pid = $db->insert_id();
		$url = 'wxch_prize.php?act=news&pid='.$pid;
		$link[] = array('href' =>$url, 'text' => $wxch_lang['ur_here']);
		sys_msg('继续设置规则的中奖率',0,$link);
	}
	else 
	{
		$starttime = date("Y-m-d",time());
		$endtime = date("Y-m-d",time()+(60*60*24*30));
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->assign('starttime',$starttime);
		$smarty->assign('endtime',$endtime);
		$smarty->assign('form_act','add_prize');
		$smarty->display('wxch_prize_add.html');
	}
}
elseif($_REQUEST['act'] == 'edit_prize') 
{
	$wxch_lang['ur_here'] = '编辑抽奖规则';
	$pid = $_GET['pid'];
	if($_POST) 
	{
		$pid = $_POST['pid'];
		$name = $_POST['name'];
		$starttime = strtotime($_POST['starttime']);
		$fun = $_POST['fun'];
		$num = $_POST['num'];
		$loop = $_POST['loop'];
		$point=$_POST['point'];
		
		if(empty($point)){
			sys_msg('所需积分不能为空',0,$link);
		}
		$endtime = strtotime($_POST['endtime']);
		$time = time();
		$insert_sql = "UPDATE `wxch_prize` SET `title` = '$name',`fun` = '$fun',`point`='$point',`loop`= '$loop',`num` = '$num',`starttime` = '$starttime',`endtime` = '$endtime',`dateline` = '$time' WHERE `pid` ='$pid';";
		$db->query($insert_sql);
		$url = 'wxch_prize.php?act=edit_prize&pid='.$pid;
		$link[] = array('href' =>$url, 'text' => $wxch_lang['ur_here']);
		sys_msg('编辑成功',0,$link);
	}
	else 
	{
		$sql = "SELECT * FROM `wxch_prize` WHERE `pid` = $pid";
		$ret = $db->getRow($sql);
		$starttime = date("Y-m-d",$ret['starttime']);
		$endtime = date("Y-m-d",$ret['endtime']);
		$form_act = 'edit_prize';
		$smarty->assign('pid',$pid);
		$smarty->assign('data',$ret);
		$smarty->assign('starttime',$starttime);
		$smarty->assign('endtime',$endtime);
		$smarty->assign('form_act',$form_act);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_prize_add.html');
	}
}
elseif($_REQUEST['act'] == 'edit') 
{
	$wxch_lang['ur_here'] = '抽奖规则';
	$pid = $_GET['pid'];
	$sql = "SELECT * FROM `wxch_prize_append` WHERE `prize_id` = $pid";
	$ret = $db->getAll($sql);
	$prize_count = count($ret);
	$i = 1;
	$count_rand = 0;
	foreach($ret as $v) 
	{
		$count_rand += $v['prize_value'];
	}
	if(empty($ret)) 
	{
		$url = 'wxch_prize.php?act=news&pid='.$pid;
		$link[] = array('href' =>$url, 'text' => '奖品设置');
		sys_msg('还未设置奖品名称、数量',0,$link);
	}
	foreach($ret as $k=>$v) 
	{
		if($v['prize_value']>=1) 
		{
			$v['rand'] = round(($v['prize_value']/$count_rand)*1000,3);
		}
		else 
		{
			$v['rand'] = 0;
		}
		$wxchdata[$k] = $v;
		switch($i) 
		{
			case 1:$wxchdata[$k]['level'] = '一等奖';
			break;
			case 2:$wxchdata[$k]['level'] = '二等奖';
			break;
			case 3:$wxchdata[$k]['level'] = '三等奖';
			break;
			case 4:$wxchdata[$k]['level'] = '四等奖';
			break;
			case 5:$wxchdata[$k]['level'] = '五等奖';
			break;
			case 6:$wxchdata[$k]['level'] = '六等奖';
			break;
		}
		$i++;
	}
	$form_act = 'add';
	$smarty->assign('pid',$pid);
	$smarty->assign('data',$wxchdata);
	$smarty->assign('form_act',$form_act);
	$smarty->assign('wxch_lang',$wxch_lang);
	$smarty->display('wxch_prize_info.html');
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
	$ret = $db->getAll("SELECT * FROM `wxch_keywords` LIMIT $start , $filter[page_size]");
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
	make_json_result($smarty->fetch('wxch_keywords.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
}
elseif($_REQUEST['act'] == 'edit_title') 
{
	$title = json_str_iconv(trim($_POST['val']));
	make_json_result(stripslashes($title));
}
elseif ($_REQUEST['act'] == 'get_article_list') 
{
	include_once(ROOT_PATH . 'include/cls_json.php');
	$json = new JSON;
	$filters =(array) $json->decode(json_str_iconv($_GET['JSON']));
	$where = " WHERE cat_id > 0 ";
	if (!empty($filters['title'])) 
	{
		$keyword = trim($filters['title']);
		$where .= " AND title LIKE '%" . mysql_like_quote($keyword) . "%' ";
	}
	$sql = 'SELECT article_id, title FROM ' .$ecs->table('article'). $where. 'ORDER BY article_id DESC LIMIT 50';
	$res = $db->query($sql);
	$arr = array();
	while ($row = $db->fetchRow($res)) 
	{
		$arr[] = array('value' => $row['article_id'], 'text' => $row['title'], 'data'=>'');
	}
	make_json_result($arr);
}
elseif ($_REQUEST['act'] == 'add_article') 
{
	include_once(ROOT_PATH . 'include/cls_json.php');
	$json = new JSON;
	$articles = $json->decode($_GET['add_ids']);
	$arguments = $json->decode($_GET['JSON']);
	if(!empty($arguments[0]))
	{
		$kws_id = $arguments[0];
	}
	else
	{
		$insert_sql = "INSERT INTO `wxch_keywords` (`name`) VALUES
('');";
		$db->query($insert_sql);
		$kws_id = $db->insert_id();
		session_start();
		$_SESSION['kws_id'] = $kws_id;
	}
	foreach ($articles AS $val) 
	{
		$sql = "INSERT INTO wxch_keywords_article (kws_id, article_id) VALUES ('$kws_id', '$val')";
		$db->query($sql);
	}
	$arr = get_keywords_articles($kws_id,$db);
	$opt = array();
	foreach ($arr AS $val) 
	{
		$opt[] = array('value' => $val['article_id'], 'text' => $val['title'], 'data' => '');
	}
	clear_cache_files();
	make_json_result($opt);
}
elseif ($_REQUEST['act'] == 'drop_article') 
{
	include_once(ROOT_PATH . 'include/cls_json.php');
	$json = new JSON;
	$articles = $json->decode($_GET['drop_ids']);
	$arguments = $json->decode($_GET['JSON']);
	foreach ($articles AS $val) 
	{
		$sql = "DELETE FROM `wxch_keywords_article` WHERE `wxch_keywords_article`.`article_id` = $val;";
		$db->query($sql);
	}
	$arr = get_keywords_articles($arguments[0],$db);
	$opt = array();
	if(is_array($arr))
	{
		foreach ($arr AS $val) 
		{
			$opt[] = array('value' => $val['article_id'], 'text' => $val['title'], 'data' => '');
		}
	}
	clear_cache_files();
	make_json_result($opt);
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
		$ret = $db->getAll("SELECT * FROM `wxch_keywords` WHERE `name` LIKE '%$keyword%' LIMIT $start , $filter[page_size]");
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
		make_json_result($smarty->fetch('wxch_keywords.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
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
function get_keywords_articles($kws_id,$db) 
{
	$sql = "SELECT `article_id` FROM `wxch_keywords_article` WHERE `kws_id` = '$kws_id'";
	$ret = $db->getAll($sql);
	foreach($ret as $v)
	{
		$articles .= $v['article_id'].',';
	}
	$length = strlen($articles)-1;
	$articles = substr($articles, 0, $length);
	if(!empty($articles))
	{
		$sql2 = "SELECT `article_id`,`title` FROM ".$GLOBALS['ecs']->table('article')." WHERE `article_id` IN ($articles)";
		$res = $db->getAll($sql2);
	}
	return $res;
}
?>