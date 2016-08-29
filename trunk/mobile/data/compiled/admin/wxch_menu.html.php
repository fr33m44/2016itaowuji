<!-- $Id: wxch_menu.html  2013-11-16 10:30:26Z djks $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $this->_var['wxch_lang']['cp_home']; ?><?php if ($this->_var['wxch_lang']['ur_here']): ?> - <?php echo $this->_var['wxch_lang']['ur_here']; ?> <?php endif; ?></title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="styles/general.css" rel="stylesheet" type="text/css" />
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <?php echo $this->smarty_insert_scripts(array('files'=>'../data/static/js/transport.js,./js/common.js')); ?>
    <script language="JavaScript">
        <!--
        // 这里把JS用到的所有语言都赋值到这里
        <?php $_from = $this->_var['lang']['js_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
        var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        //-->
    </script>
</head>
<body>

<h1>
    <?php if ($this->_var['action_link']): ?>
    <span class="action-span"><a href="<?php echo $this->_var['action_link']['href']; ?>"><?php echo $this->_var['action_link']['text']; ?></a></span>
    <?php endif; ?>
    <?php if ($this->_var['action_link2']): ?>
    <span class="action-span"><a href="<?php echo $this->_var['action_link2']['href']; ?>"><?php echo $this->_var['action_link2']['text']; ?></a>&nbsp;&nbsp;</span>
    <?php endif; ?>
    <span class="action-span1"><a href="index.php?act=main"><?php echo $this->_var['wxch_lang']['cp_home']; ?></a> </span><span id="search_id" class="action-span1"> - <?php echo $this->_var['wxch_lang']['ur_here']; ?></span>
    <div style="clear:both"></div>
</h1>

<?php echo $this->smarty_insert_scripts(array('files'=>'../data/static/js/utils.js,./js/selectzone.js,./js/colorselector.js')); ?>
<script type="text/javascript" src="../data/static/js/calendar.php?lang=<?php echo $this->_var['cfg_lang']; ?>"></script>
<link href="../data/static/js/calendar/calendar.css" rel="stylesheet" type="text/css" />

<?php if ($this->_var['warning']): ?>
<ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
    <li style="border: 1px solid #CC0000; background: #FFFFCC; padding: 10px; margin-bottom: 5px;" ><?php echo $this->_var['warning']; ?></li>
</ul>
<?php endif; ?>

<div class="tab-div">
<div id="tabbar-div">
    <p>
        <span class="tab-front" id="one-table">菜单一</span>
        <span class="tab-back" id="two-table">菜单二</span>
        <span class="tab-back" id="three-table">菜单三</span>
    </p>
</div>

<!-- tab body -->
<div id="tabbody-div">
<form enctype="multipart/form-data" action="" method="post" name="theForm" >
<!-- 通用信息 -->
<table width="50%" id="one-table" align="center">
    <tr>
            <td class="label">级别&nbsp;&nbsp;</td>
            <td><strong>类型</strong></td>
            <td><strong>名称</strong></td>
            <td><strong>值</strong></td>
    </tr>
    <tr>
        <td class="label">一级菜单：</td>
        <td>
            <select name="first_type[]">
                    <option value="click"<?php if ($this->_var['data']['first']['0']['menu_type'] == click): ?>selected<?php endif; ?> >click</option>
                    <option value="view" <?php if ($this->_var['data']['first']['0']['menu_type'] == view): ?>selected<?php endif; ?> >view</option>
            </select>  
        </td>
        <td>
                <label><input type="text" name="first[]" value="<?php echo $this->_var['data']['first']['0']['name']; ?>" size="8"/></label>
        </td>
        <td>
                <label><input type="text" name="first_value[]" value="<?php echo $this->_var['data']['first']['0']['value']; ?>" size="8"/></label>
        </td>
    </tr>
    <?php $_from = $this->_var['data']['second1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'items');if (count($_from)):
    foreach ($_from AS $this->_var['items']):
?>
    <tr>
        <td class="label">二级菜单<?php echo $this->_var['items']['num']; ?>：</td>
        <td>
            <select name="menu_type1[]">
                    <option value="click" <?php if ($this->_var['items']['menu_type'] == click): ?>selected<?php endif; ?> >click</option>
                    <option value="view" <?php if ($this->_var['items']['menu_type'] == view): ?>selected<?php endif; ?> >view</option>
            </select>  
        </td>
        <td>
                <label><input type="text" name="second1[]" value="<?php echo $this->_var['items']['name']; ?>" size="8"/></label>
        </td>
        <td>
                <label><input type="text" name="value1[]" value="<?php echo $this->_var['items']['value']; ?>" size="8"/></label>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
<table width="50%" id="two-table" align="center" style="display:none">
    <tr>
            <td class="label">级别&nbsp;&nbsp;</td>
            <td><strong>类型</strong></td>
            <td><strong>名称</strong></td>
            <td><strong>值</strong></td>
    </tr>
    <tr>
        <td class="label">一级菜单：</td>
        <td>
            <select name="first_type[]">
                    <option value="click"<?php if ($this->_var['data']['first']['1']['menu_type'] == click): ?>selected<?php endif; ?> >click</option>
                    <option value="view" <?php if ($this->_var['data']['first']['1']['menu_type'] == view): ?>selected<?php endif; ?> >view</option>
            </select>  
        </td>
        <td>
                <label><input type="text" name="first[]" value="<?php echo $this->_var['data']['first']['1']['name']; ?>" size="8"/></label>
        </td>
        <td>
                <label><input type="text" name="first_value[]" value="<?php echo $this->_var['data']['first']['1']['value']; ?>" size="8"/></label>
        </td>
    </tr>
    <?php $_from = $this->_var['data']['second2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'items');if (count($_from)):
    foreach ($_from AS $this->_var['items']):
?>
    <tr>
        <td class="label">二级菜单<?php echo $this->_var['items']['num']; ?>：</td>
        <td>
            <select name="menu_type2[]">
                    <option value="click" <?php if ($this->_var['items']['menu_type'] == click): ?>selected<?php endif; ?> >click</option>
                    <option value="view" <?php if ($this->_var['items']['menu_type'] == view): ?>selected<?php endif; ?> >view</option>
            </select>  
        </td>
        <td>
                <label><input type="text" name="second2[]" value="<?php echo $this->_var['items']['name']; ?>" size="8"/></label>
        </td>
        <td>
                <label><input type="text" name="value2[]" value="<?php echo $this->_var['items']['value']; ?>" size="8"/></label>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    
</table>
<table width="50%" id="three-table" align="center" style="display:none">
    <tr>
            <td class="label">级别&nbsp;&nbsp;</td>
            <td><strong>类型</strong></td>
            <td><strong>名称</strong></td>
            <td><strong>值</strong></td>
    </tr>
    <tr>
        <td class="label">一级菜单：</td>
        <td>
            <select name="first_type[]">
                    <option value="click" <?php if ($this->_var['items']['menu_type'] == click): ?>selected<?php endif; ?> >click</option>
                    <option value="view" <?php if ($this->_var['items']['menu_type'] == view): ?>selected<?php endif; ?> >view</option>
            </select>  
        </td>
        <td>
                <label><input type="text" name="first[]" value="<?php echo $this->_var['data']['first']['2']['name']; ?>" size="8"/></label>
        </td>
        <td>
                <label><input type="text" name="first_value[]" value="<?php echo $this->_var['data']['first']['2']['value']; ?>" size="8"/></label>
        </td>
    </tr>
    <?php $_from = $this->_var['data']['second3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'items');if (count($_from)):
    foreach ($_from AS $this->_var['items']):
?>
    <tr>
        <td class="label">二级菜单<?php echo $this->_var['items']['num']; ?>：</td>
        <td>
            <select name="menu_type3[]">
                    <option value="click" <?php if ($this->_var['items']['menu_type'] == click): ?>selected<?php endif; ?> >click</option>
                    <option value="view" <?php if ($this->_var['items']['menu_type'] == view): ?>selected<?php endif; ?> >view</option>
            </select>  
        </td>
        <td>
                <label><input type="text" name="second3[]" value="<?php echo $this->_var['items']['name']; ?>" size="8"/></label>
        </td>
        <td>
                <label><input type="text" name="value3[]" value="<?php echo $this->_var['items']['value']; ?>" size="8"/></label>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>

<div class="button-div">
    <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" />
    <input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button" />
</div>
<input type="hidden" name="act" value="<?php echo $this->_var['form_act']; ?>" />
</form>
</div>
</div>
<!-- end goods form -->
<?php echo $this->smarty_insert_scripts(array('files'=>'./js/validator.js,./js/tab.js')); ?>

<script language="JavaScript">

var articleId = <?php echo empty($this->_var['article']['article_id']) ? '0' : $this->_var['article']['article_id']; ?>;
var elements  = document.forms['theForm'].elements;
var sz        = new SelectZone(1, elements['source_select'], elements['target_select'], '');


onload = function()
{
  // 开始检查订单
  startCheckOrder();
}

function validate()
{
  var validator = new Validator('theForm');
  validator.required('title', no_title);

<?php if ($this->_var['article']['cat_id'] >= 0): ?>
  validator.isNullOption('article_cat',no_cat);
<?php endif; ?>


  return validator.passed();
}

document.getElementById("tabbar-div").onmouseover = function(e)
{
    var obj = Utils.srcElement(e);

    if (obj.className == "tab-back")
    {
        obj.className = "tab-hover";
    }
}

document.getElementById("tabbar-div").onmouseout = function(e)
{
    var obj = Utils.srcElement(e);

    if (obj.className == "tab-hover")
    {
        obj.className = "tab-back";
    }
}

document.getElementById("tabbar-div").onclick = function(e)
{
    var obj = Utils.srcElement(e);

    if (obj.className == "tab-front")
    {
        return;
    }
    else
    {
        objTable = obj.id.substring(0, obj.id.lastIndexOf("-")) + "-table";

        var tables = document.getElementsByTagName("table");
        var spans  = document.getElementsByTagName("span");

        for (i = 0; i < tables.length; i++)
        {
            if (tables[i].id == objTable)
            {
                tables[i].style.display = (Browser.isIE) ? "block" : "table";
            }
            else
            {
                tables[i].style.display = "none";
            }
        }
        for (i = 0; spans.length; i++)
        {
            if (spans[i].className == "tab-front")
            {
                spans[i].className = "tab-back";
                obj.className = "tab-front";
                break;
            }
        }
    }
}

function showNotice(objId)
{
    var obj = document.getElementById(objId);

    if (obj)
    {
        if (obj.style.display != "block")
        {
            obj.style.display = "block";
        }
        else
        {
            obj.style.display = "none";
        }
    }
}


</script>
<?php echo $this->fetch('wxch_pagefooter.htm'); ?>
