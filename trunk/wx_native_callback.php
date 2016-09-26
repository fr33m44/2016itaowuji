<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_payment.php');
require_once(ROOT_PATH .'includes/modules/payment/wx_new_qrcode.php');

$fp =  fopen(ROOT_PATH.'/wx_hjq.txt','a+');
fwrite($fp,'-----------'.local_date('Y-m-d H:i:s').'-----------------');
fwrite($fp,'in callback file\r\n');
fclose($fp);

$payment = new wx_new_qrcode();
$payment->respond();
exit;