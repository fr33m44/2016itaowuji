<?php
/**
* 告警通知手机端
*/

define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/../include/init.php');

//接收数据
$postStr = $GLOBALS['HTTP_RAW_POST_DATA'];

if(!empty($postStr)){
	//转换数据
	$postData = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

	//插入留言表
	$sql = "INSERT INTO " . $GLOBALS['ecs']->table('feedback') . "(msg_title, msg_content, msg_time)" .
            " VALUES ('告警通知', 'ErrorType：$postData[ErrorType] "."\n\r"." 错误描述：$postData[Description] "."\n\r"." 错误详情：$postData[AlarmContent]', '".gmtime()."')";
	$GLOBALS['db']->query($sql);
	echo 'success';
}
else{
	echo '';
}