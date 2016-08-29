<?php
/**
* 维权通知
*/
define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/../include/init.php');
require(ROOT_PATH . 'include/lib_payment.php');
require(ROOT_PATH . 'include/kernel/library/Http.class.php');
//接收数据
$postStr = $GLOBALS['HTTP_RAW_POST_DATA'];

if(!empty($postStr)){
	//强制转换为数组
	$postData = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

	/* 支付方式代码 */
	$pay_code = 'wxpay';
	/* 支付信息 */
	$payment  = get_payment($pay_code);

	//确定为新增还是确认
	if(isset($postData['TransId']) && !empty($postData['TransId'])){
		$data = $postData;
	}
	else{
		$data = $postData;
	}

//标记客户的投诉处理状态api
	//获取access_token
	$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$payment['wxpay_appid'].'&secret='.$payment['wxpay_appsecret'];
	$data = Http::doGet($url);
	$token = json_decode($data);

	//标记客户的投诉处理状态
	$url1 = 'https://api.weixin.qq.com/payfeedback/update?access_token='.$token->access_token.'&openid='.$data['OpenId'].'&feedbackid='.$data['FeedBackId'];
	$rs =  Http::doGet($url1);
	$rs_back = json_decode($rs);

	if($rs_back->errcode == 0){
		echo $rs_back->errmsg; //OK
		//logResult($rs_back->errmsg);
	}
	else{
		echo $rs_back->errcode . ':' . $rs_back->errmsg;
		//logResult($rs_back->errcode . ':' . $rs_back->errmsg);
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