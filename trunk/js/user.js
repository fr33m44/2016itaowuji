/* $Id : user.js 4865 2007-01-31 14:04:10Z paulgao $ */
/* *
 * 修改会员信息
 */
function userEdit() {
	var frm = document.forms['formEdit'];
	var email = frm.elements['email'].value;
	var msg = '';
	var reg = null;
	var passwd_answer = frm.elements['passwd_answer'] ? Utils.trim(frm.elements['passwd_answer'].value) : '';
	var sel_question = frm.elements['sel_question'] ? Utils.trim(frm.elements['sel_question'].value) : '';


	if (passwd_answer.length > 0 && sel_question == 0 || document.getElementById('passwd_quesetion') && passwd_answer.length == 0) {
		msg += no_select_question + '\n';
	}

	for (i = 7; i < frm.elements.length - 2; i++) // 从第七项开始循环检查是否为必填项
	{
		needinput = document.getElementById(frm.elements[i].name + 'i') ? document.getElementById(frm.elements[i].name + 'i') : '';

		if (needinput != '' && frm.elements[i].value.length == 0) {
			msg += '- ' + needinput.innerHTML + msg_blank + '\n';
		}
	}

	if (msg.length > 0) {
		alert(msg);
		return false;
	}
	else {
		return true;
	}
}

/* 会员修改密码 */
function editPassword() {
	var frm = document.forms['formPassword'];
	var old_password = frm.elements['old_password'].value;
	var new_password = frm.elements['new_password'].value;
	var confirm_password = frm.elements['comfirm_password'].value;

	var msg = '';
	var reg = null;

	if (old_password.length == 0) {
		msg += old_password_empty + '\n';
	}

	if (new_password.length == 0) {
		msg += new_password_empty + '\n';
	}

	if (confirm_password.length == 0) {
		msg += confirm_password_empty + '\n';
	}

	if (new_password.length > 0 && confirm_password.length > 0) {
		if (new_password != confirm_password) {
			msg += both_password_error + '\n';
		}
	}

	if (msg.length > 0) {
		alert(msg);
		return false;
	}
	else {
		return true;
	}
}

/* *
 * 对会员的留言输入作处理
 */
function submitMsg() {
	var frm = document.forms['formMsg'];
	var msg_title = frm.elements['msg_title'].value;
	var msg_content = frm.elements['msg_content'].value;
	var msg = '';

	if (msg_title.length == 0) {
		msg += msg_title_empty + '\n';
	}
	if (msg_content.length == 0) {
		msg += msg_content_empty + '\n'
	}

	if (msg_title.length > 200) {
		msg += msg_title_limit + '\n';
	}

	if (msg.length > 0) {
		alert(msg);
		return false;
	}
	else {
		return true;
	}
}

/* *
 * 会员找回密码时，对输入作处理
 */
function submitPwdInfo() {
	var frm = document.forms['formUser'];
	var mobile = frm.elements['mobile'].value;
	var qrm = frm.elements['qrm'].value;
	var captcha = frm.elements['captcha'].value;

	var errorMsg = '';
	if (mobile.length == 0) {
		errorMsg += '手机号不能为空' + '\n';
	}

	if (qrm.length == 0) {
		errorMsg += '确认码不能为空' + '\n';
	}
	if (captcha.length == 0) {
		errorMsg += '验证码不能为空' + '\n';
	}
	if (!(Utils.isMobile(mobile))) {
		errorMsg += '手机号格式不对' + '\n';
	}

	if (errorMsg.length > 0)
	{
		alert(errorMsg);
		return false;
	}

	return true;
}

/* *
 * 会员找回密码时，对输入作处理
 */
function submitPwd() {
	var frm = document.forms['getPassword2'];
	var password = frm.elements['new_password'].value;
	var confirm_password = frm.elements['confirm_password'].value;

	var errorMsg = '';
	if (password.length == 0) {
		errorMsg += new_password_empty + '\n';
	}

	if (confirm_password.length == 0) {
		errorMsg += confirm_password_empty + '\n';
	}

	if (confirm_password != password) {
		errorMsg += both_password_error + '\n';
	}

	if (errorMsg.length > 0) {
		alert(errorMsg);
		return false;
	}
	else {
		return true;
	}
}

/* *
 * 处理会员提交的缺货登记
 */
function addBooking() {
	var frm = document.forms['formBooking'];
	var goods_id = frm.elements['id'].value;
	var rec_id = frm.elements['rec_id'].value;
	var number = frm.elements['number'].value;
	var desc = frm.elements['desc'].value;
	var linkman = frm.elements['linkman'].value;
	var email = frm.elements['email'].value;
	var tel = frm.elements['tel'].value;
	var msg = "";

	if (number.length == 0) {
		msg += booking_amount_empty + '\n';
	}
	else {
		var reg = /^[0-9]+/;
		if (!reg.test(number)) {
			msg += booking_amount_error + '\n';
		}
	}

	if (desc.length == 0) {
		msg += describe_empty + '\n';
	}

	if (linkman.length == 0) {
		msg += contact_username_empty + '\n';
	}

	if (email.length == 0) {
		msg += email_empty + '\n';
	}
	else {
		if (!(Utils.isEmail(email))) {
			msg += email_error + '\n';
		}
	}

	if (tel.length == 0) {
		msg += contact_phone_empty + '\n';
	}

	if (msg.length > 0) {
		alert(msg);
		return false;
	}

	return true;
}

/* *
 * 会员登录
 */
function userLogin() {
	var frm = document.forms['formLogin'];
	var username = frm.elements['username'].value;
	var password = frm.elements['password'].value;
	var msg = '';

	if (username.length == 0) {
		msg += username_empty + '\n';
	}

	if (password.length == 0) {
		msg += password_empty + '\n';
	}

	if (msg.length > 0) {
		alert(msg);
		return false;
	}
	else {
		return true;
	}
}

function chkstr(str) {
	for (var i = 0; i < str.length; i++) {
		if (str.charCodeAt(i) < 127 && !str.substr(i, 1).match(/^\w+$/ig)) {
			return false;
		}
	}
	return true;
}

function check_password(password) {
	if (password.length < 6) {
		document.getElementById('password_notice').innerHTML = password_shorter;
	}
	else {
		document.getElementById('password_notice').innerHTML = '';
	}
}

function check_shop_name(shopname) {

	if (Utils.trim(shopname) == '') {
		document.getElementById('shop_name_notice').innerHTML = '店铺名称不能为空';
	}
	else {
		document.getElementById('shop_name_notice').innerHTML = '';
	}
}

function check_captcha(captcha) {

	if (Utils.trim(captcha) == '') {
		document.getElementById('captcha_notice').innerHTML = '验证码不能为空';
	}
	else {
		document.getElementById('captcha_notice').innerHTML = '';
	}
}

function check_shop_addr(shop_addr) {

	if (Utils.trim(shop_addr) == '') {
		document.getElementById('shop_addr_notice').innerHTML = '详细地址不能为空';
	}
	else {
		document.getElementById('shop_addr_notice').innerHTML = '';
	}
	
}




function check_mobile(mobile) {
	
	
	//ajax判断是否已经注册
	
	if (Utils.trim(mobile) == '') {
		document.getElementById('mobile_notice').innerHTML = '手机号不能为空';
	}
	else if (!(Utils.isMobile(mobile))) {
		document.getElementById('mobile_notice').innerHTML = '手机号格式不对';
	}
	else{
		Ajax.call('user.php?act=check_mobile', 'mobile=' + mobile, mobileCheckCallback, 'GET', 'TEXT', true, true);
	}
}

function mobileCheckCallback(result) {
	if (result == "false") 
	{//已经存在
		document.getElementById('mobile_notice').innerHTML = '该手机号已经被注册';
	}
	if (result == "true") 
	{//可以注册
		document.getElementById('mobile_notice').innerHTML = '';
	}
}
function check_qrm(qrm) {
	if (Utils.trim(qrm) == '') {
		document.getElementById('qrm_notice').innerHTML = '确认码不能为空';
	}
	else {
		document.getElementById('qrm_notice').innerHTML = '';
	}
}

function check_conform_password(conform_password) {
	password = document.getElementById('password').value;

	if (conform_password.length < 6) {
		document.getElementById('conform_password_notice').innerHTML = password_shorter;
		return false;
	}
	else if (conform_password != password) {
		document.getElementById('conform_password_notice').innerHTML = confirm_password_invalid;
		return false;
	}
	else {
		document.getElementById('conform_password_notice').innerHTML = '';
	}
}

function is_registered(username) {
	var unlen = username.replace(/[^\x00-\xff]/g, "**").length;

	if (username == '') {
		document.getElementById('username_notice').innerHTML = msg_un_blank;
	}

	else if (!chkstr(username)) {
		document.getElementById('username_notice').innerHTML = msg_un_format;
	}
	else if (username[0] >= '0' && username[0] <='9') {
		document.getElementById('username_notice').innerHTML = '用户名不能以数字开头';
	}
	else if (unlen < 3) {
		document.getElementById('username_notice').innerHTML = username_shorter;
	}
	else if (unlen > 14) {
		document.getElementById('username_notice').innerHTML = msg_un_length;
	}
	else
	{
		Ajax.call('user.php?act=is_registered', 'username=' + username, registed_callback, 'GET', 'TEXT', true, true);
	}
}

function registed_callback(result) {
	if (result == "true") {
		document.getElementById('username_notice').innerHTML = '';
	}
	else {
		document.getElementById('username_notice').innerHTML = msg_un_registered;
	}
}

function get_qrm(mobile, action) {
	/* 检查验证码和手机号 */
	var regex_captcha = /\w{4}$/;

	var captcha = Utils.trim(document.forms['formUser'].elements['captcha'].value);
	var mobile = Utils.trim(document.forms['formUser'].elements['mobile'].value);
	if (captcha == "") {
		alert('验证码不能为空');
		return;
	}
	if (Utils.trim(mobile) == '') {
		alert('手机号不能为空');
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

function qrm_callback(result) {

	if (parseInt(result.error) > 0) {
		document.getElementById('captcha_notice').innerHTML = result.content;
		document.getElementById("get_qrm_btn").disabled = "";
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

function checkEmail(email) {

	if (email == '') {
		document.getElementById('email_notice').innerHTML = msg_email_blank;
	}
	else if (!Utils.isEmail(email)) {
		document.getElementById('email_notice').innerHTML = msg_email_format;
	}
	Ajax.call('user.php?act=check_email', 'email=' + email, check_email_callback, 'GET', 'TEXT', true, true);
}

function check_email_callback(result) {
	if (result == 'ok') {
		document.getElementById('email_notice').innerHTML = '';
	}
	else {
		document.getElementById('email_notice').innerHTML = msg_email_registered;
	}
}

/* *
 * 处理注册用户
 */
function register() {
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
	
	var username_notice = document.getElementById("username_notice").innerHTML;
	var password_notice = document.getElementById("password_notice").innerHTML;
	var shop_name_notice = document.getElementById("shop_name_notice").innerHTML;
	var captcha_notice = document.getElementById("captcha_notice").innerHTML;
	var shop_addr_notice = document.getElementById("shop_addr_notice").innerHTML;
	var mobile_notice = document.getElementById("mobile_notice").innerHTML;
	var qrm_notice = document.getElementById("qrm_notice").innerHTML;
	var conform_password_notice = document.getElementById("conform_password_notice").innerHTML;

	var msg = "";
	// 检查输入
	var msg = '';
	if (username.length == 0)
	{
		msg += username_empty + '\n';
	}
	else if (username[0]>='0' && username[0]<='9')
	{
		msg += '用户名不能以数字开头' + '\n';
	}
	else if (username.match(/^\s*$|^c:\\con\\con$|[%,\'\*\"\s\t\<\>\&\\]/))
	{
		msg += username_invalid + '\n';
	}
	else if (username.replace(/[^\x00-\xff]/g, "**").length < 3)
	{
		msg += username_shorter + '\n';
	}
	else if(username_notice != '')
	{
		msg += username_notice + '\n';
	}
	if (shop_addr.length == 0) {
		msg += '详细地址不能为空' + '\n';
	}
	if (shop_name.length == 0) {
		msg += '店铺名称不能为空' + '\n';
	}
	if (mobile.length == 0) {
		msg += '手机号不能为空' + '\n';
	}
	if (qrm.length == 0) {
		msg += '手机确认码号不能为空' + '\n';
	}
	if (captcha.length == 0) {
		msg += '验证码不能为空' + '\n';
	}
	if (password.length == 0) {
		msg += password_empty + '\n';
	}
	else if (password.length < 6) {
		msg += password_shorter + '\n';
	}
	if (/ /.test(password) == true) {
		msg += passwd_balnk + '\n';
	}
	if (confirm_password != password) {
		msg += confirm_password_invalid + '\n';
	}
	if (checked_agreement != true) {
		msg += agreement + '\n';
	}

	if (mobile.length > 0) {
		if (!(Utils.isMobile(mobile))) {
			msg += mobile_phone_invalid + '\n';
		}
	}
	if (province == "0" || city == "0" || district == "0" || province=='' || city==''|| district=='') {
		msg += '店铺地址未填写完整' + '\n';
	}
	if(password_notice != '')
	{
		msg += password_notice + '\n';
	}
	if(conform_password_notice != '')
	{
		msg += conform_password_notice + '\n';
	}
	if(shop_name_notice != '')
	{
		msg += shop_name_notice + '\n';
	}
	if(captcha_notice != '')
	{
		msg += captcha_notice + '\n';
	}
	if(shop_addr_notice != '')
	{
		msg += shop_addr_notice + '\n';
	}
	if(mobile_notice != '')
	{
		msg += mobile_notice + '\n';
	}
	if(qrm_notice != '')
	{
		msg += qrm_notice + '\n';
	}
	

	if (msg.length > 0) {
		alert(msg);
		return false;
	}
	else {
		return true;
	}
}

/* *
 * 用户中心订单保存地址信息
 */
function saveOrderAddress(id) {
	var frm = document.forms['formAddress'];
	var consignee = frm.elements['consignee'].value;
	var email = frm.elements['email'].value;
	var address = frm.elements['address'].value;
	var zipcode = frm.elements['zipcode'].value;
	var tel = frm.elements['tel'].value;
	var mobile = frm.elements['mobile'].value;
	var sign_building = frm.elements['sign_building'].value;
	var best_time = frm.elements['best_time'].value;

	if (id == 0) {
		alert(current_ss_not_unshipped);
		return false;
	}
	var msg = '';
	if (address.length == 0) {
		msg += address_name_not_null + "\n";
	}
	if (consignee.length == 0) {
		msg += consignee_not_null + "\n";
	}

	if (msg.length > 0) {
		alert(msg);
		return false;
	}
	else {
		return true;
	}
}

/* *
 * 会员余额申请
 */
function submitSurplus() {
	var frm = document.forms['formSurplus'];
	var surplus_type = frm.elements['surplus_type'].value;
	var surplus_amount = frm.elements['amount'].value;
	var process_notic = frm.elements['user_note'].value;
	var payment_id = 0;
	var msg = '';

	if (surplus_amount.length == 0) {
		msg += surplus_amount_empty + "\n";
	}
	else {
		var reg = /^[\.0-9]+/;
		if (!reg.test(surplus_amount)) {
			msg += surplus_amount_error + '\n';
		}
	}

	if (process_notic.length == 0) {
		msg += process_desc + "\n";
	}

	if (msg.length > 0) {
		alert(msg);
		return false;
	}

	if (surplus_type == 0) {
		for (i = 0; i < frm.elements.length; i++) {
			if (frm.elements[i].name == "payment_id" && frm.elements[i].checked) {
				payment_id = frm.elements[i].value;
				break;
			}
		}

		if (payment_id == 0) {
			alert(payment_empty);
			return false;
		}
	}

	return true;
}

/* *
 *  处理用户添加一个红包
 */
function addBonus() {
	var frm = document.forms['addBouns'];
	var bonus_sn = frm.elements['bonus_sn'].value;

	if (bonus_sn.length == 0) {
		alert(bonus_sn_empty);
		return false;
	}
	else {
		var reg = /^[0-9]{10}$/;
		if (!reg.test(bonus_sn)) {
			alert(bonus_sn_error);
			return false;
		}
	}

	return true;
}

/* *
 *  合并订单检查
 */
function mergeOrder() {
	if (!confirm(confirm_merge)) {
		return false;
	}

	var frm = document.forms['formOrder'];
	var from_order = frm.elements['from_order'].value;
	var to_order = frm.elements['to_order'].value;
	var msg = '';

	if (from_order == 0) {
		msg += from_order_empty + '\n';
	}
	if (to_order == 0) {
		msg += to_order_empty + '\n';
	}
	else if (to_order == from_order) {
		msg += order_same + '\n';
	}
	if (msg.length > 0) {
		alert(msg);
		return false;
	}
	else {
		return true;
	}
}

/* *
 * 订单中的商品返回购物车
 * @param       int     orderId     订单号
 */
function returnToCart(orderId) {
	Ajax.call('user.php?act=return_to_cart', 'order_id=' + orderId, returnToCartResponse, 'POST', 'JSON');
}

function returnToCartResponse(result) {
	alert(result.message);
}

/* *
 * 检测密码强度
 * @param       string     pwd     密码
 */
function checkIntensity(pwd) {
	var Mcolor = "#FFF",
		Lcolor = "#FFF",
		Hcolor = "#FFF";
	var m = 0;

	var Modes = 0;
	for (i = 0; i < pwd.length; i++) {
		var charType = 0;
		var t = pwd.charCodeAt(i);
		if (t >= 48 && t <= 57) {
			charType = 1;
		}
		else if (t >= 65 && t <= 90) {
			charType = 2;
		}
		else if (t >= 97 && t <= 122)
			charType = 4;
		else
			charType = 4;
		Modes |= charType;
	}

	for (i = 0; i < 4; i++) {
		if (Modes & 1) m++;
		Modes >>>= 1;
	}

	if (pwd.length <= 4) {
		m = 1;
	}

	switch (m) {
		case 1:
			Lcolor = "2px solid red";
			Mcolor = Hcolor = "2px solid #DADADA";
			break;
		case 2:
			Mcolor = "2px solid #f90";
			Lcolor = Hcolor = "2px solid #DADADA";
			break;
		case 3:
			Hcolor = "2px solid #3c0";
			Lcolor = Mcolor = "2px solid #DADADA";
			break;
		case 4:
			Hcolor = "2px solid #3c0";
			Lcolor = Mcolor = "2px solid #DADADA";
			break;
		default:
			Hcolor = Mcolor = Lcolor = "";
			break;
	}
	if (document.getElementById("pwd_lower")) {
		document.getElementById("pwd_lower").style.borderBottom = Lcolor;
		document.getElementById("pwd_middle").style.borderBottom = Mcolor;
		document.getElementById("pwd_high").style.borderBottom = Hcolor;
	}


}

function changeType(obj) {
	if (obj.getAttribute("min") && document.getElementById("ECS_AMOUNT")) {
		document.getElementById("ECS_AMOUNT").disabled = false;
		document.getElementById("ECS_AMOUNT").value = obj.getAttribute("min");
		if (document.getElementById("ECS_NOTICE") && obj.getAttribute("to") && obj.getAttribute('fee')) {
			var fee = parseInt(obj.getAttribute("fee"));
			var to = parseInt(obj.getAttribute("to"));
			if (fee < 0) {
				to = to + fee * 2;
			}
			document.getElementById("ECS_NOTICE").innerHTML = notice_result + to;
		}
	}
}

function calResult() {
	var amount = document.getElementById("ECS_AMOUNT").value;
	var notice = document.getElementById("ECS_NOTICE");

	reg = /^\d+$/;
	if (!reg.test(amount)) {
		notice.innerHTML = notice_not_int;
		return;
	}
	amount = parseInt(amount);
	var frm = document.forms['transform'];
	for (i = 0; i < frm.elements['type'].length; i++) {
		if (frm.elements['type'][i].checked) {
			var min = parseInt(frm.elements['type'][i].getAttribute("min"));
			var to = parseInt(frm.elements['type'][i].getAttribute("to"));
			var fee = parseInt(frm.elements['type'][i].getAttribute("fee"));
			var result = 0;
			if (amount < min) {
				notice.innerHTML = notice_overflow + min;
				return;
			}

			if (fee > 0) {
				result = (amount - fee) * to / (min - fee);
			}
			else {
				//result = (amount + fee* min /(to+fee)) * (to + fee) / min ;
				result = amount * (to + fee) / min + fee;
			}

			notice.innerHTML = notice_result + parseInt(result + 0.5);
		}
	}
}