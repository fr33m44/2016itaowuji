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

<?php echo $this->smarty_insert_scripts(array('files'=>'transport.js,common.js,user.js')); ?>
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/jquery-1.4.4.min.js"></script>
</head>
<body>
 
<?php if ($this->_var['action'] == 'default'): ?>
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="./"> 返回 </a> </div>
  <h1> 会员中心 </h1>
</header>
<dl class="user_top">
  <dt> <?php if ($this->_var['info']['avatar'] != ''): ?><img src="<?php echo $this->_var['info']['avatar']; ?>"><?php else: ?><img src="<?php echo $this->_var['ectouch_themes']; ?>/images/get_avatar.png"><?php endif; ?> </dt>
  <dd>
    <p>会员ID：<?php echo $this->_var['info']['username']; ?></p>
    <p>微信昵称：<?php echo $this->_var['info']['nickname']; ?></p>
    <p>关注时间：<?php echo $this->_var['info']['subscribe_time']; ?></p>
  </dd>
  <div class="user_top_list">
    <ul>
      <li> <a href="user.php?act=order_list"> <strong><?php echo $this->_var['info']['order_count']; ?></strong> <img  src="<?php echo $this->_var['ectouch_themes']; ?>/images/ico_user_01.png"> <span>30天订单</span> </a> </li>
      <li> <a href="user.php?act=point"> <strong><?php echo $this->_var['info']['integral']; ?></strong> <img  src="<?php echo $this->_var['ectouch_themes']; ?>/images/ico_user_02.png"> <span>积分</span> </a> </li>
      <li> <a href="distribute.php?act=account_detail"> <strong><?php echo $this->_var['info']['surplus']; ?></strong> <img  src="<?php echo $this->_var['ectouch_themes']; ?>/images/ico_user_03.png"> <span>余额</span> </a> </li>
      <li> <a class="fragment" href="user.php?act=bonus"> <strong><?php echo $this->_var['info']['bonus']; ?></strong> <img  src="<?php echo $this->_var['ectouch_themes']; ?>/images/ico_user_04.png"> <span>红包</span> </a> </li>
    </ul>
  </div>
  <div class="quan1"></div>
  <div class="quan2"></div>
  <div class="quan3"></div>
</dl>
<div class="blank3"></div>
<section class="wrap">
<!--
  <div class="list_box padd1 radius10" style="padding-top:0;padding-bottom:0;">
   <a href="index.php?u=<?php echo $this->_var['user_id']; ?>" class="clearfix"> <span>我的店铺</span><i></i> </a>
   <a href="user.php?act=dianpu" class="clearfix"> <span>修改店铺名</span><i></i> </a>  
    <a href="user.php?act=fenxiao1" class="clearfix"> <span>我的直销</span><i></i> </a>
 <a href="user.php?act=fenxiao2" class="clearfix"> <span>二级分销</span><i></i> </a>
  <a href="user.php?act=fenxiao3" class="clearfix"> <span>三级分销</span><i></i> </a>
  <a href="user.php?act=fenxiao4" class="clearfix"> <span>四级分销</span><i></i> </a>
	</div>
	-->
	<div class="list_box padd1 radius10" style="padding-top:0;padding-bottom:0;">
	 <a  href="distribute.php" class="clearfix"> <span>分销中心</span><i></i> </a>
	</div>
	  <div class="blank3"></div>
	  
  <div class="list_box padd1 radius10" style="padding-top:0;padding-bottom:0;"> 
  <a  href="tel:<?php echo $this->_var['service_phone']; ?>" class="clearfix"> <span>呼叫客服</span><i></i> </a><a href="user.php?act=profile" class="clearfix"> <span><?php echo $this->_var['lang']['label_profile']; ?></span><i></i> </a> <a href="user.php?act=order_list" class="clearfix"> <span><?php echo $this->_var['lang']['label_order']; ?></span><i></i> </a> <a href="user.php?act=address_list"  class="clearfix"> <span><?php echo $this->_var['lang']['label_address']; ?></span><i></i> </a> <a href="user.php?act=collection_list"  class="clearfix"> <span><?php echo $this->_var['lang']['label_collection']; ?></span><i></i> </a> </div>
  <div class="blank3"></div>
  <div class="list_box padd1 radius10" style="padding-top:0;padding-bottom:0;"> <a href="user.php?act=message_list" class="clearfix"> <span><?php echo $this->_var['lang']['label_message']; ?></span><i></i> </a>  <a href="user.php?act=comment_list"  class="clearfix"> <span><?php echo $this->_var['lang']['label_comment']; ?></span><i></i> </a>
	<a href="user.php?act=user_card"  class="clearfix"> <span>会员卡</span><i></i> </a>
  </div>
  <div class="blank3"></div>
  <div class="list_box padd1 radius10" style="padding-top:0;padding-bottom:0;"> 
    <a href="user.php?act=logout" class="clearfix"> <span>退出登录</span><i></i> </a> </div>
</section>

<?php endif; ?> 
 
 
<?php if ($this->_var['action'] == 'message_list'): ?>
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="user.php"> 返回 </a> </div>
  <h1> <?php echo $this->_var['lang']['label_message']; ?> </h1>
</header>
<section class="wrap message_list">
  <section class="order_box padd1 radius10 single_item">
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="ectouch_table">
    <tr>
        <td class="message"></td>
    </tr>
  </table>
  </section>
  <a href="javascript:;" style="text-align:center" class="get_more"></a>
  <section class="order_box padd1 radius10">
  <form action="user.php" method="post" enctype="multipart/form-data" name="formMsg" onSubmit="return submitMsg()">
    <table width="100%" border="0" cellpadding="5" cellspacing="0" class="ectouch_table">
      <?php if ($this->_var['order_info']): ?>
      <tr>
        <td> <?php echo $this->_var['lang']['order_number']; ?> <a href ="<?php echo $this->_var['order_info']['url']; ?>"><img src="themes/miqinew/images/note.gif" /><?php echo $this->_var['order_info']['order_sn']; ?></a>
          <input name="msg_type" type="hidden" value="5" />
          <input name="order_id" type="hidden" value="<?php echo $this->_var['order_info']['order_id']; ?>" class="inputBg_touch" /></td>
      </tr>
      <?php else: ?>
      <tr>
        <td><input name="msg_type" type="radio" value="0" checked="checked" />
          <?php echo $this->_var['lang']['type']['0']; ?>
          <input type="radio" name="msg_type" value="1" />
          <?php echo $this->_var['lang']['type']['1']; ?>
          <input type="radio" name="msg_type" value="2" />
          <?php echo $this->_var['lang']['type']['2']; ?>
          <input type="radio" name="msg_type" value="3" />
          <?php echo $this->_var['lang']['type']['3']; ?>
          <input type="radio" name="msg_type" value="4" />
          <?php echo $this->_var['lang']['type']['4']; ?> </td>
      </tr>
      <?php endif; ?>
      <tr>
        <td><input name="msg_title" type="text" placeholder="<?php echo $this->_var['lang']['message_title']; ?>" class="inputBg_touch" /></td>
      </tr>
      <tr>
        <td><textarea name="msg_content" placeholder="<?php echo $this->_var['lang']['message_content']; ?>" cols="50" rows="4" wrap="virtual" style="border: 1px #DDD solid; width: 90%;"></textarea></td>
      </tr>
      <tr>
        <td><input type="hidden" name="act" value="act_add_message" />
          <input type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="c-btn3" /></td>
      </tr>
    </table>
  </form>
  </section>
</section>
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/jquery.more.js"></script>
<script type="text/javascript">
jQuery(function($){
    $('.message_list').more({'address': 'user.php?act=async_message_list', amount: 5, 'spinner_code':'<div style="text-align:center; margin:10px;"><img src="<?php echo $this->_var['ectouch_themes']; ?>/images/loader.gif" /></div>'})
	$(window).scroll(function () {
		if ($(window).scrollTop() == $(document).height() - $(window).height()) {
			$('.get_more').click();
		}
	});	
});
</script>
<?php endif; ?> 
 
 
<?php if ($this->_var['action'] == 'comment_list'): ?>
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="user.php"> 返回 </a> </div>
  <h1> <?php echo $this->_var['lang']['label_comment']; ?> </h1>
</header>
<section class="wrap comment_list">
  <section class="order_box padd1 radius10 single_item">
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="ectouch_table">
    <tr>
        <td class="comment"></td>
    </tr>
  </table>
  </section>
  <a href="javascript:;" style="text-align:center" class="get_more"></a>
</section>
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/jquery.more.js"></script>
<script type="text/javascript">
jQuery(function($){
    $('.comment_list').more({'address': 'user.php?act=async_comment_list', amount: 5, 'spinner_code':'<div style="text-align:center; margin:10px;"><img src="<?php echo $this->_var['ectouch_themes']; ?>/images/loader.gif" /></div>'})
	$(window).scroll(function () {
		if ($(window).scrollTop() == $(document).height() - $(window).height()) {
			$('.get_more').click();
		}
	});
});
</script>
<?php endif; ?> 
 

 
<?php if ($this->_var['action'] == 'collection_list'): ?> 
<?php echo $this->smarty_insert_scripts(array('files'=>'transport.js,utils.js')); ?>
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="user.php"> 返回 </a> </div>
  <h1> <?php echo $this->_var['lang']['label_collection']; ?> </h1>
</header>
<section class="wrap collection_list">
  <section class="order_box padd1 radius10 single_item">
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="ectouch_table">
    <tr>
        <td class="collection"></td>
    </tr>
  </table>
</section>
<a href="javascript:;" style="text-align:center" class="get_more"></a>
</section>
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/jquery.more.js"></script>
<script type="text/javascript">
jQuery(function($){
    $('.collection_list').more({'address': 'user.php?act=async_collection_list', amount: 5, 'spinner_code':'<div style="text-align:center; margin:10px;"><img src="<?php echo $this->_var['ectouch_themes']; ?>/images/loader.gif" /></div>'})
	$(window).scroll(function () {
		if ($(window).scrollTop() == $(document).height() - $(window).height()) {
			$('.get_more').click();
		}
	});
});
</script>
<?php endif; ?> 
 
 
<?php if ($this->_var['action'] == 'booking_list'): ?>
<header id="header">
  <div class="c-inav">
    <section>
      <button class="back">
      <span><em></em></span><a href="javascript:history.go(-1)">返回</a>
      </button>
    </section>
    <section> <span style="font-size:14px; color:#333; font-weight:normal"><?php echo $this->_var['lang']['label_booking']; ?></span> </section>
    <section></section>
  </div>
</header>
<div class="blank"></div>
<div class="fullscreen">
  <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
    <tr align="center">
      <td width="20%" bgcolor="#ffffff">名称</td>
      <td width="20%" bgcolor="#ffffff">数量</td>
      <td width="20%" bgcolor="#ffffff"><?php echo $this->_var['lang']['booking_time']; ?></td>
      <td width="25%" bgcolor="#ffffff">备注</td>
      <td width="15%" bgcolor="#ffffff"><?php echo $this->_var['lang']['handle']; ?></td>
    </tr>
    <?php $_from = $this->_var['booking_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
    <tr>
      <td align="center" bgcolor="#ffffff"><a href="<?php echo $this->_var['item']['url']; ?>" target="_blank" class="f6"><?php echo $this->_var['item']['goods_name']; ?></a></td>
      <td align="center" bgcolor="#ffffff"><?php echo $this->_var['item']['goods_number']; ?></td>
      <td align="center" bgcolor="#ffffff"><?php echo $this->_var['item']['booking_time']; ?></td>
      <td align="center" bgcolor="#ffffff"><?php echo $this->_var['item']['dispose_note']; ?></td>
      <td align="center" bgcolor="#ffffff"><a href="javascript:if (confirm('<?php echo $this->_var['lang']['confirm_remove_account']; ?>')) location.href='user.php?act=act_del_booking&id=<?php echo $this->_var['item']['rec_id']; ?>'" class="f6"><?php echo $this->_var['lang']['drop']; ?></a></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </table>
</div>
<?php endif; ?> 

 
<?php if ($this->_var['action'] == 'add_booking'): ?>
<header id="header">
  <div class="c-inav">
    <section>
      <button class="back">
      <span><em></em></span><a href="javascript:history.go(-1)">返回</a>
      </button>
    </section>
    <section> <span style="font-size:14px; color:#333; font-weight:normal">{<?php echo $this->_var['lang']['add']; ?><?php echo $this->_var['lang']['label_booking']; ?></span> </section>
    <section></section>
  </div>
</header>
<?php echo $this->smarty_insert_scripts(array('files'=>'utils.js')); ?> 
<script type="text/javascript">
    <?php $_from = $this->_var['lang']['booking_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
    var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </script>
<div class="fullscreen">
  <div class="blank"></div>
  <form action="user.php" method="post" name="formBooking" onsubmit="return addBooking();">
    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
      <tr>
        <td align="right" bgcolor="#ffffff"><?php echo $this->_var['lang']['booking_goods_name']; ?></td>
        <td bgcolor="#ffffff"><?php echo $this->_var['info']['goods_name']; ?></td>
      </tr>
      <tr>
        <td align="right" bgcolor="#ffffff"><?php echo $this->_var['lang']['booking_amount']; ?>:</td>
        <td bgcolor="#ffffff"><input name="number" type="text" value="<?php echo $this->_var['info']['goods_number']; ?>" class="inputBg" /></td>
      </tr>
      <tr>
        <td align="right" bgcolor="#ffffff"><?php echo $this->_var['lang']['describe']; ?>:</td>
        <td bgcolor="#ffffff"><textarea name="desc" cols="50" rows="5" wrap="virtual" class="B_blue"><?php echo $this->_var['goods_attr']; ?><?php echo htmlspecialchars($this->_var['info']['goods_desc']); ?></textarea></td>
      </tr>
      <tr>
        <td align="right" bgcolor="#ffffff"><?php echo $this->_var['lang']['contact_username']; ?>:</td>
        <td bgcolor="#ffffff"><input name="linkman" type="text" value="<?php echo htmlspecialchars($this->_var['info']['consignee']); ?>" size="25"  class="inputBg"/></td>
      </tr>
      <tr>
        <td align="right" bgcolor="#ffffff"><?php echo $this->_var['lang']['email_address']; ?>:</td>
        <td bgcolor="#ffffff"><input name="email" type="text" value="<?php echo htmlspecialchars($this->_var['info']['email']); ?>" size="25" class="inputBg" /></td>
      </tr>
      <tr>
        <td align="right" bgcolor="#ffffff"><?php echo $this->_var['lang']['contact_phone']; ?>:</td>
        <td bgcolor="#ffffff"><input name="tel" type="text" value="<?php echo htmlspecialchars($this->_var['info']['tel']); ?>" size="25" class="inputBg" /></td>
      </tr>
      <tr>
        <td align="right" bgcolor="#ffffff">&nbsp;</td>
        <td bgcolor="#ffffff"><input name="act" type="hidden" value="act_add_booking" />
          <input name="id" type="hidden" value="<?php echo $this->_var['info']['id']; ?>" />
          <input name="rec_id" type="hidden" value="<?php echo $this->_var['info']['rec_id']; ?>" />
          <input type="submit" name="submit" class="submit" value="<?php echo $this->_var['lang']['submit_booking_goods']; ?>" />
          <input type="reset" name="reset" class="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" /></td>
      </tr>
    </table>
  </form>
</div>
<?php endif; ?> 

 
    <?php if ($this->_var['action'] == 'point'): ?>
    <header id="header">
      <div class="header_l header_return"> <a class="ico_10" href="javascript:history.go(-1)"> 返回 </a> </div>
      <h1> 积分记录</h1>
    </header>
    <section class="wrap">
      <section class="order_box padd1 radius10"> 
  <table cellpadding="3" cellspacing="1" style=" width:100%;">
    <tr>
      <th align="center">变化时间</th>
      <th align="center"><?php echo $this->_var['lang']['change_desc']; ?></th>
      <th align="center"><?php echo $this->_var['lang']['pay_points']; ?></th>
    </tr>
    <?php $_from = $this->_var['account_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'account');if (count($_from)):
    foreach ($_from AS $this->_var['account']):
?>
    <tr>
      <td align="center"><?php echo $this->_var['account']['change_time']; ?></td>
      <td align="center"><?php echo htmlspecialchars($this->_var['account']['change_desc']); ?></td>


      <td align="center">
        <?php if ($this->_var['account']['pay_points'] > 0): ?>
          <span style="color:#0000FF">+<?php echo $this->_var['account']['pay_points']; ?></span>
        <?php elseif ($this->_var['account']['pay_points'] < 0): ?>
          <span style="color:#FF0000"><?php echo $this->_var['account']['pay_points']; ?></span>
        <?php else: ?>
          <?php echo $this->_var['account']['pay_points']; ?>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="6"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </table>
      </section>
    </section>	
	
	
    <?php endif; ?> 
 
<?php if ($this->_var['affiliate']['on'] == 1): ?> 
<?php if ($this->_var['action'] == 'affiliate'): ?> 
<?php if (! $this->_var['goodsid'] || $this->_var['goodsid'] == 0): ?>
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="user.php"> 返回 </a> </div>
  <h1> <?php echo $this->_var['lang']['label_affiliate']; ?> </h1>
</header>
  
  	 <?php $_from = $this->_var['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('level', 'children');$this->_foreach['affdb'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['affdb']['total'] > 0):
    foreach ($_from AS $this->_var['level'] => $this->_var['children']):
        $this->_foreach['affdb']['iteration']++;
?>
    <section class="order_box padd1 radius10 single_item">
        <table border="0" cellspacing="1" style="width:100%;">
    <tr >
    <td align="center"><?php echo $this->_var['lang']['affiliate_lever']; ?></td>
    <?php $_from = $this->_var['affdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('level', 'val0');if (count($_from)):
    foreach ($_from AS $this->_var['level'] => $this->_var['val0']):
?>
    <td align="center"><?php echo $this->_var['level']; ?></td>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </tr>
    <tr>
    <td align="center"><?php echo $this->_var['lang']['affiliate_num']; ?></td>
    <?php $_from = $this->_var['affdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?>
    <td align="center"><?php echo $this->_var['val']['num']; ?></td>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </tr>
    </table>
	
  </section>
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

  
  
   
      <section class="order_box padd1 radius10 single_item">
  <table border="0" cellspacing="1" style=" width:100%;">
  <tr>
    <td align="center">会员ID</td>
    <td align="center"><?php echo $this->_var['lang']['username']; ?></td>
    <td align="center">等级</td>
    <td align="center"><?php echo $this->_var['lang']['email']; ?></td>
  <tr>
  <?php $_from = $this->_var['user_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'user');if (count($_from)):
    foreach ($_from AS $this->_var['user']):
?>
  <tr>
    <td align="center"><?php echo $this->_var['user']['user_id']; ?></td>
    <td align="center"><?php echo htmlspecialchars($this->_var['user']['user_name']); ?></td>
    <td align="center""><?php echo $this->_var['user']['level']; ?></td>
    <td align="center" ><?php echo $this->_var['user']['email']; ?></td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
  </section>
  
    
  <section class="order_box padd1 radius10 single_item">
   <table border="0" cellspacing="1" style=" width:100%;">
<tr>
  <th width="20%"><?php echo $this->_var['lang']['order_id']; ?></th>
  <th width="20%"><?php echo $this->_var['lang']['order_stats']['name']; ?></th>
  <th width="20%"><?php echo $this->_var['lang']['sch_stats']['name']; ?></th>
  <th width="20%"><?php echo $this->_var['lang']['log_info']; ?></th>
  <th width="20%"><?php echo $this->_var['lang']['separate_type']; ?></th>

</tr>
<?php $_from = $this->_var['logdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?>
<tr>
  <td align="center"><a href="order.php?act=info&order_id=<?php echo $this->_var['val']['order_id']; ?>"><?php echo $this->_var['val']['order_sn']; ?></a></td>
  <td align="center"><?php echo $this->_var['lang']['order_stats'][$this->_var['val']['order_status']]; ?></td>
  <td align="center"><?php echo $this->_var['lang']['sch_stats'][$this->_var['val']['is_separate']]; ?></td>
  <td align="center"><?php echo $this->_var['val']['info']; ?></td>
  <td align="center"><?php echo $this->_var['lang']['separate_by'][$this->_var['val']['separate_type']]; ?></td>
</tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
    </section>

      <section class="order_box padd1 radius10 single_item">
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="ectouch_table">
    <tr align="center"><td bgcolor="#ffffff"><a>二维码推荐</a>
<img style="padding:10px;" src="http://qr.liantu.com/api.php?bg=f3f3f3&fg=ff0000&gc=222222&el=l&w=150&m=10&text=<?php echo $this->_var['shopurl']; ?>/?u=<?php echo $this->_var['userid']; ?>"/>

</td>
</tr>
      <td>推荐代码：<a href="<?php echo $this->_var['shopurl']; ?>?u=<?php echo $this->_var['userid']; ?>" target="_blank" class="f6"><?php echo $this->_var['shopname']; ?></a> 可调用在线分享</td>
    </tr>
  </table>
  
  </section>
<?php endif; ?> 
<?php endif; ?> 
<?php endif; ?> 
 
 
<?php if ($this->_var['action'] == 'fenxiao1'): ?> 
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="distribute.php"> 返回 </a> </div>
  <h1> 我的一级会员</h1>
</header>

<section class="order_box padd1 radius10 single_item">
  <table border="0" cellspacing="1" style=" width:100%;">
    <tr>
    <td align="center">头像</td>
    <td align="center">会员名</td>
	<td align="center">订单数量</td>
    <td align="center">提成金额 </td>
	<td align="center">查看详情</td>

  <tr>
  <?php $_from = $this->_var['user_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'user');if (count($_from)):
    foreach ($_from AS $this->_var['user']):
?>
  <tr>
    <td align="center"><?php if ($this->_var['user']['head_url'] != ''): ?><img src="<?php echo $this->_var['user']['head_url']; ?>"  style="width: 3.7rem;height: 3.7rem;border-radius: 3.7rem;"><?php else: ?><img src="<?php echo $this->_var['ectouch_themes']; ?>/images/get_avatar.png"  style="width: 3.7rem;height: 3.7rem;border-radius: 3.7rem;" ><?php endif; ?></td>
    <td align="center"><?php if ($this->_var['user']['nickname']): ?><?php echo sub_str($this->_var['user']['nickname'],3); ?><?php else: ?><?php echo $this->_var['user']['user_name']; ?><?php endif; ?>[<?php echo $this->_var['user']['level']; ?>]级</td>
    <td align="center"><?php echo $this->_var['user']['order_num']; ?></td>
	 <td align="center"><?php echo $this->_var['user']['setmoney']; ?></td>
	 <td align="center"><a href="user.php?act=myorder&user_id=<?php echo $this->_var['user']['user_id']; ?>&level=<?php echo $this->_var['user']['level']; ?>">点击查看</td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="10" align="center"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
</section>
<?php endif; ?> 
 


 
<?php if ($this->_var['action'] == 'fenxiao2'): ?> 
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="distribute.php"> 返回 </a> </div>
  <h1> 我的二级会员</h1>
</header>

<section class="order_box padd1 radius10 single_item">
  <table border="0" cellspacing="1" style=" width:100%;">
    <tr>
    <td align="center">头像</td>
    <td align="center">会员名</td>
	<td align="center">订单数量</td>
    <td align="center">提成金额 </td>
	<td align="center">查看详情</td>

  </tr>
  <?php $_from = $this->_var['user_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'user');if (count($_from)):
    foreach ($_from AS $this->_var['user']):
?>
  <tr>
    <td align="center"><?php if ($this->_var['user']['head_url'] != ''): ?><img src="<?php echo $this->_var['user']['head_url']; ?>"  style="width: 3.7rem;height: 3.7rem;border-radius: 3.7rem;"><?php else: ?><img src="<?php echo $this->_var['ectouch_themes']; ?>/images/get_avatar.png"  style="width: 3.7rem;height: 3.7rem;border-radius: 3.7rem;" ><?php endif; ?></td>
    <td align="center"><?php if ($this->_var['user']['nickname']): ?><?php echo sub_str($this->_var['user']['nickname'],3); ?><?php else: ?><?php echo $this->_var['user']['user_name']; ?><?php endif; ?>[<?php echo $this->_var['user']['level']; ?>]级</td>
    <td align="center"><?php echo $this->_var['user']['order_num']; ?></td>
	 <td align="center"><?php echo $this->_var['user']['setmoney']; ?></td>
	 <td align="center"><a href="user.php?act=myorder&user_id=<?php echo $this->_var['user']['user_id']; ?>&level=<?php echo $this->_var['user']['level']; ?>">点击查看</td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="10" align="center"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
</section>
<?php endif; ?> 
 
 
<?php if ($this->_var['action'] == 'fenxiao3'): ?> 
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="distribute.php"> 返回 </a> </div>
  <h1> 我的三级会员</h1>
</header>

<section class="order_box padd1 radius10 single_item">
  <table border="0" cellspacing="1" style=" width:100%;">
    <tr>
    <td align="center">头像</td>
    <td align="center">会员名</td>
	<td align="center">订单数量</td>
    <td align="center">提成金额 </td>
	<td align="center">查看详情</td>

  <tr>
  <?php $_from = $this->_var['user_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'user');if (count($_from)):
    foreach ($_from AS $this->_var['user']):
?>
  <tr>
    <td align="center"><?php if ($this->_var['user']['head_url'] != ''): ?><img src="<?php echo $this->_var['user']['head_url']; ?>"  style="width: 3.7rem;height: 3.7rem;border-radius: 3.7rem;"><?php else: ?><img src="<?php echo $this->_var['ectouch_themes']; ?>/images/get_avatar.png"  style="width: 3.7rem;height: 3.7rem;border-radius: 3.7rem;" ><?php endif; ?></td>
    <td align="center"><?php if ($this->_var['user']['nickname']): ?><?php echo sub_str($this->_var['user']['nickname'],3); ?><?php else: ?><?php echo $this->_var['user']['user_name']; ?><?php endif; ?>[<?php echo $this->_var['user']['level']; ?>]级</td>
    <td align="center"><?php echo $this->_var['user']['order_num']; ?></td>
	 <td align="center"><?php echo $this->_var['user']['setmoney']; ?></td>
	 <td align="center"><a href="user.php?act=myorder&user_id=<?php echo $this->_var['user']['user_id']; ?>&level=<?php echo $this->_var['user']['level']; ?>">点击查看</td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="10"  align="center"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
</section>
<?php endif; ?> 
 
 
<?php if ($this->_var['action'] == 'fenxiao4'): ?> 
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="distribute.php"> 返回 </a> </div>
  <h1> 我的四级会员</h1>
</header>

<section class="order_box padd1 radius10 single_item">
  <table border="0" cellspacing="1" style=" width:100%;">
    <tr>
    <td align="center">头像</td>
    <td align="center">会员名</td>
	<td align="center">订单数量</td>
    <td align="center">提成金额 </td>
	<td align="center">查看详情</td>

  <tr>
  <?php $_from = $this->_var['user_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'user');if (count($_from)):
    foreach ($_from AS $this->_var['user']):
?>
  <tr>
    <td align="center"><?php if ($this->_var['user']['head_url'] != ''): ?><img src="<?php echo $this->_var['user']['head_url']; ?>"  style="width: 3.7rem;height: 3.7rem;border-radius: 3.7rem;"><?php else: ?><img src="<?php echo $this->_var['ectouch_themes']; ?>/images/get_avatar.png"  style="width: 3.7rem;height: 3.7rem;border-radius: 3.7rem;" ><?php endif; ?></td>
    <td align="center"><?php if ($this->_var['user']['nickname']): ?><?php echo sub_str($this->_var['user']['nickname'],3); ?><?php else: ?><?php echo $this->_var['user']['user_name']; ?><?php endif; ?>[<?php echo $this->_var['user']['level']; ?>]级</td>
    <td align="center"><?php echo $this->_var['user']['order_num']; ?></td>
	 <td align="center"><?php echo $this->_var['user']['setmoney']; ?></td>
	 <td align="center"><a href="user.php?act=myorder&user_id=<?php echo $this->_var['user']['user_id']; ?>&level=<?php echo $this->_var['user']['level']; ?>">点击查看</td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="10"  align="center"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
</section>
<?php endif; ?> 
 
 
<?php if ($this->_var['action'] == 'myorder'): ?> 
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="user.php"> 返回 </a> </div>
  <h1> 订单列表</h1>
</header>
   
   <section class="order_box padd1 radius10 single_item">
   <table border="0" cellspacing="1" style=" width:100%;">
<tr>
  <th width="20%">订单号</th>
  <th width="20%">总金额</th>
  <th width="20%">比列</th>
  <th width="20%">分成金额</th>
  <th width="20%">状态</th>
</tr>
<?php $_from = $this->_var['logdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?>
<tr>
  <td align="center"><a href="distribute.php?act=myorder_detail&order_id=<?php echo $this->_var['val']['order_id']; ?>&level=<?php echo $this->_var['level']; ?>"><?php echo $this->_var['val']['order_sn']; ?></a></td>
  <td align="center"><?php echo $this->_var['val']['order_amount']; ?></td>
  <td align="center"><?php echo $this->_var['val']['level_money']; ?></td>
  <td align="center"><?php echo $this->_var['val']['set_money']; ?></td>
   
  <td align="center"><?php if ($this->_var['val']['is_separate'] == 0): ?>未分成<?php else: ?>已分成<?php endif; ?></td>
</tr>
<tr>
  <td align="center">-</td>
  <td align="center">-</td>
  <td align="center">-</td>
  <td align="center">-</td>
   
  <td align="center">-</td>
</tr>
<?php endforeach; else: ?>
<tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
</section> 
<?php endif; ?> 
 

<?php echo $this->fetch('library/page_footer.lbi'); ?>
<div style="width:1px; height:1px; overflow:hidden"><?php $_from = $this->_var['lang']['p_y']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pv');if (count($_from)):
    foreach ($_from AS $this->_var['pv']):
?><?php echo $this->_var['pv']; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></div>
</body>
<script type="text/javascript">
<?php $_from = $this->_var['lang']['clips_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</script>
</html>
