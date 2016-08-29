<?php
define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . '/include/cls_image.php');
require('wxch_lg.php');
$_REQUEST['act'] = trim($_REQUEST['act']);
if($_REQUEST['act'] == 'list') 
{
	$smarty->display('wxch_keywords.html');
}

elseif($_REQUEST['act'] == 'add') 
{
	if($_POST)
	{
		$image = new cls_image($_CFG['bgcolor']);
		$path = $image->upload_image($_FILES['path']);
		$name = "关注回复文本";
		$type = $_POST['type'];
		$contents = $_POST['contents'];
		if($type == 0)
		{
			$get_type = $_GET['type'];
			switch ($get_type)
			{
				case 'text':$type = 1;
				break;
				case 'image':$type = 2;
				break;
			}
		}
		if($type == 1)
		{
		$keyword="关注回复文本";
		$sql = 'SELECT id FROM  wxch_keywords1 where type=3';
		$id=$db->getOne($sql);
		$type=3;
		$contents = strip_tags($_POST['contents'],"<a>");
		if($id){
			$update_sql = "UPDATE  `wxch_keywords1` SET  `name` =  '$name',`keyword` =  '$keyword',`type` =  '$type',`name` =  '$name',`contents` =  '$contents' WHERE  `id` ='$id';";
		$db->query($update_sql);
		}else{
			$contents = htmltowei($contents);
			$db->query("INSERT INTO `wxch_keywords1` (`name`, `keyword`, `type`, `contents`, `count`, `status`) VALUES
			('$name', '$keyword', $type, '$contents', 0, 1);");
		}
		$link[] = array('href' =>'wxch-ent.php?act=regmsg', 'text' => '消息自动回复');
		sys_msg('编辑成功',0,$link);
		}
		elseif($type == 2)
		{
			$sql = 'SELECT id FROM  wxch_keywords1 where type=4';
			$id=$db->getOne($sql);
			if(empty($id)){
			
			$kws_id = $_SESSION['kws_id'];
			if(!empty($kws_id))
			{
				$db->query("UPDATE  `wxch_keywords1` SET  `name` =  '$name',`keyword` = '$keyword',`type` = '$type',`status` =  '1' WHERE `id` =$kws_id;");
			}
		}else{
			$name ="关注回复图文";
			$keyword = "关注回复图文";
			$type = 4;
			$contents = strip_tags($_POST['contents'],"<a>");
			$update_sql = "UPDATE  `wxch_keywords1` SET  `name` =  '$name',`keyword` =  '$keyword',`type` =  '$type',`name` =  '$name',`contents` =  '$contents' WHERE  `id` ='$id';";
			$db->query($update_sql);
		}
		$link[] = array('href' =>'wxch-ent.php?act=regmsg', 'text' => '消息自动回复');
		sys_msg('修改成功',0,$link);
		}
	}
	else
	{

	if($_GET['type'] == 'text')
	{	
		
			$sql = 'SELECT id FROM  wxch_keywords1 where type=3';
			$id=$db->getOne($sql);
	}	
	elseif($_GET['type'] == 'image')
	{
		$sql = 'SELECT id FROM  wxch_keywords1 where type=4';
		$id=$db->getOne($sql);
	}
	if(empty($id)){

		$lang = array();
		$lang['tab_general'] = '文字信息';
		
			$lang['tab_images'] = '图文信息';
			$input_name="contents";
			$kindeditor="<script charset='utf-8' src='../include/kindeditor/kindeditor-min.js'></script>
			<script>
			var editor;
            KindEditor.ready(function(K) {
                editor = K.create('textarea[name=\"$input_name\"]', {
                    allowFileManager : true,
                    width : '700px',
                    height: '300px',
                    resizeType: 0   //固定宽高
                });
            });
			</script>
			<textarea id=\"$input_name\" name=\"$input_name\" style='width:700px;height:300px;'></textarea>
			";
		$smarty->assign('FCKeditor', $kindeditor);
		$smarty->assign('lang',$lang);
		$smarty->assign('wxch_lang',$wxch_lang);
		if($_GET['type'] == 'text')
		{
			$wxch_lang['ur_here'] = '添加文字信息';
			$smarty->display('wxch_keywords1_infotext.html');
		}
		elseif($_GET['type'] == 'image')
		{
			$wxch_lang['ur_here'] = '添加图文信息';
			$smarty->display('wxch_keywords1_infoimage.html');
		}	
	}else{

			$data = $db->getRow("SELECT * FROM `wxch_keywords1` WHERE `id` = $id");
			if($_GET['type'] == 'image') 
			{
				$article_list = get_keywords_articles($id, $db);
			}
			$lang = array();
			$lang['tab_general'] = '主要信息';
			$input_name="contents";
			$contents=$data['contents'];
			$kindeditor="<script charset='utf-8' src='../include/kindeditor/kindeditor-min.js'></script>
			<script>
			var editor;
            KindEditor.ready(function(K) {
                editor = K.create('textarea[name=\"$input_name\"]', {
                    allowFileManager : true,
                    width : '700px',
                    height: '300px',
                    resizeType: 0   //固定宽高
                });
            });
			</script>
			<textarea id=\"$input_name\" name=\"$input_name\" style='width:700px;height:300px;'>$contents</textarea>
			";
			$smarty->assign('FCKeditor', $kindeditor);
			$smarty->assign('data', $data);
			$smarty->assign('lang',$lang);
			$smarty->assign('data',$data);
			$smarty->assign('article_list',$article_list);
			$smarty->assign('wxch_lang',$wxch_lang);
			if($_GET['type'] == 'text')
			{
				$wxch_lang['ur_here'] = '添加文字信息';
				$smarty->display('wxch_keywords1_infotext.html');
			}
			elseif($_GET['type'] == 'image')
			{
				$wxch_lang['ur_here'] = '添加图文信息';
				$smarty->display('wxch_keywords1_infoimage.html');
			}
		}
}
}
elseif($_REQUEST['act'] == 'edit') 
{
	if($_POST)
	{
		$id = $_POST['id'];
		$name = $_POST['name'];
		$keyword = $_POST['keyword'];
		$type = $_POST['type'];
		$contents = strip_tags($_POST['contents'],"<a>");
		$update_sql = "UPDATE  `wxch_keywords` SET  `name` =  '$name',`keyword` =  '$keyword',`type` =  '$type',`name` =  '$name',`contents` =  '$contents' WHERE  `id` ='$id';";
		$db->query($update_sql);
		$link[] = array('href' =>'wxch-ent.php?act=keywords', 'text' => '消息自动回复');
		sys_msg('修改成功',0,$link);
	}
	require(ROOT_PATH . 'include/fckeditor/fckeditor.php');
	$id = $_GET['id'];
	$data = $db->getRow("SELECT * FROM `wxch_keywords` WHERE `id` = $id");
	if($_GET['type'] == 'image') 
	{
		$article_list = get_keywords_articles($id, $db);
	}
	$lang = array();
	$lang['tab_general'] = '主要信息';
	$editor = new FCKeditor('contents');
	$editor->BasePath = '../include/fckeditor/';
	$editor->ToolbarSet = 'Normal';
	$editor->Width = '60%';
	$editor->Height = '320';
	$editor->Value = $data['contents'];
	$FCKeditor = $editor->CreateHtml();
	$smarty->assign('data', $data);
	$smarty->assign('FCKeditor', $FCKeditor);
	$smarty->assign('lang',$lang);
	$smarty->assign('data',$data);
	$smarty->assign('article_list',$article_list);
	$smarty->assign('wxch_lang',$wxch_lang);
	if($_GET['type'] == 'text')
	{
		$wxch_lang['ur_here'] = '添加文字信息';
		$smarty->display('wxch_keywords_infotext.html');
	}
	elseif($_GET['type'] == 'image')
	{
		$wxch_lang['ur_here'] = '添加图文信息';
		$smarty->display('wxch_keywords_infoimage.html');
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