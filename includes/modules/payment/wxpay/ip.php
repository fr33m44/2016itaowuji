<?php
//获取浏览器ip地址
function real_ip()
{
    static $realip;

    if ($realip !== NULL) {
        return $realip;
    }

    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            foreach ($arr as $ip) {
                $ip = trim($ip);

                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        }
        else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (isset($_SERVER['REMOTE_ADDR'])) {
            $realip = $_SERVER['REMOTE_ADDR'];
        }
        else {
            $realip = '0.0.0.0';
        }
    }
    else if (getenv('HTTP_X_FORWARDED_FOR')) {
        $realip = getenv('HTTP_X_FORWARDED_FOR');
    }
    else if (getenv('HTTP_CLIENT_IP')) {
        $realip = getenv('HTTP_CLIENT_IP');
    }
    else {
        $realip = getenv('REMOTE_ADDR');
    }

    preg_match('/[\\d\\.]{7,15}/', $realip, $onlineip);
    $realip = (!empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0');
    return $realip;
}
//print_r($_SERVER['REMOTE_ADDR']);