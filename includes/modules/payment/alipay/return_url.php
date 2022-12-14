<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.5
 * 日期：2016-06-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */

function jqlog($word)
{
	$fp = fopen("hjq.txt","a+");
	fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time()) .$word."\n");
	fclose($fp);
}

jqlog("old return");

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");


define('IN_ECS', true);

require_once(dirname(__FILE__) . '/../../../../includes/init.php');
require_once(ROOT_PATH . 'includes/lib_order.php');
require_once(ROOT_PATH . 'includes/lib_payment.php');


//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	//print_r($_GET);die();
	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号
	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];
	
	//交易金额
	
	$total_fee = $_GET['total_fee'];
	
	$is_cz = strpos($_GET['subject'],'充值');
	

	
	//返回商户订单号
	$order_sn = str_replace($_GET['subject'], '', $_GET['out_trade_no']);
	$order_sn = trim(addslashes($order_sn));
	if($is_cz === FALSE)
	{
		$pay_log_id = get_order_id_by_sn($order_sn);
		$order_id = get_order_id_by_sn1($order_sn);
	}
	else
	{
		$pay_log_id = $_GET['extra_common_param'];
	}
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS')
	{
		
        /* 检查支付的金额是否相符 */
		if (check_money($pay_log_id, $total_fee))
        {
			order_paid($pay_log_id, 2);
        }
		else
		{
			echo '支付金额不正确！';
		}
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }
	
	if($is_cz === FALSE)//订单
	{
		ecs_header("Location: http://".$_SERVER['SERVER_NAME']."/user.php?act=order_detail&order_id=".$order_id."\n");
	}
	else
	{
		ecs_header("Location: http://".$_SERVER['SERVER_NAME']."/user.php?act=account_detail\n");
	}
	//echo "验证成功<br />";

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
	ecs_header("Location: http://".$_SERVER['SERVER_NAME']."/user.php\n");
}
?>