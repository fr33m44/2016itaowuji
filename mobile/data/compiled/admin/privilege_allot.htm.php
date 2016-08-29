<!-- $Id: privilege_allot.htm 16970 2010-01-08 08:52:36Z liuhui $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<form method="POST" action="privilege.php" name="theFrom">
<div class="list-div">
<table cellspacing='1' id="list-table">
<?php $_from = $this->_var['priv_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'priv');if (count($_from)):
    foreach ($_from AS $this->_var['priv']):
?>
 <tr>
  <td width="18%" valign="top" class="first-cell">
    <input name="chkGroup" type="checkbox" value="checkbox" onclick="check('<?php echo $this->_var['priv']['priv_list']; ?>',this);" class="checkbox"><?php echo $this->_var['lang'][$this->_var['priv']['action_code']]; ?>
  </td>
  <td>
    <?php $_from = $this->_var['priv']['priv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('priv_list', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['priv_list'] => $this->_var['list']):
?>
    <div style="width:200px;float:left;">
    <label for="<?php echo $this->_var['priv_list']; ?>"><input type="checkbox" name="action_code[]" value="<?php echo $this->_var['priv_list']; ?>" id="<?php echo $this->_var['priv_list']; ?>" class="checkbox" <?php if ($this->_var['list']['cando'] == 1): ?> checked="true" <?php endif; ?> onclick="checkrelevance('<?php echo $this->_var['list']['relevance']; ?>', '<?php echo $this->_var['priv_list']; ?>')" title="<?php echo $this->_var['list']['relevance']; ?>"/>
    <?php echo $this->_var['lang'][$this->_var['list']['action_code']]; ?></label>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</td></tr>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <tr>
    <td align="center" colspan="2" >
      <input type="checkbox" name="checkall" value="checkbox" onclick="checkAll(this.form, this);" class="checkbox" /><?php echo $this->_var['lang']['check_all']; ?>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <input type="submit"   name="Submit"   value="<?php echo $this->_var['lang']['button_save']; ?>" class="button" />
      <input type="hidden"   name="id"    value="<?php echo $this->_var['user_id']; ?>" />
      <input type="hidden"   name="act"   value="<?php echo $this->_var['form_act']; ?>" />
    </td>
  </tr>
</table>
</div>
</form>


<script language="javascript">
function checkAll(frm, checkbox)
{
  for (i = 0; i < frm.elements.length; i++)
  {
    if (frm.elements[i].name == 'action_code[]' || frm.elements[i].name == 'chkGroup')
    {
      frm.elements[i].checked = checkbox.checked;
    }
  }
}

function check(list, obj)
{
  var frm = obj.form;

    for (i = 0; i < frm.elements.length; i++)
    {
      if (frm.elements[i].name == "action_code[]")
      {
          var regx = new RegExp(frm.elements[i].value + "(?!_)", "i");

          if (list.search(regx) > -1) frm.elements[i].checked = obj.checked;
      }
    }
}

function checkrelevance(relevance, priv_list)
{
  if(document.getElementById(priv_list).checked && relevance != '')
  {
    document.getElementById(relevance).checked=true;
  }
  else
  {
    var ts=document.getElementsByTagName("input");
    
    for (var i=0; i<ts.length;i++)
    {
      var text=ts[i].getAttribute("title");

      if( text == priv_list) 
      {
        document.getElementById(ts[i].value).checked = false;
      }
    }
  }
}
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>
