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
</head>

<body>
<div id="page">
  <header id="header">
    <div class="header_l"> <a class="ico_10" onClick="javascript:history.back();"> 返回 </a> </div>
    <h1> 提示消息 </h1>
    <div class="header_r"></div>
  </header>
</div>
<div class="blank3"></div>
<section class="wrap" style="padding-bottom:5rem">
  <ul class="radius10 itemlist">
    <div style="margin:1rem auto; text-align:center">
      <p style="font-size:0.8rem; font-weight:bold; color: red;"><?php echo $this->_var['message']['content']; ?></p>
      <div class="blank"></div>
      <?php if ($this->_var['message']['url_info']): ?> 
      <?php $_from = $this->_var['message']['url_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('info', 'url');if (count($_from)):
    foreach ($_from AS $this->_var['info'] => $this->_var['url']):
?>
      <p class="info"><a href="<?php echo $this->_var['url']; ?>"><?php echo $this->_var['info']; ?></a></p>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
	  
      <?php endif; ?> 
    </div>
  </ul>
</section>
<div style="width:1px; height:1px; overflow:hidden"><?php $_from = $this->_var['lang']['p_y']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pv');if (count($_from)):
    foreach ($_from AS $this->_var['pv']):
?><?php echo $this->_var['pv']; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></div>
</body>
</html>