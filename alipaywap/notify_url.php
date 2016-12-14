<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.5
 * 日期：2016-06-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
*/
function jqlog($word) {
	$fp = fopen("hjq.txt", "a+");
	fwrite($fp, "执行日期：" . strftime("%Y%m%d%H%M%S", time()) . $word . "\n");
	fclose($fp);
}
define('IN_ECS', true);
require_once ("config.php");
require_once 'wappay/service/AlipayTradeService.php';
require_once (dirname(__FILE__) . '/../includes/init.php');
require_once (ROOT_PATH . 'includes/lib_order.php');
require_once (ROOT_PATH . 'includes/lib_payment.php');
//计算得出通知验证结果
$arr = $_POST;
$arr['fund_bill_list'] = stripslashes($arr['fund_bill_list']);
$alipaySevice = new AlipayTradeService($config);
$result = $alipaySevice->check($arr);
jqlog("notify data:");
jqlog("check return:".print_r($arr, true));
if ($result) { //验证成功
	jqlog("notify ok");
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	//商户订单号
	$out_trade_no = $_POST['out_trade_no'];
	//支付宝交易号
	$trade_no = $_POST['trade_no'];
	//交易状态
	$trade_status = $_POST['trade_status'];
	//交易金额
	$total_amount = $_POST['total_amount'];
	//判断是充值还是订单
	$is_cz = strpos($_POST['subject'], '充值');
	//返回商户订单号
	$order_sn = str_replace($_POST['subject'], '', $_POST['out_trade_no']);
	$order_sn = trim(addslashes($order_sn));
	if ($is_cz === FALSE) {
		$pay_log_id = get_order_id_by_sn($order_sn);
		$order_id = get_order_id_by_sn1($order_sn);
	} else {
		$pay_log_id = $_POST['extra_common_param'];
	}
	if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		/* 检查支付的金额是否相符 */
		jqlog('pay_log_id:' . $pay_log_id);
		if (check_money($pay_log_id, $total_amount)) {
			order_paid($pay_log_id, 2); //2 == PS_PAYED
			
		} else {
			jqlog('check_money fail of notify');
		}
	} else {
		jqlog('trade_status ='.$_POST['trade_status']);
	}
	
	echo "success";		//请不要修改或删除
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
} else {
	//验证失败
	jqlog("notify check fail");
	echo "fail";		//请不要修改或删除
}
?>