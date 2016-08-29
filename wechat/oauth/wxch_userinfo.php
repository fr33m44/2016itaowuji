<?php
if($_GET['code']){
    $wxch_config = $db -> getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
    $appid = $wxch_config['appid'];
    $appsecret = $wxch_config['appsecret'];
    $code = !empty($_GET['code']) ? $_GET['code'] : '';
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $code . '&grant_type=authorization_code';
    $ret_oa_json = curl_get_contents($url);
    $ret_oa = json_decode($ret_oa_json);
    if(strlen($ret_oa -> openid) == 28){
        $thistable = $ecs -> prefix . 'users';
        $openid = $ret_oa -> openid;
        $w_openid = $db -> getOne("SELECT `wxid` FROM `wxch_user` WHERE `wxid` = '$openid'");
        $time = time();
        if(empty($w_openid)){
            if(!empty($openid)){
                $db -> query("INSERT INTO `wxch_user` ( `wxid` , `dateline`) VALUES ('$openid','$time') ");
            }
        }
        $wxch_user_name_sql = "SELECT `user_name` FROM `$thistable` WHERE `wxch_bd`='ok' AND `wxid` = '$openid'";
        $wxch_user_name = $db -> getOne($wxch_user_name_sql);
        $wxch_user_wxid_sql = "SELECT `wxid` FROM `$thistable` WHERE `wxid`=`user_name` AND `wxid` = '$openid'";
        $wxch_user_wxid = $db -> getOne($wxch_user_wxid_sql);
        if(empty($wxch_user_wxid)){
            if(empty($wxch_user_name)){
                $wxch_nobd_wxid_sql = "SELECT `wxid` FROM `$thistable` WHERE `wxch_bd`='no' AND `wxid` = '$openid'";
                $wxch_nobd_wxid = $db -> getOne($wxch_nobd_wxid_sql);
                if(empty($wxch_nobd_wxid)){
                    $wxch_user_sql = "INSERT INTO `$thistable` ( `user_name`,`password`,`wxid`,`user_rank`,`wxch_bd`) VALUES ('$openid','$ec_pwd','$openid','99','no')";
                    $db -> query($wxch_user_sql);
                    $ecs_user_id = $db -> insert_id();
                    $ecs_user_name = 'wx' . $ecs_user_id;
                    $ecs_update = " UPDATE `$thistable` SET `user_name` = '$ecs_user_name' WHERE `user_id` = '$ecs_user_id'";
                    $db -> query($ecs_update);
                }
            }
            $sns_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $ret_oa -> access_token . '&openid=' . $openid . '&lang=zh_CN';
            $ret_sns_json = curl_get_contents($sns_url);
            $ret_sns = json_decode($ret_sns_json);
            if($openid == $ret_sns -> openid){
                $w_sql = "UPDATE  `wxch_user` SET  `nickname` =  '$ret_sns->nickname',`sex` =  '$ret_sns->sex',`city` =  '$ret_sns->city',`country` = '$ret_sns->country',`headimgurl` =  '$ret_sns->headimgurl' WHERE `wxid` = '$openid';";
                $db -> query($w_sql);
            }
        }
        if(empty($ecs_user_name)){
            $ecs_user_name = $db -> getOne("SELECT `user_name` FROM `$thistable` WHERE `wxid` = '$openid'");
        }
        $is_login = 0;
        if(!empty($ecs_user_name)){
            $user -> set_session($ecs_user_name);
            $user -> set_cookie($ecs_user_name);
            update_user_info();
            recalculate_price();
            $is_login = 1;
        }
        if($is_login == 0){
            $user -> set_session($ecs_user_name);
            $user -> set_cookie($ecs_user_name);
            update_user_info();
            recalculate_price();
        }
        $_SESSION['wxoauth'] = 'true';
    }
}elseif(!$_SESSION['user_id']){
    if (strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger")){
        if($_SESSION['wxoauth'] != 'true'){
            $wxch_config = $db -> getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
            $appid = $wxch_config['appid'];
            $appsecret = $wxch_config['appsecret'];
            $_SESSION['wxoauth'] = 'false';
            $back_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $state = 'wechat';
            $scope = 'snsapi_userinfo';
            $redirect_uri = urlencode($back_url);
            $oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $redirect_uri . '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
            goheader($oauth_url);
        }
    }
}
function curl_get_contents($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
    curl_setopt($ch, CURLOPT_REFERER, _REFERER_);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}
function goheader($oauth_url){
    header('Expires: 0');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cahe, must-revalidate');
    header('Cache-Control: post-chedk=0, pre-check=0', false);
    header('Pragma: no-cache');
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $oauth_url");
    exit;
}
?>