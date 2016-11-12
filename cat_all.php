<?php

/**
 * iTaoWuJi 分类聚合页
 * 开发者：fr33m4n(微信)
 * ----------------------------------------------------------------------------
 * $Id: index.php 15013 2010-03-25 09:31:42Z $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
$pcat_array = get_categories_tree();


foreach ($pcat_array as $key => $pcat_data) {
    $pcat_array[$key]['name'] = $pcat_data['name'];
    if ($pcat_data['cat_id']) {
        foreach ($pcat_data['cat_id'] as $k => $v) {
            $pcat_array[$key]['cat_id'][$k]['name'] = $v['name'];
        }
    }
}

$smarty->assign('page_title', '全部品类 淘五季');    // 页面标题
$smarty->assign('pcat_array', $pcat_array);

$smarty->display("category_all.dwt");
?>