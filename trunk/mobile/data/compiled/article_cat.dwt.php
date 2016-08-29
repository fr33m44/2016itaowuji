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
<header id="header">
  <div class="header_l header_return"> <a class="ico_10" href="index.php"> 返回 </a> </div>
  <h1> 文章分类 </h1>
  <div class="header_r header_search"> <a class="ico_03"  onClick="showSearch()"> 搜索 </a> </div>
  <div id="search_box">
    <div class="search_box">
      <form action="<?php echo $this->_var['search_url']; ?>" name="searchForm" id="searchForm_id" method="post">
        <input placeholder="请输入关键词" name="keywords" type="text" id="keywordBox" value="<?php echo $this->_var['search_value']; ?>" />
        <input name="id" type="hidden" value="<?php echo $this->_var['cat_id']; ?>" />
        <button class="ico_07" type="submit" onclick="return check('keywordBox')"> </button>
      </form>
    </div>
    <a class="ico_08" onClick="closeSearch()"></a> </div>
</header>

<div class="blank3"></div>
<section class="wrap">
  <div class="list_box padd1 radius10" style="padding-top:0;padding-bottom:0;"> 
    <?php if ($this->_var['article_categories']): ?> 
    <?php $_from = $this->_var['article_cat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['article_cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['article_cat']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['article_cat']['iteration']++;
?> 
    <a href="article_cat.php?id=<?php echo $this->_var['cat']['cat_id']; ?>" class="clearfix"> <span><?php echo htmlspecialchars($this->_var['cat']['cat_name']); ?></span><i></i> </a> 
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    <?php else: ?> 
    <?php $_from = $this->_var['artciles_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');$this->_foreach['artciles_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['artciles_list']['total'] > 0):
    foreach ($_from AS $this->_var['article']):
        $this->_foreach['artciles_list']['iteration']++;
?> 
    <a href="<?php echo $this->_var['article']['url']; ?>" class="clearfix"> <span><?php echo $this->_var['article']['short_title']; ?></span><i></i> </a> 
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    <?php endif; ?> 
	
	<?php if ($this->_var['artciles_list']): ?>
	<?php $_from = $this->_var['artciles_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');$this->_foreach['artciles_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['artciles_list']['total'] > 0):
    foreach ($_from AS $this->_var['article']):
        $this->_foreach['artciles_list']['iteration']++;
?> 
	<a href="<?php echo $this->_var['article']['url']; ?>" class="clearfix"> <span><?php echo $this->_var['article']['short_title']; ?></span><i></i> </a> 
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 	
	<?php endif; ?> 
  </div>
  <?php echo $this->fetch('library/pages.lbi'); ?>
</section>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<script type="text/javascript">
/*头部搜索点击关闭或者弹出搜索框*/  
function showSearch( ){
	document.getElementById("search_box").style.display="block";
}
function closeSearch(){
	document.getElementById("search_box").style.display="none";
}
/* 搜索验证 */
function check(Id){
	var strings = document.getElementById(Id).value;
	if(strings.replace(/(^\s*)|(\s*$)/g, "").length == 0){
		return false;
	}
	return true;
}
</script>
</body>
</html>