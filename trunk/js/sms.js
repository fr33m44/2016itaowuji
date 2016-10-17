function get_qrm(mobile, action) {
	/* 检查验证码和手机号 */
	var regex_captcha = /\w{4}$/;
	var captcha = Utils.trim(document.forms['formUser'].elements['captcha'].value);
	var mobile = Utils.trim(document.forms['formUser'].elements['mobile'].value);
	if (Utils.trim(mobile) == '') {
		alert('手机号不能为空');
		return;
	}
	if (captcha == "") {
		alert('验证码不能为空');
		return;
	}
	if (!regex_captcha.test(captcha)) {
		alert('验证码格式不对');
		return;
	}
	if (!(Utils.isMobile(mobile))) {
		alert('手机号格式不对');
		return;
	}
	document.getElementById("get_qrm_btn").disabled = "disabled";
	Ajax.call('user.php?act=getqrm', 'mobile=' + mobile + '&captcha=' + captcha + '&action=' + action, qrm_callback, 'GET', 'JSON', true, true);
}

function qrm_callback(res) {

	if (parseInt(res.error) > 0) {
		document.getElementById("get_qrm_btn").disabled = "";
		alert(res.content);
	}
	else {
		var sec = 500;
		document.getElementById("get_qrm_btn").disabled = "disabled";
		var id = setInterval(function() {
			document.getElementById("get_qrm_btn").value = "(" + sec + ")秒重新获取";
			sec--;
			if (sec == 0) {
				clearInterval(id);

				document.getElementById("get_qrm_btn").disabled = "";
				document.getElementById("get_qrm_btn").value = "获取手机确认码";
			}
		}, 1000);
	}
}

function register2(){
	var frm = document.forms['formUser'];
	var username = Utils.trim(frm.elements['username'].value);
	var password = Utils.trim(frm.elements['password'].value);
	var confirm_password = Utils.trim(frm.elements['confirm_password'].value);
	var checked_agreement = frm.elements['agreement'].checked;

	var shop_type = Utils.trim(frm.elements['shop_type'].value);
	var shop_name = Utils.trim(frm.elements['shop_name'].value);
	var province = Utils.trim(frm.elements['province'].value);
	var city = Utils.trim(frm.elements['city'].value);
	var district = Utils.trim(frm.elements['district'].value);

	var shop_addr = Utils.trim(frm.elements['shop_addr'].value);
	var mobile = Utils.trim(frm.elements['mobile'].value);
	var qrm = Utils.trim(frm.elements['qrm'].value);
	var captcha = Utils.trim(frm.elements['captcha'].value);
	
	if (username.length == 0)
	{
		alert(username_empty);
		return false;
	}
	else if (username[0]>='0' && username[0]<='9')
	{
		alert('用户名不能以数字开头');
		return false;
	}
	else if (username.match(/^\s*$|^c:\\con\\con$|[%,\'\*\"\s\t\<\>\&\\]/))
	{
		alert(username_invalid);
		return false;
	}
	else if (username.replace(/[^\x00-\xff]/g, "**").length < 3)
	{
		alert(username_shorter);
		return false;
	}
	else if (username.replace(/[^\x00-\xff]/g, "**").length > 14)
	{
		alert(msg_un_length);
		return false;
	}
	if (password.length == 0) {
		alert('密码不能为空');
		return false;
	}
	else if (password.length < 6) {
		alert(password_shorter);
		return false;
	}
	if (/ /.test(password) == true) {
		alert(passwd_balnk);
		return false;
	}
	if (confirm_password != password) {
		alert(confirm_password_invalid);
		return false;
	}
	if (shop_name.length == 0) {
		alert('店铺名称不能为空');
		return false;
	}
	if (shop_type == 0) {
		alert('请选择店铺类型');
		return false;
	}
	if (province == "0" || city == "0" || district == "0" || province=='' || city==''|| district=='') {
		alert('请选择店铺区域');
		return false;
	}
	if (shop_addr.length == 0) {
		alert('店铺地址不能为空');
		return false;
	}
	if (mobile.length == 0) {
		alert('手机号不能为空');
		return false;
	}
	if (mobile.length > 0) {
		if (!(Utils.isMobile(mobile))) {
			alert(mobile_phone_invalid);
			return false;
		}
	}
	if(captcha.length == 0)
	{
		alert('验证码不能为空');
		return false;
	}
	if (qrm.length == 0) {
		alert('手机确认码号不能为空');
		return false;
	}
	
	if (checked_agreement != true) {
		alert(agreement);
		return false;
	}
	Ajax.call( 'user.php?act=act_register', $('#formUser').serialize(), act_reg_callback , 'POST', 'JSON', true, true );
	return false;
}
function act_reg_callback(res){
	alert(res.msg);
	document.getElementById("captchaimg").src = 'captcha.php?' + Math.random();
	if(res.err == 0)
	{
		window.location = 'user.php';
	}
}
function submitForget(){
	var status = true;
	var mobile = $('#mobile_phone').val();
    var mobile_code = $('#mobile_code').val();
	if(mobile.length == ''){
		alert('请填写手机号码');
		return false;
	}
	if(mobile_code.length == ''){
		alert('请填写手机验证码');
		return false;
	}
	$.ajax({
		type: "POST",
		url: "sms.php?act=check",
		data: "mobile="+mobile+"&mobile_code="+mobile_code,
		dataType: "json",
		async: false,
		success: function(result){
			if (result.code!=2){
				alert(result.msg);
				status = false;
			}
		}
	});
	return status;
}
		
var iTime = 59;
var Account;
function RemainTime(){
  document.getElementById('zphone').disabled = true;
  var iSecond,sSecond="",sTime="";
  if (iTime >= 0){
    iSecond = parseInt(iTime%60);
    if (iSecond >= 0){
      sSecond = iSecond + "秒";
    }
    sTime=sSecond;
    if(iTime==0){
      clearTimeout(Account);
      sTime='获取手机验证码';
      iTime = 59;
      document.getElementById('zphone').disabled = false;
    }else{
      Account = setTimeout("RemainTime()",1000);
      iTime=iTime-1;
    }
  }else{
    sTime='没有倒计时';
  }
  document.getElementById('zphone').value = sTime;
}