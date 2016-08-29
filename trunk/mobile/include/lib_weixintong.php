<?php
 $wxid = isset($_GET['wxid']) ? $_GET['wxid'] : '';
 if(!empty($wxid) && strlen($wxid) == 28){ 
 $step = $db->getOne("SELECT `setp` FROM `wxch_user` WHERE `wxid` = '$wxid'"); 
 $wxch_bd = ($step == 3) ? 'ok':'no'; 
 $query_sql = "SELECT `user_name` FROM  ".$ecs->table('users')." WHERE `wxch_bd` = '$wxch_bd' AND `wxid` = '$wxid'";
 $username = $db->getOne($query_sql);
 if(empty($username)) {
 $query_sql = "SELECT `user_name` FROM  ".$ecs->table('users')." WHERE `wxid` = '$wxid'"; 
 $username = $db->getOne($query_sql); } 
 $user->set_session($username); 
 $user->set_cookie($username); 
 update_user_info(); 
 }
 ?> 