<?php
/**
 * ECTOUch 帮助手册
   * 网站地址: http://www.ecmoban.com；
 * ----------------------------------------------------------------------------
    * $Author: hnllyrp $
 * $Id: help_wei.php 16220 2014-05-14 21:08:59Z hnllyrp $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- ectouch帮助手册
/*------------------------------------------------------ */
if($_REQUEST['act'] == 'ectouch'){

    $smarty->assign('ur_here', "ectouch帮助手册");
    $smarty->assign('full_page',  1);

    $smarty->display('help_ectouch.htm');
}

/*------------------------------------------------------ */
//-- 微信通帮助手册
/*------------------------------------------------------ */
if($_REQUEST['act'] == 'weixintong'){

    $smarty->assign('ur_here', "微信通帮助手册");
    $smarty->assign('full_page',  1);

    $smarty->display('help_weixintong.htm');
}

/*------------------------------------------------------ */
//-- 微信支付相关配置
/*------------------------------------------------------ */
if($_REQUEST['act'] == 'weixinpay'){

    $smarty->assign('ur_here', "微信支付相关配置");
    $smarty->assign('full_page',  1);

    $smarty->display('help_weixinpay.htm');
}

?>