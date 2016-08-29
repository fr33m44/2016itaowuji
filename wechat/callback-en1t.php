<?php
class wechatCallbackapi {
	public function valid($db) {
		$echoStr = $_GET["echostr"];
		if ($this -> checkSignature($db)) {
			echo $echoStr;
		} 
	} 
	public function msgError($error) {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (isset($postStr)) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj -> FromUserName;
			$msgType = $postObj -> MsgType;
			$toUsername = $postObj -> ToUserName;
			$keyword = trim($postObj -> Content);
			$time = time();
			$textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";
			$contentStr = $error;
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
			exit;
		} 
	} 
	public function responseMsg($db, $user, $base_url) {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$debug = 0;
		if ($_GET['debug'] == 1) {
			$debug = 1;
		} 
		if ($_GET['de_base']) {
			$de_base = 1;
		} 
		if (!empty($postStr) or $debug == 1) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj -> FromUserName;
			$msgType = $postObj -> MsgType;
			$toUsername = $postObj -> ToUserName;
			$keyword = trim($postObj -> Content);
			if (empty($keyword)) {
				$keyword = $_GET['keyword'];
			} 
			if (empty($fromUsername)) {
				if ($_GET['wxid']) {
					$fromUsername = $_GET['wxid'];
				} else {
					$fromUsername = 'oIM-ajhetL3OwUfIm2DNgC1pW9Uk';
				} 
			} 
			$textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";
			$time = time();
			$lang = array();
			$setp = $db -> getOne("SELECT `setp` FROM `wxch_user` WHERE `wxid` = '$fromUsername'");
			if ($setp == 2 or $setp == 3 or $setp == 10) {
				$uname = $db -> getOne("SELECT `uname` FROM `wxch_user` WHERE `wxid` = '$fromUsername'");
			} 
			if (empty($uname)) {
				$postfix = '&wxid=' . $fromUsername;
			} else {
				$ret['wxid'] = $db -> getOne("SELECT `wxid` FROM `wxch_user` WHERE `wxid` = '$fromUsername'");
				$postfix = '&wxid=' . $ret['wxid'];
			} 
			$m_ret = $db -> getRow("SELECT * FROM  `wxch_cfg` WHERE `cfg_name` = 'murl'");
			$base_ret = $db -> getRow("SELECT * FROM  `wxch_cfg` WHERE `cfg_name` = 'baseurl'");
			$imgpath_ret = $db -> getRow("SELECT * FROM  `wxch_cfg` WHERE `cfg_name` = 'imgpath'");
			$plustj_ret = $db -> getRow("SELECT * FROM  `wxch_cfg` WHERE `cfg_name` = 'plustj'");
			$cxbd = $db -> getOne("SELECT `cfg_value` FROM `wxch_cfg` WHERE `cfg_name` = 'cxbd'");
			$oauth_state = $db -> getOne("SELECT `cfg_value` FROM `wxch_cfg` WHERE `cfg_name` = 'oauth'");
			$goods_is_ret = $db -> getOne("SELECT `cfg_value` FROM `wxch_cfg` WHERE `cfg_name` = 'goods'");
			$article_url = $db -> getOne("SELECT `cfg_value` FROM `wxch_cfg` WHERE `cfg_name` = 'article'");
			$affiliate_id = $db -> getOne("SELECT `affiliate` FROM `wxch_user` WHERE `wxid` = '$fromUsername'");
			if ($affiliate_id >= 1) {
				$affiliate = '&u=' . $affiliate_id;
			} 
			if ($goods_is_ret == 'false') {
				$goods_is = ' AND is_delete = 0 AND is_on_sale = 1';
			} else {
				$goods_is = '';
			} 
			$plustj = $plustj_ret['cfg_value'];
			$wxch_bd = $db -> getOne("SELECT `cfg_value` FROM `wxch_cfg` WHERE `cfg_name` = 'bd'");
			if (empty($base_ret['cfg_value'])) {
				$m_url = $base_url . $m_ret['cfg_value'];
			} else {
				$m_url = $base_ret['cfg_value'] . $m_ret['cfg_value'];
				$base_url = $base_ret['cfg_value'];
			} 
			if ($de_base) {
				echo $base_url;
			} 
			$img_path = $imgpath_ret['cfg_value'];
			$base_img_path = $base_url;
			if ($img_path == 'local') {
				$img_murl = $db -> getOne("SELECT `cfg_value` FROM `wxch_cfg` WHERE `cfg_name` = 'murl'");
				if (empty($img_murl)) {
					$temp_img_arr = explode('.', $base_ret['cfg_value']);
					$temp_do = array('http://m', 'http://mobile');
					if (in_array($temp_img_arr[0], $temp_do)) {
						$base_img_path = 'http://www.' . $temp_img_arr[1] . '.' . $temp_img_arr[2];
					} 
				} 
			} 
			if (file_exists('config.php')) {
				include('config.php');
			} 
			$oauth_location = $base_url . 'wechat/oauth/wxch_oauths.php?uri=';
			$thistable = $db -> prefix . 'users';
			$ret = $db -> getRow("SELECT `user_id` FROM `$thistable` WHERE `user_name` ='$uname'");
			$user_name=$db -> getOne("SELECT `user_name` FROM `$thistable` WHERE `wxid` ='$fromUsername'");
			if (!empty($ret['user_id'])) {
				$user_id = $ret['user_id'];
			} 
			$ret = $db -> getRow("SELECT `wxid` FROM `wxch_user` WHERE `wxid` = '$fromUsername'");
			if (empty($ret)) {
				if (!empty($fromUsername)) {
					$db -> query("INSERT INTO `wxch_user` (`subscribe`, `wxid` , `dateline`) VALUES ('1','$fromUsername','$time')");
				} 
			} else {
				$db -> query("UPDATE  `wxch_user` SET  `subscribe` =  '1',`dateline` = '$time' WHERE  `wxch_user`.`wxid` = '$fromUsername';");
			} 
			$subscribe = 1;
			if ($msgType == 'text') {
				$db -> query("INSERT INTO `wxch_message` (`wxid`, `message`, `dateline`) VALUES
( '$fromUsername', '$keyword', $time);");
			} 
			$belong = $db -> insert_id();
			$thistable = $db -> prefix . 'users';
			$ec_pwd = $db -> getOne("SELECT `cfg_value` FROM `wxch_cfg` WHERE `cfg_name` = 'userpwd'");
			$ec_pwd = md5($ec_pwd);
			$ret_22 = $db -> getRow("SELECT * FROM `$thistable` WHERE `wxid` = '$fromUsername'");
			if (strlen($ret_22['user_name']) == 28) {
				$sql_del = "UPDATE `$thistable` SET `user_rank` = '99',`wxch_bd`='no' WHERE `wxid` ='$fromUsername'";
				$db -> query($sql_del);
			} 
			if (empty($uname)) {
			//兼容老会员
			$old_sql = "SELECT `user_name` FROM `$thistable` WHERE  `openid` = '$fromUsername'";
			$old_user_name = $db -> getOne($old_sql);
		if(!empty($old_user_name)){
			//老会员的处理流程，自动绑定会员
			$oldtable=$db -> prefix . 'users';
			$wxid=$fromUsername;
			$db->query("UPDATE ".$oldtable." SET `wxch_bd`='ok',`wxid`='$wxid' WHERE `user_name`='$old_user_name'");
		}else{
				$wxch_user_name_sql = "SELECT `user_name` FROM `$thistable` WHERE `wxch_bd`='ok' AND `wxid` = '$fromUsername'";
				$wxch_user_name = $db -> getOne($wxch_user_name_sql);
				$wxch_user_wxid_sql = "SELECT `wxid` FROM `$thistable` WHERE `wxid`=`user_name` AND `wxid` = '$fromUsername'";
				$wxch_user_wxid = $db -> getOne($wxch_user_wxid_sql);
				if (empty($wxch_user_wxid)) {
					if (empty($wxch_user_name)) {
						$wxch_nobd_wxid_sql = "SELECT `wxid` FROM `$thistable` WHERE `wxch_bd`='no' AND `wxid` = '$fromUsername'";
						$wxch_nobd_wxid = $db -> getOne($wxch_nobd_wxid_sql);
						if (empty($wxch_nobd_wxid)) {
							if (file_exists('uc_state.php')) {
								include('uc_state.php');
							} 
							if ($uc_state) {
								$salt = $uc_salt;
								$uc_pwd = $uc_pwd;
								$uc_sql = "INSERT INTO $uc_table (`username`, `password`, `salt`) VALUES ('$fromUsername', '$uc_pwd', '$salt')";
								$db -> query($uc_sql);
								$ecs_user_id = $db -> insert_id();
								$uc_username = 'wx' . $ecs_user_id;
								$uc_update = "UPDATE $uc_table  SET `username` = '$uc_username' WHERE `uid` = '$ecs_user_id'";
								$db -> query($uc_update);
								$ecs_password = md5($ecs_password);
								$wxch_user_sql = "INSERT INTO `$thistable` (`user_id`,`user_name`,`password`,`wxid`,`user_rank`,`wxch_bd`) VALUES ('$ecs_user_id','$uc_username','$ecs_password','$fromUsername','99','no')";
								$db -> query($wxch_user_sql);
							} else {
								$wxch_user_sql = "INSERT INTO `$thistable` ( `user_name`,`password`,`wxid`,`user_rank`,`wxch_bd`) VALUES ('$fromUsername','$ec_pwd','$fromUsername','99','no')";
								$db -> query($wxch_user_sql);
								$ecs_user_id = $db -> insert_id();
								$ecs_user_name = 'wx' . $ecs_user_id;
								$ecs_update = " UPDATE `$thistable` SET `user_name` = '$ecs_user_name' WHERE `user_id` = '$ecs_user_id'";
								$db -> query($ecs_update);
								$zhanghaoinfo="您的账号：".$ecs_user_name."密码：".$ec_pwd;
							} 
						} 
					} 
				} else {
				//如果没绑定好的，直接全部复位
					$wxch_user_sql = " UPDATE `$thistable` SET `user_rank` = '99',`wxch_bd`='no' WHERE `wxid` ='$fromUsername'";
					$db -> query($wxch_user_sql);
				}
			
			
			
				} 
			} 
			$newsTpl = "<xml>
                         <ToUserName><![CDATA[%s]]></ToUserName>
                         <FromUserName><![CDATA[%s]]></FromUserName>
                         <CreateTime>%s</CreateTime>
                         <MsgType><![CDATA[%s]]></MsgType>
                         <ArticleCount>%s</ArticleCount>
                         <Articles>
                         %s
                         </Articles>
                         <FuncFlag>0</FuncFlag>
                         </xml>";
			$serviceTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        </xml>";
			$imageTpl = "<xml>
                            <ToUserName><![CDATA[toUser]]></ToUserName>
                            <FromUserName><![CDATA[fromUser]]></FromUserName>
                            <CreateTime>12345678</CreateTime>
                            <MsgType><![CDATA[image]]></MsgType>
                            <Image>
                            <MediaId><![CDATA[media_id]]></MediaId>
                            </Image>
                            </xml>";
			$voiceTpl = "<xml>
                            <ToUserName><![CDATA[toUser]]></ToUserName>
                            <FromUserName><![CDATA[fromUser]]></FromUserName>
                            <CreateTime>12345678</CreateTime>
                            <MsgType><![CDATA[voice]]></MsgType>
                            <Voice>
                            <MediaId><![CDATA[media_id]]></MediaId>
                            </Voice>
                            </xml>";
			$de_test = '123';
			if ($postObj -> Event == 'subscribe') {
				if (strlen($postObj -> EventKey) == 0) {
					$ret = $db -> getRow("SELECT `type_id` FROM  `wxch_coupon` WHERE `id` = 1");
					if ($ret['type_id'] >= 1) {
						$postObj -> EventKey = 'gzyhj';
					} else {
						$postObj -> EventKey = 'subscribe';
					} 
				} else {
					$qrscene = $postObj -> EventKey;
					$scene_id_arr = explode("qrscene_", $qrscene);
					$scene_id = $scene_id_arr[1];
					$db -> query("UPDATE `wxch_qr` SET `subscribe`=`subscribe` + 1 WHERE `scene_id`= '$scene_id';");
					$scan_ret = $db -> getRow("SELECT * FROM `wxch_qr` WHERE scene_id =$scene_id");
					if ($scene_id >= 1) {
						$postObj -> EventKey = 'affiliate_' . $scan_ret['function'] . '_' . $scene_id;
					} else {
						$postObj -> EventKey = $scan_ret['function'];
					} 
				} 
			} elseif ($postObj -> Event == 'unsubscribe') {
				$db -> query("UPDATE  `wxch_user` SET  `subscribe` =  '0' WHERE  `wxch_user`.`wxid` = '$fromUsername';");
				$subscribe = 0;
			} elseif ($postObj -> Event == 'SCAN') {
				$qrscene = $postObj -> EventKey;
				$scene_id = $qrscene;
				$update_sql = "UPDATE `wxch_qr` SET `scan`=`scan` + 1 WHERE `scene_id`= '$scene_id';";
				$db -> query("$update_sql");
				
				$scan_ret = $db -> getRow("SELECT * FROM `wxch_qr` WHERE scene_id =$scene_id");
				if ($scan_ret['affiliate'] >= 1) {
					$postObj -> EventKey = 'affiliate_' . $scan_ret['function'] . '_' . $scan_ret['affiliate'];
				} else {
					$postObj -> EventKey = $scan_ret['function'];
				} 
			} 
			if ($wxch_bd == 'step') {
				if ($setp > 0 and $setp < 3) {
					$msgType = "text";
					if ($keyword == 'ko' or $keyword == 'KO' or $keyword == 'Ko' or $keyword == '退出') {
						$db -> query("UPDATE `wxch_user` SET `setp`= 0,`uname` = '' WHERE `wxid`= '$fromUsername';");
						$contentStr = "您已退出会员绑定流程，再次绑定输入bd进入绑定流程";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
						$this -> universal($fromUsername, $base_url);
						echo $resultStr;
						exit;
					} 
					if ($setp == 1) {
						$users_table = $db -> prefix . 'users';
						$ret = $db -> getRow("SELECT `user_name` FROM  `$users_table` WHERE `user_name` = '$keyword'");
						if (empty($ret)) {
							$contentStr = '您输入的用户名不存在，检查之后请重新输入，（输入ko退出绑定流程）' . $keyword;
						} else {
							$ret = $db -> getRow("SELECT `uname` FROM  `wxch_user` WHERE `uname` = '$keyword' AND `setp`>=3");
							if (!empty($ret)) {
								$contentStr = $keyword . '已经被其他用户绑定了，请绑定其他账号，退出绑定输入 ko';
								$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
								$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
								$this -> universal($fromUsername, $base_url);
								echo $resultStr;
								exit;
							} 
							$db -> query("UPDATE `wxch_user` SET `setp`=`setp`+1 WHERE `wxid`= '$fromUsername';");
							$db -> query("UPDATE `wxch_user` SET `uname` = '$keyword' WHERE `wxid`= '$fromUsername';");
							$contentStr = '请输入密码';
						} 
					} elseif ($setp == 2) {
						$password = $keyword;
						$verifyLogin = $user -> login($uname, $password, '');
						if (!$verifyLogin) {
							$contentStr = '您输入的密码不正确，请重新输入，（输入ko退出绑定流程）';
						} else {
							$db -> query("UPDATE `wxch_user` SET `setp`=`setp` + 1 WHERE `wxid`= '$fromUsername';");
							$contentStr = $uname . '，您的账号已经绑定成功！';
							$users_table = $db -> prefix . 'users';
							$db -> query("UPDATE `$users_table` SET `wxch_bd`='ok',`wxid`='$fromUsername' WHERE `user_name`= '$uname';");
						} 
					} 
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> plusPoint($db, $uname, $keyword, $fromUsername);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} 
			} 
			if ($wxch_bd == 'web') {
				$msgType = "text";
				if ($keyword == 'ko' or $keyword == 'KO' or $keyword == 'Ko' or $keyword == '退出') {
					$db -> query("UPDATE `wxch_user` SET `setp`= 0,`uname` = '' WHERE `wxid`= '$fromUsername';");
					$contentStr = "您已退出会员绑定流程，再次绑定输入bd进入绑定流程";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} 
				$setp = $db -> getOne("SELECT `setp` FROM `wxch_user` WHERE `wxid` = '$fromUsername'");
				if ($keyword == 'bd') {
					if ($setp == 0) {
						$bd_url = '<a href="' . $m_url . 'user_wxch.php?wxid=' . $fromUsername . '">点击绑定会员</a>';
						$bd_lang = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'bd'");
						$contentStr = $bd_url . $bd_lang . ',（中途退出绑定，请输入ko退出绑定流程）';
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						$this -> plusPoint($db, $uname, $keyword, $fromUsername);
						$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
						$this -> universal($fromUsername, $base_url);
						echo $resultStr;
						exit;
					} 
					if ($setp >= 1 AND $setp < 3) {
						$bd_url = '<a href="' . $m_url . 'user_wxch.php?wxid=' . $fromUsername . '">点击绑定会员</a>';
						$bd_lang = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'bd'");
						$contentStr = $bd_url . $bd_lang . ',（中途退出绑定，请输入ko退出绑定流程）';
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						$this -> plusPoint($db, $uname, $keyword, $fromUsername);
						$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
						$this -> universal($fromUsername, $base_url);
						echo $resultStr;
						exit;
					} 
				} 
				if ($setp >= 1 AND $setp < 3) {
					$bd_url = '<a href="' . $m_url . 'user_wxch.php?wxid=' . $fromUsername . '">点击绑定会员</a>';
					$bd_lang = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'bd'");
					$contentStr = $bd_url . $bd_lang . ',（中途退出绑定，请输入ko退出绑定流程）';
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> plusPoint($db, $uname, $keyword, $fromUsername);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} 
			} 
			if ($setp == 10) {
				if ($keyword == 'ko' or $keyword == 'KO' or $keyword == 'Ko' or $keyword == '退出') {
					$db -> query("UPDATE `wxch_user` SET `setp`= 3 WHERE `wxid`= '$fromUsername';");
					if ($cxbd == 'true') {
						$contentStr = "您已退出会员绑定流程，再次绑定输入cxbd进入绑定流程";
					} elseif ($cxbd == 'true') {
						$contentStr = "您已退出会员绑定流程";
					} 
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} 
				$password = $keyword;
				$verifyLogin = $user -> login($uname, $password, '');
				if (!$verifyLogin) {
					$contentStr = '您输入的密码不正确，请重新输入，想要退出请输出ko';
				} else {
					$db -> query("UPDATE `wxch_user` SET `setp`=1,`uname` = '' WHERE `wxid`= '$fromUsername';");
					$users_table = $db -> prefix . 'users';
					$contentStr = '已经解除：' . $uname . '账号的绑定' . "\r\n您已经重新进入会员绑定流程，想要退出绑定流程请回复ko或“退出”,继续请输入网站会员昵称";
					$db -> query("UPDATE `$users_table` SET `wxch_bd`='no',`wxid`='' WHERE `user_name`= '$uname';");
					$db -> query("UPDATE `wxch_user` SET `uname`='' WHERE `uname`= '$uname';");
				} 
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} 
			if ($postObj -> MsgType == 'event') {
				$keyword = $postObj -> EventKey;
				$menu_message = 'menu:' . $keyword;
				$db -> query("INSERT INTO `wxch_message` (`wxid`, `message`, `dateline`) VALUES
( '$fromUsername', '$menu_message', $time);");
			} 
			if ($postObj -> MsgType == 'voice') {
				$keyword = $postObj -> Recognition;
				$menu_message = 'voice:' . $keyword;
				$db -> query("INSERT INTO `wxch_message` (`wxid`, `message`, `dateline`) VALUES
( '$fromUsername', '$menu_message', $time);");
			} 
			$wxch_table = 'wxch_msg';
			$wxch_msg = $db -> getAll("SELECT * FROM  `$wxch_table`");
			foreach($wxch_msg as $k => $v) {
				$commands[$k] = $v;
			} 
			foreach($commands as $kk => $vv) {
				$temp_msg = explode(" ", $vv['command']);
				if (in_array($keyword, $temp_msg)) {
					$keyword = $vv['function'];
				} 
			} 
			$this -> getauto($db, $keyword, $textTpl, $newsTpl, $base_url, $m_url, $fromUsername, $toUsername, $time, $article_url);
			if ($keyword == 'debug') {
				$msgType = "text";
				$contentStr = $uname . $wxch_user_name . $wxch_user_sql . $wxch_user_name_sql;
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} 
			if ($keyword == 'kefu') {
				$msgType = "transfer_customer_service";
				$contentStr = '客服转接';
				$resultStr = sprintf($serviceTpl, $fromUsername, $toUsername, $time, $msgType);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} 
			if ($keyword == 'login') {
				$bd_url = '<a href="' . $m_url . 'user.php?wxid=' . $fromUsername . '&login=1">点击自动登录会员中心</a>';
				$contentStr = $bd_url."\r\n【甜心100为您服务】";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} 
			$aff_arr = explode('_', $keyword);
			if ($aff_arr[0] == 'affiliate') {
				if (!empty($aff_arr[2])) {
					$aff_query = "SELECT * FROM `ecs_users` WHERE `user_id` = $aff_arr[2]";
					$aff_db = $db -> getRow($aff_query);
					if (!empty($aff_db)) {
						$aff_update = "UPDATE `ecs_user` SET `parent_id` = '$aff_arr[2]' WHERE `wxid` = '$fromUsername';";
						$db -> query($aff_update);
					} 
				} 
				if (!empty($aff_arr[1])) {
					$keyword = $aff_arr[1];
				} 
			} 
			if ($keyword == 'bd') {
				if ($setp < 3) {
					if ($wxch_bd == 'web') {
						$bd_url = '<a href="' . $m_url . 'user_wxch.php?wxid=' . $fromUsername . '">点击绑定会员</a>';
						$bd_lang = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'bd'");
						$contentStr = $bd_url . $bd_lang . '';
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						$this -> plusPoint($db, $uname, $keyword, $fromUsername);
						$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
						$this -> universal($fromUsername, $base_url);
						echo $resultStr;
						exit;
					} 
					$contentStr = '您已进入会员绑定流程，想要退出绑定流程请回复ko或“退出”,继续请输入网站会员昵称';
					$db -> query("UPDATE `wxch_user` SET `setp`=`setp`+1 WHERE `wxid`= '$fromUsername';");
				} 
				if ($setp == 3) {
					$ret = $db -> getRow("SELECT `uname` FROM  `wxch_user` WHERE `wxid` = '$fromUsername'");
					$uname = $ret['uname'];
					if ($cxbd == 'true') {
						$contentStr = '您已经绑定了会员账号：' . $uname . "\r\n" . '如需重新绑定请输入cxbd';
					} else {
						$contentStr = '您已经绑定了会员账号：' . $uname;
					} 
				} 
				if ($setp == 0) {
					$db -> query("UPDATE `wxch_user` SET `setp`=1 WHERE `wxid`= '$fromUsername';");
				} 
				$msgType = "text";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'cxbd') {
				if ($cxbd == 'false') {
					$this -> universal($fromUsername, $base_url, $keyword);
					exit('false');
				} 
				$msgType = "text";
				if ($setp == 3) {
					$contentStr = '请输入' . $uname . '的密码才能继续绑定,（想要退出绑定流程请回复ko或“退出”）';
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$db -> query("UPDATE `wxch_user` SET `setp`=10 WHERE `wxid`= '$fromUsername';");
					echo $resultStr;
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url, $keyword);
					exit;
				} else {
					$contentStr = '您没有绑定会员';
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url, $keyword);
					exit;
				} 
			} elseif ($keyword == 'news') {
				$thistable = $db -> prefix . 'goods';
				$query_sql = "SELECT * FROM  `$thistable` WHERE `is_new` = 1 $goods_is ORDER BY sort_order, last_update DESC  LIMIT 0 , 6 ";
				$ret = $db -> getAll($query_sql);
				$ArticleCount = count($ret);
				$items = '';
				if ($ArticleCount >= 1) {
					foreach($ret as $v) {
						if ($img_path == 'local') {
							$v['thumbnail_pic'] = $base_img_path . $v['goods_img'];
						} elseif ($img_path == 'server') {
							$v['thumbnail_pic'] = $v['goods_img'];
						} 
						if ($oauth_state == 'true') {
							$goods_url = $oauth_location . $m_url . 'goods.php?id=' . $v['goods_id'] . $affiliate;
						} elseif ($oauth_state == 'false') {
							$goods_url = $m_url . 'goods.php?id=' . $v['goods_id'] . $postfix . $affiliate;
						} 
						$items .= "<item>
                 <Title><![CDATA[" . $v['goods_name'] . "]]></Title>
                 <PicUrl><![CDATA[" . $v['thumbnail_pic'] . "]]></PicUrl>
                 <Url><![CDATA[" . $goods_url . "]]></Url>
                 </item>";
					} 
					$msgType = "news";
				} 
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'best') {
				$thistable = $db -> prefix . 'goods';
				$query_sql = "SELECT * FROM  `$thistable` WHERE `is_best` = 1 $goods_is ORDER BY sort_order, last_update DESC  LIMIT 0 , 6 ";
				$ret = $db -> getAll($query_sql);
				$ArticleCount = count($ret);
				$items = '';
				if ($ArticleCount >= 1) {
					foreach($ret as $v) {
						if ($img_path == 'local') {
							$v['thumbnail_pic'] = $base_img_path . $v['goods_img'];
						} elseif ($img_path == 'server') {
							$v['thumbnail_pic'] = $v['goods_img'];
						} 
						if ($oauth_state == 'true') {
							$goods_url = $oauth_location . $m_url . 'goods.php?id=' . $v['goods_id'] . $affiliate;
						} elseif ($oauth_state == 'false') {
							$goods_url = $m_url . 'goods.php?id=' . $v['goods_id'] . $postfix . $affiliate;
						} 
						$items .= "<item>
                 <Title><![CDATA[" . $v['goods_name'] . "]]></Title>
                 <PicUrl><![CDATA[" . $v['thumbnail_pic'] . "]]></PicUrl>
                 <Url><![CDATA[" . $goods_url . "]]></Url>
                 </item>";
					} 
					$msgType = "news";
				} 
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			}elseif ($keyword == 'goods') {
				$thistable = $db -> prefix . 'goods';
				$query_sql = "SELECT * FROM  `$thistable` WHERE `is_share` = 1  ORDER BY sort_order, last_update DESC  LIMIT 0 ,9 ";
				$ret = $db -> getAll($query_sql);
				$ArticleCount = count($ret);
				$items = '';
				if ($ArticleCount >= 1) {
					foreach($ret as $v) {
						if ($img_path == 'local') {
							$v['thumbnail_pic'] = $base_img_path . $v['goods_img'];
						} elseif ($img_path == 'server') {
							$v['thumbnail_pic'] = $v['goods_img'];
						} 
						if ($oauth_state == 'true') {
							$goods_url = $oauth_location . $m_url . 'goods.php?id=' . $v['goods_id'] . $affiliate;
						} elseif ($oauth_state == 'false') {
							$goods_url = $m_url . 'goods.php?id=' . $v['goods_id'] .'&fu='.$fromUsername. $postfix . $affiliate;
						} 
						$items .= "<item>
                 <Title><![CDATA[" . $v['goods_name'] . "]]></Title>
                 <PicUrl><![CDATA[" . $v['thumbnail_pic'] . "]]></PicUrl>
                 <Url><![CDATA[" . $goods_url . "]]></Url>
                 </item>";
					} 
					$msgType = "news";
				} 
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			}
			elseif ($keyword == 'article') {
				$thistable = $db -> prefix . 'article';
				$query_sql = "SELECT * FROM  `$thistable` WHERE `is_share` = 1    LIMIT 0 , 9 ";
				$ret = $db -> getAll($query_sql);
				$ArticleCount = count($ret);
				$items = '';
				if ($ArticleCount >= 1) {
					foreach($ret as $v) {
					
						$v['thumbnail_pic'] = "";
						
						if ($oauth_state == 'true') {
							$goods_url = $oauth_location . $m_url . 'article.php?id=' . $v['article_id'] .'&fu='.$fromUsername .$affiliate;
						} elseif ($oauth_state == 'false') {
							$goods_url = $m_url . 'article.php?id=' . $v['article_id'] .'&fu='.$fromUsername . $postfix . $affiliate;
						} 
						$items .= "<item>
                 <Title><![CDATA[" . $v['title'] . "]]></Title>
                 <PicUrl><![CDATA[" . $v['thumbnail_pic'] . "]]></PicUrl>
                 <Url><![CDATA[" . $goods_url . "]]></Url>
                 </item>";
					} 
					$msgType = "news";
				} 
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			}elseif ($keyword == 'hot') {
				$thistable = $db -> prefix . 'goods';
				$ret = $db -> getAll("SELECT * FROM  `$thistable` WHERE `is_hot` = 1 $goods_is ORDER BY sort_order, last_update DESC  LIMIT 0 , 6 ");
				$ArticleCount = count($ret);
				$items = '';
				if ($ArticleCount >= 1) {
					foreach($ret as $v) {
						if ($img_path == 'local') {
							$v['thumbnail_pic'] = $base_img_path . $v['goods_img'];
						} elseif ($img_path == 'server') {
							$v['thumbnail_pic'] = $v['goods_img'];
						} 
						if ($oauth_state == 'true') {
							$goods_url = $oauth_location . $m_url . 'goods.php?id=' . $v['goods_id'] . $affiliate;
						} elseif ($oauth_state == 'false') {
							$goods_url = $m_url . 'goods.php?id=' . $v['goods_id'] . $postfix . $affiliate;
						} 
						$items .= "<item>
                 <Title><![CDATA[" . $v['goods_name'] . "]]></Title>
                 <PicUrl><![CDATA[" . $v['thumbnail_pic'] . "]]></PicUrl>
                 <Url><![CDATA[" . $goods_url . "]]></Url>
                 </item>";
					} 
					$msgType = "news";
				} 
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'jfcx') {
				$thistable = $db -> prefix . 'users';
				$sql = "SELECT * FROM `$thistable` WHERE `wxid` = '$fromUsername'";
				$ret = $db -> getAll($sql);
				if (count($ret) >= 2) {
					foreach($ret as $v) {
						if ($v['wxch_bd'] == 'ok') {
							$pay_points = $v['pay_points'];
							$money = $v['user_money'];
						} 
					} 
				} 
				if (empty($pay_points)) {
					$pay_points = $ret[0]['pay_points'];
					$money = $ret[0]['user_money'];
				} 
				$msgType = "text";
				$contentStr = "余额：$money\r\n积分：$pay_points";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'ddlb') {
				$msgType = "text";
				$thistable = $db -> prefix . 'order_info';
				if ($setp == 3) {
					$table2 = $db -> prefix . 'users';
					$user_id = $db -> getOne("SELECT `user_id` FROM `$table2` WHERE `wxch_bd` = 'ok' AND `wxid` ='$fromUsername'");
				} else {
					$table2 = $db -> prefix . 'users';
					$user_id = $db -> getOne("SELECT `user_id` FROM `$table2` WHERE `wxch_bd` = 'no' AND `wxid` ='$fromUsername'");
				} 
				$orders = $db -> getAll("SELECT * FROM `$thistable` WHERE `user_id` = '$user_id' ORDER BY `order_id` DESC LIMIT 0,5");
				$ArticleCount = count($orders);
				if ($ArticleCount >= 1) {
					$order_goods_tb = $db -> prefix . 'order_goods';
					$items = '';
					foreach($orders as $k => $v) {
						$order_id = $v['order_id'];
						$order_goods = $db -> getAll("SELECT * FROM `$order_goods_tb`  WHERE `order_id` = '$order_id'");
						$shopinfo = '';
						foreach($order_goods as $vv) {
							if (empty($v['goods_attr'])) {
								$shopinfo .= $vv['goods_name'] . '(' . $vv['goods_number'] . '),';
							} else {
								$shopinfo .= $vv['goods_name'] . '（' . $vv['goods_attr'] . '）' . '(' . $vv['goods_number'] . '),';
							} 
						} 
						$shopinfo = substr($shopinfo, 0, strlen($shopinfo)-1);
						if ($k != 0) {
							if ($oauth_state == 'true') {
								$title = "\r\n" . '------------------' . "\r\n" . '订单号：<a href="' . $oauth_location . $m_url . 'user.php?act=order_detail&order_id=' . $v['order_id'] . '">' . $v['order_sn'] . "</a>";
							} elseif ($oauth_state == 'false') {
								$title = "\r\n" . '------------------' . "\r\n" . '订单号：<a href="' . $m_url . 'user.php?act=order_detail&order_id=' . $v['order_id'] . '&wxid=' . $fromUsername . '">' . $v['order_sn'] . "</a>";
							} 
						} else {
							if ($oauth_state == 'true') {
								$title = '订单号：<a href="' . $oauth_location . $m_url . 'user.php?act=order_detail&order_id=' . $v['order_id'] . '">' . $v['order_sn'] . "</a>\r\n";
							} elseif ($oauth_state == 'false') {
								$title = '订单号：<a href="' . $m_url . 'user.php?act=order_detail&order_id=' . $v['order_id'] . '&wxid=' . $fromUsername . '">' . $v['order_sn'] . "</a>\r\n";
							} 
						} 
						if ($v['order_amount'] == 0.00) {
							if ($v['money_paid'] > 0) {
								$v['order_amount'] = $v['money_paid'];
							} 
						} 
						$description = "\r" . '商品信息：' . $shopinfo . "\r总金额：" . $v['order_amount'] . "\r物流公司：" . $v['shipping_name'] . ' 物流单号：' . $v['invoice_no'];
						$items .= $title . $description;
					} 
					if ($oauth_state == 'true') {
						$items_oder_list = '<a href="' . $oauth_location . $m_url . 'user.php?act=order_list">"我的订单"</a>';
					} elseif ($oauth_state == 'false') {
						$items_oder_list = '<a href="' . $m_url . 'user.php?act=order_list&wxid=' . $fromUsername . '">"我的订单"</a>';
					} 
					$items_more = "\r\n" . '更多详细信息请点击' . $items_oder_list . '';
					$contentStr = $items . $items_more;
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> plusPoint($db, $uname, $keyword, $fromUsername);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} else {
					$msgType = "text";
					$contentStr = "您还没有订单";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} 
			} elseif ($keyword == 'ddcx') {
				$ArticleCount = 1;
				$msgType = "news";
				$thistable = $db -> prefix . 'order_info';
				$order_goods_tb = $db -> prefix . 'order_goods';
				if ($setp == 3) {
					$table2 = $db -> prefix . 'users';
					$ret = $db -> getRow("SELECT `user_id` FROM `$table2` WHERE `wxch_bd`='ok' AND `wxid` ='$fromUsername'");
					$user_id = $ret['user_id'];
					$orders = $db -> getRow("SELECT * FROM `$thistable` WHERE `user_id` = '$user_id' ORDER BY `order_id` DESC");
					$order_id = $orders['order_id'];
					$order_goods = $db -> getAll("SELECT * FROM `$order_goods_tb`  WHERE `order_id` = '$order_id'");
				} else {
					$table2 = $db -> prefix . 'users';
					$ret = $db -> getRow("SELECT `user_id` FROM `$table2` WHERE `wxch_bd`='no' AND `wxid` ='$fromUsername'");
					$user_id = $ret['user_id'];
					$orders = $db -> getRow("SELECT * FROM `$thistable` WHERE `user_id` = '$user_id' ORDER BY `order_id` DESC");
					$order_id = $orders['order_id'];
					$order_goods = $db -> getAll("SELECT * FROM `$order_goods_tb`  WHERE `order_id` = '$order_id'");
				} 
				$shopinfo = '';
				if (!empty($order_goods)) {
					foreach($order_goods as $v) {
						if (empty($v['goods_attr'])) {
							$shopinfo .= $v['goods_name'] . '(' . $v['goods_number'] . '),';
						} else {
							$v['goods_attr'] = $this -> guolv($v['goods_attr']);
							$shopinfo .= $v['goods_name'] . '（' . $v['goods_attr'] . '）' . '(' . $v['goods_number'] . '),';
						} 
					} 
					$shopinfo = substr($shopinfo, 0, strlen($shopinfo)-1);
					$title = '订单号：' . $orders['order_sn'];
					if ($orders['pay_status'] == 0) {
						$pay_status = '支付状态：未付款';
					} elseif ($orders['pay_status'] == 1) {
						$pay_status = '支付状态：付款中';
					} elseif ($orders['pay_status'] == 2) {
						$pay_status = '支付状态：已付款';
					} 
					if ($oauth_state == 'true') {
						$url = $oauth_location . $m_url . 'user.php?act=order_detail&order_id=' . $orders['order_id'];
					} elseif ($oauth_state == 'false') {
						$url = $m_url . 'user.php?act=order_detail&order_id=' . $orders['order_id'] . $postfix;
					} 
					if ($orders['order_amount'] == 0.00) {
						if ($orders['money_paid'] > 0) {
							$orders['order_amount'] = $orders['money_paid'];
						} 
					} 
					$description = '商品信息：' . $shopinfo . "\r\n总金额：" . $orders['order_amount'] . "\r\n" . $pay_status . "\r\n快递公司：" . $orders['shipping_name'] . ' 物流单号：' . $orders['invoice_no'];
					$items = "<item>
                 <Title><![CDATA[" . $title . "]]></Title>
                 <Description><![CDATA[" . $description . "]]></Description>
                 <PicUrl><![CDATA[]]></PicUrl>
                 <Url><![CDATA[" . $url . "]]></Url>
                 </item>";
					$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
					$w_message = '图文消息';
					$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
					$this -> plusPoint($db, $uname, $keyword, $fromUsername);
					echo $resultStr;
				} else {
					$msgType = "text";
					$contentStr = "您还没有订单";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> universal($fromUsername, $base_url);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					echo $resultStr;
				} 
				exit;
			} elseif ($keyword == 'kdcx') {
				$thistable = $db -> prefix . 'order_info';
				$table2 = $db -> prefix . 'users';
				if ($setp == 3) {
					$ret = $db -> getRow("SELECT `user_id` FROM `$table2` WHERE `wxch_bd` = 'ok' AND `wxid` ='$fromUsername'");
					$user_id = $ret['user_id'];
					$orders = $db -> getRow("SELECT * FROM `$thistable` WHERE `user_id` = '$user_id' ORDER BY `order_id` DESC");
				} else {
					$ret = $db -> getRow("SELECT `user_id` FROM `$table2` WHERE `wxch_bd` = 'no' AND `wxid` ='$fromUsername'");
					$user_id = $ret['user_id'];
					$orders = $db -> getRow("SELECT * FROM `$thistable` WHERE `user_id` = '$user_id' ORDER BY `order_id` DESC");
				} 
				if (empty($orders)) {
					$msgType = "text";
					$contentStr = '您还没有订单，无法查询快递';
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} 
				if (empty($orders['invoice_no'])) {
					$msgType = "text";
					$contentStr = '订单号：' . $orders['order_sn'] . '还没有快递单号，不能查询';
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} 
				$k_arr = $this -> kuaidi($orders['invoice_no'], $orders['shipping_name']);
				$contents = '';
				$msgType = "text";
				if ($k_arr['message'] == 'ok') {
					$count = count($k_arr['data']) - 1;
					for($i = $count;$i >= 0;$i--) {
						$contents .= "\r\n" . $k_arr['data'][$i]['time'] . "\r\n" . $k_arr['data'][$i]['context'];
					} 
					$contentStr = "订单号：$orders[invoice_no]\r\n" . "快递信息" . $contents;
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> plusPoint($db, $uname, $keyword, $fromUsername);
					$this -> universal($fromUsername, $base_url, $keyword);
					exit;
				} else {
					$contentStr = "没有查到订单号：$orders[invoice_no] 的" . "快递信息";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> plusPoint($db, $uname, $keyword, $fromUsername);
					$this -> universal($fromUsername, $base_url, $keyword);
				} 
				exit;
			} elseif ($keyword == 'reg') {
			} elseif ($keyword == 'help' or $keyword == 'HELP') {
				$msgType = "text";
				$lang['help'] = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'help'");
				$contentStr = $lang['help'];
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'dzp') {
				$data = $this -> dzp($db, $base_url, $fromUsername);
				$msgType = "news";
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $data['ArticleCount'], $data['items']);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'ggl') {
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
			} elseif ($keyword == 'zjd') {
				$data = $this -> egg($db, $base_url, $fromUsername);
				$msgType = "news";
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $data['ArticleCount'], $data['items']);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			}elseif ($keyword == 'qrcode') {
				$ArticleCount = 1;
				$scene_id = $user_id;
				$affiliate=$user_id;
				$gourl = $base_url . 'wechat/egg/index1.php?scene_id=' . $scene_id;
				$type = 'tj';
				$qr_path = $db->getOne("SELECT `qr_path` FROM `wxch_qr` WHERE `scene_id`='$scene_id'");
				$user_name = $db->getOne("SELECT `user_name` FROM `ecs_users` WHERE `user_id`='$scene_id'");
				
				$scene=$user_name;
				if(!empty($qr_path)){
					 $surl=$qr_path;
					 }else{
				$action_name="QR_LIMIT_SCENE";
				$json_arr = array('action_name'=>$action_name,'action_info'=>array('scene'=>array('scene_id'=>$scene_id)));
				$data = json_encode($json_arr);
				$this -> access_token($db);
				$ret = $db->getRow("SELECT `access_token` FROM `wxch_config`");
				$access_token = $ret['access_token'];
				if(strlen($access_token) >= 64) 
				{
					$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
					$res_json =$this -> curl_grab_page($url, $data);
					$json = json_decode($res_json);
				}
			$ticket = $json->ticket;

		if($ticket)
		{
			$ticket_url = urlencode($ticket);
			$ticket_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket_url;
			$imageinfo=$this -> downloadimageformweixin($ticket_url);
			$time = time();	
			$url=$_SERVER['HTTP_HOST'];			
			$path = '../images/qrcode/'.$time.'.jpg';
			$surl="http://".$url.'/images/qrcode/'.$time.'.jpg';
			$local_file=fopen($path,'w');
		
			if(false !==$local_file){
	
				if(false !==fwrite($local_file,$imageinfo["body"])){
					fclose($local_file);
					//将生成的二维码图片的地址放到数据库中
					$insert_sql = "INSERT INTO `wxch_qr` (`type`,`action_name`,`ticket`, `scene_id`, `scene` ,`qr_path`,`function`,`affiliate`,`endtime`,`dateline`) VALUES
					('$type','$action_name', '$ticket',$scene_id, '$scene' ,'$surl','$function','$affiliate','$endtime','$dateline')";
					$db->query($insert_sql);
						
				}

			}
		}
}		
				$des="扫描二维码可以获得推荐关系！";
				$items = "<item>
				<Title><![CDATA[推荐二维码]]></Title>
				<Description><![CDATA[" . $des . "]]></Description>
				<PicUrl><![CDATA[" . $surl . "]]></PicUrl>
				<Url><![CDATA[" . $gourl . "]]></Url>
				</item>";				
				$msgType = "news";
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			}elseif ($keyword == 'fenxiang') {
			
				$sql_two="SELECT * FROM " . $GLOBALS['ecs']->table('users') . " WHERE wxid = '$fromUsername'";
				$userinfo=$GLOBALS['db']->GetRow($sql_two);
				$user_id=$userinfo['user_id'];
				$url = $base_url . "mobile/fenxiang.php?u=".$user_id;
				$name = '点击进入，分享链接赢积分';
				$PicUrl = '';
				$items = "<item>
             <Title><![CDATA[" . $name . "]]></Title>
             <PicUrl><![CDATA[" . $PicUrl . "]]></PicUrl>
             <Url><![CDATA[" . $url . "]]></Url>
             </item>";
				$ArticleCount = 1;
				$msgType = "news";
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'gzyhj') {
				$msgType = "text";
				$contentStr = $this -> coupon($db, $fromUsername);
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;		
			
			}			
			elseif ($keyword == 'map232') {
				$url = 'http://api.map.baidu.com/direction?origin=latlng:34.264642646862,108.95108518068|name:我家&desti
nation=大雁塔&mode=driving&region=西安';
				$name = '地图';
				$PicUrl = '';
				$items = "<item>
             <Title><![CDATA[" . $name . "]]></Title>
             <PicUrl><![CDATA[" . $PicUrl . "]]></PicUrl>
             <Url><![CDATA[" . $url . "]]></Url>
             </item>";
				$ArticleCount = 1;
				$msgType = "news";
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'gzyhj') {
				$msgType = "text";
				$contentStr = $this -> coupon($db, $fromUsername);
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'qiandao') {
				$jf_state = $db -> getOne("SELECT `autoload` FROM `wxch_point` WHERE `point_name` = '$keyword'");
				$msgType = "text";
				if ($jf_state == 'yes') {
					$qd_jf = $db -> getOne("SELECT `point_value` FROM `wxch_point` WHERE `point_name` = '$keyword'");
					$res = $this -> plusPoint($db, $uname, $keyword, $fromUsername);
					if ($res['errmsg'] == 'ok') {
						$contentStr = $res['contentStr'] . $qd_jf;
					} else {
						$contentStr = $res['contentStr'];
					} 
				} elseif ($jf_state == 'no') {
					$qdstop = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'qdstop'");
					if (empty($qdstop)) {
						$qdstop = '签到积送已停止使用';
					} 
					$contentStr = $qdstop;
				} 
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} elseif ($keyword == 'subscribe') {
				$msgType = "text";
				$lang['regmsg'] = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'regmsg'");
				$contentStr = $lang['regmsg'];
				if(!empty($zhanghaoinfo)){
					$contentStr .=$zhanghaoinfo;
				}else{
					$contentStr .= "您已经注册过！账号是".$user_name;
				}				
				
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} 
			if (file_exists('wxch_development.php')) {
				include('wxch_development.php');
			} 
			if (!empty($keyword)) {
				$ck_keyword = strtolower($keyword);
				$ck_ret = stristr($ck_keyword, 'ck');
				if ($ck_ret) {
					$ck_arr = explode('ck', $keyword);
					$ck_sn = $ck_arr[1];
					$ck_table = $db -> prefix . 'goods';
					$ck_ret = $db -> getAll("SELECT * FROM  `$ck_table` WHERE  `goods_sn` LIKE '%$ck_sn%'");
					$msgType = "text";
					$ck_goods = '';
					if (count($ck_ret) > 10) {
						$contentStr = '结果超出十条以上，请您输入更准确的货号，例如：' . $ck_sn . 'AB';
					} elseif (count($ck_ret) > 0) {
						foreach($ck_ret as $v) {
							if ($v['is_on_sale'] == 0) {
								$ck_title = '下架';
							} elseif ($v['goods_number'] > 20) {
								$ck_title = '充足';
							} elseif ($v['goods_number'] >= 5 and $v['goods_number'] <= 20) {
								$ck_title = '紧张';
							} elseif ($v['goods_number'] >= 1 and $v['goods_number'] <= 5) {
								$ck_title = $v['goods_number'];
							} elseif ($v['goods_number'] == 0) {
								$ck_title = '缺货';
							} 
							$ck_goods .= $v['goods_sn'] . ':' . $v['goods_name'] . '--' . $ck_title . "\r\n";
						} 
						$contentStr = $ck_goods;
					} else {
						$contentStr = '没有查询到' . $ck_sn . '货号的商品，建议您输入更简短的货号查询';
					} 
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} 
				$this -> getauto($db, $keyword, $textTpl, $newsTpl, $base_url, $m_url, $fromUsername, $toUsername, $time, $article_url);
				$thistable = $db -> prefix . 'goods';
				$goods_name = $keyword;
				$search_sql = "SELECT * FROM  `$thistable` WHERE  `goods_name` LIKE '%$goods_name%' $goods_is ORDER BY sort_order, last_update DESC LIMIT 0,6";
				$ret = $db -> getAll($search_sql);
				$ArticleCount = count($ret);
				$items = '';
				if ($ArticleCount >= 1) {
					foreach($ret as $v) {
						if ($img_path == 'local') {
							$v['thumbnail_pic'] = $base_img_path . $v['goods_img'];
						} elseif ($img_path == 'server') {
							$v['thumbnail_pic'] = $v['goods_img'];
						} 
						if ($oauth_state == 'true') {
							$goods_url = $oauth_location . $m_url . 'goods.php?id=' . $v['goods_id'] . $affiliate;
						} elseif ($oauth_state == 'false') {
							$goods_url = $m_url . 'goods.php?id=' . $v['goods_id'] . $postfix . $affiliate;
						} 
						$items .= "<item>
                 <Title><![CDATA[" . $v['goods_name'] . "]]></Title>
                 <PicUrl><![CDATA[" . $v['thumbnail_pic'] . "]]></PicUrl>
                 <Url><![CDATA[" . $goods_url . "]]></Url>
                 </item>";
					} 
					$msgType = "news";
				} else {
					$msgType = "text";
					$thistable = $db -> prefix . 'goods';
					if ($plustj == 'true') {
						$tj_str = $this -> plusTj($db, $m_url, $postfix, $oauth_location, $oauth_state, $goods_is, $affiliate);
						$contentStr = '没有搜索到"' . $goods_name . '"的商品' . $tj_str;
					} elseif ($plustj == 'false') {
						exit;
					} 
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					$this -> insert_wmessage($db, $fromUsername, $contentStr, $time, $belong);
					$this -> plusPoint($db, $uname, $keyword, $fromUsername);
					$this -> universal($fromUsername, $base_url);
					echo $resultStr;
					exit;
				} 
				$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
				$w_message = '图文消息';
				$this -> insert_wmessage($db, $fromUsername, $w_message, $time, $belong);
				$this -> plusPoint($db, $uname, $keyword, $fromUsername);
				$this -> universal($fromUsername, $base_url);
				echo $resultStr;
				exit;
			} 
		} else {
			echo "";
			exit;
		} 
	} 
	protected function plusPoint($db, $uname, $keyword, $fromUsername) {
		$res_arr = array();
		$sql = "SELECT * FROM `wxch_point_record` WHERE `point_name` = '$keyword' AND `wxid` = '$fromUsername'";
		$record = $db -> getRow($sql);
		$num = $db -> getOne("SELECT `point_num` FROM `wxch_point` WHERE `point_name` = 'qiandao'");
		$lasttime = time();
		if (empty($record)) {
			$dateline = time();
			$insert_sql = "INSERT INTO `wxch_point_record` (`wxid`, `point_name`, `num`, `lasttime`, `datelinie`) VALUES
('$fromUsername', '$keyword' , 1, $lasttime, $dateline);";
			$potin_name = $db -> getOne("SELECT `point_name` FROM `wxch_point` WHERE `point_name` = '$keyword'");
			if (!empty($potin_name)) {
				$db -> query($insert_sql);
			} 
		} else {
			$qdtoday = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'qdtoday'");
			if (empty($qdtoday)) {
				$qdtoday = '今天您已经签到了，明天再来赚积分吧';
			} 
			$time = time();
			$lasttime_sql = "SELECT `lasttime` FROM `wxch_point_record` WHERE `point_name` = '$keyword' AND `wxid` = '$fromUsername'";
			$db_lasttime = $db -> getOne($lasttime_sql);
			if (($time - $db_lasttime) > (60 * 60 * 24)) {
				$update_sql = "UPDATE `wxch_point_record` SET `num` = 0,`lasttime` = '$lasttime' WHERE `wxid` ='$fromUsername';";
				$db -> query($update_sql);
			} 
			$record_num = $db -> getOne("SELECT `num` FROM `wxch_point_record` WHERE `point_name` = '$keyword' AND `wxid` = '$fromUsername'");
			if ($record_num < $num) {
				$update_sql = "UPDATE `wxch_point_record` SET `num` = `num`+1,`lasttime` = '$lasttime' WHERE `point_name` = '$keyword' AND `wxid` ='$fromUsername';";
				$db -> query($update_sql);
			} else {
				$qdno = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'qdno'");
				if (empty($qdno)) {
					$qdno = '签到数次已用完';
				} 
				$res_arr['errmsg'] = 'no';
				$res_arr['contentStr'] = $qdno;
				return $res_arr;
			} 
		} 
		$wxch_table = 'wxch_point';
		$wxch_points = $db -> getAll("SELECT * FROM  `$wxch_table`");
		foreach($wxch_points as $k => $v) {
			if ($v['point_name'] == $keyword) {
				if ($v['autoload'] == 'yes') {
					$points = $v['point_value'];
					$thistable = $db -> prefix . 'users';
					if (!empty($uname)) {
						$sql = "UPDATE `$thistable` SET `pay_points` = `pay_points`+$points WHERE `user_name` ='$uname'";
					} else {
						$sql = "UPDATE `$thistable` SET `pay_points` = `pay_points`+$points WHERE `wxid` ='$fromUsername'";
					} 
					$db -> query($sql);
				} 
			} 
		} 
		$qdok = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'qdok'");
		if (empty($qdok)) {
			$qdok = '签到成功,积分+';
		} 
		$res_arr['errmsg'] = 'ok';
		$res_arr['contentStr'] = $qdok;
		return $res_arr;
	} 
	protected function getNews($db, $base_url, $m_url, $postfix, $img_path) {
		$thistable = $db -> prefix . 'goods';
		$ret = $db -> getAll("SELECT * FROM  `$thistable` ORDER BY `add_time` LIMIT 0 , 6");
		$ArticleCount = count($ret);
		$items = '';
		if ($ArticleCount >= 1) {
			foreach($ret as $v) {
				if ($img_path == 'local') {
					$v['thumbnail_pic'] = $base_img_path . $v['goods_img'];
				} elseif ($img_path == 'server') {
					$v['thumbnail_pic'] = $v['goods_img'];
				} 
				$goods_url = $m_url . 'goods.php?id=' . $v['goods_id'] . $postfix;
				$items .= "<item>
             <Title><![CDATA[" . $v['goods_name'] . "]]></Title>
             <PicUrl><![CDATA[" . $v['thumbnail_pic'] . "]]></PicUrl>
             <Url><![CDATA[" . $goods_url . "]]></Url>
             </item>";
			} 
		} 
		$data = array();
		$data['ArticleCount'] = $ArticleCount;
		$data['items'] = $items;
		return $data;
	} 
	protected function insert_wmessage($db, $fromUsername, $w_message, $time, $belong) {
		$w_message = mysql_real_escape_string($w_message);
		$sql = "INSERT INTO `wxch_message` (`wxid`, `w_message`, `belong`, `dateline`) VALUES
('$fromUsername', '$w_message', '$belong', '$time');";
		$db -> query($sql);
	} 
	protected function plusTj($db, $m_url, $postfix, $oauth_location, $oauth_state, $goods_is, $affiliate) {
		$thistable = $db -> prefix . 'goods';
		$ret = $db -> getAll("SELECT * FROM  `$thistable` WHERE  `is_best` =1 $goods_is ");
		$tj_count = count($ret);
		$tj_key = mt_rand(0, $tj_count);
		$tj_goods = $ret[$tj_key];
		if ($tj_goods['goods_id']) {
			if ($oauth_state == 'true') {
				return $tj_str = "\r\n我们为您推荐:" . "<a href='$oauth_location" . "$m_url" . 'goods.php?id=' . $tj_goods['goods_id'] . $affiliate . "'>$tj_goods[goods_name]</a>";
			} elseif ($oauth_state == 'false') {
				return $tj_str = "\r\n我们为您推荐:" . '<a href="' . $m_url . 'goods.php?id=' . $tj_goods[goods_id] . $postfix . $affiliate . '">' . $tj_goods[goods_name] . '</a>';
			} 
		} 
	} 
	protected function get_keywords_articles($kws_id, $db) {
		$sql = "SELECT `article_id` FROM `wxch_keywords_article` WHERE `kws_id` = '$kws_id'";
		$ret = $db -> getAll($sql);
		$articles = '';
		foreach($ret as $v) {
			$articles .= $v['article_id'] . ',';
		} 
		$length = strlen($articles)-1;
		$articles = substr($articles, 0, $length);
		if (!empty($articles)) {
			$sql2 = "SELECT `article_id`,`title`,`file_url`,`description` FROM " . $GLOBALS['ecs'] -> table('article') . " WHERE `article_id` IN ($articles) ORDER BY `add_time` DESC ";
			$res = $db -> getAll($sql2);
		} 
		return $res;
	} 
	protected function coupon($db, $fromUsername) {
		$retc = $db -> getRow("SELECT `coupon` FROM `wxch_user` WHERE `wxid` ='$fromUsername'");
		$lang = $db -> getAll("SELECT * FROM `wxch_lang` WHERE `lang_name` LIKE '%coupon%'");
		if (!empty($retc['coupon'])) {
			$contentStr = $lang[0]['lang_value'] . $retc['coupon'] . $lang[3]['lang_value'];
			return $contentStr;
		} else {
			$ret = $db -> getRow("SELECT * FROM `wxch_coupon` WHERE `id` = 1");
			$type_id = $ret['type_id'];
			$thistable = $db -> prefix . 'bonus_type';
			$ret = $db -> getRow("SELECT * FROM `$thistable` WHERE `type_id` =$type_id ");
			$type_money = $ret['type_money'];
			$use_end_date = date("Y年-m月-d日", $ret['use_end_date']);
			$time = time();
			if (($time >= $ret['send_start_date']) or ($time <= $ret['send_end_date'])) {
				$thistable = $db -> prefix . 'user_bonus';
				$ret = $db -> getRow("SELECT `bonus_sn` FROM `$thistable` WHERE `bonus_type_id` = $type_id AND `used_time` = 0 ");
				if (!empty($ret['bonus_sn'])) {
					$user_bonus = $db -> getAll("SELECT `bonus_sn` FROM  `$thistable` WHERE `bonus_type_id` = $type_id");
					$wx_bonus = $db -> getAll("SELECT `coupon` FROM  `wxch_user` ");
					foreach ($wx_bonus as $k => $v) {
						foreach ($user_bonus as $kk => $vv) {
							if ($v['coupon'] == $vv['bonus_sn']) {
								unset($user_bonus[$kk]);
							} 
						} 
					} 
					$bonus_rand = array_rand($user_bonus);
					$coupon = $user_bonus[$bonus_rand]['bonus_sn'];
					if (!empty($user_bonus[$bonus_rand]['bonus_sn'])) {
						$contentStr = $lang[1]['lang_value'] . $type_money . "元,优惠券：" . $coupon . "\r\n使用结束日期：$use_end_date" . $lang[3]['lang_value'];
						$db -> query("UPDATE `wxch_user` SET `coupon` = '$coupon' WHERE `wxid` ='$fromUsername';");
					} else {
						$contentStr = $lang[2]['lang_value'] . $lang[3]['lang_value'];
					} 
				} else {
					$contentStr = $lang[2]['lang_value'] . $lang[3]['lang_value'];
				} 
			} 
		} 
		return $contentStr;
	} 
	protected function dzp($db, $base_url, $fromUsername) {
		$ret = $db -> getAll("SELECT * FROM `wxch_prize` WHERE `fun` = 'dzp' AND `status` = 1 ORDER BY `dateline` DESC ");
		$temp_count = count($ret);
		$time = time();
		if ($temp_count > 1) {
			foreach($ret as $k => $v) {
				if ($time <= $v['starttime']) {
					unset($ret[$k]);
				} elseif ($time >= $v['endtime']) {
					unset($ret[$k]);
				} 
			} 
		} 
		$ArticleCount = 1;
		$prize_count = count($ret);
		$prize = $ret[array_rand($ret)];
		$wxch_lang = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'prize_dzp'");
		if ($prize_count <= 0) {
			$items = '<item>
                 <Title><![CDATA[大转盘暂时未开放]]></Title>
                 <PicUrl><![CDATA[]]></PicUrl>
                 <Url><![CDATA[]]></Url>
                 </item>';
		} else {
			$gourl = $base_url . 'wechat/dzp/index.php?pid=' . $prize['pid'] . '&wxid=' . $fromUsername;
			$PicUrl = $base_url . 'wechat/dzp/images/wx_bd.jpg';
			$items = "<item>
                 <Title><![CDATA[大转盘]]></Title>
                 <Description><![CDATA[" . $wxch_lang . "]]></Description>
                 <PicUrl><![CDATA[" . $PicUrl . "]]></PicUrl>
                 <Url><![CDATA[" . $gourl . "]]></Url>
                 </item>";
		} 
		$data = array();
		$data['ArticleCount'] = $ArticleCount;
		$data['items'] = $items;
		return $data;
	} 
	protected function egg($db, $base_url, $fromUsername) {
		$ret = $db -> getAll("SELECT * FROM `wxch_prize` WHERE `fun` = 'egg' AND `status` = 1 ORDER BY `dateline` DESC ");
		$temp_count = count($ret);
		$time = time();
		if ($temp_count > 1) {
			foreach($ret as $k => $v) {
				if ($time <= $v['starttime']) {
					unset($ret[$k]);
				} elseif ($time >= $v['endtime']) {
					unset($ret[$k]);
				} 
			} 
		} 
		$ArticleCount = 1;
		$prize_count = count($ret);
		$prize = $ret[array_rand($ret)];
		$wxch_lang = $db -> getOne("SELECT `lang_value` FROM `wxch_lang` WHERE `lang_name` = 'prize_egg'");
		if ($prize_count <= 0) {
			$items = '<item>
             <Title><![CDATA[砸金蛋暂时未开放]]></Title>
             <PicUrl><![CDATA[]]></PicUrl>
             <Url><![CDATA[]]></Url>
             </item>';
		} else {
			$gourl = $base_url . 'wechat/egg/index.php?pid=' . $prize['pid'] . '&wxid=' . $fromUsername;
			$PicUrl = $base_url . 'wechat/egg/images/wx_bd.jpg';
			$items = "<item>
             <Title><![CDATA[砸金蛋]]></Title>
             <Description><![CDATA[" . $wxch_lang . "]]></Description>
             <PicUrl><![CDATA[" . $PicUrl . "]]></PicUrl>
             <Url><![CDATA[" . $gourl . "]]></Url>
             </item>";
		} 
		$data = array();
		$data['ArticleCount'] = $ArticleCount;
		$data['items'] = $items;
		return $data;
	} 
	protected function getauto($db, $keyword, $textTpl, $newsTpl, $base_url, $m_url, $fromUsername, $toUsername, $time, $article_url) {
		$this -> universal($fromUsername, $base_url);
		$auto_res = $ret = $db -> getAll("SELECT * FROM `wxch_keywords`");
		if (count($auto_res) > 0) {
			foreach($auto_res as $k => $v) {
				if ($v['status'] == 1) {
					$res_ks = explode(' ', $v['keyword']);
					if ($v['type'] == 1) {
						$msgType = "text";
						foreach($res_ks as $kk => $vv) {
							if ($vv == $keyword) {
								$contentStr = $v['contents'];
								$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
								echo $resultStr;
								$db -> query("UPDATE `wxch_keywords` SET `count` = `count`+1 WHERE `id` =$v[id]");
								exit;
							} 
						} 
					} elseif ($v['type'] == 2) {
						$msgType = "news";
						$items = '';
						foreach($res_ks as $kk => $vv) {
							if ($vv == $keyword) {
								$res = $this -> get_keywords_articles($v['id'], $db);
								foreach($res as $vvv) {
									if (!empty($vvv['file_url'])) {
										$picurl = $base_url . $vvv['file_url'];
									} else {
										$picurl = $base_url . 'themes/default/images/logo.gif';
										if (!is_null($GLOBALS['_CFG']['template'])) {
											$picurl = $base_url . 'themes/' . $GLOBALS['_CFG']['template'] . '/images/logo.gif';
										} 
									} 
									$gourl = $m_url . $article_url . $vvv['article_id'];
									$ArticleCount = count($res);
									$items .= "<item>
                             <Title><![CDATA[" . $vvv['title'] . "]]></Title>
                             <Description><![CDATA[" . $vvv['description'] . "]]></Description>
                             <PicUrl><![CDATA[" . $picurl . "]]></PicUrl>
                             <Url><![CDATA[" . $gourl . "]]></Url>
                             </item>";
								} 
								$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $ArticleCount, $items);
								echo $resultStr;
								$db -> query("UPDATE `wxch_keywords` SET `count` = `count`+1 WHERE `id` =$v[id];");
								exit;
							} 
						} 
					} 
				} 
			} 
		} 
	} 
	protected function bdmap() {
		$url = 'http://api.map.baidu.com/direction?origin=latlng:34.264642646862,108.95108518068|name:我家&desti
nation=大雁塔&mode=driving&region=西安&output=html';
	} 
	public function access_token($db) {
		$ret = $db -> getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
		$appid = $ret['appid'];
		$appsecret = $ret['appsecret'];
		$dateline = $ret['dateline'];
		$time = time();
		if (($time - $dateline) > 7200) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
			$ret_json = curl_get_contents($url);
			$ret = json_decode($ret_json);
			if ($ret -> access_token) {
				$db -> query("UPDATE `wxch_config` SET `access_token` = '$ret->access_token',`dateline` = '$time' WHERE `wxch_config`.`id` =1;");
			} 
		} 
	} 
	public function create_menu($db) {
		$this -> access_token($db);
		$ret = $db -> getRow("SELECT `access_token` FROM `wxch_config`");
		$access_token = $ret['access_token'];
		if (strlen($access_token) == 150) {
			$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
			$menu = '{
     "button":[
     {
          "type":"click",
          "name":"新款推荐",
          "key":"XKTJ"
      },
      {
           "type":"click",
           "name":"绑定会员",
           "key":"BDHY"
      },
      {
           "name":"帮助",
           "sub_button":[
           {
               "type":"click",
               "name":"订单查询",
               "key":"DDCX",
            },
            {
              "type":"click",
               "name":"快递查询",
               "key":"KDCX",
            },
            {
               "type":"click",
               "name":"帮助",
               "key":"HELP"
            }]
       }]
 }';
			$ret = $this -> curl_grab_page($url, $menu);
			$errmsg = $ret -> errmsg;
			if ($errmsg == 'ok') {
				echo '创建菜单成功';
			} else {
				$i = 1;
				$max = 100;
				for($i;$i <= $max;$i++) {
					sleep(1);
					$ret_json = $this -> curl_grab_page($url, $menu);
					$ret = json_decode($ret_json);
					if ($ret -> errcode == 0) {
						echo '尝试第' . $i . '时成功创建';
						break;
					} 
				} 
				if ($ret -> errcode == -1) {
					echo '尝试创建' . $i . '次菜单失败，请稍后再试';
				} 
			} 
			print_r($ret);
		} else {
			echo 'access_token:' . str_len($access_token);
		} 
	} 
	public function delete_menu($db) {
		$this -> access_token($db);
		$ret = $db -> getRow("SELECT `access_token` FROM `wxch_config`");
		$access_token = $ret['access_token'];
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $access_token;
		$ret_json = $this -> curl_get_contents($url);
		$ret = json_decode($ret_json);
		return $ret;
	} 
	public function orders($user_id, $size = 10, $start = 0) {
		include_once(ROOT_PATH . 'includes/lib_transaction.php');
		$orders = get_user_orders($user_id, $size, $start);
		return $orders;
	} 
	public function kuaidi($invoice_no, $shipping_name) {
		switch ($shipping_name) {
			case '中国邮政':$logi_type = 'ems';
				break;
			case '申通快递':$logi_type = 'shentong';
				break;
			case '圆通速递':$logi_type = 'yuantong';
				break;
			case '顺丰速运':$logi_type = 'shunfeng';
				break;
			case '韵达快递':$logi_type = 'yunda';
				break;
			case '天天快递':$logi_type = 'tiantian';
				break;
			case '中通速递':$logi_type = 'zhongtong';
				break;
			case '增益速递':$logi_type = 'zengyisudi';
				break;
		} 
		$kurl = 'http://www.kuaidi100.com/query?type=' . $logi_type . '&postid=' . $invoice_no;
		$ret = $this -> curl_get_contents($kurl);
		$k_arr = json_decode($ret, true);
		return $k_arr;
	} 
	public function universal($wxid, $base_url) {
		$arr = explode("/", $base_url);
		if (count($arr) == 5) {
			$gourl = $arr[2];
			$append = '/' . $arr[3];
			$this -> update_info_url($gourl, $wxid, $append);
		} else {
			$gourl = $arr[2];
			$this -> update_info($gourl, $wxid);
		} 
	} 
	public function mydebug($textTpl, $fromUsername, $toUsername, $time, $contents) {
		if ($fromUsername == 'oXcUzuDVEDbMarygeXUtFCRgbl7s') {
			$msgType = "text";
			$contentStr = $contents;
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
			exit;
		} 
	} 
	public function curl_get_contents($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
		curl_setopt($ch, CURLOPT_REFERER, _REFERER_);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
			curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		} 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$r = curl_exec($ch);
		curl_close($ch);
		return $r;
	} 
	public function curl_grab_page($url, $data, $proxy = '', $proxystatus = '', $ref_url = '') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($proxystatus == 'true') {
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
		} 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		if (!empty($ref_url)) {
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_REFERER, $ref_url);
		} 
		if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
			curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		} 
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		ob_start();
		return curl_exec ($ch);
		ob_end_clean();
		curl_close ($ch);
		unset($ch);
	} 
	public function guolv($str) {
		$str = str_replace("\r", "", $str);
		$str = str_replace("\n", "", $str);
		$str = str_replace("\t", "", $str);
		$str = str_replace("\r\n", "", $str);
		$str = trim($str);
		return $str;
	} 
	private function checkSignature($db) {
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$ret = $db -> getRow("SELECT * FROM `wxch_config` WHERE `id` = 1");
		$token = $ret['token'];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		} 
	} 
	private function update_info($host, $wxid) {
		if (function_exists(fsockopen)) {
			$fp = fsockopen("$host", 80, $errno, $errstr, 10);
		} else {
			$fp = pfsockopen("$host", 80, $errno, $errstr, 10);
		} 
		$url = "/wechat/userinfo.php?wxid=$wxid";
		if (!$fp) {
			echo "$errstr $errno <br />\n";
		} else {
			$out = "GET  $url HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			$inheader = 1;
			$result = '';
			while (!feof($fp)) {
				$line = fgets($fp, 1024);
				if ($inheader && ($line == "\n" || $line == "\r\n")) {
					$inheader = 0;
				} 
				if ($inheader == 0) {
					$result .= trim($line);
				} 
			} 
			fclose($fp);
		} 
	} 
	private function update_info_url($host, $wxid, $append) {
		if (function_exists(fsockopen)) {
			$fp = fsockopen("$host", 80, $errno, $errstr, 10);
		} else {
			$fp = pfsockopen("$host", 80, $errno, $errstr, 10);
		} 
		$url = $append . "/wechat/userinfo.php?wxid=$wxid";
		if (!$fp) {
			echo "$errstr $errno <br />\n";
		} else {
			$out = "GET  $url HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			$inheader = 1;
			$result = '';
			while (!feof($fp)) {
				$line = fgets($fp, 1024);
				if ($inheader && ($line == "\n" || $line == "\r\n")) {
					$inheader = 0;
				} 
				if ($inheader == 0) {
					$result .= trim($line);
				} 
			} 
			fclose($fp);
		} 
	}


//新增
function htmltowei($contents) 
{
	$contents = strip_tags($contents,'<br>');
	$contents = str_replace('<br />',"\r\n",$contents);
	$contents = str_replace('&quot;','"',$contents);
	$contents = str_replace('&nbsp;','',$contents);
	return $contents;
}
function downloadimageformweixin($url) {
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_NOBODY, 0); //只取body头
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSLVERSION,3); 
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);
		return array_merge(array('body' => $package), array('header' => $httpinfo));
	 }	
} 
