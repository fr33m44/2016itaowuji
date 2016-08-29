<?php
define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');
require('wxch_lg.php');
$act = trim($_REQUEST['act']);
$wxch_lang['ur_here'] = '系统信息';
if($act == 'info'){
    $is_fun = array();
    if(function_exists(curl_exec)){
        array_push($is_fun, array('name' => 'curl', 'val' => 1));
    }else{
        array_push($is_fun, array('name' => 'curl', 'val' => 0));
    }
    if(function_exists(fsockopen)){
        array_push($is_fun, array('name' => 'fsockopen', 'val' => 1));
    }else{
        array_push($is_fun, array('name' => 'fsockopen', 'val' => 0));
    }
    if(function_exists(ob_gzhandler)){
        array_push($is_fun, array('name' => 'zlib', 'val' => 1));
    }else{
        array_push($is_fun, array('name' => 'zlib', 'val' => 0));
    }
    $ret = $db -> getRow("SELECT * FROM `wxch_ver` WHERE `vid` = 1");
    $ver = $ret['type'] . ' ' . $ret['ver'];
    $new_ver = @file_get_contents($url);
    $new = 0;
    if($new_ver == $ret['ver']){
        $new = 0;
    }
    if($new_ver > $ret['ver']){
        $new = 1;
    }
    $smarty -> assign('new_ver', $new_ver);
    $smarty -> assign('new', $new);
    $smarty -> assign('ver', $ver);
    $smarty -> assign('is_fun', $is_fun);
    $smarty -> assign('wxch_lang', $wxch_lang);
    $smarty -> display('wxch_ver.html');
}
?>