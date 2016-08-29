<?php

/**
 * 手机银联支付异步响应操作 by Bragg
 */

define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/../include/init.php');
require(ROOT_PATH . 'include/lib_payment.php');
require(ROOT_PATH . 'include/lib_order.php');

/* 支付方式代码 */
$pay_code = 'upop_wap';
/* 支付信息 */
$payment  = get_payment($pay_code);

// 获取异步数据
$async_data = $_POST;

if(!empty($async_data)){
    $plugin_file = ROOT_PATH . 'include/modules/payment/' . $pay_code . '.php';
    /* 检查插件文件是否存在，如果存在则验证支付是否成功，否则则返回失败信息 */
    if (file_exists($plugin_file)) {
        /* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
        include_once($plugin_file);
        if (upop_wap::verifySignature($async_data, $payment['upop_wap_security_key'])){ // 服务器签名验证成功
            if ($async_data['transStatus'] == "00"){ // 交易处理成功
                //获取log_id
                $out_trade_no = explode('O', $async_data['orderNumber']);
                $order_sn = $out_trade_no[1];//订单号log_id
                // 改变订单状态
                order_paid($order_sn, 2);
            }
            echo "success";
        }else {// 服务器签名验证失败
            echo "fail";
        }
    } else {
        exit("fail");
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