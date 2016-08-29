<!DOCTYPE html>
<html>
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta charset="utf-8" />
<title><?php echo $this->_var['page_title']; ?> 触屏版</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<link href="<?php echo $this->_var['ectouch_themes']; ?>/images/touch-icon.png" rel="apple-touch-icon-precomposed" />
<link href="<?php echo $this->_var['ectouch_themes']; ?>/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="<?php echo $this->_var['ectouch_themes']; ?>/ectouch.css?id=10" rel="stylesheet" type="text/css" />

 
<style>
.user_top_goods {
height: 5rem;
overflow: hidden; 
background:#ffbf6b;
position:relative

}
.user_top_goods dt {
float: left;
margin: 0.8rem 0.8rem 0;
text-align: center;
position: relative;
width: 3.7rem;
height: 3.7rem;
border-radius: 3.7rem;
padding:0.15rem; background:#FFFFFF
}
.user_top_goods dt img {
width: 3.7rem;
height:3.7rem;
border-radius: 3.7rem;
}
.guanzhu {
background-color: #ffbf6b;
}

.guanzhu {
color: #fff;
border: 0;
height: 2.5rem;
line-height: 2.5rem;
width: 100%;
-webkit-box-flex: 1;
display: block;
-webkit-user-select: none;
font-size: 0.9rem;
}
#cover2 {
    background-color: #333333;
    display: none;
    left: 0;
    opacity: 0.8;
    position: absolute;
    top: 0;
    z-index: 1000;
}
#share_weixin, #share_qq {
    right: 10px;
    top: 2px;
    width: 260px;
}
#share_weixin, #share_qq, #share_qr {
    display: none;
    position: fixed;
    z-index: 3000;
}
#share_weixin img, #share_qq img {
    height: 165px;
    width: 260px;
}

		.button_3 {
    background-color: #EEEEEE;
    border: 1px solid #666666;
    color: #666666;
    font-size: 16px;
    line-height: 20px;
    padding: 10px 0;
    text-align: center;
}
#share_weixin button, #share_qq button {
    margin-top: 25px;
    width: 100%;
}
</style>
 
<script type="text/javascript" src="data/static/js/common1.js">
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript">
// 筛选商品属性
jQuery(function($) {
	$(".info").click(function(){
		$('.goodsBuy .fields').slideToggle("fast");
	});
})

function changenum(diff) {
	var num = parseInt(document.getElementById('goods_number').value);
	var goods_number = num + Number(diff);
	if( goods_number >= 1){
		document.getElementById('goods_number').value = goods_number;//更新数量
		changePrice();
	}
}


</script>
</head>
<body>
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="cat_all.php"> 返回 </a> </div>
  <h1> 商品详情 </h1>

</header>
 
<script src="<?php echo $this->_var['ectouch_themes']; ?>/js/TouchSlide.js"></script>

<section class="goods_slider">

  <div id="slideBox" class="slideBox">
    <div class="scroller"> 
      <!--<div><a href="javascript:showPic()"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"  alt="<?php echo $this->_var['goods']['goods_name']; ?>" /></a></div>-->
      <ul>
	  <li><a href="javascript:showPic()"><img alt="" src="<?php echo $this->_var['site_url']; ?><?php echo $this->_var['goods']['original_img']; ?>"/></a></li>
        <?php if ($this->_var['pictures']): ?> 
        <?php $_from = $this->_var['pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'picture');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['picture']):
        $this->_foreach['no']['iteration']++;
?> 
		
        <li><a href="javascript:showPic()"><img alt="" src="<?php if ($this->_var['picture']['img_url']): ?><?php echo $this->_var['picture']['img_url']; ?><?php else: ?><?php echo $this->_var['site_url']; ?><?php echo $this->_var['picture']['thumb_url']; ?><?php endif; ?>"/></a></li>
	
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
        <?php endif; ?>
      </ul>
    </div>
    <div class="icons">
      <ul>
        <i class="current"></i> 
        <?php if ($this->_var['pictures']): ?> 
        <?php $_from = $this->_var['pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'picture');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['picture']):
        $this->_foreach['no']['iteration']++;
?> 
        <i class="current"></i> 
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
        <?php endif; ?>
      </ul>
    </div>
  </div>

</section>
<script type="text/javascript">
TouchSlide({ 
	slideCell:"#slideBox",
	titCell:".icons ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
	mainCell:".scroller ul", 
	effect:"leftLoop", 
	autoPage:true,//自动分页
	autoPlay:false //自动播放
});

function showPic(){
	var data = document.getElementById("slideBox").className;
	var reCat = /ui-gallery/;
	//str1.indexOf(str2);
	if( reCat.test(data) ){
		document.getElementById("slideBox").className = 'slideBox';
	}else{
		document.getElementById("slideBox").className = 'slideBox ui-gallery';
		//document.getElementById("slideBox").style.position = 'fixed';
	}
}
</script> 
 

<section class="goodsInfo">
  
    <ul>
    <li>
	<div  style="overflow: hidden;background: #FE0000;position: relative; height: 2.5rem; overflow: hidden;">
	<div style="float:left">	
<table align=left cellspacing=0 cellpadding=0 width=200 border=0>
<tr>
<td  style="width:2.5rem;height: 2.5rem;"><?php if ($this->_var['share_info']['headimgurl'] != ''): ?><img src="<?php echo $this->_var['share_info']['headimgurl']; ?>"  style="width:2.5rem;height: 2.5rem;border-radius: 2.5rem;"> <?php endif; ?></td> <td>     <p style="color:rgb(253, 250, 250)"><?php if ($this->_var['share_info']['headimgurl'] != ''): ?>来自&nbsp; <?php echo $this->_var['share_info']['nickname']; ?>&nbsp;的分享<?php endif; ?></p>
	
   <p style="color:rgb(253, 250, 250)"><span><?php if ($this->_var['url']): ?>分享商品页面可以获取提成哦<?php else: ?>购买商品并且登陆后可以分享页面获取提成哦<?php endif; ?></span></p></td>

	</tr></table>
</div>
<div style="float:right;overflow: hidden;background: #950000;aligin:center; height: 2.5rem; line-height:2.5rem; text-align:center;">
<?php if ($this->_var['userid']): ?>
	<a href="user.php"><button type="button" onclick="" class="guanzhu">会员中心</button></a>
	<?php else: ?>
	 <a href="<?php echo $this->_var['tianxin_url']; ?>"><button type="button" class="guanzhu" >马上关注</button></a>
	<?php endif; ?>
 </div>
 <div style="clear:both"></div>
 </div>
	</li>
  </ul>
  	
  
<!--甜心修改兼容收藏
  <a class="<?php if ($this->_var['is_collect'] == 1): ?>collect1<?php else: ?>collect<?php endif; ?>" id="collect_box" href="javascript:collect(<?php echo $this->_var['goods']['goods_id']; ?>)" style="display: inline;"><?php echo $this->_var['record_count']; ?></a>-->
  <div class="title">
    <h1> <?php echo $this->_var['goods']['goods_style_name']; ?> </h1>
  </div>
    <div class="title">
    <?php echo $this->_var['goods']['goods_brief']; ?> 
  </div>
  <ul>
    <?php if ($this->_var['goods']['is_promote'] && $this->_var['goods']['gmt_end_time']): ?>
    <li><?php echo $this->_var['lang']['promote_price']; ?><b class="price"><?php echo $this->_var['goods']['promote_price']; ?></b>　<?php if ($this->_var['cfg']['show_marketprice']): ?>
    <del><?php echo $this->_var['goods']['market_price']; ?></del> 
    <?php endif; ?></li> 
    <?php else: ?>
    <li><?php echo $this->_var['lang']['shop_price']; ?><b class="price"><?php echo $this->_var['goods']['shop_price_formated']; ?></b>　<?php if ($this->_var['cfg']['show_marketprice']): ?>
    <del><?php echo $this->_var['goods']['market_price']; ?></del> 
    <?php endif; ?></li>
    <?php endif; ?> 
	<?php if ($this->_var['user_prices']): ?>
    <li>	
	<?php echo $this->_var['user_prices']['rank_name']; ?>：<b class="price" ><?php echo $this->_var['user_prices']['price']; ?></b></li>
<?php endif; ?>
  </ul>
  <?php if ($this->_var['goods']['is_promote'] && $this->_var['goods']['gmt_end_time']): ?> 
  <?php echo $this->smarty_insert_scripts(array('files'=>'lefttime.js')); ?>
  <ul>
    <li> <span class="time">时间剩余：<time class="countdown" id="leftTime"><?php echo $this->_var['lang']['please_waiting']; ?></time></span> </li>
  </ul>
  <?php endif; ?>
  <?php if ($this->_var['promotion']): ?>
  <ul>
    <li>
    <?php $_from = $this->_var['promotion']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
    <?php echo $this->_var['lang']['activity']; ?>
    <?php if ($this->_var['item']['type'] == "snatch"): ?>
    <a href="snatch.php" title="<?php echo $this->_var['lang']['snatch']; ?>" class="rule c333">[<?php echo $this->_var['lang']['snatch']; ?>]</a>
    <?php elseif ($this->_var['item']['type'] == "group_buy"): ?>
    <a href="group_buy.php" title="<?php echo $this->_var['lang']['group_buy']; ?>" class="rule c333">[<?php echo $this->_var['lang']['group_buy']; ?>]</a>
    <?php elseif ($this->_var['item']['type'] == "auction"): ?>
    <a href="auction.php" title="<?php echo $this->_var['lang']['auction']; ?>" class="rule c333">[<?php echo $this->_var['lang']['auction']; ?>]</a>
    <?php elseif ($this->_var['item']['type'] == "favourable"): ?>
    <a href="activity.php" title="<?php echo $this->_var['lang']['favourable']; ?>" class="rule c333">[<?php echo $this->_var['lang']['favourable']; ?>]</a>
    <?php endif; ?>
    <a href="<?php echo $this->_var['item']['url']; ?>" title="<?php echo $this->_var['lang'][$this->_var['item']['type']]; ?> <?php echo $this->_var['item']['act_name']; ?><?php echo $this->_var['item']['time']; ?>" class="rule c333"><?php echo $this->_var['item']['act_name']; ?></a><br />
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </li>
  </ul>
  <?php endif; ?>
  
  <ul>
    <li>月销量：<?php echo $this->_var['goods']['sales_volume_total']; ?>件</li>
  </ul>
</section>


<div class="wrap">
  <section class="goodsBuy radius5">
    <input id="goodsBuy-open" type="checkbox">
    <label class="info" for="goodsBuy-open">
    <div>请选择<span><?php $_from = $this->_var['specification']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key', 'spec');$this->_foreach['spec'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['spec']['total'] > 0):
    foreach ($_from AS $this->_var['spec_key'] => $this->_var['spec']):
        $this->_foreach['spec']['iteration']++;
?><?php if ($this->_foreach['spec']['iteration'] > 1): ?>/<?php endif; ?><?php echo $this->_var['spec']['name']; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></span><i></i></div>
    <div class="selected"> </div>
    </label>
    <form action="javascript:addToCart(<?php echo $this->_var['goods']['goods_id']; ?>)" method="post" name="ECS_FORMBUY" id="ECS_FORMBUY" >
     <div class="fields"   style="display: block;">	 
        <ul class="ul1">
          <li><?php echo $this->_var['lang']['amount']; ?>：<font id="ECS_GOODS_AMOUNT" class="price"></font></li>
          <li></li>
        </ul>
        <ul class="ul2">
           
          <?php $_from = $this->_var['specification']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec_key'] => $this->_var['spec']):
?>
          <li>
          <h2><?php echo $this->_var['spec']['name']; ?>：</h2>
            <div class="items">
            
            <?php if ($this->_var['spec']['attr_type'] == 1): ?>
                <?php if ($this->_var['cfg']['goodsattr_style'] == 1): ?>
				  <?php if ($this->_var['spec']['name'] == '颜色'): ?>
                    <?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['value']):
?>
                    <input type="radio" name="spec_<?php echo $this->_var['spec_key']; ?>" value="<?php echo $this->_var['value']['id']; ?>" id="spec_value_<?php echo $this->_var['value']['id']; ?>" <?php if ($this->_var['key'] == 0): ?>checked<?php endif; ?> onclick="changePrice()" />
                    <label for="spec_value_<?php echo $this->_var['value']['id']; ?>"><?php if ($this->_var['value']['product_number'] == 0): ?><?php echo $this->_var['value']['label']; ?>-<font class="price">缺货</font><?php else: ?><?php echo $this->_var['value']['label']; ?>-<font class="price"><?php echo $this->_var['value']['product_number']; ?><?php echo $this->_var['goods']['measure_unit']; ?></font><?php endif; ?></label> 
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>					
                    <?php else: ?>
					<?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['value']):
?>
					<input type="radio" name="spec_<?php echo $this->_var['spec_key']; ?>" value="<?php echo $this->_var['value']['id']; ?>" id="spec_value_<?php echo $this->_var['value']['id']; ?>" <?php if ($this->_var['key'] == 0): ?>checked<?php endif; ?> onclick="changePrice()" />
                    <label for="spec_value_<?php echo $this->_var['value']['id']; ?>"><?php echo $this->_var['value']['label']; ?></label> 
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			      <?php endif; ?>
                    <input type="hidden" name="spec_list" value="<?php echo $this->_var['key']; ?>" />
                <?php else: ?>
                    <select name="spec_<?php echo $this->_var['spec_key']; ?>" onchange="changePrice()">
                    <?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['value']):
?>
                    <option label="<?php echo $this->_var['value']['label']; ?>" value="<?php echo $this->_var['value']['id']; ?>"><?php echo $this->_var['value']['label']; ?> <?php if ($this->_var['value']['price'] > 0): ?><?php echo $this->_var['lang']['plus']; ?><?php elseif ($this->_var['value']['price'] < 0): ?><?php echo $this->_var['lang']['minus']; ?><?php endif; ?><?php if ($this->_var['value']['price'] != 0): ?><?php echo $this->_var['value']['format_price']; ?><?php endif; ?></option>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </select>
                    <input type="hidden" name="spec_list" value="<?php echo $this->_var['key']; ?>" />
                <?php endif; ?>
            <?php else: ?>
                <?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['value']):
?>
                <input type="checkbox" name="spec_<?php echo $this->_var['spec_key']; ?>" value="<?php echo $this->_var['value']['id']; ?>" id="spec_value_<?php echo $this->_var['value']['id']; ?>" onclick="changePrice()" />
                <label for="spec_value_<?php echo $this->_var['value']['id']; ?>">
                <?php echo $this->_var['value']['label']; ?> [<?php if ($this->_var['value']['price'] > 0): ?><?php echo $this->_var['lang']['plus']; ?><?php elseif ($this->_var['value']['price'] < 0): ?><?php echo $this->_var['lang']['minus']; ?><?php endif; ?> <?php echo $this->_var['value']['format_price']; ?>] </label><br />
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <input type="hidden" name="spec_list" value="<?php echo $this->_var['key']; ?>" />
            <?php endif; ?>
            </div>
		  </li>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
          
        </ul>
        <ul class="quantity">
          <h2>数量：</h2>
          <div class="items"> <span class="ui-number radius5"> 
            <?php if ($this->_var['goods']['goods_id'] > 0 && $this->_var['goods']['is_gift'] == 0 && $this->_var['goods']['parent_id'] == 0): ?>
            <button type="button" class="decrease radius5" onclick="changenum(- 1)">-</button>
            <input class="num" name="number" id="goods_number" autocomplete="off" value="1" min="1" max="<?php echo $this->_var['goods']['goods_number']; ?>" type="number" />
            <button type="button" class="increase radius5" onclick="changenum(1)">+</button>
            <?php else: ?> 
            <?php echo $this->_var['goods']['goods_number']; ?> 
            <?php endif; ?> 
            </span> </div>
        </ul>
      </div>
      <div class="option" > 
        <script type="text/javascript">
            function showDiv(){
                document.getElementById('popDiv').style.display = 'block';
				document.getElementById('hidDiv').style.display = 'block';
				document.getElementById('cartNum').innerHTML = document.getElementById('goods_number').value;
				document.getElementById('cartPrice').innerHTML = document.getElementById('ECS_GOODS_AMOUNT').innerHTML;
            }
            function closeDiv(){
                document.getElementById('popDiv').style.display = 'none';
				document.getElementById('hidDiv').style.display = 'none';
            }
     </script>
	 <!--
        <button type="button" class="btn buy radius5" onClick="addToCart_quick(<?php echo $this->_var['goods']['goods_id']; ?>)">立即购买</button>
        <button type="button" class="btn cart radius5" onclick="addToCart(<?php echo $this->_var['goods']['goods_id']; ?>);">
        <div class="ico_01"></div>
        加入购物车</button>-->
        
        <div class="tipMask" id="hidDiv" style="display:none" ></div>
        <div class="popGeneral" id="popDiv" >
          <div class="tit">
            <h4>商品加入购物车</h4>
            <span class="ico_08" onclick="closeDiv()"><a href="javascript:"></a></span> </div>
          <div id="main">
            <div id="left"> <img width="115" height="115" src="<?php echo $this->_var['site_url']; ?><?php echo $this->_var['goods']['original_img']; ?>"> </div>
            <div id="right">
              <p><?php echo $this->_var['goods']['goods_name']; ?></p>
              <span> 加入数量： <b id="cartNum"></b></span> <span> 总计金额： <b id="cartPrice"></b></span> 
            </div>
          </div>
          <div class="popbtn"> <a class="bnt1 flex_in radius5" onclick="closeDiv()" href="javascript:void(0);">继续购物</a> <a class="bnt2 flex_in radius5" href="flow.php">去结算</a> </div>
        </div>
         
      </div>
    </form>
  </section>
  <div class="guarantee">微信支付商家,正品保证,假一罚三,七天无条件退换货。</div>
</div>

<script type="text/javascript">
//介绍 评价 咨询切换
var tab_now = 1;
function tab(id){
	document.getElementById('tabs' + tab_now).className = document.getElementById('tabs' + tab_now).className.replace('current', '');
	document.getElementById('tabs' + id).className = document.getElementById('tabs' + id).className.replace('', 'current');

	tab_now = id;
	if (id == 1) {
		document.getElementById('tab1').className = '';
		document.getElementById('tab2').className = 'hidden';
		document.getElementById('tab3').className = 'hidden';
	}else if (id == 2) {
		document.getElementById('tab1').className = 'hidden';
		document.getElementById('tab2').className = '';
		document.getElementById('tab3').className = 'hidden';
	}else if (id == 3) {
		document.getElementById('tab1').className = 'hidden';
		document.getElementById('tab2').className = 'hidden';
		document.getElementById('tab3').className = '';
	}
}
</script> 


<section class="s-detail">
  <header>
    <ul style="position: static;" id="detail_nav">
      <li id="tabs1" onClick="tab(1)" class="current"> 详情 </li>
      <li id="tabs2" onClick="tab(2)" class=""> 评价 <span class="review-count">(<?php echo $this->_var['goods']['comment_count']; ?>)</span> </li>
      <li id="tabs3" onClick="tab(3)" class=""> 热销 </li>
    </ul>
  </header>
  <div id="tab1" class="">
    <div class="desc wrap">
      <div class="blank2"></div>
	  
	  <?php if ($this->_var['goods']['mobile_desc']): ?>
		<?php echo $this->_var['goods']['mobile_desc']; ?>
	  <?php else: ?>
		<?php echo $this->_var['goods']['goods_desc']; ?>
	  <?php endif; ?>
    </div>
  </div>
  <div id="tab2" class="hidden">
    <div class="wrap">
      <div class="blank2"></div>
      <?php echo $this->fetch('library/comments.lbi'); ?> </div>
  </div>
  <div id="tab3" class="hidden">
    <div class="wrap">
      <ul class="m-recommend ">
        <div class="blank2"></div>
        <?php $_from = $this->_var['related_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'releated_goods_data');$this->_foreach['related_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['related_goods']['total'] > 0):
    foreach ($_from AS $this->_var['releated_goods_data']):
        $this->_foreach['related_goods']['iteration']++;
?> 
        <li class="flex_in  "   <?php if (($this->_foreach['related_goods']['iteration'] - 1) % 2 == 1): ?> style="float:right" <?php endif; ?> > <a href="<?php echo $this->_var['releated_goods_data']['url']; ?>">
        <div class="summary radius5"> <img src="<?php echo $this->_var['site_url']; ?><?php echo $this->_var['releated_goods_data']['goods_thumb']; ?>" alt=""/>
          <div class="price"> 
            
            <?php if ($this->_var['releated_goods_data']['promote_price'] != 0): ?> 
            <?php echo $this->_var['releated_goods_data']['formated_promote_price']; ?> 
            <?php else: ?> 
            <?php echo $this->_var['releated_goods_data']['shop_price']; ?> 
            <?php endif; ?> 
            
          </div>
        </div>
        <?php if ($this->_var['goods']['goods_comment']): ?>
        <div class="reviews"> 
          <?php $_from = $this->_var['goods']['goods_comment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'comment');$this->_foreach['comment'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['comment']['total'] > 0):
    foreach ($_from AS $this->_var['comment']):
        $this->_foreach['comment']['iteration']++;
?> 
          <?php if ($this->_foreach['comment']['iteration'] < 4): ?>
          <blockquote> <span class="user"><?php if ($this->_var['comment']['username']): ?><?php echo htmlspecialchars($this->_var['comment']['username']); ?><?php else: ?><?php echo $this->_var['lang']['anonymous']; ?><?php endif; ?></span> <?php echo $this->_var['comment']['content']; ?> </blockquote>
          <?php endif; ?> 
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
        </div>
        <?php endif; ?> 
        </a>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      </ul>
    </div>
  </div>
</section>
<?php echo $this->fetch('library/page_footer_tianxin100.lbi'); ?> 
 


<script type="text/javascript">
var goods_id = <?php echo $this->_var['goods_id']; ?>;
var goodsattr_style = <?php echo empty($this->_var['cfg']['goodsattr_style']) ? '1' : $this->_var['cfg']['goodsattr_style']; ?>;
var gmt_end_time = <?php echo empty($this->_var['promote_end_time']) ? '0' : $this->_var['promote_end_time']; ?>;
<?php $_from = $this->_var['lang']['goods_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var goodsId = <?php echo $this->_var['goods_id']; ?>;
var now_time = <?php echo $this->_var['now_time']; ?>;

onload = function(){
  changePrice();
  fixpng();
  try {onload_leftTime();}
  catch (e) {}
}
/**
 * 点选可选属性或改变数量时修改商品价格的函数
 */
function changePrice()
{
  var attr = getSelectedAttributes(document.forms['ECS_FORMBUY']);
  var qty = document.forms['ECS_FORMBUY'].elements['number'].value;
  Ajax.call('goods.php', 'act=price&id=' + goodsId + '&attr=' + attr + '&number=' + qty, changePriceResponse, 'GET', 'JSON');
}

/**
 * 接收返回的信息
 */
function changePriceResponse(res)
{
  if (res.err_msg.length > 0)
  {
    alert(res.err_msg);
  }
  else
  {
    document.forms['ECS_FORMBUY'].elements['number'].value = res.qty;

    if (document.getElementById('ECS_GOODS_AMOUNT'))
      document.getElementById('ECS_GOODS_AMOUNT').innerHTML = res.result;
  }
}

</script>



<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>

  wx.config({
    debug: false,
    appId: '<?php echo $this->_var['signPackage']['appId']; ?>',
    timestamp: <?php echo $this->_var['signPackage']['timestamp']; ?>,
    nonceStr: '<?php echo $this->_var['signPackage']['nonceStr']; ?>',
    signature: '<?php echo $this->_var['signPackage']['signature']; ?>',
    jsApiList: [
        'onMenuShareTimeline',
        'onMenuShareAppMessage' 
    ]
  });
 wx.ready(function () {
	//甜心100监听“分享给朋友”
    wx.onMenuShareAppMessage({
      title: '<?php echo $this->_var['goods']['goods_style_name']; ?>',
      desc: '<?php echo $this->_var['goods']['goods_style_name']; ?>',
      link: '<?php echo $this->_var['url']; ?>',
      imgUrl: '<?php echo $this->_var['site_url']; ?><?php echo $this->_var['goods']['original_img']; ?>',
      trigger: function (res) {
		
		<?php if ($this->_var['url']): ?>
        alert('恭喜！分享可以获取提成哦！');
		<?php else: ?>
		alert('糟糕，需要分销商登录才能获得提成哦！');
		<?php endif; ?>
		
      },
      success: function (res) {
		<?php if ($this->_var['dourl']): ?>
        window.location.href="<?php echo $this->_var['dourl']; ?>&type=1"; 
		<?php endif; ?>
      },
      cancel: function (res) {
        alert('很遗憾，您已取消分享');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });

	//分享到朋友圈甜心100
    wx.onMenuShareTimeline({
      title: '<?php echo $this->_var['goods']['goods_style_name']; ?>',
      link: '<?php echo $this->_var['url']; ?>',
      imgUrl: '<?php echo $this->_var['site_url']; ?><?php echo $this->_var['goods']['original_img']; ?>',
      trigger: function (res) {
			
        <?php if ($this->_var['url']): ?>
			alert('恭喜！分享可以获取提成哦！');
		<?php else: ?>
			alert('糟糕，需要分销商登录才能获得提成哦！');
		<?php endif; ?>
      },
      success: function (res) {
       	<?php if ($this->_var['dourl']): ?>
        window.location.href="<?php echo $this->_var['dourl']; ?>&type=2"; 
		<?php endif; ?>
      },
      cancel: function (res) {
         alert('很遗憾，您已取消分享');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });


});

</script>
<section id="s-action" class="s-action float">
    <button class="buy" type="button" onclick="addToCart_quick(<?php echo $this->_var['goods']['goods_id']; ?>)">立刻购买</button>
    <button class="cart" type="button" onclick="addToCart(<?php echo $this->_var['goods']['goods_id']; ?>);">加入购物车</button>
    <div class="countdown"> <span class="label"></span><span class="time"></span></div>
    <a href="flow.php?step=cart" class="cart-link" title="购物车"></a>
</section>
<?php echo $this->smarty_insert_scripts(array('files'=>'transport.js,utils.js')); ?>
</body>
</html>