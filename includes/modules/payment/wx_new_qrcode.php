<?php
/**
 * iTaoWuJi微信新版JSAPI支付插件  fr33m4n 开发  修复
 */
if (!defined('IN_ECS'))
{
	die('Hacking attempt');
}
require_once ROOT_PATH . "/includes/modules/payment/wxpay/lib/WxPay.Api.php";

require_once ROOT_PATH . "/includes/modules/payment/wxpay/lib/WxPay.Notify.php";

require_once ROOT_PATH . "/includes/modules/payment/wxpay/example/WxPay.NativePay.php";

require_once ROOT_PATH . '/includes/modules/payment/wxpay/example/log.php';

// 初始化日志
class PayNotifyCallBack extends WxPayNotify

{
	// 查询订单
	public function Queryorder($transaction_id)

	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	// 重写回调处理函数
	public function NotifyProcess($data, &$msg)

	{
		$notfiyOutput = array();
		if (!array_key_exists("transaction_id", $data))
		{
			$msg = "输入参数不正确";
			return false;
		}
		// 查询订单，判断订单真实性
		if (!$this->Queryorder($data["transaction_id"]))
		{
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}
$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/wx_new_qrcode.php';
if (file_exists($payment_lang))
{
	global $_LANG;
	include_once ($payment_lang);

}
/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
	$i = isset($modules) ? count($modules) : 0;
	/* 代码 */
	$modules[$i]['code'] = basename(__FILE__, '.php');
	/* 描述对应的语言项 */
	$modules[$i]['desc'] = 'wx_new_qrcode_desc';
	/* 是否支持货到付款 */
	$modules[$i]['is_cod'] = '0';
	/* 是否支持在线支付 */
	$modules[$i]['is_online'] = '1';
	/* 作者 */
	$modules[$i]['author'] = 'HJQ QQ 383434167';
	/* 网址 */
	$modules[$i]['website'] = 'http://wx.qq.com';
	/* 版本号 */
	$modules[$i]['version'] = '1.0.0';
	/* 配置信息 */
	$modules[$i]['config'] = array(
		array(
			'name' => 'appid',
			'type' => 'text',
			'value' => ''
		) ,
		array(
			'name' => 'mchid',
			'type' => 'text',
			'value' => ''
		) ,
		array(
			'name' => 'key',
			'type' => 'text',
			'value' => ''
		) ,
		array(
			'name' => 'appsecret',
			'type' => 'text',
			'value' => ''
		) ,
		array(
			'name' => 'logs',
			'type' => 'text',
			'value' => ''
		) ,
	);
	return;
}
class wx_new_qrcode

{
	function __construct()
	{
		$payment = get_payment('wx_new_qrcode');
		if (!defined('WXAPPID'))
		{
			$root_url = str_replace('mobile/', '', $GLOBALS['ecs']->url());
			define("WXAPPID", $payment['appid']);
			define("WXMCHID", $payment['mchid']);
			define("WXKEY", $payment['key']);
			define("WXAPPSECRET", $payment['appsecret']);
			define("WXCURL_TIMEOUT", 30);
			// define('WXNOTIFY_URL',$root_url.'wx_native_callback.php');
			// define('WXSSLCERT_PATH',dirname(__FILE__).'/WxPayPubHelper/cacert/apiclient_cert.pem');
			// define('WXSSLKEY_PATH',dirname(__FILE__).'/WxPayPubHelper/cacert/apiclient_key.pem');
			// define('WXJS_API_CALL_URL',$root_url.'wx_refresh.php');
		}
		// require_once(dirname(__FILE__)."/WxPayPubHelper/WxPayPubHelper.php");
	}
	function get_code($order, $payment)
	{
		$rand = str_pad(strval(rand(0,999999)), 6, "0", STR_PAD_LEFT);
		$out_trade_no = $order['order_sn'];
		$notify = new NativePay();
		$input = new WxPayUnifiedOrder();
		if(empty($order['surplus_amount']))
		{
			$input->SetBody('订单号：'.$order['order_sn']); //商品描述
		}
		else
		{
			$input->SetBody('充值：'.$order['surplus_amount'].'元'); //商品描述
		}
		
		$input->SetOut_trade_no($out_trade_no."_".$rand); //商户订单号
		$input->SetAttach(strval($order['log_id'])); //商户支付日志
		$input->SetTotal_fee(strval(intval($order['order_amount'] * 100))); //总金额
		$input->SetNotify_url("http://" . $_SERVER['SERVER_NAME'] . "/wx_native_callback.php");
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($out_trade_no);
		$payUrl = $notify->GetPayUrl($input);
		$result = WxPayApi::unifiedOrder($input);
		$this->log("WxPayApi::unifiedOrder return:\r\n" . var_export($result, true));
		$code_url = $payUrl["code_url"];
		if ($code_url != NULL)
		{
			if(checkmobile()){
				$html ="wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '".WXAPPID."', // $result[appid]这个返回的和config里面的不一致!!!造成invaild signature错误
    timestamp: '".time()."', // 必填，生成签名的时间戳
    nonceStr: '".$result[nonce_str]."', // 必填，生成签名的随机串
    signature: '".sha1("jsapi_ticket=kgt8ON7yVITDhtdwci0qeaANwLF01SZZt6MXpbQQ_4v7jSDXWqwnw8Y3rYXIGLqj85K9ZKUn4O6PWnyKe4lWFQ&noncestr=".$result[nonce_str]."&timestamp=".time()."&url=http://192.168.0.112/user.php?act=order_detail&order_id=99")."',// 必填，签名，见附录1
    jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
document.querySelector('#chooseWXPay').onclick = function () {
    // 注意：此 Demo 使用 2.7 版本支付接口实现，建议使用此接口时参考微信支付相关最新文档。
    wx.chooseWXPay({
      timestamp: '".time()."',
      nonceStr: '".$result[nonce_str]."',
      package: 'prepay_id=".$result[prepay_id]."',
      signType: 'MD5', // 注意：新版支付接口使用 MD5 加密
      paySign: '".$result[sign]."'
    });
};";
			}
			else{
				$html = '<div class="wx_qrcode" style="text-align:center">';
				$html.= $this->img_qr($code_url);
				$html.= "</div>";
			}
		}
		return $html;
	}
	function respond()
	{
		$this->log("respond()\r\n" . var_export('respond', true));
		$payment = get_payment('wx_new_qrcode');
		$notify = new PayNotifyCallBack();
		$notify->Handle(false);
		$xmlpost = $GLOBALS['HTTP_RAW_POST_DATA'];
		$post = json_decode(json_encode(simplexml_load_string($xmlpost, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		$log_id = intval($post['attach']);
		
		if ($payment['logs'])
		{
			$this->log("传递过来的XML\r\n" . var_export($xml, true));
		}
		// return_code 通信是否返回成功 result_code 交易是否成功
		$this->log("ret:\r\n" . var_export($ret, true));
		$this->log("rawpost:\r\n".var_export($post,true));
		//
		if (!empty($post['return_code']) && $post['return_code'] == 'SUCCESS')
		{
			if ($post["result_code"] == "FAIL")
			{
				// 此处应该更新一下订单状态，商户自行增删操作
				if ($payment['logs'])
				{
					$this->log("支付失败\r\n");
				}
			}
			//支付成功
			if ($post["result_code"] == "SUCCESS")
			{
				$total_fee = $post["total_fee"];
				$sql = 'SELECT order_amount FROM ' . $GLOBALS['ecs']->table('pay_log') . " WHERE log_id = '$log_id'";
				$amount = $GLOBALS['db']->getOne($sql);
				if ($payment['logs'])
				{
					$this->log('订单金额' . $amount . "\r\n");
				}
				if (intval($amount * 100) != $total_fee)
				{
					if ($payment['logs'])
					{
						$this->log('订单金额不符' . "\r\n");
					}
					echo 'fail';
					return;
				}
				$this->log($log_id . "\r\n");
				$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('pay_log') . " WHERE log_id = '$log_id'";
				$pay_log = $GLOBALS['db']->getRow($sql);
				$sql = "select autoload from `wxch_order` where order_name='reorder'";
				$autoload = $GLOBALS['db']->getOne($sql);
				if ($pay_log['is_paid'] == 0)
				{
					if ($autoload == "yes")
					{
						/* 取得订单信息 */
						$sql = 'SELECT * ' . 'FROM ' . $GLOBALS['ecs']->table('order_info') . " WHERE order_id = '$pay_log[order_id]'";
						$order = $GLOBALS['db']->getRow($sql);
						$wxch_order_name = "pay";
						include (ROOT_PATH . 'wxch_order.php');

					}
				}
				order_paid($log_id, 2);
				return true;
			}
		}
		else
		{
			$this->log("通信失败\r\n");
		}
		return false;
	}
	function img_qr($url)
	{
		return '<img src="' . $GLOBALS['ecs']->url() . 'includes/modules/payment/wxpay/example/qrcode.php?data=' . $url . '" />';
	}
	function log($txt)
	{
		$fp = fopen('wx_hjq.txt', 'a+');
		fwrite($fp, '-----------' . local_date('Y-m-d H:i:s') . '-----------------');
		fwrite($fp, $txt);
		fwrite($fp, "\r\n\r\n\r\n");
		fclose($fp);
	}
}