<?php 
//  微博登录插件类，如有BUG请联系本人！！ 
/*===========================================================
**/

if (defined('WEBSITE'))
{
	global $_LANG;
	$_LANG['help']['APP_KEY'] = '在 http://dev.renren.com/ 里申请的 App Key';
	$_LANG['help']['APP_SECRET'] = '请注意填写，最长的就填此处';
	$_LANG['APP_KEY'] = 'App Key';
	$_LANG['APP_SECRET'] = 'App Secret';

	$i = isset($web) ? count($web) : 0;
	
	// 类名
	$web[$i]['name'] = '人人';
	
	// 文件名，不包含后缀
	
	$web[$i]['type'] = 'renn';
	
	// 作者信息
	$web[$i]['author'] = 'ECTouch Team';
	
	$web[$i]['className'] = 'renn';
	
	// 作者QQ
	$web[$i]['qq'] = '2880175560';
	
	// 作者邮箱
	$web[$i]['email'] = 'zhong@ecmoban.com';
	
	// 申请网址
	$web[$i]['website'] = 'http://dev.renren.com/';
	
	// 版本号
	$web[$i]['version'] = 'Renn API2.0 SDK PHP v0.1';
	
	// 更新日期
	$web[$i]['date']  = '2014-3-27';
	
	// 配置信息
	$web[$i]['config'] = array(
		array('type'=>'text' , 'name'=>'APP_KEY', 'value'=>''),
		array('type'=>'text' , 'name' => 'APP_SECRET' , 'value' => ''),    
	);
}


if (!defined('WEBSITE'))
{
	include_once(dirname(__FILE__).'/oath2.class.php');
	class website extends oath2
	{
		// 类赋值
		
		function website()
		{
			$this->app_key = APP_KEY;
			$this->app_secret = APP_SECRET;
			$this->tokenURL = 'http://graph.renren.com/oauth/token';
			$this->authorizeURL = 'http://graph.renren.com/oauth/authorize';
            $this->display = 'touch';
			$this->meth = 'GET';
            $this->userURL = 'https://api.renren.com/v2/user/login/get';  

			/*$this->post_msg = array(
				'client_id' => '',
				'client_secret'=>'',
				'oauth_consumer_key' => $this->app_key,
				'format'=>'json'
			);*/
		}
       /* function message($info)
		{
			$arr = array();
			$arr['user_id']  = $info['id'];
			$arr['name'] = empty($info['screen_name']) ? $info['name'] : $info['screen_name'];
			$arr['location'] = $info['location'];
			$arr['sex'] = $info['gender'] == 'm' ? 1 : 0;
			$arr['img']  = empty($info['avatar_large']) ? '' : $info['avatar_large'];
			$arr['lang'] = $info['lang'];
			$arr['info'] = $info;
			return $arr;
		}*/
		
		/*function is_error($response , $is_chuli = true)
		{
			if (strrpos($response, "callback") !== false)
			{
				$lpos = strpos($response, "(");
				$rpos = strrpos($response, ")");
				$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
				$ret = json_decode($response , true);
				
				if($ret['ret'] || $ret['error'])
				{
					$this->add_error((isset($ret['ret']) ? $ret['ret'] : $ret['error']) , isset($ret['error_description']) ? $ret['error_description'] : '' , isset($ret['msg']) ? $ret['msg'] : '');
					return false;
				}
			}
			
			if($is_chuli)
			{
				$ret = json_decode($response , true);
				if($ret['ret'] || $ret['error'])
				{

					$this->add_error((isset($ret['ret']) ? $ret['ret'] : $ret['error']) , isset($ret['error_description']) ? $ret['error_description'] : '' , isset($ret['msg']) ? $ret['msg'] : '');
					return false;
				}
				return $ret ? $ret : $response;
			}
			else
			{
				return true;
			}
		}*/
	}
}
?>