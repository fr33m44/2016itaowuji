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

if (!defined('IN_ECTOUCH')){
    die('Hacking attempt');
}

class commonMod {

    public $model; //数据库模型对象
    public $tpl; //模板对象
    public $config; //全局配置
    static $global; //静态变量，用来实现单例模式
    private $_data = array();

    public function __construct() {
        //实例化数据库模型和模板
        if (!isset(self::$global['config'])) {
            global $config;
            //@ini_set('session.save_path', realpath('../data/session_path').'/'); //兼容windows
            session_start(); //开启session
            //@ini_set('session.save_path', dirname ( __FILE__ ) . '/../data/session_path/');
            $this->config = self::$global['config'] = $config; //配置
            $this->model = self::$global['model'] = new EcModel($this->config); //实例化数据库模型类
            $this->tpl = self::$global['tpl'] = new EcTemplate($this->config); //实例化模板类
        } else {
            $this->config = self::$global['config']; //配置
            $this->model = self::$global['model']; //数据库模型对象
            $this->tpl = self::$global['tpl']; //模板类对象
        }
    }

    //模板变量解析
    protected function assign($name, $value) {
        return $this->tpl->assign($name, $value);
    }

    //模板输出
    protected function display($tpl = '') {
        $this->tpl->assign($this->_data);
        $tpl = empty($tpl) ? $_GET['_module'] . '_' .$_GET['_action'] : $tpl;
        return $this->tpl->display($tpl);
    }

    //设置表名
    protected function table($table, $ignore_prefix = false) {
        return $this->model->table($table, $ignore_prefix);
    }

    //直接跳转
    protected function redirect($url, $code = 301) {
        header('location:' . $url, false, $code);
        exit;
    }

    //操作成功之后跳转,默认三秒钟跳转
    protected function success($msg, $url = NULL, $waitSecond = 2) {
        if ($url == NULL) $url = __URL__;
        $this->assign('message', $msg);
        $this->assign('url', $url);
        $this->assign('waitSecond', $waitSecond * 1000);
        $this->display('success');
        exit;
    }

    //出错之后跳转，后退到前一页
    protected function error($msg) {
        header("Content-type: text/html; charset=utf-8");
        $msg = "alert('$msg');";
        echo "<script>$msg history.go(-1);</script>";
        exit;
    }

    //出错之后返回json数据
    protected function jserror($msg) {
        echo json_encode(array("msg" => $msg, "result" => '0'));
        exit;
    }

    //成功之后返回json
    protected function jssuccess($msg, $url = 'back') {
        echo json_encode(array("msg" => $msg, "url" => $url, "result" => '1'));
        exit;
    }

    //判断是否是post提交
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    //page 404
    protected function get404() {
        header("HTTP/1.0 404 Not Found");
        $this->display('404');
        exit;
    }

    public function __get($name) {
        return isset($this->_data[$name]) ? $this->_data[$name] : NULL;
    }

    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }

}
