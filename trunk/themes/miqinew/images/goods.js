/* $Id: default.js 15469 20010-08-24 15:11:44Z testhuang $ */

/* *
 * 商品购买记录的翻页函数
 */
function gotoBuyPage(page, id)
{
  Ajax.call(WWW_ROOW+'goods.php?act=gotopage', 'page=' + page + '&id=' + id, gotoBuyPageResponse, 'GET', 'JSON');
}

function gotoBuyPageResponse(result)
{
  document.getElementById("ECS_BOUGHT").innerHTML = result.result;
}

/* *
 * ajax刷新商品购买咨询
 */
function gotozixunPage(page, id)
{
  Ajax.call(WWW_ROOW+'goods.php?act=gotozixun_page', 'page=' + page + '&id=' + id, gotozixunPageResponse, 'GET', 'JSON');
}

function gotozixunPageResponse(result)
{
  document.getElementById("ECS_ZIXUN").innerHTML = result.result;
}

/* *
 * 取得格式化后的价格
 * @param : float price
 */
function getFormatedPrice(price)
{
  if (currencyFormat.indexOf("%s") > - 1)
  {
    return currencyFormat.replace('%s', advFormatNumber(price, 2));
  }
  else if (currencyFormat.indexOf("%d") > - 1)
  {
    return currencyFormat.replace('%d', advFormatNumber(price, 0));
  }
  else
  {
    return price;
  }
}

/* *
 * 夺宝奇兵会员出价
 */

function bid(step)
{
  var price = '';
  var msg   = '';
  if (step != - 1)
  {
    var frm = document.forms['formBid'];
    price   = frm.elements['price'].value;
    id = frm.elements['snatch_id'].value;
    if (price.length == 0)
    {
      msg += price_not_null + '\n';
    }
    else
    {
      var reg = /^[\.0-9]+/;
      if ( ! reg.test(price))
      {
        msg += price_not_number + '\n';
      }
    }
  }
  else
  {
    price = step;
  }

  if (msg.length > 0)
  {
    alert(msg);
    return;
  }

  Ajax.call(WWW_ROOW+'snatch.php?act=bid&id=' + id, 'price=' + price, bidResponse, 'POST', 'JSON')
}

/* *
 * 夺宝奇兵会员出价反馈
 */
function bidResponse(result)
{
  if (result.error == 0)
  {
    document.getElementById('ECS_SNATCH').innerHTML = result.content;
    if (document.forms['formBid'])
    {
      document.forms['formBid'].elements['price'].focus();
    }
    //刷新价格列表
    newPrice();
  }
  else
  {
    alert(result.content);
  }
}

/* *
 * 夺宝奇兵最新出价
 */

function newPrice(id)
{
  Ajax.call(WWW_ROOW+'snatch.php?act=new_price_list&id=' + id, '', newPriceResponse, 'GET', 'TEXT');
}

/* *
 * 夺宝奇兵最新出价反馈
 */

function newPriceResponse(result)
{
  document.getElementById('ECS_PRICE_LIST').innerHTML = result;
}

/* *
 *  返回属性列表
 */
function getAttr(cat_id)
{
  var tbodies = document.getElementsByTagName('tbody');
  for (i = 0; i < tbodies.length; i ++ )
  {
    if (tbodies[i].id.substr(0, 10) == 'goods_type')tbodies[i].style.display = 'none';
  }

  var type_body = 'goods_type_' + cat_id;
  try
  {
    document.getElementById(type_body).style.display = '';
  }
  catch (e)
  {
  }
}

/* *
 * 截取小数位数
 */
function advFormatNumber(value, num) // 四舍五入
{
  var a_str = formatNumber(value, num);
  var a_int = parseFloat(a_str);
  if (value.toString().length > a_str.length)
  {
    var b_str = value.toString().substring(a_str.length, a_str.length + 1);
    var b_int = parseFloat(b_str);
    if (b_int < 5)
    {
      return a_str;
    }
    else
    {
      var bonus_str, bonus_int;
      if (num == 0)
      {
        bonus_int = 1;
      }
      else
      {
        bonus_str = "0."
        for (var i = 1; i < num; i ++ )
        bonus_str += "0";
        bonus_str += "1";
        bonus_int = parseFloat(bonus_str);
      }
      a_str = formatNumber(a_int + bonus_int, num)
    }
  }
  return a_str;
}

function formatNumber(value, num) // 直接去尾
{
  var a, b, c, i;
  a = value.toString();
  b = a.indexOf('.');
  c = a.length;
  if (num == 0)
  {
    if (b != - 1)
    {
      a = a.substring(0, b);
    }
  }
  else
  {
    if (b == - 1)
    {
      a = a + ".";
      for (i = 1; i <= num; i ++ )
      {
        a = a + "0";
      }
    }
    else
    {
      a = a.substring(0, b + num + 1);
      for (i = c; i <= b + num; i ++ )
      {
        a = a + "0";
      }
    }
  }
  return a;
}

function showshare(){//显示分享列表
 
  $("shareul").style.display="block";

}

function hideshare(){//隐藏分享列表

  $("shareul").style.display="none";

}

function closediv1(){
    if($("minicart")) $("minicart").style.display='none';
    if($("yinying")) document.body.removeChild($("yinying"));
    return false;
}

function group(id,marketprice,pufungprice,releated_id,releated_id_all,obj){
	if($("packCount")&&$(id)){
		var goods_market_price = parseFloat($(marketprice).value);
		var goods_pufung_price = parseFloat($(pufungprice).value);
		var summkprice = parseFloat($("sumopriceDiv").innerHTML);
		var sumpfprice = parseFloat($("sumpriceDiv").innerHTML);
		if(obj.checked==true){
			$(releated_id_all).value +=","+$(releated_id).value;
			$(id).className="pic_choice";
			$("packCount").innerHTML = parseInt($("packCount").innerHTML)+1;
			$("sumopriceDiv").innerHTML = (goods_market_price+summkprice).toFixed(2);
			$("sumpriceDiv").innerHTML = (goods_pufung_price+sumpfprice).toFixed(2);
		}
		if(obj.checked==false){
			$(releated_id_all).value =$(releated_id_all).value.replace(","+$(releated_id).value,"");
			$(id).className="";
			$("packCount").innerHTML = parseInt($("packCount").innerHTML)-1;
			$("sumopriceDiv").innerHTML = (summkprice-goods_market_price).toFixed(2);
			$("sumpriceDiv").innerHTML = (sumpfprice-goods_pufung_price).toFixed(2);
		}
    if(parseInt($("packCount").innerHTML)<=0){
			$("packCount").innerHTML=1;
			$("sumopriceDiv").innerHTML = $("marketprice").innerHTML;
			$("sumpriceDiv").innerHTML = $("pufungprice").innerHTML;
		}
	}
}
/*检查咨询*/
function checkzixun(theform){
  var title = theform.elements["title"].value;
	var content = theform.elements["content"].value;
	var tips="";
	if(Utils.isEmpty(title)) tips+="标题不能为空\n";
	if(Utils.isEmpty(content)) tips+="内容不能为空\n";
	if(tips){
		alert(tips);
		return false;
	}
	var cmt = new Object;
	
	 cmt.email           = theform.elements['email'].value;
     cmt.content         = theform.elements['content'].value;
     cmt.type            = theform.elements['cmt_type'].value;
	 cmt.title			 = title;
     cmt.id              = theform.elements['id'].value;
	 cmt.rank            = 5;
	
	 Ajax.call(WWW_ROOW+'refresh.php?step=sub_zixun', 'cmt=' + cmt.toJSONString(), checkzixunResponse, 'POST', 'JSON');
	 return false;

}
 function checkzixunResponse(result)
 {    
	 
      if (result.message)
      {
           alert(result.message);
      }

      if (result.error == 0)
      {
            var layer = document.getElementById('ECS_ZIXUN');
			if (layer){
                        layer.innerHTML = result.content;
             }
            
        }
  }
/*检查评论*/
function checkcomment(theform){
  var rank = theform.elements["comment_rank"].value;
	var content = theform.elements["content"].value;
	var captcha = theform.elements["captcha"];
	var tips="";
	if(Utils.isEmpty(rank)) tips+="您还没评分哦\n";
	if(Utils.isEmpty(content)) tips+="评论内容不能为空\n";
	if(captcha.value==captcha.defaultValue||Utils.isEmpty(captcha.value)) tips+="验证码不能为空\n";
	if(tips){
		alert(tips);
		return false;
	}
}
/*弹出评论框*/
function ajaxlogin(){
  Ajax.call(WWW_ROOW+'comment.php', 'cmt=' + cmt.toJSONString(), commentResponse, 'POST', 'JSON');
}
function showcommentform(e){
	/*
	isLogined = false;
  if (isLogined) {
		if (confirm('此操作需要登录，您现在要登录吗？')) {
		  location.href = WWW_ROOW+'user.php?act=login';
			return;
		}
		return;
	}
	else {
		if (false){
			alert('只有购买过此商品的顾客且没有评论才可以打分和评论');
			return;
		}
	}
  */
	e=e||window.event;
	x=mousePos(e).x;
	y=mousePos(e).y;

  if($("commentform")){
    var isie= navigator.appVersion.indexOf("MSIE")!=-1?true:false;
    var newdiv=document.createElement("div");
    newdiv.id="yinyingcom";
    newdiv.style.position="absolute";
    newdiv.style.width="100%";
    newdiv.style.height=document.body.clientHeight+"px";
    newdiv.style.background="black";
    if(!isie) newdiv.style.opacity=0.5;
    else newdiv.style.filter="alpha(opacity=50)";
    newdiv.style.left=0+"px";
    newdiv.style.top=0+"px";
    newdiv.style.zIndex=999;
		document.body.appendChild(newdiv);
		$("commentform").style.display = "block";
		$("commentform").style.top=y-120+"px";
		$("commentform").style.left=x-$("commentform").offsetWidth+30+"px";
	}
}
function hiddencommentform(){
  if($("commentform")){
		if($("yinyingcom")) document.body.removeChild($("yinyingcom"));
    $("commentform").style.display = "none";
	}
}
/*打分函数*/
function showrank(obj,num,rankid,commentrank){
  obj.parentNode.className = "cmtRank fen"+num;
	$(rankid).innerHTML = num+".0分";
	if(commentrank) $(commentrank).value = num;
}
function hiddenrank(obj,rankid,commentrank){
  if($(commentrank).value) showrank(obj,$(commentrank).value,'Rank','commentrank');
	else showrank(obj,1,'Rank','commentrank');
}
/*减少数量*/
function subnum(id){
  var num = parseInt($(id).value);
	num-=1;
	if(num==0) num = 1;
	$(id).value = num;
}
/*增加数量*/
function addnum(id){
  var num = parseInt($(id).value);
	num+=1;
	$(id).value = num; 
}

function showpro_box(el){
  var uls = el.parentNode.getElementsByTagName("li");
	for(var i=0;i<uls.length;i++){
    uls[i].className = "";
	}
	el.className = "curr";
}

function scroll_float(){
	var top = document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop;
  if($("dapei")&&top>970){
		$("float_box").className = "float_scroll";
		$("float_box").style.top = top + "px";
	}
	else if(!$("dapei")&&top>690){
    $("float_box").className = "float_scroll";
		$("float_box").style.top = top + "px";
	}
	else{
		$("float_box").className= "";
	}
}