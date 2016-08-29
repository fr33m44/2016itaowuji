<?php
define('IN_ECTOUCH', true);
require ('includes/init.php');
$exc = new exchange($ecs->table('touch_payment') , $db, 'pay_code', 'pay_name');

if ($_REQUEST['act'] == 'list') {
    $pay_list = array();
    $sql = "SELECT * FROM " . $ecs->table('touch_payment') . " WHERE enabled = '1' ORDER BY pay_order";
    $res = $db->query($sql);
    while ($row = $db->fetchRow($res)) {
        $pay_list[$row['pay_code']] = $row;
    }
    $modules = read_modules('../include/modules/payment');
    for ($i = 0; $i < count($modules); $i++) {
        $code = $modules[$i]['code'];
        $modules[$i]['pay_code'] = $modules[$i]['code'];
        if (isset($pay_list[$code])) {
            $modules[$i]['name'] = $pay_list[$code]['pay_name'];
            $modules[$i]['pay_fee'] = $pay_list[$code]['pay_fee'];
            $modules[$i]['is_cod'] = $pay_list[$code]['is_cod'];
            $modules[$i]['desc'] = $pay_list[$code]['pay_desc'];
            $modules[$i]['pay_order'] = $pay_list[$code]['pay_order'];
            $modules[$i]['install'] = '1';
        } else {
            $modules[$i]['name'] = $_LANG[$modules[$i]['code']];
            if (!isset($modules[$i]['pay_fee'])) {
                $modules[$i]['pay_fee'] = 0;
            }
            $modules[$i]['desc'] = $_LANG[$modules[$i]['desc']];
            $modules[$i]['install'] = '0';
        }
        if ($modules[$i]['pay_code'] == 'tenpayc2c') {
            $tenpayc2c = $modules[$i];
        }
    }
    include_once (ROOT_PATH . 'include/lib_compositor.php');
    assign_query_info();
    $smarty->assign('ur_here', $_LANG['02_payment_list']);
    $smarty->assign('modules', $modules);
    $smarty->assign('tenpayc2c', $tenpayc2c);
    $smarty->display('payment_list.htm');
} elseif ($_REQUEST['act'] == 'install') {
    admin_priv('payment');
    $set_modules = true;
    include_once (ROOT_PATH . 'include/modules/payment/' . $_REQUEST['code'] . '.php');
    $data = $modules[0];
    if (isset($data['pay_fee'])) {
        $data['pay_fee'] = trim($data['pay_fee']);
    } else {
        $data['pay_fee'] = 0;
    }
    $pay['pay_code'] = $data['code'];
    $pay['pay_name'] = $_LANG[$data['code']];
    $pay['pay_desc'] = $_LANG[$data['desc']];
    $pay['is_cod'] = $data['is_cod'];
    $pay['pay_fee'] = $data['pay_fee'];
    $pay['is_online'] = $data['is_online'];
    $pay['pay_config'] = array();
    foreach ($data['config'] AS $key => $value) {
        $config_desc = (isset($_LANG[$value['name'] . '_desc'])) ? $_LANG[$value['name'] . '_desc'] : '';
        $pay['pay_config'][$key] = $value + array(
            'label' => $_LANG[$value['name']],
            'value' => $value['value'],
            'desc' => $config_desc
        );
        if ($pay['pay_config'][$key]['type'] == 'select' || $pay['pay_config'][$key]['type'] == 'radiobox') {
            $pay['pay_config'][$key]['range'] = $_LANG[$pay['pay_config'][$key]['name'] . '_range'];
        }
    }
    assign_query_info();
    $smarty->assign('action_link', array(
        'text' => $_LANG['02_payment_list'],
        'href' => 'payment.php?act=list'
    ));
    $smarty->assign('pay', $pay);
    $smarty->display('payment_edit.htm');
} elseif ($_REQUEST['act'] == 'get_config') {
    check_authz_json('payment');
    $code = $_REQUEST['code'];
    $set_modules = true;
    include_once (ROOT_PATH . 'include/modules/payment/' . $code . '.php');
    $data = $modules[0]['config'];
    $config = '<table>';
    $range = '';
    foreach ($data AS $key => $value) {
        $config.= "<tr><td width=80><span class='label'>";
        $config.= $_LANG[$data[$key]['name']];
        $config.= "</span></td>";
        if ($data[$key]['type'] == 'text') {
            if ($data[$key]['name'] == 'alipay_account') {
                $config.= "<td><input name='cfg_value[]' type='text' value='" . $data[$key]['value'] . "' /><a href=\"https://www.alipay.com/himalayas/practicality.htm\" target=\"_blank\">" . $_LANG['alipay_look'] . "</a></td>";
            } elseif ($data[$key]['name'] == 'tenpay_account') {
                $config.= "<td><input name='cfg_value[]' type='text' value='" . $data[$key]['value'] . "' />" . $_LANG['penpay_register'] . "</td>";
            } else {
                $config.= "<td><input name='cfg_value[]' type='text' value='" . $data[$key]['value'] . "' /></td>";
            }
        } elseif ($data[$key]['type'] == 'select') {
            $range = $_LANG[$data[$key]['name'] . '_range'];
            $config.= "<td><select name='cfg_value[]'>";
            foreach ($range AS $index => $val) {
                $config.= "<option value='$index'>" . $range[$index] . "</option>";
            }
            $config.= "</select></td>";
        }
        $config.= "</tr>";
        $config.= "<input name='cfg_name[]' type='hidden' value='" . $data[$key]['name'] . "' />";
        $config.= "<input name='cfg_type[]' type='hidden' value='" . $data[$key]['type'] . "' />";
        $config.= "<input name='cfg_lang[]' type='hidden' value='" . $data[$key]['lang'] . "' />";
    }
    $config.= '</table>';
    make_json_result($config);
} elseif ($_REQUEST['act'] == 'edit') {
    admin_priv('payment');
    if (isset($_REQUEST['code'])) {
        $_REQUEST['code'] = trim($_REQUEST['code']);
    } else {
        die('invalid parameter');
    }
    $sql = "SELECT * FROM " . $ecs->table('touch_payment') . " WHERE pay_code = '$_REQUEST[code]' AND enabled = '1'";
    $pay = $db->getRow($sql);
    if (empty($pay)) {
        $links[] = array(
            'text' => $_LANG['back_list'],
            'href' => 'payment.php?act=list'
        );
        sys_msg($_LANG['payment_not_available'], 0, $links);
    }
    $set_modules = true;
    include_once (ROOT_PATH . 'include/modules/payment/' . $_REQUEST['code'] . '.php');
    $data = $modules[0];
    if (is_string($pay['pay_config'])) {
        $store = unserialize($pay['pay_config']);
        $code_list = array();
        foreach ($store as $key => $value) {
            $code_list[$value['name']] = $value['value'];
        }
        $pay['pay_config'] = array();
        foreach ($data['config'] as $key => $value) {
            $pay['pay_config'][$key]['desc'] = (isset($_LANG[$value['name'] . '_desc'])) ? $_LANG[$value['name'] . '_desc'] : '';
            $pay['pay_config'][$key]['label'] = $_LANG[$value['name']];
            $pay['pay_config'][$key]['name'] = $value['name'];
            $pay['pay_config'][$key]['type'] = $value['type'];
            if (isset($code_list[$value['name']])) {
                $pay['pay_config'][$key]['value'] = $code_list[$value['name']];
            } else {
                $pay['pay_config'][$key]['value'] = $value['value'];
            }
            if ($pay['pay_config'][$key]['type'] == 'select' || $pay['pay_config'][$key]['type'] == 'radiobox') {
                $pay['pay_config'][$key]['range'] = $_LANG[$pay['pay_config'][$key]['name'] . '_range'];
            }
        }
    }
    if (!isset($pay['pay_fee'])) {
        if (isset($data['pay_fee'])) {
            $pay['pay_fee'] = $data['pay_fee'];
        } else {
            $pay['pay_fee'] = 0;
        }
    }
    assign_query_info();
    $smarty->assign('action_link', array(
        'text' => $_LANG['02_payment_list'],
        'href' => 'payment.php?act=list'
    ));
    $smarty->assign('ur_here', $_LANG['edit'] . $_LANG['payment']);
    $smarty->assign('pay', $pay);
    $smarty->display('payment_edit.htm');
} elseif (isset($_POST['Submit'])) {
    admin_priv('payment');
    if (empty($_POST['pay_name'])) {
        sys_msg($_LANG['payment_name'] . $_LANG['empty']);
    }
    $sql = "SELECT COUNT(*) FROM " . $ecs->table('touch_payment') . " WHERE pay_name = '$_POST[pay_name]' AND pay_code <> '$_POST[pay_code]'";
    if ($db->GetOne($sql) > 0) {
        sys_msg($_LANG['payment_name'] . $_LANG['repeat'], 1);
    }
    $pay_config = array();
    if (isset($_POST['cfg_value']) && is_array($_POST['cfg_value'])) {
        for ($i = 0; $i < count($_POST['cfg_value']); $i++) {
            $pay_config[] = array(
                'name' => trim($_POST['cfg_name'][$i]) ,
                'type' => trim($_POST['cfg_type'][$i]) ,
                'value' => trim($_POST['cfg_value'][$i])
            );
        }
    }
    $pay_config = serialize($pay_config);
    $pay_fee = empty($_POST['pay_fee']) ? 0 : $_POST['pay_fee'];
    $link[] = array(
        'text' => $_LANG['back_list'],
        'href' => 'payment.php?act=list'
    );
    if ($_POST['pay_id']) {
        $sql = "UPDATE " . $ecs->table('touch_payment') . "SET pay_name = '$_POST[pay_name]'," . "    pay_desc = '$_POST[pay_desc]'," . "    pay_config = '$pay_config', " . "    pay_fee    =  '$pay_fee' " . "WHERE pay_code = '$_POST[pay_code]' LIMIT 1";
        $db->query($sql);
        admin_log($_POST['pay_name'], 'edit', 'payment');
        sys_msg($_LANG['edit_ok'], 0, $link);
    } else {
        $sql = "SELECT COUNT(*) FROM " . $ecs->table('touch_payment') . " WHERE pay_code = '$_REQUEST[pay_code]'";
        if ($db->GetOne($sql) > 0) {
            $sql = "UPDATE " . $ecs->table('touch_payment') . "SET pay_name = '$_POST[pay_name]'," . "    pay_desc = '$_POST[pay_desc]'," . "    pay_config = '$pay_config'," . "    pay_fee    =  '$pay_fee', " . "    enabled = '1' " . "WHERE pay_code = '$_POST[pay_code]' LIMIT 1";
            $db->query($sql);
        } else {
            $sql = "INSERT INTO " . $ecs->table('touch_payment') . " (pay_code, pay_name, pay_desc, pay_config, is_cod, pay_fee, enabled, is_online)" . "VALUES ('$_POST[pay_code]', '$_POST[pay_name]', '$_POST[pay_desc]', '$pay_config', '$_POST[is_cod]', '$pay_fee', 1, '$_POST[is_online]')";
            $db->query($sql);
        }
        admin_log($_POST['pay_name'], 'install', 'payment');
        sys_msg($_LANG['install_ok'], 0, $link);
    }
} elseif ($_REQUEST['act'] == 'uninstall') {
    admin_priv('payment');
    $sql = "UPDATE " . $ecs->table('touch_payment') . "SET enabled = '0' " . "WHERE pay_code = '$_REQUEST[code]' LIMIT 1";
    $db->query($sql);
    admin_log($_REQUEST['code'], 'uninstall', 'payment');
    $link[] = array(
        'text' => $_LANG['back_list'],
        'href' => 'payment.php?act=list'
    );
    sys_msg($_LANG['uninstall_ok'], 0, $link);
} elseif ($_REQUEST['act'] == 'edit_name') {
    check_authz_json('payment');
    $code = json_str_iconv(trim($_POST['id']));
    $name = json_str_iconv(trim($_POST['val']));
    if (empty($name)) {
        make_json_error($_LANG['name_is_null']);
    }
    if (!$exc->is_only('pay_name', $name, $code)) {
        make_json_error($_LANG['name_exists']);
    }
    $exc->edit("pay_name = '$name'", $code);
    make_json_result(stripcslashes($name));
} elseif ($_REQUEST['act'] == 'edit_desc') {
    check_authz_json('payment');
    $code = json_str_iconv(trim($_POST['id']));
    $desc = json_str_iconv(trim($_POST['val']));
    $exc->edit("pay_desc = '$desc'", $code);
    make_json_result(stripcslashes($desc));
} elseif ($_REQUEST['act'] == 'edit_order') {
    check_authz_json('payment');
    $code = json_str_iconv(trim($_POST['id']));
    $order = intval($_POST['val']);
    $exc->edit("pay_order = '$order'", $code);
    make_json_result(stripcslashes($order));
} elseif ($_REQUEST['act'] == 'edit_pay_fee') {
    check_authz_json('payment');
    $code = json_str_iconv(trim($_POST['id']));
    $pay_fee = json_str_iconv(trim($_POST['val']));
    if (empty($pay_fee)) {
        $pay_fee = 0;
    } else {
        $pay_fee = make_semiangle($pay_fee);
        if (strpos($pay_fee, '%') === false) {
            $pay_fee = floatval($pay_fee);
        } else {
            $pay_fee = floatval($pay_fee) . '%';
        }
    }
    $exc->edit("pay_fee = '$pay_fee'", $code);
    make_json_result(stripcslashes($pay_fee));
} ?>
 
