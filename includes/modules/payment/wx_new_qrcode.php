<?php
/**
 * ECSHOP微信新版JSAPI支付插件  甜心  10 0 开发  修复  
 */
if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

require_once ROOT_PATH."/includes/modules/payment/wxpay/lib/WxPay.Api.php";
require_once ROOT_PATH."/includes/modules/payment/wxpay/example/WxPay.NativePay.php";
require_once ROOT_PATH.'/includes/modules/payment/wxpay/example/log.php';

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/wx_new_qrcode.php';
if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'wx_new_qrcode_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'HJQ QQ 383434167';

    /* 网址 */
    $modules[$i]['website'] = 'http://wx.qq.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'appid',           'type' => 'text',   'value' => ''),
        array('name' => 'mchid',               'type' => 'text',   'value' => ''),
        array('name' => 'key',           'type' => 'text',   'value' => ''),
        array('name' => 'appsecret',           'type' => 'text',   'value' => ''),
		array('name' => 'logs',           'type' => 'text',   'value' => ''),
    );

    return;
}

class wx_new_qrcode
{
	function __construct()
	{
		$payment = get_payment('wx_new_qrcode');
    
        if(!defined('WXAPPID'))
        {
            $root_url = str_replace('mobile/', '', $GLOBALS['ecs']->url());
            define("WXAPPID", $payment['appid']);
            define("WXMCHID", $payment['mchid']);
            define("WXKEY", $payment['key']);
            define("WXAPPSECRET", $payment['appsecret']);
            define("WXCURL_TIMEOUT", 30);
            //define('WXNOTIFY_URL',$root_url.'wx_native_callback.php');
            //define('WXSSLCERT_PATH',dirname(__FILE__).'/WxPayPubHelper/cacert/apiclient_cert.pem');
            //define('WXSSLKEY_PATH',dirname(__FILE__).'/WxPayPubHelper/cacert/apiclient_key.pem');
            
            //define('WXJS_API_CALL_URL',$root_url.'wx_refresh.php');
        }
        //require_once(dirname(__FILE__)."/WxPayPubHelper/WxPayPubHelper.php");

	}
	function get_code($order, $payment)
	{
		$notify = new NativePay();
		
		$unifiedOrder = new WxPayUnifiedOrder();

        $unifiedOrder->SetBody($order['order_sn']);//商品描述
        $out_trade_no = $order['order_sn'];
        $unifiedOrder->SetOut_trade_no($out_trade_no);//商户订单号 
        $unifiedOrder->SetAttach(strval($order['log_id']));//商户支付日志
        $unifiedOrder->SetTotal_fee(strval(intval($order['order_amount']*100)));//总金额
		//$unifiedOrder->SetTime_start(date("YmdHis"));
		//$unifiedOrder->SetTime_expire(date("YmdHis", time() + 600));
		$unifiedOrder->SetNotify_url("http://".$_SERVER['SERVER_NAME']."/includes/modules/payment/wxpay/example/notify.php");
		$unifiedOrder->SetTrade_type("NATIVE");
		$unifiedOrder->SetProduct_id($out_trade_no);
        //$unifiedOrderResult = $unifiedOrder->getResult();
		$result = $notify->GetPayUrl($unifiedOrder);
		$this->log("GetPayUrl return:\r\n".var_export($result,true));
		$code_url = $result["code_url"];

        $html = '<button type="button" onclick="javascript:alert(\'出错了\')">微信支付</button>';

        if($code_url != NULL)
        {
            $html = '<div class="wx_qrcode" style="text-align:center">';
            $html .= $this->getcode($code_url);
            $html .= "</div>";

            $html .= "<div style=\"text-align:center\">支付后点击<a href=\"user.php?act=order_list\">此处</a>查看我的订单</div>";
        }
        
        

        return $html;
	}
    function respond()
    {
		$this->log("respond()\r\n".var_export('respond',true));
		
        $payment  = get_payment('wx_new_qrcode');

        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		
        if($payment['logs'])
        {
            $this->log("传递过来的XML\r\n".var_export($xml,true));
        }
        $notify->saveData($xml);
		$checkSign = $notify->checkSign();
		print_r("checkSign:$checkSign");
		print_r("notify data:$notify->data");
        if($checkSign == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                if($payment['logs']){
                    $this->log("return_code失败\r\n");
                }
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                if($payment['logs']){
                    $this->log("result_code失败\r\n");
                }
            }
            else{
                //此处应该更新一下订单状态，商户自行增删操作
                if($payment['logs']){
                    $this->log("支付成功\r\n");
                }
                $total_fee = $notify->data["total_fee"];
                $log_id = $notify->data["attach"];
                $sql = 'SELECT order_amount FROM ' . $GLOBALS['ecs']->table('pay_log') ." WHERE log_id = '$log_id'";
                $amount = $GLOBALS['db']->getOne($sql);
                
                if($payment['logs'])
                {
                    $this->log('订单金额'.$amount."\r\n");
                }
                
                if(intval($amount*100) != $total_fee)
                {
                    
                    if($payment['logs'])
                    {   
                        $this->log('订单金额不符'."\r\n");
                    }
                    
                    echo 'fail';
                    return;
                }
				
		 $this->log($log_id."\r\n");
        $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('pay_log') .
                " WHERE log_id = '$log_id'";
        $pay_log = $GLOBALS['db']->getRow($sql);
		$sql ="select autoload from `wxch_order` where order_name='reorder'";
		$autoload=$GLOBALS['db']->getOne($sql);
		
		
		if($pay_log['is_paid'] == 0){
			if($autoload == "yes"){
			        /* 取得订单信息 */
				$sql = 'SELECT * ' .
                        'FROM ' . $GLOBALS['ecs']->table('order_info') .
                       " WHERE order_id = '$pay_log[order_id]'";
				$order    = $GLOBALS['db']->getRow($sql);
				$wxch_order_name="pay";		 
				include(ROOT_PATH . 'wxch_order.php');
				}
		}
		
		order_paid($log_id, 2);
		        return true;
            }

        }
        else
        {
            $this->log("签名失败\r\n");
        }
        return false;
    }


    function getcode($url){
        if(file_exists(ROOT_PATH . 'includes/phpqrcode.php')){
            include(ROOT_PATH . 'includes/phpqrcode.php');
        }
        // 纠错级别：L、M、Q、H 
        $errorCorrectionLevel = 'Q';  
        // 点的大小：1到10 
        $matrixPointSize = 5;
        // 生成的文件名
        $tmp = ROOT_PATH .'images/qrcode/';
        if(!is_dir($tmp)){
            @mkdir($tmp);
        }
        $filename = $tmp . $errorCorrectionLevel . $matrixPointSize . '.png';
        QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
		//print_r($url.$filename.$errorCorrectionLevel.$matrixPointSize);die();
        return '<img src="'.$GLOBALS['ecs']->url(). 'images/qrcode/'.basename($filename).'" />';
    }
    
    function log($txt)
    {
       $fp =  fopen('wx_hjq.txt','a+');
       fwrite($fp,'-----------'.local_date('Y-m-d H:i:s').'-----------------');
       fwrite($fp,$txt);
       fwrite($fp,"\r\n\r\n\r\n");
       fclose($fp);
    }
    
}