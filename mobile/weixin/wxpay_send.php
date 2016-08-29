<?php
/**
* 发货通知
*/
require(ROOT_PATH . 'mobile/include/lib_payment.php');
require(ROOT_PATH . 'mobile/include/kernel/library/Http.class.php');

if(!empty($rs['openid']) && !empty($rs['transid'])){
	/* 支付方式代码 */
	$pay_code = 'wxpay';
	/* 支付信息 */
	$payment  = get_payment($pay_code);
	
	$data = array();
	$data['appkey'] = $payment['wxpay_paysignkey'];
	//发送给微信的数据
	$data['appid'] = $payment['wxpay_appid'];
	$data['openid'] = $rs['openid'];
	$data['transid'] = $rs['transid'];
	$data['out_trade_no'] = $order['order_sn'];
	$data['deliver_timestamp'] = gmtime();
	$data['deliver_status'] = "1";
	$data['deliver_msg'] = "ok";

	//生成签名
	ksort($data);
	reset($data);
	$string = '';
	foreach($data as $k=>$v){
		$string .= strtolower($k) .'='. $v . '&';
	}
	$string = substr($string, 0, -1);
	$data['app_signature'] = SHA1($string); //参与签名的 appid、 appkey、 openid、 transid、 out_trade_no、 deliver_timestamp、 deliver_status、deliver_msg；

	$data['sign_method'] = "sha1";

	//获取access_token
	$url_token = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$payment['wxpay_appid'].'&secret='.$payment['wxpay_appsecret'];
	$data_token = Http::doGet($url_token);
	$token = json_decode($data_token);

	//发货通知微信
	$url1 = 'https://api.weixin.qq.com/pay/delivernotify?access_token='.$token->access_token;
	$data1 = Http::doPost($url1, $data, 5, '', 'json');
	$rs1 = json_decode($data1);

	//成功通知微信
	if($rs1->errcode == 0){
		//echo 'success';
	}
	else{
		//获取access_token
		$url_token1 = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$payment['wxpay_appid'].'&secret='.$payment['wxpay_appsecret'];
		$data_token1 = Http::doGet($url_token1);
		$token1 = json_decode($data_token1);

		//第一次失败再次发送发货通知微信
		$url2 = 'https://api.weixin.qq.com/pay/delivernotify?access_token='.$token1->access_token;
		$data2 = Http::doPost($url2, $data, 5, '', 'json');
		$rs2 = json_decode($data2);

		if($rs2->errcode == 0){
			//echo 'success';
		}
		else{
			exit($rs2->errcode.':'.$rs2->errmsg);
		}
	}
}
//打印日志
function logResult($word='') {
    $fp = fopen("log.txt","a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}