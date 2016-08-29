<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../data/static/js/utils.js,./js/listtable.js')); ?>
<form method="post" action="" name="listForm">
<div class="list-div" id="listDiv">
<?php endif; ?>
<table cellspacing='1' cellpadding='3' id='list-table'>

<iframe frameborder=0 width=100% height="750" marginheight=0  marginwidth=0 scrolling=auto src="http://115.29.242.82/help-ectouch/ectouch.htm"></iframe>

</table>

<?php if ($this->_var['full_page']): ?>
</div>
</form>
<script type="Text/Javascript" language="JavaScript">
<!--
listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
listTable.pageCount = <?php echo $this->_var['page_count']; ?>;
<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

onload = function()
{
  // 开始检查订单
  startCheckOrder();
}

//-->
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>