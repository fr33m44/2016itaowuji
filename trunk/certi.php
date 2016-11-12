<?php

/**
 * iTaoWuJi 证书反查文件
 * 开发者：fr33m4n(微信)
 * ----------------------------------------------------------------------------
    * $Author: wangleisvn $
 * $Id: index.php 16075 2009-05-22 02:19:40Z wangleisvn $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
/* 检查是否登录 */
if ($_SESSION['user_id'] <= 0)
{
	ecs_header("Location: user.php\n");
	exit;
}
/*------------------------------------------------------ */
//-- 证书反查
/*------------------------------------------------------ */
$session_id = empty($_POST['session_id']) ? '' : trim($_POST['session_id']);

if (!empty($session_id))
{

    $sql = "SELECT sesskey FROM " . $ecs->table('sessions') . " WHERE sesskey = '" . $session_id . "' ";
    $sesskey = $db->getOne($sql);
    if ($sesskey != '')
    {
        exit('{"res":"succ","msg":"","info":""}');
    }
    else
    {
        exit('{"res":"fail","msg":"error:000002","info":""}');
    }
}
else
{
    exit('{"res":"fail","msg":"error:000001","info":""}');
}

?>