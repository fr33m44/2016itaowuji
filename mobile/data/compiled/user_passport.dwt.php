<!DOCTYPE html>
<html>
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta charset="utf-8" />
<title><?php echo $this->_var['page_title']; ?> 触屏版</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<link href="<?php echo $this->_var['ectouch_themes']; ?>/images/touch-icon.png" rel="apple-touch-icon-precomposed" />
<link href="<?php echo $this->_var['ectouch_themes']; ?>/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="<?php echo $this->_var['ectouch_themes']; ?>/ectouch.css" rel="stylesheet" type="text/css" />

<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,user.js')); ?>
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/jquery-1.4.4.min.js"></script>
<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js')); ?>
</head>
<body>

 
<?php if ($this->_var['action'] == 'login' || $this->_var['action'] == 'register'): ?>
<div id="page">
  <header id="header">
    <div class="header_l"> <a class="ico_10" onClick="javascript:history.back();"> 返回 </a> </div>
    <h1> 登录/注册 </h1>
  </header>
</div>
<section class="wrap">
  <div id="leftTabBox" class="loginBox">
    <div class="hd"> <span>欢迎光临，登录后体验更多服务</span>
      <ul>
        <li<?php if ($this->_var['action'] == 'login'): ?> class="on"<?php endif; ?>><a href="javascript:void(0)">登录</a></li>
        <li<?php if ($this->_var['action'] == 'register'): ?> class="on"<?php endif; ?>><a href="javascript:void(0)">注册</a></li>
      </ul>
    </div>
    <div class="bd"<?php if ($this->_var['action'] == 'register'): ?> style="display:none"<?php endif; ?>>
      <ul>
        <div class="table_box">
          <form name="formLogin" action="user.php" method="post" onSubmit="return userLogin()">
		  <dl>
              <dd>
               <label class="leftlabel"><select name="login_type"><option value="0">用户名</option><option value="3">会员卡</option></select></label>
              </dd>
            </dl>
            <dl>
              <dd>
                <input placeholder="<?php echo $this->_var['lang']['username']; ?>/<?php echo $this->_var['lang']['mobile']; ?>/<?php echo $this->_var['lang']['email']; ?>" name="username" type="text"  class="inputBg" id="username" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input placeholder="<?php echo $this->_var['lang']['label_password']; ?>"  name="password" type="password" class="inputBg" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input type="checkbox" value="1" name="remember" id="remember" style="vertical-align:middle; zoom:200%;" /><label for="remember"> 一个月内免登录</label>
              </dd>
            </dl>
            <dl>
              <dd>
                <input type="hidden" name="act" value="act_login" />
                <input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
                <input type="submit" name="submit"  value="立即登陆" class="c-btn3" />
              </dd>
            </dl>
          </form>
          <dl>
            <dd> <a href="user.php?act=get_password" class="f6">忘记密码</a> </dd>
          </dl>

        </div>
      </ul>
    </div>
    <div class="bd"<?php if ($this->_var['action'] == 'login'): ?> style="display:none"<?php endif; ?>>
      <ul style="height:25rem">
      	<?php if ($this->_var['enabled_sms_signin'] == 1): ?>
        <form action="user.php" method="post" name="formUser" onsubmit="return register2();">
          <input type="hidden" name="flag" id="flag" value="register" />
          <div class="table_box">
            <dl>
              <dd>
                <input placeholder="请输入手机号码" class="inputBg" name="mobile" id="mobile_phone" type="text" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input placeholder="请输入帐号密码" class="inputBg" name="password" id="mobile_pwd" type="password" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input placeholder="请输入验证码" class="inputBg" name="mobile_code" id="mobile_code" type="text" />
              </dd>
              <dd>
              <input id="zphone" name="sendsms" type="button" value="获取手机验证码" onClick="sendSms();" class="c-btn3" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input id="agreement" name="agreement" type="checkbox" value="1" checked="checked" style="vertical-align:middle; zoom:200%;" /><label for="agreement"> 我已看过并同意《<a href="article.php?cat_id=-1">用户协议</a>》</label>
              </dd>
            </dl>
            <dl>
              <dd>
                <input name="act" type="hidden" value="act_register" />
                <input name="enabled_sms" type="hidden" value="1" />
                <input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
                <input name="Submit" type="submit" value="下一步" class="c-btn3" />
              </dd>
            </dl>
          </div>
        </form>
        <?php else: ?>
        <form action="user.php" method="post" name="formUser" onsubmit="return register();">
          <input type="hidden" name="flag" id="flag" value="register" />
          <div class="table_box">
            <dl>
              <dd>
                <input placeholder="请输入用户名" class="inputBg" name="username" id="username" type="text" />
              </dd>
            </dl>

            <dl>
              <dd>
                <input placeholder="请输入登录密码" class="inputBg" name="password" id="password1" type="password" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input placeholder="请重新输入一遍密码" class="inputBg" name="confirm_password" id="confirm_password" type="password" />
              </dd>
            </dl>

            <dl>
              <dd>
                <input id="agreement" name="agreement" type="checkbox" value="1" checked="checked" style="vertical-align:middle; zoom:200%;" /><label for="agreement"> 我已看过并同意《<a href="article.php?cat_id=-1">用户协议</a>》</label>
              </dd>
            </dl>
            <dl>
              <dd>
                <input name="act" type="hidden" value="act_register" />
                <input name="enabled_sms" type="hidden" value="0" />
                <input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
                <input name="Submit" type="submit" value="下一步" class="c-btn3" />
              </dd>
            </dl>
          </div>
        </form>
        <?php endif; ?>
        <?php if ($this->_var['need_rechoose_gift']): ?>
        <?php echo $this->_var['lang']['gift_remainder']; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</section>
<script type="text/javascript">
jQuery(function($){
	$('.hd ul li').click(function(){
		var index = $('.hd ul li').index(this);
		$(this).addClass('on').siblings('li').removeClass('on');
		$('.loginBox .bd:eq('+index+')').show().siblings('.bd').hide();
	})
})
</script>
<?php endif; ?> 
 

 
<?php if ($this->_var['action'] == 'get_password'): ?> 
<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js')); ?> 
<script type="text/javascript">
    <?php $_from = $this->_var['lang']['password_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
      var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </script>
<div id="page">
  <header id="header">
    <div class="header_l"> <a class="ico_10" onClick="javascript:history.back();"> 返回 </a> </div>
    <h1> 找回密码 </h1>
  </header>
</div>
<section class="wrap">
  <div id="leftTabBox" class="loginBox">
    <div class="hd"> <span>您可通过<?php if ($this->_var['enabled_sms_signin'] == 1): ?>手机号码<?php else: ?>电子邮件<?php endif; ?>重置密码</span>
    </div>
    <div id="tabBox1-bd">
      <ul>
      	<?php if ($this->_var['enabled_sms_signin'] == 1): ?>
      	<form  action="user.php" method="post" name="getPassword" onSubmit="return submitForget();">
          <input type="hidden" name="flag" id="flag" value="forget" />
          <div class="table_box">
            <dl>
              <dd>
                <input placeholder="请输入手机号码" class="inputBg" name="mobile" id="mobile_phone" type="text" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input placeholder="请输入验证码" class="inputBg" name="mobile_code" id="mobile_code" type="text" />
              </dd>
              <dd>
              <input id="zphone" name="sendsms" type="button" value="获取手机验证码" onClick="sendSms();" class="c-btn3" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input name="act" type="hidden" value="send_pwd_sms" />
                <input name="Submit" type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="c-btn3" />
              </dd>
            </dl>
          </div>
        </form>
        <?php else: ?>
        <form action="user.php" method="post" name="getPassword" onsubmit="return submitPwdInfo();">
          <div class="table_box">
            <dl>
              <dd>
                <input placeholder="<?php echo $this->_var['lang']['username']; ?>" class="inputBg" name="user_name" type="text" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input placeholder="<?php echo $this->_var['lang']['email']; ?>" class="inputBg" name="email" type="text" />
              </dd>
            </dl>
            <dl>
              <dd>
                <input name="act" type="hidden" value="send_pwd_email" />
                <input name="Submit" type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="c-btn3" />
              </dd>
            </dl>
          </div>
        <br />
      </form>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</section>
<?php endif; ?> 

<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/sms.js"></script>

<div style="width:1px; height:1px; overflow:hidden"><?php $_from = $this->_var['lang']['p_y']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pv');if (count($_from)):
    foreach ($_from AS $this->_var['pv']):
?><?php echo $this->_var['pv']; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></div>
<script type="text/javascript">
var process_request = "<?php echo $this->_var['lang']['process_request']; ?>";
<?php $_from = $this->_var['lang']['passport_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var username_exist = "<?php echo $this->_var['lang']['username_exist']; ?>";
</script>
</body>
</html>
