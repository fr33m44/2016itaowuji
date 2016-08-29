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

//缓存类
class EcCache {

    protected $cache = NULL;

    public function __construct($config = array(), $type = 'FileCache') {
        $cacheDriver = 'Ec' . $type;
        require_once(dirname(__FILE__) . '/cache/' . $cacheDriver . '.class.php');
        $this->cache = new $cacheDriver($config);
    }

    //读取缓存
    public function get($key) {
        return $this->cache->get($key);
    }

    //设置缓存
    public function set($key, $value, $expire = 1800) {
        return $this->cache->set($key, $value, $expire);
    }

    //自增1
    public function inc($key, $value = 1) {
        return $this->cache->inc($key, $value);
    }

    //自减1
    public function des($key, $value = 1) {
        return $this->cache->des($key, $value);
    }

    //删除
    public function del($key) {
        return $this->cache->del($key);
    }

    //清空缓存
    public function clear() {
        return $this->cache->clear();
    }

}
