<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/../../includes/init.php');
session_start();
if (strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger")) 
{
	$scene_id = $_GET['scene_id'];
	if(empty($scene_id)) 
	{
		exit('非法链接');
	}
}
else 
{
	exit('请从微信进入');
}

$qr_path = $db->getOne("SELECT `qr_path` FROM `wxch_qr` WHERE `scene_id`='$scene_id'");

 ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>圈子团 - 扫码加入-躺着赚钱</title>
</head>
<body style="background:url(http://wxmiqi.wushuai.net/wechat/egg/thumb_54d6109fd0a00.png); background-size: 100% " >

	<div class="grid">
		<div align=center><a id="f"><img  src="<?php echo $qr_path;?>" /> </a></div>
	</div>

	
</body>
</html>
