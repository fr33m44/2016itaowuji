<?php

define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/include/init.php');

/* * ****************************************************
  短信发送 开始
 * **************************************************** */

$mobile = $_POST['mobile'];
$mobile_code = $_POST['mobile_code'];

if ($_GET['act'] == 'check') {
    if ($mobile != $_SESSION['sms_mobile'] or $mobile_code != $_SESSION['sms_mobile_code']) {
        exit(json_encode(array('msg' => '手机验证码输入错误。')));
    } else {


        exit(json_encode(array('code' => '2')));
    }
}

if ($_GET['act'] == 'send') {
    if (empty($mobile)) {
        exit(json_encode(array('msg' => '手机号码不能为空')));
    }

    $preg = '/^1[0-9]{10}$/'; //简单的方法
    if (!preg_match($preg, $mobile)) {
        exit(json_encode(array('msg' => '手机号码格式不正确')));
    }

    if ($_SESSION['sms_mobile']) {
        if (strtotime(read_file($mobile)) > (time() - 60)) {
            exit(json_encode(array('msg' => '获取验证码太过频繁，一分钟之内只能获取一次。')));
        }
    }

    $sql = "select user_id from " . $ecs->table('users') . " where mobile_phone='" . $mobile . "'";
    $user_id = $db->getOne($sql);
    if($_GET['flag'] == 'register'){
        //手机注册
        if (!empty($user_id)) {
            exit(json_encode(array('msg' => '手机号码已存在，请更换手机号码')));
        }
    }elseif($_GET['flag'] == 'forget'){
        //找回密码
        if (empty($user_id)) {
            exit(json_encode(array('msg' => "手机号码不存在\n无法通过该号码找回密码")));
        }
    }

    $mobile_code = random(4, 1);
    $message = "您的验证码是：" . $mobile_code . "，请不要把验证码泄露给其他人，如非本人操作，可不用理会！";
    //暂时关闭 上线后开启 by wang
	include(ROOT_PATH . 'include/cls_sms.php');
	$sms = new sms();
	$sms_error = array();
	$send_result = $sms->send($mobile, $message, $sms_error);
	write_file($mobile, date("Y-m-d H:i:s"));
	if ($send_result) {
		$_SESSION['sms_mobile'] = $mobile;
		$_SESSION['sms_mobile_code'] = $mobile_code;
		exit(json_encode(array('code' => 2, 'mobile_code' => $mobile_code)));
	} else {
		exit(json_encode(array('msg' => $sms_error)));
	}
}

/* * ****************************************************
  protected function
 * **************************************************** */

function random($length = 6, $numeric = 0) {
    PHP_VERSION < '4.2.0' && mt_srand((double) microtime() * 1000000);
    if ($numeric) {
        $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}

function write_file($file_name, $content) {
    mkdirs('./data/smslog/' . date('Ymd'));
    $filename = './data/smslog/' . date('Ymd') . '/' . $file_name . '.log';
    $Ts = fopen($filename, "a+");
    fputs($Ts, "\r\n" . $content);
    fclose($Ts);
}

function mkdirs($dir, $mode = 0777) {
    if (is_dir($dir) || @mkdir($dir, $mode))
        return TRUE;
    if (!mkdirs(dirname($dir), $mode))
        return FALSE;
    return @mkdir($dir, $mode);
}

function read_file($file_name) {
    $content = '';
    $filename = './data/smslog/' . date('Ymd') . '/' . $file_name . '.log';
    if (function_exists('file_get_contents')) {
        @$content = file_get_contents($filename);
    } else {
        if (@$fp = fopen($filename, 'r')) {
            @$content = fread($fp, filesize($filename));
            @fclose($fp);
        }
    }
    $content = explode("\r\n", $content);
    return end($content);
}

?>