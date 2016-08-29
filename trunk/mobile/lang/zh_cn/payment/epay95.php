<?php

/**
 * ECSHOP 95epay 支付插件
 * ============================================================================
 * 版权所有 2005-2008 广州双乾网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；如你想与本人交朋友：请加QQ:693150707
 * ============================================================================
 * $Author: admin_zhang
 */

global $_LANG;
$_LANG['epay95']          = '双乾E支付';//该支付方式的名称，推荐在后台修改
$_LANG['epay95_desc']     = '双乾是国内领先的独立第三方支付企业，旨在为各类企业及个人提供安全、便捷和保密的综合电子支付服务。目前，双乾是支付产品最丰富、应用最广泛的电子支付企业之一，其推出的支付产品不但包括人民币借记卡、信用卡的支付，还支持Visa、Master Card、JCB等国际3D、非3D信用卡的网上支付。近期，双乾支付开发了国内、国际信用卡的远程线下支付，极大地满足了国内外商户和消费者的需求' ;
$_LANG['MerNo']    = '商户编号';
$_LANG['MD5key']   = 'MD5私钥';
$_LANG['Currency'] = '支付币种';
$_LANG['Currency_range'][1] = '美元';
$_LANG['Currency_range'][2] = '欧元';
$_LANG['Currency_range'][3] = '人民币';
$_LANG['Currency_range'][4] = '英镑';

$_LANG['Currency_range'][5] = '港币';
$_LANG['Currency_range'][6] = '日元';
$_LANG['Currency_range'][7] = '澳元';
$_LANG['Currency_range'][8] = '加元';
$_LANG['Rate']   = '95epay币种兑当前默认币种汇率:';

$_LANG['Language'] = '付款语言';
$_LANG['Language_range']['en'] = '英文';
$_LANG['Language_range']['es'] = '西班牙语';
$_LANG['Language_range']['fr'] = '法语';
$_LANG['Language_range']['it'] = '意大利语';
$_LANG['Language_range']['ja'] = '日语';
$_LANG['Language_range']['de'] = '德语';
$_LANG['Language_range']['zh'] = '中文';



$_LANG['pay_button']        = 'payment online';

$_LANG['TransactionURL']   = '提交地址:';

$_LANG['Returnurl']   = '返回网址:';

$_LANG['Noticeurl']='付款结果接收地址：';

?>