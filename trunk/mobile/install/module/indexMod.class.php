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

if (!defined('IN_ECTOUCH')) {
    die('Hacking attempt');
}

class indexMod extends commonMod {

    public function __construct() {
        parent::__construct();
        $this->lockFile = ROOT_PATH .'data/install.lock';
        if (file_exists($this->lockFile)) {
            $this->redirect('../');
        }
    }

    //安装首页
    public function index() {
        $this->redirect(__APP__.'?a=test');
        //$this->display('index');
    }

    //运行环境测试
    public function test() {
        if (phpversion() < 5) {
            die('本系统需要PHP5+MYSQL >=4.1环境，当前PHP版本为：' . phpversion());
        }
        $this->phpv = @phpversion();
        $this->os = @PHP_OS;
        $this->os = @php_uname();
        $tmp = function_exists('gd_info') ? gd_info() : array();
        $this->server = $_SERVER["SERVER_SOFTWARE"];
        $this->host = (empty($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_HOST"] : $_SERVER["SERVER_ADDR"]);
        $this->name = $_SERVER["SERVER_NAME"];
        $this->max_execution_time = ini_get('max_execution_time');
        $this->allow_reference = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $this->allow_url_fopen = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $this->safe_mode = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');

        $err = 0;
        if (empty($tmp['GD Version'])) {
            $this->gd = '<font color=red>[×]Off</font>';
            $err++;
        } else {
            $this->gd = '<font color=green>[√]On</font> ' . $tmp['GD Version'];
        }
        if (function_exists('mysql_connect')) {
            $this->mysql = '<font color=green>[√]On</font>';
        } else {
            $this->mysql = '<font color=red>[×]Off</font>';
            $err++;
        }
        if (ini_get('file_uploads')) {
            $this->uploadSize = '<font color=green>[√]On</font> 文件限制:' . ini_get('upload_max_filesize');
        } else {
            $this->uploadSize = '禁止上传';
        }
        if (function_exists('session_start')) {
            $this->session = '<font color=green>[√]On</font>';
        } else {
            $this->session = '<font color=red>[×]Off</font>';
            $err++;
        }
        $this->err = $err;
        $dir_list = array(
            '/',
            'data',
            'data/config.php',
        );
        $this->dir_list = $dir_list;
        $this->display('test');
    }
    
    //安装
    public function install() {
        $tmp_a = str_replace('/install', '', __ROOT__);
        $tmp_b = strrpos($tmp_a, '/');
        $db_config = array(
            'DB_HOST' => $this->config['DB_HOST'],
            'DB_PORT' => $this->config['DB_PORT'],
            'DB_NAME' => $this->config['DB_NAME'],
            'DB_USER' => $this->config['DB_USER'],
            'DB_PWD' => $this->config['DB_PWD'],
            'DB_PREFIX' => $this->config['DB_PREFIX'],
            'site_url' => 'http://'.$_SERVER['HTTP_HOST'].substr($tmp_a, 0, $tmp_b + 1) //电脑版URL
        );

        if (!$this->create_db($db_config)) {
            $this->assign('error', '数据库创建失败，请检测本帐号是否有权限！');
            $this->display('error');
            exit;
        }

        $DB_PREFIX = $db_config['DB_PREFIX'];
        if (empty($DB_PREFIX)) {
            $DB_PREFIX = 'ecs_';
        }
		
        //处理安装SQL的字段兼容
        if(!$this->get_column($db_config, $DB_PREFIX.'users', 'aite_id')){
          $growing = "ALTER TABLE `ecs_users` ADD COLUMN `aite_id` VARCHAR(40) NOT NULL COMMENT '第三方登陆标识';";
          $this->update_install_sql($growing);
        }
        if(!$this->get_column($db_config, $DB_PREFIX.'goods', 'sales_count')){
          $growing = "ALTER TABLE `ecs_goods` ADD COLUMN `sales_count` int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `click_count`;";
          $this->update_install_sql($growing);
        }
        if(!$this->get_column($db_config, $DB_PREFIX.'brand', 'brand_banner')){
          $growing = "ALTER TABLE `ecs_brand` ADD COLUMN `brand_banner` VARCHAR(80) NOT NULL COMMENT '商品品牌banner';";
          $this->update_install_sql($growing);
        }
        if(!$this->get_column($db_config, $DB_PREFIX.'category', 'cat_ico')){
          $growing = "ALTER TABLE `ecs_category` ADD COLUMN `cat_ico` varchar(255) NOT NULL;";
          $this->update_install_sql($growing);
        }
        if(!$this->get_column($db_config, $DB_PREFIX.'users', 'wxch_bd')){
          $growing = "ALTER TABLE `ecs_users` ADD COLUMN `wxch_bd` CHAR(2) NOT NULL AFTER `passwd_answer`;";
          $this->update_install_sql($growing);
        }
        if(!$this->get_column($db_config, $DB_PREFIX.'users', 'wxid')){
          $growing = "ALTER TABLE `ecs_users` ADD COLUMN `wxid` CHAR(28) NOT NULL AFTER `passwd_answer`;";
          $this->update_install_sql($growing);
        }

        $sql_data = Install::mysql(ROOT_PATH . 'install/install.sql', 'ecs_', $DB_PREFIX);
        if (!$this->run_sql($db_config, $sql_data)) {
            $this->assign('error', '基础数据导入失败，请检查后手动删除数据库重新安装！');
            $this->display('error');
        }

        if ($this->save_config($db_config)) {
            @fopen($this->lockFile, 'w');
            $this->display('success');
        } else {
            $this->assign('error', '配置文件写入失败，请检测config.php是否有写入权限！');
            $this->display('error');
        }
    }
    
    //创建数据库
    private function create_db($data) {
        $model = new EcModel($data);
        $sql = "CREATE DATABASE IF NOT EXISTS `" . $data['DB_NAME'] . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
        return $model->query($sql);
    }
	
	//获取字段
	private function get_column($data, $_table = '', $_column = ''){
        $model = new EcModel($data);
		$sql = "describe `" . $_table . "` `" . $_column . "`";
		$resource = $model->query($sql);
		$result = mysql_fetch_array($resource);
		if(is_array($result)){
			return true;
		}else{
			return false;
		}
	}
	
	//更新安装sql文件
	private function update_install_sql($growing = ''){
		$fp = fopen(ROOT_PATH . 'install/install.sql', "a");
		flock($fp, LOCK_EX);
		fwrite($fp, "\n\r".$growing);
		flock($fp, LOCK_UN);
		fclose($fp);
	}

    //执行sql文件
    private function run_sql($data, $sql_array = array()) {
        $model = new EcModel($data);
        foreach ($sql_array as $sql) {
            if (!@$model->db->query($sql)) {
                return false;
            }
        }
        return true;
    }

    //保存配置
    private function save_config($array) {
        if (empty($array) || !is_array($array)) {
            return false;
        }
        $config_array = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $config_array["config['" . $key . "']['" . $k . "']"] = $v;
                }
            } else {
                $config_array["config['" . $key . "']"] = $value;
            }
        }
        $config_file = ROOT_PATH . 'data/config.php';
        $config = file_get_contents($config_file); //读取配置
        foreach ($config_array as $name => $value) {
            $name = str_replace(array("'", '"', '[', '*'), array("\\'", '\"', '\[', '\*'), $name); //转义特殊字符，再传给正则替换
            if (is_string($value) && !in_array($value, array('true', 'false', '3306'))) {
                if (!is_numeric($value)) {
                    $value = "'" . $value . "'"; //如果是字符串，加上单引号
                }
            }
            $config = preg_replace("/(\\$" . $name . ")\s*=\s*(.*?);/iU", "$1={$value};", $config); //查找替换
        }
        // 写入配置
        if (!@file_put_contents($config_file, $config)) {
            return false;
        }
        return true;
    }

}
