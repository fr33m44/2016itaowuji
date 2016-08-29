<?php
define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');
require('wxch_lg.php');
$_REQUEST['act'] = trim($_REQUEST['act']);
if($_REQUEST['act'] == 'add') 
{
	$wxch_lang['ur_here'] = '功能变量';
	if($_POST)
	{
		$image = new cls_image($_CFG['bgcolor']);
		$path = $image->upload_image($_FILES['path']);
		$name = $_POST['name'];
		$keyword = $_POST['keyword'];
		$type = $_POST['type'];
		$contents = $_POST['contents'];
		if($type == 1)
		{
			$contents = htmltowei($contents);
			$db->query("INSERT INTO `wxch_keywords` (`name`, `keyword`, `type`, `contents`, `count`, `status`) VALUES
('$name', '$keyword', $type, '$contents', 0, 1);");
		}
		elseif($type == 2)
		{
			$p_title = $_POST['p_title'];
			$summary = $_POST['summary'];
			$db->query("INSERT INTO `wxch_keywords` (`name`, `keyword`, `type`, `p_title`, `path`, `summary`, `contents`, `count`, `status`) VALUES
('$name', '$keyword', $type, '$p_title', '$path', '$summary', '$contents', 0, 1);");
		}
		$link[] = array('href' =>'wxch-pro.php?act=fun', 'text' => '功能变量');
		sys_msg('添加成功',0,$link);
	}
	else
	{
		require(ROOT_PATH . 'include/fckeditor/fckeditor.php');
		$lang = array();
		$lang['tab_general'] = '主要信息';
		$editor = new FCKeditor('contents');
		$editor->BasePath = '../include/fckeditor/';
		$editor->ToolbarSet = 'Normal';
		$editor->Width = '60%';
		$editor->Height = '320';
		$editor->Value = $content['template_content'];
		$FCKeditor = $editor->CreateHtml();
		$smarty->assign('FCKeditor', $FCKeditor);
		$smarty->assign('lang',$lang);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_wxch_fun_info.html');
	}
}
elseif($_REQUEST['act'] == 'edit') 
{
	$wxch_lang['ur_here'] = '变量编辑';
	if($_POST) 
	{
		$id = $_POST['id'];
		$command = $_POST['command'];
		$sql = "UPDATE  `wxch_msg` SET  `command` =  '$command' WHERE  `id` =$id;";
		$db->query($sql);
		$link[] = array('href' =>'wxch-ent.php?act=fun', 'text' => $wxch_lang['ur_here']);
		sys_msg('修改成功',0,$link);
	}
	else 
	{
		$id = $_GET['id'];
		$data = $db->getRow("SELECT * FROM `wxch_msg` WHERE `id` = $id");
		if(empty($data['command']))
		{
			$data[$k]['count'] = 0;
		}
		else
		{
			$t_ret = explode(' ',$data['command']);
			$data[$k]['count'] = count($t_ret);
		}
		$data['command'] = trim($data['command']);
		$lang = array();
		$lang['tab_general'] = '主要信息';
		$smarty->assign('data', $data);
		$smarty->assign('lang',$lang);
		$smarty->assign('wxch_lang',$wxch_lang);
		$smarty->display('wxch_fun_info.html');
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
	make_json_result($smarty->fetch('wxch_fun.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
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
		$filter['page_count'] = $_POST['page_count'];
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
		make_json_result($smarty->fetch('wxch_fun.html'), '',array('filter' => $filter, 'page_count' => $filter['page_count']));
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
?>