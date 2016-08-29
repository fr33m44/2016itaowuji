<?php

/**
 * ECSHOP 银联在线手机支付
 * ============================================================================
 * 版权所有 2014 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ============================================================================
 * $Author: wanganlin $
 * $Id: upop_wap.php 17063 2010-03-25 06:35:46Z douqinghua $
 */
if (!defined('IN_ECTOUCH')) {
    die('Hacking attempt');
}

// 包含配置文件
$payment_lang = ROOT_PATH . 'lang/' . $GLOBALS['_CFG']['lang'] . '/payment/upop_wap.php';

if (file_exists($payment_lang)) {
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE) {
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code'] = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'upop_wap_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod'] = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online'] = '1';

    /* 作者 */
    $modules[$i]['author'] = 'ECTOUCH TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.ectouch.cn';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'upop_wap_merAbbr', 'type' => 'text', 'value' => '商户名称'),
        array('name' => 'upop_wap_account', 'type' => 'text', 'value' => ''),
        array('name' => 'upop_wap_security_key', 'type' => 'text', 'value' => ''),
    );

    return;
}

/**
 * 类
 */
class upop_wap {

    static $timezone          = "Asia/Shanghai"; //时区

    static $version           = "1.0.0"; // 版本号
    static $charset           = "UTF-8"; // 字符编码
    static $sign_method       = "MD5"; // 签名方法，目前仅支持MD5

    static $upmp_trade_url    = "https://mgate.unionpay.com/gateway/merchant/trade";
    static $upmp_query_url    = "https://mgate.unionpay.com/gateway/merchant/query";
    // UPOP Pay 测试接口
    //static $upmp_trade_url    = "http://222.66.233.198:8080/gateway/merchant/trade";
    //static $upmp_query_url    = "http://222.66.233.198:8080/gateway/merchant/query";

    const VERIFY_HTTPS_CERT   = false;

    const RESPONSE_CODE_SUCCESS 	= "00"; // 成功应答码
    const SIGNATURE 				= "signature"; // 签名
    const SIGN_METHOD 				= "signMethod"; // 签名方法
    const RESPONSE_CODE 			= "respCode"; // 应答码
    const RESPONSE_MSG				= "respMsg"; // 应答信息

    const QSTRING_SPLIT				= "&"; // &
    const QSTRING_EQUAL 			= "="; // =

    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */

    function get_code($order, $payment) {

        // 订单相关时间
        $orderTime = $order['add_time'];
        $orderTimeout = $order['add_time'] + 3600; //一天后订单超时

        //需要填入的部分
        $req['version']     		= self::$version; // 版本号
        $req['charset']     		= self::$charset; // 字符编码
        $req['transType']   		= "01"; // 交易类型
        $req['merId']       		= $payment['upop_wap_account']; // 商户代码
        $req['backEndUrl']      	= $this->return_wap_url(); // 通知URL
        $req['frontEndUrl']     	= return_url(basename(__FILE__, '.php')); // 前台通知URL(可选)
        $req['orderDescription']	= $order['order_sn'];// 订单描述(可选)
        $req['orderTime']   		= date('YmdHis', $orderTime); // 交易开始日期时间yyyyMMddHHmmss
        $req['orderTimeout']   		= ''; // 订单超时时间yyyyMMddHHmmss(可选)
        $req['orderNumber'] 		= $order['order_sn'] .'O'. $order['log_id']; //订单号(商户根据自己需要生成订单号)
        $req['orderAmount'] 		= $order['order_amount'] * 100; // 订单金额，乘以100以分为单位
        $req['orderCurrency']   = "156"; // 交易币种(可选)

        $resp = array ();
        $validResp = self::trade($req, $resp, $payment['upop_wap_security_key']);

        // 商户的业务逻辑
        if ($validResp){
            // 服务器应答签名验证成功
            $resultURL = urlencode($req['frontEndUrl'].'&result=');
            $paydata = 'tn='.$resp['tn'].',resultURL='.$resultURL.',usetestmode=false'; // 生产模式
            $html = '<div style="text-align:center">';
            $html .= '<div id="upop_uc_browser" style="display:none"><embed type="application/x-unionpayplugin" uc_plugin_id="unionpay" height="53" width="178" paydata="'.base64_encode($paydata).'"></embed></div>';
            $html .= '<div id="upop_third_browser" style="display:none"><a href="uppay://uppayservice/?style=token&paydata='.base64_encode($paydata).'" alt="银联手机支付"/><img src="data/static/images/upop_wap_pay.jpg" /></a></div>';
            $html .= '</div>';
        }else {
            // 服务器应答签名验证失败
            $html = $resp['respMsg'];
        }
        // 获取头信息
        $headers = array();
        //getallheaders()函数 在iis或nginx并不支持，可以通过自定义函数实现
        if (!function_exists('getallheaders'))
        {
            function getallheaders()
            {
               foreach ($_SERVER as $name => $value)
               {
                   if (substr($name, 0, 5) == 'HTTP_')
                   {
                       $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                   }
               }
               return $headers;
            }
        }
        foreach (getallheaders() as $name => $value) {
            $headers[$name] = $value;
        }
        // 判断显示相应支付标签
        $script = '<script type="text/javascript">
// accept 头部由商户后台返回给前端页面
var accept = "'.$headers['Accept'].'";
var ret = browserSupport(accept);
if(ret.support != "true"){
    document.getElementById("upop_third_browser").style.display = "Block";
}else{
    document.getElementById("upop_uc_browser").style.display = "Block";
}
function browserSupport(accept) {
    var ucSupport = ucSnoof(accept);
    if(ucSupport.support == "true"){
        return ucSupport;
    }
    // 通用判断
    var userAgent = navigator.userAgent.toLowerCase();
    // 到这里，uc 已经不判断了
    if (plateformSnoof(userAgent) == "android") {
        if ((/360 aphone browser/.test(userAgent)) || (/opera\//.test(userAgent) && /oupenghd/.test(userAgent)) || (/qqbrowser/.test(userAgent))) {
            return {
                \'support\' : \'true\',
                \'tag\' : \'a\'
            };
        }else{
            return {
                \'support\' : \'false\',
            };
        }
    } else {
        return {
            \'support\' : \'false\',
        };
    }
}
function creditSupport(accept){
    var ucSupport = ucSnoof(accept);
    if(ucSupport.support == "true"){
        return ucSupport;
    } else{
        return {
            \'support\' : \'false\',
        };
    }
}
function ucSnoof(accept){
    if (!accept) {
        return;
    }
    // uc 浏览器判断
    var ucIos = new RegExp("ios_plugin/1");
    var ucAndroid = new RegExp("plugin/1");
    if (ucIos.test(accept)) {
        return {
            \'support\' : \'true\',
            \'tag\' : \'a\'
        };
    } else if (ucAndroid.test(accept)) {
        return {
            \'support\' : \'true\',
            \'tag\' : \'embed\'
        };
    } else{
        return {
            \'support\' : \'false\',
        };
    }
}
function plateformSnoof(userAgent) {
    var ios = new RegExp("iphone os");
    var android = new RegExp("android");
    if (ios.test(userAgent)) {
        return "ios";
    }
    if (android.test(userAgent)) {
        return "android";
    }
}
</script>';
        return $html.$script;
    }

    /**
     * 响应操作
     */
    function respond() {
        //0：支付成功
        if ($_GET['result'] == '0') {
                return true;
        } else {
                return false;
        }
    }

    /**
    * 取得返回信息地址
    */
    function return_wap_url()
    {
        return $GLOBALS['ecs']->url() . 'notify/upop_wap.php';
    }

    /**
     * 交易接口处理
     * @param req 请求要素
     * @param resp 应答要素
     * @return 是否成功
     */
    static function trade($req, &$resp, $security_key = '') {
    	$nvp = self::buildReq($req, $security_key);
    	$respString = self::post(self::$upmp_trade_url, $nvp);
    	return self::verifyResponse($respString, $resp, $security_key);
    }

    /**
     * 交易查询处理
     * @param req 请求要素
     * @param resp 应答要素
     * @return 是否成功
     */
    static function query($req, &$resp, $security_key = '') {
    	$nvp = self::buildReq($req, $security_key);
    	$respString = self::post(self::$upmp_query_url, $nvp);
    	return self::verifyResponse($respString, $resp, $security_key);
    }

    /**
     * 拼接请求字符串
     * @param req 请求要素
     * @return 请求字符串
     */
    static function buildReq($req, $security_key = '') {
    	//除去待签名参数数组中的空值和签名参数
    	$filteredReq = self::paraFilter($req);
    	// 生成签名结果
    	$signature = self::buildSignature($filteredReq, $security_key);

    	// 签名结果与签名方式加入请求
    	$filteredReq[self::SIGNATURE] = $signature;
    	$filteredReq[self::SIGN_METHOD] = self::$sign_method;

    	return self::createLinkstring($filteredReq, false, true);
    }

    /**
     * 拼接保留域
     * @param req 请求要素
     * @return 保留域
     */
    static function buildReserved($req) {
    	$prestr = "{".self::createLinkstring($req, true, true)."}";
    	return $prestr;
    }

    /**
     * 应答解析
     * @param respString 应答报文
     * @param resp 应答要素
     * @return 应答是否成功
     */
    static function verifyResponse($respString, &$resp, $security_key = '') {
    	if  ($respString != ""){
    		parse_str($respString, $para);

    		$signIsValid = self::verifySignature($para, $security_key);

    		$resp = $para;
    		if ($signIsValid) {
    			return true;
    		}else {
    			return false;
    		}
    	}


    }

    /**
     * 异步通知消息验证
     * @param para 异步通知消息
     * @return 验证结果
     */
    static function verifySignature($para, $security_key = '') {
    	$respSignature = $para[self::SIGNATURE];
    	// 除去数组中的空值和签名参数
    	$filteredReq = self::paraFilter($para);
    	$signature = self::buildSignature($filteredReq, $security_key);
    	if ("" != $respSignature && $respSignature==$signature) {
    		return true;
    	}else {
    		return false;
    	}
    }

    /* ================== 分割线 公用函数 分割线 ================== */

    /**
     * 除去请求要素中的空值和签名参数
     * @param para 请求要素
     * @return 去掉空值与签名参数后的请求要素
     */
    static function paraFilter($para) {
        $result = array();
        while (list ( $key, $value ) = each($para)) {
            if ($key == self::SIGNATURE || $key == self::SIGN_METHOD || $value == "") {
                continue;
            } else {
                $result [$key] = $para [$key];
            }
        }
        return $result;
    }

    /**
     * 生成签名
     * @param req 需要签名的要素
     * @return 签名结果字符串
     */
    static function buildSignature($req, $security_key = '') {
        $prestr = self::createLinkstring($req, true, false);
        $prestr = $prestr . self::QSTRING_SPLIT . md5($security_key);
        return md5($prestr);
    }

    /**
     * 把请求要素按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param para 请求要素
     * @param sort 是否需要根据key值作升序排列
     * @param encode 是否需要URL编码
     * @return 拼接成的字符串
     */
    static function createLinkString($para, $sort, $encode) {
        $linkString = "";
        if ($sort) {
            $para = self::argSort($para);
        }
        while (list ($key, $value) = each($para)) {
            if ($encode) {
                $value = urlencode($value);
            }
            $linkString.=$key . self::QSTRING_EQUAL . $value . self::QSTRING_SPLIT;
        }
        //去掉最后一个&字符
        $linkString = substr($linkString, 0, count($linkString) - 2);

        return $linkString;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    static function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /*
     * curl_call
     *
     * @url:  string, curl url to call, may have query string like ?a=b
     * @content: array(key => value), data for post
     *
     * return param:
     * 	mixed:
     * 	  false: error happened
     * 	  string: curl return data
     *
     */

    static function post($url, $content = null) {

        if (function_exists("curl_init")) {
            $curl = curl_init();

            if (is_array($content)) {
                $data = http_build_query($content);
            }

            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
            // https verify
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, self::VERIFY_HTTPS_CERT);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, self::VERIFY_HTTPS_CERT);

            $ret_data = curl_exec($curl);

            if (curl_errno($curl)) {
                printf("curl call error(%s): %s\n", curl_errno($curl), curl_error($curl));
                curl_close($curl);
                return false;
            } else {
                curl_close($curl);
                return $ret_data;
            }
        } else {
            throw new Exception("[PHP] curl module is required");
        }
    }

}
?>