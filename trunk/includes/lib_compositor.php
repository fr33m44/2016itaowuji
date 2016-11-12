<?php

/**
 * iTaoWuJi 支付插件排序文件
 * 开发者：fr33m4n(微信)
 * ----------------------------------------------------------------------------
 * $Id: lib_compositor.php 2009-07-24 09:31:42Z $
 */

if(isset($modules))
{

    /* 将财付通提升至第二个显示 */
    foreach ($modules as $k =>$v)
    {
        if($v['pay_code'] == 'tenpay')
        {
            $tenpay = $modules[$k];
            unset($modules[$k]);
            array_unshift($modules, $tenpay);
        }
    }
    /* 将快钱直连银行显示在快钱之后 */
    foreach ($modules as $k =>$v)
    {
        if(strpos($v['pay_code'], 'kuaiqian')!== false)
        {
            $tenpay = $modules[$k];
            unset($modules[$k]);
            array_unshift($modules, $tenpay);
        }
    }

    /* 将快钱提升至第一个显示 */
    foreach ($modules as $k =>$v)
    {
        if($v['pay_code'] == 'kuaiqian')
        {
            $tenpay = $modules[$k];
            unset($modules[$k]);
            array_unshift($modules, $tenpay);
        }
    }

}

?>