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
<div id="page" style="right: 0px; left: 0px; display: block;">
  <header id="header" style="z-index:1">
    <div class="header_l"> <a class="ico_10" href="cat_all.php"> 返回 </a> </div>
    <div id="search_box2" >
      <div class="search_box" >
        <form method="post" action="search.php" name="searchForm" id="searchForm_id">
          <input placeholder="请输入商品名称" name="keywords" type="text" id="keywordBox" />
          <button class="ico_07" type="submit" onclick="return check('keywordBox')"> </button>
        </form>
      </div>
    </div>
    <div class="header_r header_search"> <a class="switchBtn switchBtn-album" href="javascript:void(0);"  onclick="changeCl(this)" style="opacity: 1;"> 切换 </a> </div>
  </header>
  <?php echo $this->fetch('library/goods_list_search.lbi'); ?>
  <?php echo $this->fetch('library/pages.lbi'); ?>
  <?php echo $this->fetch('library/page_footer.lbi'); ?>
</div>


<div id="nav" class="nav" style="right:-275px;">
  <form class="hold-height" action="category.php">
    <div class="attrExtra">
      <input type="hidden" name="category" value="<?php echo $this->_var['category']; ?>"/>
      <button class="attrExtra-submit" type="submit">确定</button>
      <button class="attrExtra-cancel" type="button"  onclick="mtShowMenu()">取消</button>
    </div>
    <div class="attrs attr-fix" style="overflow: auto;height: 100%"> 
       
      <?php if ($this->_var['brands']['1'] || $this->_var['price_grade']['1'] || $this->_var['filter_attr_list']): ?> 
      
      <?php if ($this->_var['brands']['1']): ?>
      <div class="attr brandAttr">
        <div class="attrKey"><?php echo $this->_var['lang']['brand']; ?></div>
        <div class="attrValues">
          <ul class="av-collapse filter_list">
            <?php $_from = $this->_var['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');if (count($_from)):
    foreach ($_from AS $this->_var['brand']):
?> 
            <?php if ($this->_var['brand']['selected']): ?>
            <li class="av-selected"> 
              <?php else: ?>
            <li> 
              <?php endif; ?> 
              <a href="javascript:void(0);" data="<?php echo $this->_var['brand']['brand_id']; ?>"><?php echo $this->_var['brand']['brand_name']; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <input type="hidden" name="brand" value="<?php echo $this->_var['brand_id']; ?>" />
          </ul>
          <div class="av-options"><a class="j_More avo-more avo-more-down" href="javascript:;">查看更多<i></i></a> </div>
        </div>
      </div>
      <?php endif; ?> 
      
      <?php if ($this->_var['price_grade']['1']): ?>
      <div class="attr brandAttr">
        <div class="attrKey"><?php echo $this->_var['lang']['price']; ?></div>
        <div class="attrValues">
          <ul class="av-collapse filter_list">
            <?php $_from = $this->_var['price_grade']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'grade');if (count($_from)):
    foreach ($_from AS $this->_var['grade']):
?> 
            <?php if ($this->_var['grade']['selected']): ?>
            <li class="av-selected"> 
              <?php else: ?>
            <li> 
              <?php endif; ?> 
              <a href="javascript:void(0);" data="<?php echo $this->_var['grade']['start']; ?>|<?php echo $this->_var['grade']['end']; ?>"><?php echo $this->_var['grade']['price_range']; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <input type="hidden" name="price_min" value="<?php echo $this->_var['price_min']; ?>" />
            <input type="hidden" name="price_max" value="<?php echo $this->_var['price_max']; ?>" />
          </ul>
          <div class="av-options"><a class="j_More avo-more avo-more-down" href="javascript:;">查看更多<i></i></a> </div>
        </div>
      </div>
      <?php endif; ?> 
      
      <?php $_from = $this->_var['filter_attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'filter_attr_0_13469800_1464946848');if (count($_from)):
    foreach ($_from AS $this->_var['filter_attr_0_13469800_1464946848']):
?>
      <div class="attr brandAttr">
        <div class="attrKey"><?php echo htmlspecialchars($this->_var['filter_attr_0_13469800_1464946848']['filter_attr_name']); ?></div>
        <div class="attrValues">
          <ul class="av-collapse filter_list">
            <?php $_from = $this->_var['filter_attr_0_13469800_1464946848']['attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');if (count($_from)):
    foreach ($_from AS $this->_var['attr']):
?> 
            <?php if ($this->_var['attr']['selected']): ?>
            <li class="av-selected filter_attr"> 
              <?php else: ?>
            <li class="filter_attr"> 
              <?php endif; ?> 
              <a href="javascript:void(0);" data="<?php echo $this->_var['attr']['attr_id']; ?>"><?php echo $this->_var['attr']['attr_value']; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
          <div class="av-options"><a class="j_More avo-more avo-more-down" href="javascript:void(0);">查看更多<i></i></a> </div>
        </div>
      </div>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      <input type="hidden" name="filter_attr" value="<?php echo $this->_var['filter_attr']; ?>"/>
      <?php endif; ?> 
       
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/ectouch.js"></script>
<script type="text/javascript">
jQuery(function($){
	$('#J_ItemList').more({'address': 'category.php?act=asynclist&category=<?php echo $this->_var['category']; ?>&brand=<?php echo $this->_var['brand_id']; ?>&price_min=<?php echo $this->_var['price_min']; ?>&price_max=<?php echo $this->_var['price_max']; ?>&filter_attr=<?php echo $this->_var['filter_attr']; ?>&page=<?php echo $this->_var['pager']['page']; ?>&sort=<?php echo $this->_var['pager']['sort']; ?>&order=<?php echo $this->_var['pager']['order']; ?>', 'spinner_code':'<div style="text-align:center; margin:10px;"><img src="<?php echo $this->_var['ectouch_themes']; ?>/images/loader.gif" /></div>'})
	$(window).scroll(function () {
		if ($(window).scrollTop() == $(document).height() - $(window).height()) {
			$('.get_more').click();
		}
	});
});
</script>
</body>
</html>