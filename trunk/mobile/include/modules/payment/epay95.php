<?php

/**
 * ECSHOP 95epay 支付插件
 * ============================================================================
 * 版权所有 2005-2008 广州双乾网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；如你想与本人交朋友：请加QQ:693150707
 * ============================================================================
 * $Author: admin_zhang
 */

if (!defined('IN_ECTOUCH'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'lang/' .$GLOBALS['_CFG']['lang']. '/payment/epay95.php';

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
    $modules[$i]['desc']    = 'epay95_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = '95epay';

    /* 网址 */
    $modules[$i]['website'] = 'https://www.95eapy.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.1';
	$url='https://payment.95epay.com/sslpayment';
	$returnurl = 'http://'.$_SERVER ['HTTP_HOST'].'/respond.php?code=epay95';
	$noticeurl='http://'.$_SERVER ['HTTP_HOST'].'/notice_95epay.php';
    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'MerNo', 'type' => 'text', 'value' => '1002'),
        array('name' => 'MD5key', 'type' => 'text', 'value' => '12345678'),
        array('name' => 'Currency', 'type' => 'select', 'value' => '1'),
		array('name' => 'Rate', 'type' => 'text', 'value' => '1.00000'),
        array('name' => 'Language', 'type' => 'select', 'value' => 'en'),
		array('name' => 'TransactionURL', 'type' => 'text', 'value' => "$url"),
		array('name' => 'Returnurl', 'type' => 'text', 'value' => "$returnurl"),
		array('name' => 'Noticeurl', 'type' => 'text', 'value' => "$noticeurl")
    );

    return;
}

/**
 * 类
 */
class epay95
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function epay95()
    {
    }

    function __construct()
    {
        $this->epay95();
    }

	
    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
         $MD5key = $payment['MD5key'];                             //MD5私钥
         $MerNo = $payment['MerNo'];                               //商户号
         $BillNo = $order['order_sn'] ;      //订单号
         $Currency = $payment['Currency'];                         //币种
         
		 $DisAmount = $order['order_amount'];                         //外币金额
		 
         $rate=$payment['Rate'];
		 $Amount = number_format($DisAmount * $rate, 2, '.', '');
         $Language = $payment['Language'];                         //语言
         $ReturnURL = $payment['Returnurl'];      //返回地址
		 $Noticeurl=$payment['Noticeurl'];
         $Remark = 'http://'.$_SERVER ['HTTP_HOST']; //备注
		 $MerWebsite='http://'.$_SERVER ['HTTP_HOST'];//当前域名

         $md5src = $MerNo.$BillNo.$Currency.$Amount.$Language.$ReturnURL.$MD5key; //校验源字符串
         $MD5info = strtoupper(md5($md5src));                                     //MD5检验结果

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


		/*
		 *以下九个参数为收货人信息,能收集的数据请尽力收集，,实在收集不到的参数---请赋空值,谢谢
		 */
		 $DeliveryFirstName="";//[选填]-------------------收货人的姓
		 $DeliveryLastName=$order['consignee'];//[选填]-------------------收货人的名
		 $DeliveryEmail=$order['email'];//[选填]----------收货人的Email
		 $DeliveryPhone=$order['tel'];//[选填]----------------收货人的固定电话
		 $DeliveryZipCode=$order['zipcode'];//[选填]----------------收货人的邮编
		 $DeliveryAddress=$order['address'];//[选填]-------------收货人具体地址

		 /* 获取城市名称 */
	
/*		$region_id=$order['city'];
		 $sql = 'SELECT region_name FROM ' . $GLOBALS['ecs']->table('region') .
            " WHERE region_id = '$region_id'";
		 $res= $GLOBALS['db']->query($sql);
		 $row = $GLOBALS['db']->FetchRow($res);
			
		 $DeliveryCity=$row['region_name'];//[选填]--------------------收货人所在城市*/
	    $DeliveryCity=$order['city'];

		 /* 获取省或者州名称 */
/*		 $region_id=$order['province'];
		 $sql = 'SELECT region_name FROM ' . $GLOBALS['ecs']->table('region') .
            " WHERE region_id = '$region_id'";
		 $res= $GLOBALS['db']->query($sql);
		 $row = $GLOBALS['db']->FetchRow($res);
		 $DeliveryState=$row['region_name'];//[选填]-------------------收货人所在省或州者*/

		 $DeliveryState=$order['province'];

		 /* 获取省或者州名称 */
	/*	 $region_id=$order['country'];
		 $sql = 'SELECT region_name FROM ' . $GLOBALS['ecs']->table('region') .
            " WHERE region_id = '$region_id'";
		 $res= $GLOBALS['db']->query($sql);
		 $row = $GLOBALS['db']->FetchRow($res);
		 $DeliveryCountry=$row['region_name'];//[选填]-------------------收货人所在国家*/
 		$DeliveryCountry=$order['country'];

                                            

        $button = '<form action="'.$payment['TransactionURL'].'" method="post">'.
                    "  <input type='hidden' name='MerNo' value='". $MerNo ."'>".
                    "  <input type='hidden' name='Currency' value='". $Currency ."'>".
                    "  <input type='hidden' name='BillNo' value='". $BillNo ."'>".
                    "  <input type='hidden' name='Amount' value='". $Amount ."'>".
                    "  <input type='hidden' name='DisAmount' value='". $DisAmount ."'>".
                    "  <input type='hidden' name='ReturnURL' value='". $ReturnURL ."'>".
					"  <input type='hidden' name='Noticeurl' value='". $Noticeurl ."'>".
                    "  <input type='hidden' name='Language' value='". $Language ."'>".
                    "  <input type='hidden' name='MD5info' value='". $MD5info ."'>".
					"  <input type='hidden' name='MerWebsite' value='". $MerWebsite ."'>".
                    "  <input type='hidden' name='Remark' value='". $Remark ."'>".										
					"  <input type='hidden' name='DeliveryFirstName' value='". $DeliveryFirstName ."'>".
					"  <input type='hidden' name='DeliveryLastName' value='". $DeliveryLastName ."'>".
					"  <input type='hidden' name='DeliveryEmail' value='". $DeliveryEmail ."'>".
					"  <input type='hidden' name='DeliveryPhone' value='". $DeliveryPhone ."'>".
					"  <input type='hidden' name='DeliveryZipCode' value='". $DeliveryZipCode ."'>".
					"  <input type='hidden' name='DeliveryAddress' value='". $DeliveryAddress ."'>".
					"  <input type='hidden' name='DeliveryCity' value='". $DeliveryCity ."'>".
					"  <input type='hidden' name='DeliveryState' value='". $DeliveryState ."'>".
					"  <input type='hidden' name='DeliveryCountry' value='". $DeliveryCountry ."'>".
                    "  <input type='submit' name='b1' value='visa,master payment online'>".
                    "</form>";

        return $button;
    }
	public function notice(){
		$payment  = get_payment('epay95');
		if(isset($_POST["PaymentResult"]) && !empty($_POST["PaymentResult"]) )
		{
			// 支付平台流水号
			$TradeNo=$_POST["TradeNo"];// 供商户在支付平台查询订单时使用,请合理保存
			// 订单号
			$BillNo = $_POST["BillNo"];	
			// 币种
			$Currency = $_POST["Currency"];
			// 订单金额
			$Amount = $_POST["Amount"];
			// 支付结果
			$PaymentResult = $_POST["PaymentResult"];// 交易结果: 0 : 失败；1 : 成功;2:处理中
			
			// 取得的MD5校验信息
			$MD5info = $_POST["MD5info"]; 
			
			// MD5私钥
			$MD5key = $payment['MD5key'];
			// $MD5key = "12345678";//从支付平台获取
			// 校验源字符串
			$md5src = $TradeNo.$BillNo.$Currency.$Amount.$PaymentResult.$MD5key;
			// MD5检验结果
			$md5sign = strtoupper(md5($md5src));
			
			$zh=get_order_id_by_sn($BillNo);//substr($BillNo, 14);
			$zh = intval(trim($zh));
			
			if($MD5info==$md5sign)
			{// 验证成功
			
				if (check_money($zh, $Amount/$payment['Rate']))
	                {
					
						if($PaymentResult=='2')//处理中
						{
							order_paid($zh, PS_PAYING);
							echo 'processing'.$zh;
						}
						else if($PaymentResult=='1')//付款成功
						{
							/* 改变订单状态 */
							order_paid($zh);
							echo 'success'.$zh;
						}
						else if($PaymentResult=='0')//付款失败
						{
							echo 'fail'.$zh;
						}
								                  
	                }
				
			}
			
		}
		exit;
	}
    /**
     * 响应操作
	 *返回值为：0：失败；1：成功；字符串：正在付款[注意：当该订单状态为'正在付款时，请您务必到我方服务器查看最终的结果，谢谢']
     */
    function respond()
    {
        $payment  = get_payment($_GET['code']);
		
				
		////////////////////////////////////////////////////////////////////////////////////////以下是新代码
		//判断是第几次请求
		if(isset($_POST["ReturnBillNo"]) && !empty($_POST["ReturnBillNo"]))
		{
			//订单号
			$returnBillNo = $_POST["ReturnBillNo"];	
			//币种
			$ReturnCurrency = $_POST["ReturnCurrency"];
			//金额
			$ReturnAmount = $_POST["ReturnAmount"];
			//支付状态
			$ReturnSucceed = $_POST["ReturnSucceed"];//返回码: 1 :表示交易成功 ; 0: 表示交易失败
			//支付结果文字说明
			$ReturnResult = $_POST["ReturnResult"];// success: 表示成功 ;   fail:表示失败
			//加密串
			$ReturnMD5info= $_POST["ReturnMD5info"];
			
			$MD5key = $payment['MD5key'];                         //MD5私钥
	        $md5src = $returnBillNo.$ReturnCurrency.$ReturnAmount.$ReturnSucceed.$MD5key; //校验源字符串
	        $md5sign = strtoupper(md5($md5src));                  //MD5检验结果
			
			
			$zh=get_order_id_by_sn($returnBillNo);//substr($BillNo, 14);
			$zh = intval(trim($zh));
	        /* 验证 */
	        if ($ReturnMD5info== $md5sign)
	        {
				if (check_money($zh, $ReturnAmount/$payment['Rate']))
	                {
	                   if ($Succeed == '1')
						{
							/* 改变订单状态 */
							order_paid($zh, PS_PAYED);
							return '1';
						}
						else if($Succeed=='0')//支付失败
						{
							return '0';
						}
						else
						{
							return '0';
						}
	                }
					else{
						return '0';
					}
	        }else{
	            return '0';
			}
		}
		else if(isset($_REQUEST["BillNo"]) && !empty($_REQUEST["BillNo"]))
		{
			$BillNo = $_REQUEST["BillNo"];     //订单号
	        $Currency = $_REQUEST["Currency"]; //币种
	        $Amount = $_REQUEST["Amount"];     //金额
	        $Succeed = $_REQUEST["Succeed"];   //支付状态
	        $TradeNo = $_REQUEST["TradeNo"];   //支付平台流水号
	        $Result = $_REQUEST["Result"];     //支付结果
	        $MD5info = $_REQUEST["MD5info"];   //取得的MD5校验信息
	        $Remark = $_REQUEST["Remark"];     //备注

			

	        $MD5key = $payment['MD5key'];                         //MD5私钥
	        $md5src = $BillNo.$Currency.$Amount.$Succeed.$MD5key; //校验源字符串
	        $md5sign = strtoupper(md5($md5src));                  //MD5检验结果
			
			
			$zh=get_order_id_by_sn($BillNo);//substr($BillNo, 14);
			$zh = intval(trim($zh));
	        /* 验证 */
	        if ($MD5info== $md5sign)
	        {
				if (check_money($zh, $Amount/$payment['Rate']))
	                {
	                   if ($Succeed == '88')
						{
							/* 改变订单状态 */
							order_paid($zh, PS_PAYED);
							return '1';
						}
						else if($Succeed=='1' || $Succeed=='9' || $Succeed=='19')//正在付款
						{
							/* 改变订单状态 */
							order_paid($zh, PS_PAYING);
							return $Result;
						}
						else
						{
							return '0';
						}
	                }
					else{
						return '0';
					}
	        }else{
	            return '0';
			}
	        
			}
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////以上都是新代码

        
    }

	 
	
}

?>