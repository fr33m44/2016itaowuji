<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title><?php echo $this->_var['page_title']; ?></title>



<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />

<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,left_goodslist.js')); ?>
<script>
function changeAtt(t) {
t.lastChild.checked='checked';
for (var i = 0; i<t.parentNode.childNodes.length;i++) {
        if (t.parentNode.childNodes[i].className == 'cattsel') {
            t.parentNode.childNodes[i].className = '';
        }
    }
t.className = "cattsel";
changePrice();
}
</script>
<script type="text/javascript">
function $id(element) {
  return document.getElementById(element);
}
//切屏--是按钮，_v是内容平台，_h是内容库
function reg(str){
  var bt=$id(str+"_b").getElementsByTagName("h2");
  for(var i=0;i<bt.length;i++){
    bt[i].subj=str;
    bt[i].pai=i;
    bt[i].style.cursor="pointer";
    bt[i].onclick=function(){
      $id(this.subj+"_v").innerHTML=$id(this.subj+"_h").getElementsByTagName("blockquote")[this.pai].innerHTML;
      for(var j=0;j<$id(this.subj+"_b").getElementsByTagName("h2").length;j++){
        var _bt=$id(this.subj+"_b").getElementsByTagName("h2")[j];
        var ison=j==this.pai;
        _bt.className=(ison?"":"h2bg");
      }
    }
  }
  $id(str+"_h").className="none";
  $id(str+"_v").innerHTML=$id(str+"_h").getElementsByTagName("blockquote")[0].innerHTML;
}

</script>

</head>
<body>
<?php echo $this->fetch('library/page_header.lbi'); ?> <?php echo $this->smarty_insert_scripts(array('files'=>'magiczoom.js,miqi_goods.js,magiczoomplus.js')); ?> 
<script>
$(function(){
	/*放大镜缩略图左右切换效果*/
	$("#detail_img_slider").slide({
			mainCell:"#J_tabSlider ul",
			vis:5,
			prevCell:".pre_btn",
			nextCell:".next_btn",
			effect:"leftLoop"
	})
	/*放大镜缩略图点击选中效果*/
	$("#J_tabSlider ul li a").click(function(){
		$("#J_tabSlider ul li").removeClass("cur");
		$(this).parent().addClass("cur");
	})	
})
</script>

<div class="content_title">
  <div class="block">
    <div id="ur_here"> <?php echo $this->fetch('library/ur_here.lbi'); ?> </div>
  </div>
</div>

<div class="content_detailed">
  <div class="contentBody" >
    <div class="mainBody">
      <div id="goodsInfo" class="clearfix">
       <?php echo $this->fetch('library/goods_gallery.lbi'); ?> 
        
        <div class="textInfo">
          <form action="javascript:addToCart(<?php echo $this->_var['goods']['goods_id']; ?>)" method="post" name="ECS_FORMBUY" id="ECS_FORMBUY" >
            <div class="center_title"><a><?php echo $this->_var['goods']['goods_style_name']; ?></a></div>
            <div class="detail_center">
              <div class="center_txt">
                <div class="center_text"> 
                  <?php if ($this->_var['promotion']): ?>
                
                    <?php $_from = $this->_var['promotion']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?> 
                    <?php echo $this->_var['lang']['activity']; ?> 
                    <?php if ($this->_var['item']['type'] == "snatch"): ?> 
                    <a href="snatch.php" title="<?php echo $this->_var['lang']['snatch']; ?>" style="font-weight:100; color:#FF4560; text-decoration:none;">[<?php echo $this->_var['lang']['snatch']; ?>]</a><br/>  
                    <?php elseif ($this->_var['item']['type'] == "group_buy"): ?> 
                    <a href="group_buy.php" title="<?php echo $this->_var['lang']['group_buy']; ?>" style="font-weight:100; color:#FF4560; text-decoration:none;">[<?php echo $this->_var['lang']['group_buy']; ?>]</a> 
                    <?php elseif ($this->_var['item']['type'] == "auction"): ?> 
                  <a href="auction.php" title="<?php echo $this->_var['lang']['auction']; ?>" style="font-weight:100; color:#FF4560; text-decoration:none;">[<?php echo $this->_var['lang']['auction']; ?>]</a>  
                    <?php elseif ($this->_var['item']['type'] == "favourable"): ?> 
                    <a href="activity.php" title="<?php echo $this->_var['lang']['favourable']; ?>" style="font-weight:100; color:#FF4560; text-decoration:none;">[<?php echo $this->_var['lang']['favourable']; ?>]</a>
                    <?php endif; ?> 
                   <a href="<?php echo $this->_var['item']['url']; ?>" title="<?php echo $this->_var['lang'][$this->_var['item']['type']]; ?> <?php echo $this->_var['item']['act_name']; ?><?php echo $this->_var['item']['time']; ?>" style="font-weight:100; color:#FF4560;"><?php echo $this->_var['item']['act_name']; ?></a> <br/> 
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                 
                  <?php endif; ?> 
                  
                  <?php if ($this->_var['cfg']['show_goodssn']): ?>
                  <p><?php echo $this->_var['lang']['goods_sn']; ?><?php echo $this->_var['goods']['goods_sn']; ?> </p>
                  <?php endif; ?> 
                  
                  <?php if ($this->_var['goods']['goods_brand'] != "" && $this->_var['cfg']['show_brand']): ?>
                  <p> <?php echo $this->_var['lang']['goods_brand']; ?><a href="<?php echo $this->_var['goods']['goods_brand_url']; ?>" ><?php echo $this->_var['goods']['goods_brand']; ?></a> </p>
                  <?php endif; ?> 
                  
                  <?php if ($this->_var['cfg']['show_goodsweight']): ?>
                  <p> <?php echo $this->_var['lang']['goods_weight']; ?><?php echo $this->_var['goods']['goods_weight']; ?></p>
                  <?php endif; ?> 
                  
                  <?php if ($this->_var['cfg']['show_addtime']): ?>
                  <p><?php echo $this->_var['lang']['add_time']; ?><?php echo $this->_var['goods']['add_time']; ?></p>
                  <?php endif; ?> 
                  
                  
                  <p style="display:none" > <?php echo $this->_var['lang']['goods_click_count']; ?>：<?php echo $this->_var['goods']['click_count']; ?> </p>
                  <p> 
                    
                    <?php if ($this->_var['cfg']['show_marketprice']): ?> 
                    <?php echo $this->_var['lang']['market_price']; ?><?php echo $this->_var['goods']['market_price']; ?> 
                    <?php endif; ?> 
                  </p>
                  <b> 
                   
                  本店价格：<font  id="ECS_SHOPPRICE">￥<?php echo $this->_var['goods']['shop_price']; ?></font> </b>

                  <p> 
                    <?php if ($this->_var['theuser_rank'] == '实体店买家'): ?>
                    <?php $_from = $this->_var['rank_prices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'rank_price');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['rank_price']):
?> 
                    <?php echo $this->_var['rank_price']['rank_name']; ?>：<font  id="ECS_RANKPRICE_<?php echo $this->_var['key']; ?>"><?php echo $this->_var['rank_price']['price']; ?></font>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                    <?php else: ?>
                     <?php $_from = $this->_var['rank_prices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'rank_price');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['rank_price']):
?> 
                     <?php if ($this->_var['rank_price']['rank_name'] == '个人买家'): ?>
                      <?php echo $this->_var['rank_price']['rank_name']; ?>：<font  id="ECS_RANKPRICE_<?php echo $this->_var['key']; ?>"><?php echo $this->_var['rank_price']['price']; ?></font>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php endif; ?>
                  </p>
                  <p>节&nbsp;&nbsp;&nbsp;省：¥<?php echo $this->_var['goods']['jiesheng']; ?>（<?php echo $this->_var['goods']['zhekou']; ?>折）</p>
                  
                  <p>已售出： <span style="color: #4c4c4c;"><?php echo $this->_var['goods']['sales_volume']; ?>件</span></p>
                  <p style="display:none"> <?php echo $this->_var['lang']['goods_rank']; ?> <img src="themes/miqinew/images/stars<?php echo $this->_var['goods']['comment_rank']; ?>.gif" alt="comment rank <?php echo $this->_var['goods']['comment_rank']; ?>" /> </p>
                  
                  <?php if ($this->_var['volume_price_list']): ?>
                  <p> <font class="f1"><?php echo $this->_var['lang']['volume_price']; ?>：</font><br />
                  
                  <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#aad6ff">
                    <tr>
                      <td align="center" bgcolor="#FFFFFF"><strong><?php echo $this->_var['lang']['number_to']; ?></strong></td>
                      <td align="center" bgcolor="#FFFFFF"><strong><?php echo $this->_var['lang']['preferences_price']; ?></strong></td>
                    </tr>
                    <?php $_from = $this->_var['volume_price_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('price_key', 'price_list');if (count($_from)):
    foreach ($_from AS $this->_var['price_key'] => $this->_var['price_list']):
?>
                    <tr>
                      <td align="center" bgcolor="#FFFFFF" class="shop"><?php echo $this->_var['price_list']['number']; ?></td>
                      <td align="center" bgcolor="#FFFFFF" class="shop"><?php echo $this->_var['price_list']['format_price']; ?></td>
                    </tr>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                  </table>
     
                  </p>
                  <?php endif; ?> 
      			<div class="clearfix"></div>
                  <?php if ($this->_var['goods']['is_promote'] && $this->_var['goods']['gmt_end_time']): ?> 
                  <?php echo $this->smarty_insert_scripts(array('files'=>'lefttime.js')); ?>
                  <p > <?php echo $this->_var['lang']['promote_price']; ?><?php echo $this->_var['goods']['promote_price']; ?>
                    <?php echo $this->_var['lang']['residual_time']; ?> <font  id="leftTime"><?php echo $this->_var['lang']['please_waiting']; ?></font> </p>
                  <?php endif; ?>
                  <p style="display:none" > <?php echo $this->_var['lang']['amount']; ?>：<font id="ECS_GOODS_AMOUNT" class="shop"></font> </p>
                  <p style="display:none"> 
                    <?php if ($this->_var['goods']['give_integral'] > 0): ?> 
                    <?php echo $this->_var['lang']['goods_give_integral']; ?><font class="f4"><?php echo $this->_var['goods']['give_integral']; ?> <?php echo $this->_var['points_name']; ?></font> 
                    <?php endif; ?> 
                    
                  </p>
                  <?php if ($this->_var['goods']['bonus_money']): ?>
                  <p style="display:none"> <?php echo $this->_var['lang']['goods_bonus']; ?><font class="shop"><?php echo $this->_var['goods']['bonus_money']; ?></font><br />
                  </p>
                  <?php endif; ?> 
                </div>
              </div>
              
              
                     
                    
              <div class="center_txt clearfix"> 
                
                <?php if ($this->_var['cfg']['use_integral']): ?>
                <p style="display:none"><strong><?php echo $this->_var['lang']['goods_integral']; ?></strong><font class="f4"><?php echo $this->_var['goods']['integral']; ?> <?php echo $this->_var['points_name']; ?></font></p>
                <?php endif; ?> 
                
                <?php if ($this->_var['goods']['is_shipping']): ?>
                <p> <?php echo $this->_var['lang']['goods_free_shipping']; ?> </p>
                <?php endif; ?> 
                
                 
                <div class="center_add">
                  <div class="center_add_left"> <a onclick="buyNumber.minus()" href="javascript:;"><img src="themes/miqinew/images/jian.gif" /></a>
                    <input name="number" type="text" value="1" defaultnumber="1" onblur="changePrice()" id="product_num" />
					<a onclick="buyNumber.plus()" href="javascript:;"><img src="themes/miqinew/images/jia.gif" /></a></div>

                  <p><?php if ($this->_var['goods']['goods_number'] != "" && $this->_var['cfg']['show_goodsnumber']): ?> 
                    
                    <?php if ($this->_var['goods']['goods_number'] == 0): ?> 
                    <?php echo $this->_var['lang']['goods_number']; ?>
                    <?php echo $this->_var['lang']['stock_up']; ?> 
                    <?php else: ?> 
                    <?php echo $this->_var['lang']['goods_number']; ?>
                    <?php echo $this->_var['goods']['goods_number']; ?> <?php echo $this->_var['goods']['measure_unit']; ?> 
                    <?php endif; ?> 
                    
                    <?php endif; ?></p>
                </div>
                <script>
                       
                                // add by liuguichun 2011-7-19
                                var buyNumber = {
                                    maxNumber : 100,
                                    minNumber : 1,
                                    defaultNumber : function(){
                                        var defaultnumber = $('#product_num').attr('defaultnumber');
                                        defaultnumber = parseInt(defaultnumber)
                                        if(defaultnumber < 1){
                                            defaultnumber = 1;
                                        }
                                        return defaultnumber;
                                    },
                                                                                                                                    
                                    goodNumber : function(num){
                                        if(typeof(num) == 'number'){
                                            return $('#product_num').val(num);
                                        }else{
                                            return parseInt($('#product_num').val());
                                        }
                                                                                                                                        
                                    },
                                    plus : function(){
                                        var num = buyNumber.goodNumber() + buyNumber.defaultNumber();
                                        if(num <= buyNumber.maxNumber){
                                            buyNumber.goodNumber(num);
                                        }
                                    },
                                    minus : function(){
                                        var num = buyNumber.goodNumber() - buyNumber.defaultNumber();
                                        if(num >= buyNumber.minNumber){
                                            buyNumber.goodNumber(num);
                                        }
                                    }
                                                                                                                                    
                                }
                            </script>
                <div class="center_btn">
                  <div class="center_shop_btn"> <a href="javascript:addToCartShowDiv(<?php echo $this->_var['goods']['goods_id']; ?>)"></a> </div>
                  <div class="center_collect_btn"> <a href="javascript:collect(<?php echo $this->_var['goods']['goods_id']; ?>)">收藏该商品</a> </div>
                   
                  
                </div>
                <div class="center_pop" id="addtocartdialog" style="display:none;">
               <div class="center_pop_close"><a href="javascript:void(0)"></a></div>
              <div class="center_pop_txt">
    
              </div>
<div class="center_pop_btn">
    <a href="flow.php"></a>
</div>

                            
                        </div>
                
              </div>
              <div class="center_bottom">
				

<div class="bdsharebuttonbox" data-tag="share_1">
	<a class="bds_mshare" data-cmd="mshare"></a>
	<a class="bds_qzone" data-cmd="qzone" href="#"></a>
	<a class="bds_tsina" data-cmd="tsina"></a>
	<a class="bds_baidu" data-cmd="baidu"></a>
	<a class="bds_renren" data-cmd="renren"></a>
	<a class="bds_tqq" data-cmd="tqq"></a>
	<a class="bds_more" data-cmd="more">更多</a>
	<a class="bds_count" data-cmd="count"></a>
</div>
<script>
	window._bd_share_config = {
		common : {
			bdText : '<?php echo $this->_var['userurl']; ?>',	
			bdDesc : '<?php echo $this->_var['userurl']; ?>',	
			bdUrl : '<?php echo $this->_var['userurl']; ?>', 	
		},
		share : [{
			"bdSize" : 16
		}],
		slide : [{	   
			bdImg : 0,
			bdPos : "right",
			bdTop : 100
		}],
		image : [{
			viewType : 'list',
			viewPos : 'top',
			viewColor : 'black',
			viewSize : '16',
			viewList : ['qzone','tsina','huaban','tqq','renren']
		}],
		selectShare : [{
			"bdselectMiniList" : ['qzone','tqq','kaixin001','bdxc','tqf']
		}]
	}
	with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
</script>


					
					
					
              </div>
            </div>
			
            <div class="detail_logo">
              <div class="detail_logo_img"> <a target="_blank" href="<?php echo $this->_var['goods']['goods_brand_url']; ?>"><?php if ($this->_var['goods']['brand_logo']): ?><img src="data/brandlogo/<?php echo $this->_var['goods']['brand_logo']; ?>" border="0" width="180" height="100" alt="<?php echo $this->_var['goods']['goods_brand']; ?>"><?php else: ?><?php echo $this->_var['goods']['goods_brand']; ?><?php endif; ?></a> </div>
              <div class="detail_logo_txt">
                <p><?php echo $this->_var['goods']['goods_brand']; ?></p>
              </div>
              <div class="detail_logo_btn"> <a href="<?php echo $this->_var['goods']['goods_brand_url']; ?>" >进入品牌专柜</a> </div>
            </div>
          </form>
        </div>
      </div>
      <div class="blank"></div>
    </div>
  </div>
  <div class="blank5"></div>
  
  
  <div class="cart_table">
                  

                
                <table>
                <?php $_from = $this->_var['specification']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec_key'] => $this->_var['spec']):
?>
                    
                    
                    <?php if ($this->_var['spec_key'] == 227): ?>
                    <tr><td></td>
                    
                        <?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key1', 'spec1');$this->_foreach['spec1name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['spec1name']['total'] > 0):
    foreach ($_from AS $this->_var['spec_key1'] => $this->_var['spec1']):
        $this->_foreach['spec1name']['iteration']++;
?>
                            
                            <?php $_from = $this->_var['spec1']['label']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key2', 'spec2');$this->_foreach['spec2name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['spec2name']['total'] > 0):
    foreach ($_from AS $this->_var['spec_key2'] => $this->_var['spec2']):
        $this->_foreach['spec2name']['iteration']++;
?>
                                <td><?php echo $this->_var['spec2']; ?></td>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </tr>
                    <?php endif; ?>
                    
                    <?php if ($this->_var['spec_key'] == 228): ?>
                    <?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key3', 'spec3');if (count($_from)):
    foreach ($_from AS $this->_var['spec_key3'] => $this->_var['spec3']):
?>
                        <?php $_from = $this->_var['spec3']['label']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key4', 'spec4');if (count($_from)):
    foreach ($_from AS $this->_var['spec_key4'] => $this->_var['spec4']):
?>
                      
    
                        <tr><td><?php echo $this->_var['spec4']; ?></td>
                        
                       <?php $_from = $this->_var['specification']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key_inner', 'spec_inner');if (count($_from)):
    foreach ($_from AS $this->_var['spec_key_inner'] => $this->_var['spec_inner']):
?>
                    <?php if ($this->_var['spec_key_inner'] == 227): ?>
                    <?php $_from = $this->_var['spec_inner']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key5', 'spec5');$this->_foreach['spec5name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['spec5name']['total'] > 0):
    foreach ($_from AS $this->_var['spec_key5'] => $this->_var['spec5']):
        $this->_foreach['spec5name']['iteration']++;
?>
                    <?php $_from = $this->_var['spec5']['label']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key6', 'spec6');$this->_foreach['spec6name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['spec6name']['total'] > 0):
    foreach ($_from AS $this->_var['spec_key6'] => $this->_var['spec6']):
        $this->_foreach['spec6name']['iteration']++;
?>
                    <td><input type="text" value="0" /></td>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php endif; ?>
                     <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                        
                        </tr>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php endif; ?>
                    
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                </table>
               
                
  </div>
  <div class="blank5"></div>

  <div class="detail_middle ">
    <div class="block clearfix"> 
      
      <div class="AreaL" style=" width:275px"> 
       <?php echo $this->fetch('library/goods_related.lbi'); ?>  <?php echo $this->fetch('library/top10_goods.lbi'); ?>  <?php $this->assign('brand_goods',$this->_var['brand_goods_14']); ?><?php $this->assign('goods_brand',$this->_var['goods_brand_14']); ?><?php echo $this->fetch('library/brand_goods.lbi'); ?> 
        <div class=" newStarPro  lshadow"  >
          <div class="ff_1">
            <h2 id="ECS_tree1" class=" tabFront" style="">热销排行</h2>
            <h2 id="ECS_tree2" class=" tabBack"  style="">推荐排行</h2>
          </div>
          <div style="clear:both"></div>
          <div class="tab_body">
            <div id="ECS_tree1_BODY"> <?php echo $this->fetch('library/recommend_new_goods.lbi'); ?> </div>
            <div id="ECS_tree2_BODY" style="display:none;"> <?php echo $this->fetch('library/recommend_best_goods.lbi'); ?> </div>
          </div>
        </div>
        <script type="text/javascript">
//<![CDATA[

  var cycleList = ['ECS_tree1','ECS_tree2'];
  var tabFront = 'tabFront';
  var tabBack = 'tabBack';
  function cycleShow(obj)
  {
    var curObj;
    var curBody;
    for (i=0; i < cycleList.length; i++)
    {
      curObj = document.getElementById(cycleList[i]);
      curBody = document.getElementById(cycleList[i] + '_BODY');
      if (obj.id == curObj.id)
      {
        curObj.className = tabFront;
        curBody.style.display = "";
      }
      else
      {
        curObj.className = tabBack;
        curBody.style.display = "none";
      }
    }
  }

  // 添加事件
  for (i=0; i< cycleList.length; i++)
  {
    document.getElementById(cycleList[i]).onmousemove = function()
    {
      cycleShow(this);
    };
  }

//]]>
</script> 
          
         
        
      </div>
       
      
      <div class="AreaR" style=" width:904px; overflow:hidden"> 
      
      
 
      <?php echo $this->fetch('library/goods_fittings.lbi'); ?> 
      
      
        
        <div class="inDetail_boxOut ">
          <DIV class="inDetail_box">
            <div class="fixed_out ">
              <div  id="inner" class="fixed" >
                <ul class="inLeft_btn">
                  <li><a id="property-id" href="#shangpsx" class="current">商品属性</a></li>
                  <li><a id="detail-id" href="#shangpjs">本单详情</a></li>
                  <li><a id="shot-id" href="#miqsp">产品实拍</a></li>
                  <li><a id="coms1-id" href="#coms1">买家评论</a></li>
                  <li class="advantage"><a id="good-id" href="#miqfw">淘五季优势</a></li>
                </ul>
              </div>
            </div>
            <script type="text/javascript">
var obj11 = document.getElementById("inner");
var top11 = getTop(obj11);
var isIE6 = /msie 6/i.test(navigator.userAgent);
window.onscroll = function(){
var bodyScrollTop = document.documentElement.scrollTop || document.body.scrollTop;
if (bodyScrollTop > top11){
obj11.style.position = (isIE6) ? "absolute" : "fixed";
obj11.style.top = (isIE6) ? bodyScrollTop + "px" : "0px";
} else {
obj11.style.position = "static";
}
}
function getTop(e){
var offset = e.offsetTop;
if(e.offsetParent != null) offset += getTop(e.offsetParent);
return offset;
}
</script>
            <div class="inLeft_ensure"> <a href="#" target="_blank"></a> <a href="#" target="_blank"></a> <a href="#" target="_blank"></a> <a href="#" target="_blank"></a> </div>
            <div  id="no_try_record"> 
              
              
              <div class="inLeft_title property_title" id="shangpsx"> <img border="0" src="themes/miqinew/images/inLeft_title01.gif"> </div>
              <div class="inLeft_attributes" style="margin:0;">
                <div class="inLeft_attributes_1">
                  <table border="0" cellspacing="0" cellpadding="0" >
                    <tbody>
                      <tr>
                        <td>商品名称</td>
                        <td><p class="fit_people "> <?php echo $this->_var['goods']['goods_style_name']; ?></p></td>
                        <td align="center" style="text-align:center;" rowspan="9"><img src="<?php echo $this->_var['goods']['goods_img']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>"  /></td>
                      </tr>
                      
                      <?php $_from = $this->_var['properties']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'property_group');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['property_group']):
?> 
                      <?php $_from = $this->_var['property_group']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'property');if (count($_from)):
    foreach ($_from AS $this->_var['property']):
?>
                      <tr >
                        <td><?php echo htmlspecialchars($this->_var['property']['name']); ?></td>
                        <td><p class="fit_people "><?php echo $this->_var['property']['value']; ?></p></td>
                      </tr>
                      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
                      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                      
                    </tbody>
                  </table>
                </div>
              </div>
              
              <div class="inLeft_title " id="shangpjs"> <img border="0" src="themes/miqinew/images/inLeft_title02.gif"> </div>
              <div class="inLeft_attributes inLeft_style">
                <div class="inLeft_orderDetail" >
                  <div class="inLeft_orderDetail_in" > <?php echo $this->_var['goods']['goods_desc']; ?> </div>
                </div>
              </div>
              
              
              
              
              <div class="inLeft_title " id="miqsp">
                                <img border="0" src="themes/miqinew/images/inLeft_title04.gif">
                            </div>
                            
                  <?php echo $this->_var['goods']['goods_shipai']; ?>          
                           
                                           
                                           
                                                           
                     <div class="inLeft_title " id="coms1">
                                <img border="0" src="themes/miqinew/images/inLeft_title06.gif">
                            </div>   
                                 
                                 
                    
                             <div class="inLeft_comment" > 
                             
                                   
                            <div id="i-comment"  style="margin-top:20px;">
                <div class="rate"> <strong><?php echo $this->_var['comment_percent']['haoping_percent']; ?>%</strong> <br> 好评度 </div>
                <div class="percent"> 
                  <dl> 
                    <dt>好评</dt> 
                    <dd class="d1"> 
                      <div style="width: <?php echo $this->_var['comment_percent']['haoping_percent']; ?>%;"> </div> 
                    </dd> 
                    <dd class="d2"> <?php echo $this->_var['comment_percent']['haoping_percent']; ?>%</dd> 
                  </dl> 
                  <dl> 
                    <dt>中评</dt> 
                    <dd class="d1"> 
                      <div style="width: <?php echo $this->_var['comment_percent']['zhongping_percent']; ?>%;"> </div> 
                    </dd> 
                    <dd class="d2"> <?php echo $this->_var['comment_percent']['zhongping_percent']; ?>%</dd> 
                  </dl> 
                  <dl> 
                    <dt>差评</dt> 
                    <dd class="d1"> 
                      <div style="width: <?php echo $this->_var['comment_percent']['chaping_percent']; ?>%;"> </div> 
                    </dd> 
                    <dd class="d2"> <?php echo $this->_var['comment_percent']['chaping_percent']; ?>%</dd> 
                  </dl> 
                </div> 
                <div class="actor"> 
                 <a href="<?php echo $this->_var['goods']['url']; ?>"> <img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" alt="" style="width:100px;height:100px;"></a>
                </div> 
                <div class="btns"> 
                  <div>购买过<?php echo $this->_var['goods']['goods_name']; ?>的顾客，在收到商品才可以对该商品发表评论</div> 
                  <a href="javascript:void(0);" class="btn-comment" id="showcommentform">我要评价</a>
                </div>
                <div class="clear"></div>
              </div>
                   
                                
              <div class="comment_body " >
               
                <?php echo $this->fetch('library/comments.lbi'); ?> 
                </div>
                </div>
                 
                
                
                <div class="inLeft_title " id="miqfw">
                                <img border="0" src="themes/miqinew/images/inLeft_title07.gif">
                            </div>
               <?php echo $this->fetch('library/benwangyoushi.lbi'); ?> 
            </DIV>
          </div>
        </div>
        <div class="blank"></div>
         
         </div>
       
    </div>
  </div>
</div>
<div class="blank5"></div>
<div class="footer">
  <div class="footerBody">
    <Div class="block"> 
      
      <?php echo $this->fetch('library/page_footer.lbi'); ?> 
      
      </Div>
  </div>
</div>

</body>
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
</html>
