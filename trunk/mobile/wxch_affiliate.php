<?php
/**
 * wxch_affiliate.php UTF8
 * User: weicaihong
 * Date: 14-6-27 下午3:35
 * Copyright: http://www.weixincaihong.com
 */
// define('IN_ECTOUCH', true);
// require (dirname(__FILE__) . '/include/init.php');

// $action  = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : '';

if($action == 'affiliate')
{
    $shop_name = $_CFG['shop_name'];
    $u_id = $_GET['u'];
    //
    require('../wechat/qr_affiliate.php');

    $wx_data = get_qrafe($db,$ecs,$u_id);

    $qr_img = $wx_data['qr_img'];
    $wx_user = $wx_data['wx_user'];

    $m_ret = $db->getOne("SELECT `cfg_value` FROM  `wxch_cfg` WHERE `cfg_name` = 'murl'");
    $base_ret = $db->getOne("SELECT `cfg_value` FROM  `wxch_cfg` WHERE `cfg_name` = 'baseurl'");
    $m_url = $base_ret.$m_ret;

    $smarty->assign('shop_name',$shop_name);
    $smarty->assign('user_id',$u_id);
    $smarty->assign('m_url',$m_url);
    $smarty->assign('wx_user', $wx_user);
    $smarty->assign('qr_img', $qr_img);
    $smarty->display('wxch_affiliate.dwt');
    exit;
}

