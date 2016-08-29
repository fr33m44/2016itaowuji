<?php

/**
 * 手机支付宝异步响应操作 by Bragg
 */

define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/../include/init.php');
require(ROOT_PATH . 'include/lib_payment.php');
require(ROOT_PATH . 'include/lib_order.php');

/* 支付方式代码 */
$pay_code = 'alipay_wap';
/* 支付信息 */
$payment  = get_payment($pay_code);

if(!empty($_POST)){
	//支付宝系统通知待签名数据构造规则比较特殊，为固定顺序。
	$parameter['service'] = $_POST['service'];
	$parameter['v'] = $_POST['v'];
	$parameter['sec_id'] = $_POST['sec_id'];
	$parameter['notify_data'] = $_POST['notify_data'];
	//生成签名字符串
	$sign = '';
	foreach ($parameter AS $key=>$val) {
		$sign .= "$key=$val&";
	}
	$sign = substr($sign, 0, -1) . $payment['alipay_key'];
	//验证签名
	if (md5($sign) != $_POST['sign']) {
		exit("fail");
	}
	//解析notify_data
	$notify_data = (array)simplexml_load_string($parameter['notify_data']);
	//商户订单号
	$out_trade_no = $notify_data['out_trade_no'];
	//支付宝交易号
	$trade_no = $notify_data['trade_no'];
	//交易状态
	$trade_status = $notify_data['trade_status'];
	//获取log_id
	$out_trade_no = explode('_', $out_trade_no);
	$order_sn = $out_trade_no[1];//订单号log_id
	if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
		/* 改变订单状态 */
		order_paid($order_sn, 2);
		echo "success";
	} else {
		echo "fail";
	}
}else{
	echo "fail";
}
//打印日志
function logResult($word='') {
	$fp = fopen("log.txt","a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}
?>