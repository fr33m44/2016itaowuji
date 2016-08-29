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
    <div class="header_l"> <a class="ico_10" href="javascript:history.go(-1)"> 返回 </a> </div>
    <div id="search_box2" >
      <div class="search_box" >
        <form method="post" action="search.php" name="searchForm" id="searchForm_id">
          <input name="keywords" type="text" id="keywordfoot" value="<?php echo htmlspecialchars($this->_var['search_keywords']); ?>" />
          <button class="ico_07" type="submit" cursor="hand" onclick="return check('keywordfoot')"> </button>
        </form>
      </div>
    </div>
    <div class="header_r header_search"> <a class="switchBtn switchBtn-album" href="javascript:void(0);"  onclick="changeCl(this)" style="opacity: 1;"> 切换 </a> </div>
  </header>
  <?php echo $this->fetch('library/goods_list_search.lbi'); ?>
</div>
<?php echo $this->fetch('library/pages.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>

<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/jquery.more.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['ectouch_themes']; ?>/js/ectouch.js"></script>

</body>
</html>