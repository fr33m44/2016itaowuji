<?php

/**
 * ECSHOP ֧�������
 * ============================================================================
 * * ��Ȩ���� 2005-2012 �Ϻ���������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.mb5.com.cn
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�
 * ʹ�ã�������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * $Author: mb5.com.cn $
 * $Id: alipay.php 17217 2011-01-19 06:29:08Z mb5.com.cn $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'lang/' .$GLOBALS['_CFG']['lang']. '/payment/alipay1.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/* ģ��Ļ�����Ϣ */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* ���� */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* ������Ӧ�������� */
    $modules[$i]['desc']    = 'alipay_desc';

    /* �Ƿ�֧�ֻ������� */
    $modules[$i]['is_cod']  = '0';

    /* �Ƿ�֧������֧�� */
    $modules[$i]['is_online']  = '1';

    /* ���� */
    $modules[$i]['author']  = 'tianxin100';

    /* ��ַ */
    $modules[$i]['website'] = 'http://www.alipay.com';

    /* �汾�� */
    $modules[$i]['version'] = '1.0.2';

    /* ������Ϣ */
    $modules[$i]['config']  = array(
        array('name' => 'alipay_account',           'type' => 'text',   'value' => ''),
        array('name' => 'alipay_key',               'type' => 'text',   'value' => ''),
        array('name' => 'alipay_partner',           'type' => 'text',   'value' => ''),
//        array('name' => 'alipay_real_method',       'type' => 'select', 'value' => '0'),
//        array('name' => 'alipay_virtual_method',    'type' => 'select', 'value' => '0'),
//        array('name' => 'is_instant',               'type' => 'select', 'value' => '0')
        array('name' => 'alipay_pay_method',        'type' => 'select', 'value' => '')
    );

    return;
}

/**
 * ��
 */
class alipay1
{

    /**
     * ���캯��
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function alipay()
    {
    }

    function __construct()
    {
        $this->alipay();
    }

    /**
     * ����֧������
     * @param   array   $order      ������Ϣ
     * @param   array   $payment    ֧����ʽ��Ϣ
     */
    function get_code($order, $payment)
    {
        if (!defined('EC_CHARSET'))
        {
            $charset = 'utf-8';
        }
        else
        {
            $charset = EC_CHARSET;
        }
//        if (empty($payment['is_instant']))
//        {
//            /* δ��ͨ��ʱ���� */
//            $service = 'trade_create_by_buyer';
//        }
//        else
//        {
//            if (!empty($order['order_id']))
//            {
//                /* ��鶩���Ƿ�ȫ��Ϊ������Ʒ */
//                $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('order_goods').
//                        " WHERE is_real=1 AND order_id='$order[order_id]'";
//
//                if ($GLOBALS['db']->getOne($sql) > 0)
//                {
//                    /* �����д���ʵ����Ʒ */
//                    $service =  (!empty($payment['alipay_real_method']) && $payment['alipay_real_method'] == 1) ?
//                        'create_direct_pay_by_user' : 'trade_create_by_buyer';
//                }
//                else
//                {
//                    /* ������ȫ��Ϊ������Ʒ */
//                    $service = (!empty($payment['alipay_virtual_method']) && $payment['alipay_virtual_method'] == 1) ?
//                        'create_direct_pay_by_user' : 'create_digital_goods_trade_p';
//                }
//            }
//            else
//            {
//                /* �Ƕ�����ʽ������������Ʒ���� */
//                $service = (!empty($payment['alipay_virtual_method']) && $payment['alipay_virtual_method'] == 1) ?
//                    'create_direct_pay_by_user' : 'create_digital_goods_trade_p';
//            }
//        }

        $real_method = $payment['alipay_pay_method'];

        switch ($real_method){
            case '0':
                $service = 'trade_create_by_buyer';
                break;
            case '1':
                $service = 'create_partner_trade_by_buyer';
                break;
            case '2':
                $service = 'create_direct_pay_by_user';
                break;
        }

        $extend_param = 'isv^sh22';

        $parameter = array(
			//��������ɾ��������ģ�� http://www.zuimoban.com  �ṩ
         //   'extend_param'      => $extend_param,
          //  'service'           => $service,
         //   'partner'           => $payment['alipay_partner'],
            //'partner'           => ALIPAY_ID,
            '_input_charset'    => $charset,
         //   'notify_url'        => return_url(basename(__FILE__, '.php')),
        //    'return_url'        => return_url(basename(__FILE__, '.php')),
            /* ҵ����� */
      //      'subject'           => $order['order_sn'],
        //    'out_trade_no'      => $order['order_sn'] . $order['log_id'],
       //     'price'             => $order['order_amount'],
      //      'quantity'          => 1,
       //     'payment_type'      => 1,
            /* �������� */
      //      'logistics_type'    => 'EXPRESS',
   //         'logistics_fee'     => 0,
      //      'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
            /* ����˫����Ϣ */
     //       'seller_email'      => $payment['alipay_account']

              'optEmail'            => $payment['alipay_account'],
			  'payAmount'           => $order['order_amount'],
			  'title'               => $GLOBALS['_LANG']['order_number'].':' . $order['order_sn'] ,
			   'memo'              =>  $_SESSION['email'] .$order['postscript'] ,
               'smsNo'             => $payment['alipay_partner'],
				
        );

        ksort($parameter);
        reset($parameter);

        $param = '';
        $sign  = '';

        foreach ($parameter AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }

        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $payment['alipay_key'];
        //$sign  = substr($sign, 0, -1). ALIPAY_AUTH;

      // $button = '<div style="text-align:center"><input type="button" onclick="window.open(\'https://shenghuo.alipay.com/send/payment/fill.htm?'.$param.'\')" value="' .$GLOBALS['_LANG']['pay_button']. '" /></div>';
// QQ:88480702 http://www.zuimoban.com

	$button = '<div style="text-align:center">
	<form id="payment" accept-charset="GBK" action="https://shenghuo.alipay.com/send/payment/fill.htm" method="post">
		<input name="optEmail" type="hidden" value="' .$parameter['optEmail']. '" />
		<input name="payAmount" type="hidden" value="' .$parameter['payAmount']. '" />
		<input name="title" type="hidden" value="' .$parameter['title']. '" />
		<input name="smsNo" type="hidden" value="' .$parameter['smsNo']. '" />
	   </form>
		<input type="button" onclick="javascript:document.charset=\'GBK\';document.getElementById(\'payment\').submit()" value="' .$GLOBALS['_LANG']['pay_button']. '" /></div>';
	// www.zuimoban.com 

        return $button;
    }

    /**
     * ��Ӧ����
     */
    function respond()
    {
        if (!empty($_POST))
        {
            foreach($_POST as $key => $data)
            {
                $_GET[$key] = $data;
            }
        }
        $payment  = get_payment($_GET['code']);
        $seller_email = rawurldecode($_GET['seller_email']);
        $order_sn = str_replace($_GET['subject'], '', $_GET['out_trade_no']);
        $order_sn = trim($order_sn);

        /* ���֧���Ľ���Ƿ���� */
        if (!check_money($order_sn, $_GET['total_fee']))
        {
            return false;
        }

        /* �������ǩ���Ƿ���ȷ */
        ksort($_GET);
        reset($_GET);

        $sign = '';
        foreach ($_GET AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['alipay_key'];
        //$sign = substr($sign, 0, -1) . ALIPAY_AUTH;
        if (md5($sign) != $_GET['sign'])
        {
            return false;
        }

        if ($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS')
        {
            /* �ı䶩��״̬ */
            order_paid($order_sn, 2);

            return true;
        }
        elseif ($_GET['trade_status'] == 'TRADE_FINISHED')
        {
            /* �ı䶩��״̬ */
            order_paid($order_sn);

            return true;
        }
        elseif ($_GET['trade_status'] == 'TRADE_SUCCESS')
        {
            /* �ı䶩��״̬ */
            order_paid($order_sn, 2);

            return true;
        }
        else
        {
            return false;
        }
    }
}

?>