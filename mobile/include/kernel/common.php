<?php

// +----------------------------------------------------------------------
// | EcTouch [ 专注移动电商: 商创网络科技 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://ectouch.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: EcTouch Team <zhong@ecmoban.com> (QQ: 2880175560)
// +----------------------------------------------------------------------

/*
  说明：数据过滤函数库
  功能：用来过滤字符串和字符串数组，防止被挂马和sql注入
  参数$data，待过滤的字符串或字符串数组，
  $force为true，忽略get_magic_quotes_gpc
 */
function in($data, $force = false) {
    if (is_string($data)) {
        $data = trim(htmlspecialchars($data)); //防止被挂马，跨站攻击
        if (($force == true) || (!get_magic_quotes_gpc())) {
            $data = addslashes($data); //防止sql注入
        }
        return $data;
    } else if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = in($value, $force);
        }
        return $data;
    } else {
        return $data;
    }
}

//用来还原字符串和字符串数组，把已经转义的字符还原回来
function out($data) {
    if (is_string($data)) {
        return $data = stripslashes($data);
    } else if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = out($value);
        }
        return $data;
    } else {
        return $data;
    }
}

//文本输入
function text_in($str) {
    $str = strip_tags($str, '<br>');
    $str = str_replace(" ", "&nbsp;", $str);
    $str = str_replace("\n", "<br>", $str);
    if (!get_magic_quotes_gpc()) {
        $str = addslashes($str);
    }
    return $str;
}

//文本输出
function text_out($str) {
    $str = str_replace("&nbsp;", " ", $str);
    $str = str_replace("<br>", "\n", $str);
    $str = stripslashes($str);
    return $str;
}

//html代码输入
function html_in($str) {
    $search = array("'<script[^>]*?>.*?</script>'si", // 去掉 javascript
        "'<iframe[^>]*?>.*?</iframe>'si", // 去掉iframe
    );
    $replace = array("",
        "",
    );
    $str = @preg_replace($search, $replace, $str);
    $str = htmlspecialchars($str);
    if (!get_magic_quotes_gpc()) {
        $str = addslashes($str);
    }
    return $str;
}

//html代码输出
function html_out($str) {
    if (function_exists('htmlspecialchars_decode'))
        $str = htmlspecialchars_decode($str);
    else
        $str = html_entity_decode($str);

    $str = stripslashes($str);
    return $str;
}

// 获取客户端IP地址
function get_client_ip($type = 0) {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

// 字符串截取，支持中文和其他编码
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}

// 字节格式化 把字节数格式为 B K M G T 描述的大小
function byte_format($size, $dec = 2) {
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, $dec) . " " . $a[$pos];
}

//随机字母
function order_sn() {
    $year_code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    return $year_code[intval(date('Y')) - 2010] .
            strtoupper(dechex(date('m'))) . date('d') .
            substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
}

//随机数
function getcode($length = 5, $mode = 0) {
    switch ($mode) {
        case '1':
            $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
            break;
        case '2':
            $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ-=[]\',./';
            break;
        case '3':
            $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ<>?:"|{}_+';
            break;
        default:
            $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
    }
    $result = '';
    $l = strlen($str) - 1;
    $num = 0;

    for ($i = 0; $i < $length; $i ++) {
        $num = rand(0, $l);
        $a = $str[$num];
        $result = $result . $a;
    }
    return $result;
}

//模块之间相互调用
function module($module) {
    static $module_obj = array();
    static $config = array();
    if (isset($module_obj[$module])) {
        return $module_obj[$module];
    }
    if (!isset($config['MODULE_PATH'])) {
        $config['MODULE_PATH'] = EcConfig::get('MODULE_PATH');
        $config['MODULE_SUFFIX'] = EcConfig::get('MODULE_SUFFIX');
        $suffix_arr = explode('.', $config['MODULE_SUFFIX'], 2);
        $config['MODULE_CLASS_SUFFIX'] = $suffix_arr[0];
    }
    if (file_exists($config['MODULE_PATH'] . $module . $config['MODULE_SUFFIX'])) {
        require_once($config['MODULE_PATH'] . $module . $config['MODULE_SUFFIX']); //加载模型文件
        $classname = $module . $config['MODULE_CLASS_SUFFIX'];
        if (class_exists($classname)) {
            return $module_obj[$module] = new $classname();
        }
    } else {
        return false;
    }
}

//模型调用函数
if (!function_exists('model')) {

    function model($model) {
        static $model_obj = array();
        static $config = array();
        if (isset($model_obj[$model])) {
            return $model_obj[$model];
        }
        if (!isset($config['MODEL_PATH'])) {
            $config['MODEL_PATH'] = EcConfig::get('MODEL_PATH');
            $config['MODEL_SUFFIX'] = EcConfig::get('MODEL_SUFFIX');
            $suffix_arr = explode('.', $config['MODEL_SUFFIX'], 2);
            $config['MODEL_CLASS_SUFFIX'] = $suffix_arr[0];
        }
        if (file_exists($config['MODEL_PATH'] . $model . $config['MODEL_SUFFIX'])) {
            require_once($config['MODEL_PATH'] . $model . $config['MODEL_SUFFIX']); //加载模型文件
            $classname = $model . $config['MODEL_CLASS_SUFFIX'];
            if (class_exists($classname)) {
                return $model_obj[$model] = new $classname();
            }
        }
        return false;
    }

}

// 检查字符串是否是UTF8编码,是返回true,否则返回false
function is_utf8($string) {
    return preg_match('%^(?:
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);
}

/**
 * 把返回的数据集转换成Tree
 * @access public
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = & $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = & $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = & $refer[$parentId];
                    $parent[$child][] = & $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby = 'asc') {
    if (is_array($list)) {
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ($refer as $key => $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 在数据列表中搜索
 * @access public
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list, $condition) {
    if (is_string($condition))
        parse_str($condition, $condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key => $data) {
        $find = false;
        foreach ($condition as $field => $value) {
            if (isset($data[$field])) {
                if (0 === strpos($value, '/')) {
                    $find = preg_match($value, $data[$field]);
                } elseif ($data[$field] == $value) {
                    $find = true;
                }
            }
        }
        if ($find)
            $resultSet[] = &$list[$key];
    }
    return $resultSet;
}

// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from = 'gbk', $to = 'utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}

//返回两串字符串中相同的部分
function get_same_string($str1, $str2) {
    $finalstr = '';                                                     //最终返回的字符串
    $Fstr = (strlen($str1) <= strlen($str2)) ? $str2 : $str1;       //长字符串为父串
    $Sstr = (strlen($str1) <= strlen($str2)) ? $str1 : $str2;       //短字符串为子串
    $Sstrlen = strlen($Sstr);                                         //子串的长度
    for ($i = 0; $i < $Sstrlen; $i++) {
        $comstr = '';           //重置当前公共的字符串
        $cutstr = substr($Sstr, $i);     //当前截取的字符串
        $cutstrlen = strlen($cutstr); //当前截取的字符串长度
        for ($j = 0; $j < $cutstrlen; $j++) {
            $comstr.=$cutstr[$j];
            if (is_int(strpos($Fstr, $comstr)) && strlen($comstr) > strlen($finalstr)) {
                $finalstr = $comstr;
            }
        }
    }
    return$finalstr;
}

/**
 * 对输出编码
 *
 * @access  public
 * @param   string   $str
 * @return  string
 */
function encode_output($str) {
    return htmlspecialchars($str);
}

/**
 * wap分页函数
 *
 * @access      public
 * @param       int     $num        总记录数
 * @param       int     $perpage    每页记录数
 * @param       int     $curr_page  当前页数
 * @param       string  $mpurl      传入的连接地址
 * @param       string  $pvar       分页变量
 */
function get_wap_pager($num, $perpage, $curr_page, $mpurl,$pvar)
{
    $multipage = '';
    if($num > $perpage) {
        $page = 2;
        $offset = 1;
        $pages = ceil($num / $perpage);
        $all_pages = $pages;
        $tmp_page = $curr_page;
        $setp = strpos($mpurl, '?') === false ? "?" : '&amp;';
        if($curr_page > 1) {
            $multipage .= "<a href=\"$mpurl${setp}${pvar}=".($curr_page-1)."\">上一页</a>";
        }
        $multipage .= $curr_page."/".$pages;
        if(($curr_page++) < $pages) {
            $multipage .= "<a href=\"$mpurl${setp}${pvar}=".$curr_page++."\">下一页</a><br/>";
        }
    }
    return $multipage;
}

// 浏览器友好的变量输出
function dump($var, $exit = false) {
    $output = print_r($var, true);
    $output = "<pre>" . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
    echo $output;
    if ($exit)
        exit();
}

//获取微秒时间，常用于计算程序的运行时间
function utime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

//生成唯一的值
function ec_uniqid() {
    return md5(uniqid(rand(), true));
}

//汉字转拼音
function get_pinyin($srt = '') {
    $py = new Pinyin();
    return $py->output($srt); //输出
}

//获取ip地址的实际地区
function get_ip_info($ip = '') {
    //$obj = new IpArea();
    //return $obj->get($ip);
    $url = "http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
    $data = json_decode(file_get_contents($url),true);
    if($data['code'] === 1){
        //return $data['data'];
        return 'IPv4地址不符合格式';
    }else{
        //return $data['data']['country'].' '.$data['data']['region'].' '.$data['data']['city'].' '.$data['data']['county'].' '.$data['data']['area'].' '.$data['data']['isp'];
        return $data['data']['country'].' '.$data['data']['city'];
    }
}

//加密函数，可用ec_decode()函数解密，$data：待加密的字符串或数组；$key：密钥；$expire 过期时间
function ec_encode($data, $key = '', $expire = 0) {
    $string = serialize($data);
    $ckey_length = 4;
    $key = md5($key);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = substr(md5(microtime()), -$ckey_length);

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = sprintf('%010d', $expire ? $expire + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    return $keyc . str_replace('=', '', base64_encode($result));
}

//ec_encode之后的解密函数，$string待解密的字符串，$key，密钥
function ec_decode($string, $key = '') {
    $ckey_length = 4;
    $key = md5($key);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = substr($string, 0, $ckey_length);

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = base64_decode(substr($string, $ckey_length));
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
        return unserialize(substr($result, 26));
    } else {
        return '';
    }
}

//遍历删除目录和目录下所有文件
function del_dir($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    $handle = opendir($dir);
    while (($file = readdir($handle)) !== false) {
        if ($file != "." && $file != "..") {
            is_dir("$dir/$file") ? del_dir("$dir/$file") : @unlink("$dir/$file");
        }
    }
    if (readdir($handle) == false) {
        closedir($handle);
        @rmdir($dir);
    }
}

//如果json_encode没有定义，则定义json_encode函数，常用于返回ajax数据
if (!function_exists('json_encode')) {

    function format_json_value(&$value) {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } else if (is_int($value)) {
            $value = intval($value);
        } else if (is_float($value)) {
            $value = floatval($value);
        } else if (defined($value) && $value === null) {
            $value = strval(constant($value));
        } else if (is_string($value)) {
            $value = '"' . addslashes($value) . '"';
        }
        return $value;
    }

    function json_encode($data) {
        if (is_object($data)) {
            //对象转换成数组
            $data = get_object_vars($data);
        } else if (!is_array($data)) {
            // 普通格式直接输出
            return format_json_value($data);
        }
        // 判断是否关联数组
        if (empty($data) || is_numeric(implode('', array_keys($data)))) {
            $assoc = false;
        } else {
            $assoc = true;
        }
        // 组装 Json字符串
        $json = $assoc ? '{' : '[';
        foreach ($data as $key => $val) {
            if (!is_null($val)) {
                if ($assoc) {
                    $json .= "\"$key\":" . json_encode($val) . ",";
                } else {
                    $json .= json_encode($val) . ",";
                }
            }
        }
        if (strlen($json) > 1) {// 加上判断 防止空数组
            $json = substr($json, 0, -1);
        }
        $json .= $assoc ? '}' : ']';
        return $json;
    }

}
?>