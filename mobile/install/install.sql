/*
EcTouch 商创版 sql文件 by 模板堂

Date: 2014-04-21 13:39:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ecs_touch_ad_position
-- ----------------------------
DROP TABLE IF EXISTS `ecs_touch_ad_position`;
CREATE TABLE `ecs_touch_ad_position` (
  `position_id` tinyint(3) unsigned NOT NULL auto_increment,
  `position_name` varchar(60) NOT NULL default '',
  `ad_width` smallint(5) unsigned NOT NULL default '0',
  `ad_height` smallint(5) unsigned NOT NULL default '0',
  `position_desc` varchar(255) NOT NULL default '',
  `position_style` text NOT NULL,
  PRIMARY KEY  (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecs_touch_ad_position
-- ----------------------------
INSERT INTO `ecs_touch_ad_position` VALUES ('1', '手机版首页Banner', '360', '168', '', '<ul>\r\n{foreach from=$ads item=ad}\r\n  <li>{$ad}</li>\r\n{/foreach}\r\n</ul>\r\n');

-- ----------------------------
-- Table structure for ecs_touch_ad
-- ----------------------------
DROP TABLE IF EXISTS `ecs_touch_ad`;
CREATE TABLE `ecs_touch_ad` (
  `ad_id` smallint(5) unsigned NOT NULL auto_increment,
  `position_id` smallint(5) unsigned NOT NULL default '0',
  `media_type` tinyint(3) unsigned NOT NULL default '0',
  `ad_name` varchar(60) NOT NULL default '',
  `ad_link` varchar(255) NOT NULL default '',
  `ad_code` text NOT NULL,
  `start_time` int(11) NOT NULL default '0',
  `end_time` int(11) NOT NULL default '0',
  `link_man` varchar(60) NOT NULL default '',
  `link_email` varchar(60) NOT NULL default '',
  `link_phone` varchar(60) NOT NULL default '',
  `click_count` mediumint(8) unsigned NOT NULL default '0',
  `enabled` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`ad_id`),
  KEY `position_id` (`position_id`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecs_touch_ad
-- ----------------------------
INSERT INTO `ecs_touch_ad` VALUES ('1', '1', '0', '1', '', 'http://demo.ecmoban.com/shop/data/afficheimg/1394415487832396515.jpg', '1396339200', '1525161600', '', '', '', '0', '1');
INSERT INTO `ecs_touch_ad` VALUES ('2', '1', '0', '2', '', 'http://demo.ecmoban.com/shop/data/afficheimg/1394415508290057627.jpg', '1396339200', '1525161600', '', '', '', '0', '1');
INSERT INTO `ecs_touch_ad` VALUES ('3', '1', '0', '3', '', 'http://demo.ecmoban.com/shop/data/afficheimg/1394415497283455138.jpg', '1396339200', '1525161600', '', '', '', '0', '1');

-- ----------------------------
-- Table structure for ecs_touch_nav
-- ----------------------------
DROP TABLE IF EXISTS `ecs_touch_nav`;
CREATE TABLE `ecs_touch_nav` (
  `id` mediumint(8) NOT NULL auto_increment,
  `ctype` varchar(10) default NULL,
  `cid` smallint(5) unsigned default NULL,
  `name` varchar(255) NOT NULL,
  `ifshow` tinyint(1) NOT NULL,
  `vieworder` tinyint(1) NOT NULL,
  `opennew` tinyint(1) NOT NULL,
  `url` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`),
  KEY `ifshow` (`ifshow`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecs_touch_nav
-- ----------------------------
INSERT INTO `ecs_touch_nav` VALUES ('1', '', '0', '全部分类', '1', '0', '0', 'cat_all.php', 'icon_indexn_01.png', 'middle');
INSERT INTO `ecs_touch_nav` VALUES ('2', null, '0', '帮助中心', '1', '0', '0', 'article_cat.php?id=3', 'icon_indexn_02.png', 'middle');
INSERT INTO `ecs_touch_nav` VALUES ('3', '', '0', '个人中心', '1', '0', '0', 'user.php', 'icon_indexn_03.png', 'middle');
INSERT INTO `ecs_touch_nav` VALUES ('4', null, '0', '分享', '1', '0', '0', 'ectouch.php?act=share', 'icon_indexn_04.png', 'middle');
INSERT INTO `ecs_touch_nav` VALUES ('5', null, '0', '联系我们', '1', '0', '0', 'ectouch.php?act=contact', 'icon_indexn_05.png', 'middle');
INSERT INTO `ecs_touch_nav` VALUES ('6', null, '0', '论坛', '1', '0', '0', 'http://bbs.ecmoban.com', 'icon_indexn_06.png', 'middle');
INSERT INTO `ecs_touch_nav` VALUES ('7', null, '0', '客户端', '1', '0', '0', 'http://www.ecmoban.com/app/ecmoban.apk', 'icon_indexn_07.png', 'middle');
INSERT INTO `ecs_touch_nav` VALUES ('8', null, '0', '电脑版', '1', '0', '0', '../', 'icon_indexn_08.png', 'middle');

-- ----------------------------
-- Table structure for ecs_touch_payment
-- ----------------------------
DROP TABLE IF EXISTS `ecs_touch_payment`;
CREATE TABLE `ecs_touch_payment` (
  `pay_id` tinyint(3) unsigned NOT NULL auto_increment,
  `pay_code` varchar(20) NOT NULL default '',
  `pay_name` varchar(120) NOT NULL default '',
  `pay_fee` varchar(10) NOT NULL default '0',
  `pay_desc` text NOT NULL,
  `pay_order` tinyint(3) unsigned NOT NULL default '0',
  `pay_config` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL default '0',
  `is_cod` tinyint(1) unsigned NOT NULL default '0',
  `is_online` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pay_id`),
  UNIQUE KEY `pay_code` (`pay_code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ecs_touch_shipping
-- ----------------------------
DROP TABLE IF EXISTS `ecs_touch_shipping`;
CREATE TABLE `ecs_touch_shipping` (
  `shipping_id` tinyint(3) unsigned NOT NULL auto_increment,
  `shipping_code` varchar(20) NOT NULL default '',
  `shipping_name` varchar(120) NOT NULL default '',
  `shipping_desc` varchar(255) NOT NULL default '',
  `insure` varchar(10) NOT NULL default '0',
  `support_cod` tinyint(1) unsigned NOT NULL default '0',
  `enabled` tinyint(1) unsigned NOT NULL default '0',
  `shipping_print` text NOT NULL,
  `print_bg` varchar(255) default NULL,
  `config_lable` text,
  `print_model` tinyint(1) default '0',
  `shipping_order` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`shipping_id`),
  KEY `shipping_code` (`shipping_code`,`enabled`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecs_touch_shipping
-- ----------------------------
INSERT INTO `ecs_touch_shipping` SELECT * FROM `ecs_shipping`;

-- ----------------------------
-- Table structure for ecs_touch_shipping_area
-- ----------------------------
DROP TABLE IF EXISTS `ecs_touch_shipping_area`;
CREATE TABLE `ecs_touch_shipping_area` (
  `shipping_area_id` smallint(5) unsigned NOT NULL auto_increment,
  `shipping_area_name` varchar(150) NOT NULL default '',
  `shipping_id` tinyint(3) unsigned NOT NULL default '0',
  `configure` text NOT NULL,
  PRIMARY KEY  (`shipping_area_id`),
  KEY `shipping_id` (`shipping_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ecs_touch_shipping_area
-- ----------------------------
INSERT INTO `ecs_touch_shipping_area` SELECT * FROM `ecs_shipping_area`;

-- ----------------------------
-- Table structure for ecs_touch_shop_config
-- ----------------------------
DROP TABLE IF EXISTS `ecs_touch_shop_config`;
CREATE TABLE `ecs_touch_shop_config` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `parent_id` smallint(5) unsigned NOT NULL default '0',
  `code` varchar(30) NOT NULL default '',
  `type` varchar(10) NOT NULL default '',
  `store_range` varchar(255) NOT NULL default '',
  `store_dir` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=907 DEFAULT CHARSET=utf8;

-- ----------------------------
-- 增加短信接口配置项
-- ----------------------------
DELETE FROM ecs_shop_config where code = 'sms_ecmoban_user';
DELETE FROM ecs_shop_config where code = 'sms_ecmoban_password';
DELETE FROM ecs_shop_config where code = 'sms_signin';
INSERT INTO `ecs_shop_config` (parent_id, code, type, store_range, store_dir, value, sort_order)VALUES (8, 'sms_ecmoban_user', 'text', '', '', '', 0);
INSERT INTO `ecs_shop_config` (parent_id, code, type, store_range, store_dir, value, sort_order)VALUES (8, 'sms_ecmoban_password', 'password', '', '', '', 0);
INSERT INTO `ecs_shop_config` (parent_id, code, type, store_range, store_dir, value, sort_order)VALUES (8, 'sms_signin', 'select', '1,0', '', '0', 1);

-- ----------------------------
-- Records of ecs_touch_shop_config
-- ----------------------------
INSERT INTO `ecs_touch_shop_config` SELECT * FROM `ecs_shop_config`;
UPDATE `ecs_touch_shop_config` SET `type`='hidden' WHERE `code`='wap';

-- ----------------------------
-- 表的结构 `ecs_touch_template`
-- ----------------------------
DROP TABLE IF EXISTS `ecs_touch_template`;
CREATE TABLE IF NOT EXISTS `ecs_touch_template` (
  `filename` varchar(30) NOT NULL DEFAULT '',
  `region` varchar(40) NOT NULL DEFAULT '',
  `library` varchar(40) NOT NULL DEFAULT '',
  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `number` tinyint(1) unsigned NOT NULL DEFAULT '5',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `theme` varchar(60) NOT NULL DEFAULT '',
  `remarks` varchar(30) NOT NULL DEFAULT '',
  KEY `filename` (`filename`,`region`),
  KEY `theme` (`theme`),
  KEY `remarks` (`remarks`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- 转存表中的数据 `ecs_touch_template`
-- ----------------------------
INSERT INTO `ecs_touch_template` (`filename`, `region`, `library`, `sort_order`, `id`, `number`, `type`, `theme`, `remarks`) VALUES
('index', 'touch首页广告区域', '/library/ad_position.lbi', 0, 1, 4, 4, 'default', ''),
('index', '中部主区域', '/library/recommend_hot.lbi', 2, 0, 10, 0, 'default', ''),
('index', '中部主区域', '/library/recommend_new.lbi', 1, 0, 10, 0, 'default', ''),
('index', '中部主区域', '/library/recommend_best.lbi', 0, 0, 10, 0, 'default', '');

-- -------------------------- 微信通 --------------------------

-- ----------------------------
-- 表的结构 `wxch_cfg`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_cfg`;
CREATE TABLE `wxch_cfg` (
  `cfg_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `cfg_name` varchar(64) NOT NULL DEFAULT '',
  `cfg_value` varchar(100) NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`cfg_id`),
  UNIQUE KEY `cfg_name` (`cfg_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- ----------------------------
-- 转存表中的数据 `wxch_cfg`
-- ----------------------------
INSERT INTO `wxch_cfg` (`cfg_id`, `cfg_name`, `cfg_value`, `autoload`) VALUES
(1, 'murl', 'mobile/', 'yes'),
(2, 'baseurl', 'http://ecshop273utf8.com/shop/', 'yes'),
(3, 'imgpath', 'local', 'yes'),
(4, 'plustj', 'true', 'yes'),
(5, 'userpwd', 'ecmoban', 'yes'),
(6, 'cxbd', 'true', 'yes'),
(8, 'oauth', 'false', 'yes'),
(7, 'bd', 'web', 'yes'),
(9, 'goods', 'false', 'yes'),
(10, 'article', 'article.php?id=', 'yes');

-- ----------------------------
-- 表的结构 `wxch_config`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_config`;
CREATE TABLE `wxch_config` (
  `id` int(1) NOT NULL,
  `token` varchar(100) NOT NULL,
  `appid` char(18) NOT NULL,
  `appsecret` char(32) NOT NULL,
  `access_token` TEXT NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- 转存表中的数据 `wxch_config`
-- ----------------------------
INSERT INTO `wxch_config` (`id`, `token`, `appid`, `appsecret`, `access_token`, `dateline`) VALUES (1, 'weixin', '', '', '', 0);

-- ----------------------------
-- 表的结构 `wxch_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_coupon`;
CREATE TABLE `wxch_coupon` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `type_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

-- ----------------------------
-- 转存表中的数据 `wxch_coupon`
-- ----------------------------
INSERT INTO `wxch_coupon` (`id`, `type_id`) VALUES (1, 4);

-- ----------------------------
-- 表的结构 `wxch_keywords`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_keywords`;
CREATE TABLE `wxch_keywords` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `contents` text NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- ----------------------------
-- 表的结构 `wxch_keywords_article`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_keywords_article`;
CREATE TABLE `wxch_keywords_article` (
  `kws_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `article_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`kws_id`,`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- 转存表中的数据 `wxch_keywords_article`
-- ----------------------------
INSERT INTO `wxch_keywords_article` (`kws_id`, `article_id`) VALUES
(95, 25),
(95, 26),
(95, 27),
(95, 28),
(95, 29),
(95, 30),
(95, 31),
(96, 23),
(96, 24),
(96, 25);

-- ----------------------------
-- 表的结构 `wxch_lang`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_lang`;
CREATE TABLE `wxch_lang` (
  `lang_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(64) NOT NULL,
  `lang_value` text NOT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- ----------------------------
-- 转存表中的数据 `wxch_lang`
-- ----------------------------
INSERT INTO `wxch_lang` (`lang_id`, `lang_name`, `lang_value`) VALUES
(1, 'regmsg', '<p>欢迎关注微信公众号</p>'),
(2, 'help', '功能说明：\r\n输入news显示新品\r\n输入hot显示热销\r\n输入best显示推荐\r\n输入bd进入绑定会员流程\r\n输入ddcx查询最后一个订单\r\n输入kdcx查询最后一个订单中的快递\r\n输入ddlb显示多个订单信息\r\n输入help显示帮助说明'),
(3, 'coupon', '欢迎关注微信公众号,您已经领取过优惠卷'),
(4, 'coupon1', '欢迎关注微信公众号,活动期间关注送'),
(5, 'coupon2', '欢迎关注微信公众号,优惠卷已送完'),
(6, 'coupon3', '相关功能'),
(7, 'qdok', '签到成功+'),
(8, 'qdno', '签到数次已用完'),
(9, 'qdstop', '已经关闭了签到'),
(10, 'bd', '快速绑定会员帐号，享受我们提供给你更全面的服务'),
(11, 'prize_egg', '砸金蛋抽奖规则'),
(12, 'prize_dzp', '大转盘抽奖活动说明');

-- ----------------------------
-- 表的结构 `wxch_menu`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_menu`;
CREATE TABLE `wxch_menu` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `menu_type` varchar(6) NOT NULL,
  `level` int(1) NOT NULL,
  `name` varchar(30) NOT NULL,
  `value` varchar(250) NOT NULL,
  `aid` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=19;

-- ----------------------------
-- 转存表中的数据 `wxch_menu`
-- ----------------------------
INSERT INTO `wxch_menu` (`id`, `menu_type`, `level`, `name`, `value`, `aid`) VALUES
(1, 'click', 1, '商品查看', '', 0),
(2, 'click', 1, '会员功能', '', 0),
(3, 'click', 1, '更多..', '', 0),
(4, 'click', 2, '热销产品', 'hot', 1),
(5, 'click', 2, '精品推荐', 'best', 1),
(6, 'click', 2, '新款推荐', 'news', 1),
(7, 'click', 2, '', '', 1),
(8, 'click', 2, '', '', 1),
(9, 'click', 2, '会员绑定', 'bd', 2),
(10, 'click', 2, '重新绑定', 'cxbd', 2),
(11, 'click', 2, '订单查询', 'ddcx', 2),
(12, 'click', 2, '', '', 2),
(13, 'click', 2, '', '', 2),
(14, 'click', 2, '帮忙说明', 'help', 3),
(15, 'click', 2, '订单快递', 'kdcx', 3),
(16, 'click', 2, '菜单回复', 'cdhf', 3),
(17, 'view', 2, '首页', 'http://m.ecmoban.com', 3),
(18, 'click', 2, '', '', 3);

-- ----------------------------
-- 表的结构 `wxch_message`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_message`;
CREATE TABLE `wxch_message` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `wxid` char(28) NOT NULL,
  `w_message` text NOT NULL,
  `message` text NOT NULL,
  `belong` int(9) unsigned NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wxid` (`wxid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- ----------------------------
-- 表的结构 `wxch_msg`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_msg`;
CREATE TABLE `wxch_msg` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `function` varchar(30) NOT NULL,
  `command` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13;

-- ----------------------------
-- 转存表中的数据 `wxch_msg`
-- ----------------------------
INSERT INTO `wxch_msg` (`id`, `name`, `function`, `command`) VALUES
(1, '新品', 'news', 'xk 新款 News'),
(2, '精品', 'best', 'Best 精品'),
(3, '热销', 'hot', 'Hot 热销'),
(4, '绑定会员', 'bd', 'BD Bd 绑定会员'),
(5, '重新绑定', 'cxbd', '重新绑定 Cxbd'),
(6, '订单列表', 'ddlb', '订单列表 Ddlb'),
(7, '订单查询', 'ddcx', '订单查询 Ddcx'),
(8, '订单快递', 'kdcx', '订单快递 Kdcx'),
(9, '帮助说明', 'help', '帮助说明 Help 帮助'),
(10, '砸金蛋', 'zjd', '砸金蛋 Zjd'),
(11, '签到', 'qiandao', 'qiandao 签到'),
(12, '大转盘', 'dzp', '大转盘 Dzp');

-- ----------------------------
-- 表的结构 `wxch_oauth`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_oauth`;
CREATE TABLE `wxch_oauth` (
  `oid` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `contents` text NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12;

-- ----------------------------
-- 转存表中的数据 `wxch_oauth`
-- ----------------------------
INSERT INTO `wxch_oauth` (`oid`, `name`, `contents`, `count`, `status`) VALUES
(1, '手机版网站首页', 'http://m.ecmoban.com/', 1, 1);

-- ----------------------------
-- 表的结构 `wxch_order`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_order`;
CREATE TABLE `wxch_order` (
  `id` tinyint(1) NOT NULL,
  `order_name` varchar(30) NOT NULL,
  `title` varchar(100) NOT NULL,
  `image` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `autoload` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- 转存表中的数据 `wxch_order`
-- ----------------------------
INSERT INTO `wxch_order` (`id`, `order_name`, `title`, `image`, `content`, `autoload`) VALUES
(1, 'order', '发货提醒', 'images/201401/1388925596106500893.jpg', '<p>&nbsp;ffffff</p>', 'yes'),
(2, 'reorder', '订单确认提醒', 'images/201401/1389555522246530163.png', 'fff', 'yes'),
(3, 'pay', '成功支付', '', '已经成功支付', 'yes');

-- ----------------------------
-- 表的结构 `wxch_pay`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_pay`;
CREATE TABLE `wxch_pay` (
  `id` int(1) NOT NULL,
  `appid` char(18) NOT NULL,
  `paysignkey` char(128) NOT NULL,
  `partnerkey` char(32) NOT NULL,
  `appsecret` char(32) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- 转存表中的数据 `wxch_pay`
-- ----------------------------
INSERT INTO `wxch_pay` (`id`, `appid`, `paysignkey`, `partnerkey`, `appsecret`, `dateline`) VALUES
(1, '','','', '', 0);

-- ----------------------------
-- 表的结构 `wxch_point`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_point`;
CREATE TABLE `wxch_point` (
  `point_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `point_name` varchar(64) NOT NULL DEFAULT '',
  `point_value` int(3) unsigned NOT NULL,
  `point_num` int(3) NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`point_id`),
  UNIQUE KEY `option_name` (`point_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;

-- ----------------------------
-- 转存表中的数据 `wxch_point`
-- ----------------------------
INSERT INTO `wxch_point` (`point_id`, `point_name`, `point_value`, `point_num`, `autoload`) VALUES
(1, 'news', 5, 2, 'yes'),
(2, 'best', 15, 1, 'yes'),
(3, 'hot', 5, 1, 'yes'),
(4, 'bd', 30, 1, 'yes'),
(5, 'ddcx', 5, 1, 'yes'),
(6, 'kdcx', 5, 1, 'yes'),
(7, 'zjd', 5, 0, 'no'),
(8, 'qiandao', 10, 1, 'yes'),
(9, 'dzp', 10, 1, 'yes');

-- ----------------------------
-- 表的结构 `wxch_point_record`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_point_record`;
CREATE TABLE `wxch_point_record` (
  `pr_id` int(7) NOT NULL AUTO_INCREMENT,
  `wxid` char(28) NOT NULL,
  `point_name` varchar(64) NOT NULL,
  `num` int(5) NOT NULL,
  `lasttime` int(10) NOT NULL,
  `datelinie` int(10) NOT NULL,
  PRIMARY KEY (`pr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- ----------------------------
-- 表的结构 `wxch_qr`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_qr`;
CREATE TABLE `wxch_qr` (
  `qid` int(7) NOT NULL AUTO_INCREMENT,
  `type` varchar(2) NOT NULL,
  `expire_seconds` int(4) NOT NULL,
  `action_name` varchar(30) NOT NULL,
  `scene_id` int(7) NOT NULL,
  `ticket` varchar(120) NOT NULL,
  `scene` varchar(200) NOT NULL,
  `qr_path` varchar(200) NOT NULL,
  `subscribe` int(8) unsigned NOT NULL,
  `scan` int(8) unsigned NOT NULL,
  `function` varchar(100) NOT NULL,
  `affiliate` int(8) NOT NULL,
  `endtime` int(10) NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
UPDATE `wxch_qr` SET `type` = 'qr';

-- ----------------------------
-- 表的结构 `wxch_user`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_user`;
CREATE TABLE `wxch_user` (
  `uid` int(7) NOT NULL AUTO_INCREMENT,
  `subscribe` tinyint(1) unsigned NOT NULL,
  `wxid` char(28) NOT NULL,
  `nickname` varchar(200) NOT NULL,
  `sex` tinyint(1) unsigned NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `language` varchar(50) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `subscribe_time` int(10) unsigned NOT NULL,
  `localimgurl` varchar(200) NOT NULL,
  `setp` smallint(2) unsigned NOT NULL,
  `uname` varchar(50) NOT NULL,
  `coupon` varchar(30) NOT NULL,
  `affiliate` int(8) UNSIGNED NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- ----------------------------
-- 会员等级
-- ----------------------------
DELETE FROM `ecs_user_rank` where `rank_id` = 99;
INSERT INTO `ecs_user_rank` (`rank_id`, `rank_name`, `min_points`, `max_points`, `discount`, `show_price`, `special_rank`) VALUES (99, '微信用户', 0, 0, 100, 1, 1);

-- -------------------------- 砸金蛋 --------------------------

-- ----------------------------
-- 表的结构 `wxch_prize`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_prize`;
CREATE TABLE `wxch_prize` (
  `pid` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `fun` varchar(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `num` int(2) unsigned NOT NULL,
  `count` int(8) NOT NULL,
  `loop` int(3) NOT NULL,
  `starttime` int(10) NOT NULL,
  `endtime` int(10) NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- ----------------------------
-- 转存表中的数据 `wxch_prize`
-- ----------------------------
INSERT INTO `wxch_prize` (`pid`, `title`, `fun`, `status`, `num`, `count`, `loop`, `starttime`, `endtime`, `dateline`) VALUES
(1, '正在测试的', 'egg', 0, 1, 6, 1, 1394899200, 1397491200, 1395648563);

-- ----------------------------
-- 表的结构 `wxch_prize_append`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_prize_append`;
CREATE TABLE `wxch_prize_append` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `prize_id` int(4) unsigned NOT NULL,
  `prize_name` varchar(64) NOT NULL,
  `prize_value` int(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- ----------------------------
-- 转存表中的数据 `wxch_prize_append`
-- ----------------------------
INSERT INTO `wxch_prize_append` (`id`, `prize_id`, `prize_name`, `prize_value`) VALUES
(1, 1, '10万汽车1', 1),
(2, 1, 'Macbook2', 2),
(3, 1, 'Ipad3', 3),
(4, 1, '话费100元4', 4),
(5, 1, '优惠卷5', 5),
(6, 1, '未中奖6', 10),
(7, 7, '10万汽车', 1),
(8, 7, 'Macbook', 3),
(9, 7, 'Ipad', 5),
(10, 7, '未中奖', 1000),
(11, 7, '', 0),
(12, 7, '', 0);

-- ----------------------------
-- 表的结构 `wxch_prize_cnum`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_prize_cnum`;
CREATE TABLE `wxch_prize_cnum` (
  `pcid` int(5) NOT NULL AUTO_INCREMENT,
  `paid` int(5) NOT NULL,
  `pid` int(4) NOT NULL,
  `prize_name` varchar(64) NOT NULL,
  `prize_value` int(8) unsigned NOT NULL,
  `user_count` int(8) unsigned NOT NULL,
  PRIMARY KEY (`pcid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- ----------------------------
-- 转存表中的数据 `wxch_prize_cnum`
-- ----------------------------
INSERT INTO `wxch_prize_cnum` (`pcid`, `paid`, `pid`, `prize_name`, `prize_value`, `user_count`) VALUES
(1, 5, 1, '未中奖5', 5, 2),
(2, 10, 7, '未中奖', 1000, 7);

-- ----------------------------
-- 表的结构 `wxch_prize_count`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_prize_count`;
CREATE TABLE `wxch_prize_count` (
  `cid` int(7) NOT NULL AUTO_INCREMENT,
  `pid` int(5) NOT NULL,
  `wxid` char(28) NOT NULL,
  `num` int(5) NOT NULL,
  `count` int(5) unsigned NOT NULL,
  `lasttime` int(10) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- ----------------------------
-- 转存表中的数据 `wxch_prize_count`
-- ----------------------------
INSERT INTO `wxch_prize_count` (`cid`, `pid`, `wxid`, `num`, `count`, `lasttime`, `dateline`) VALUES
(1, 1, 'oo1v-tir7oHXTL42WpwAlNsLTZlc', 0, 5, 1395980256, 1395475456);

-- ----------------------------
-- 表的结构 `wxch_prize_register`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_prize_register`;
CREATE TABLE `wxch_prize_register` (
  `rid` int(8) NOT NULL AUTO_INCREMENT,
  `pid` int(7) unsigned NOT NULL,
  `wxid` char(28) NOT NULL,
  `nickname` varchar(200) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `wxid` (`wxid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- ----------------------------
-- 表的结构 `wxch_prize_users`
-- ----------------------------
DROP TABLE IF EXISTS `wxch_prize_users`;
CREATE TABLE `wxch_prize_users` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `wxid` char(28) NOT NULL DEFAULT '',
  `fun` varchar(10) NOT NULL,
  `nickname` varchar(200) NOT NULL,
  `prize_id` int(5) DEFAULT NULL,
  `prize_name` varchar(64) DEFAULT NULL,
  `prize_sn` varchar(35) NOT NULL,
  `register` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `yn` varchar(3) NOT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prize_id` (`prize_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- `wxch_ver`
--
DROP TABLE IF EXISTS `wxch_ver`;
CREATE TABLE `wxch_ver` (
  `vid` tinyint(1) NOT NULL,
  `type` varchar(5) NOT NULL,
  `ver` varchar(8) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wxch_ver`
--
INSERT INTO `wxch_ver` (`vid`, `type`, `ver`, `dateline`) VALUES
(1, 'ent', '20140507', 1399362212);



DROP TABLE IF EXISTS `wxch_keywords1`;
CREATE TABLE `wxch_keywords1` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `contents` text NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `is_start` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

#
# Data for table "wxch_keywords1"
#

/*!40000 ALTER TABLE `wxch_keywords1` DISABLE KEYS */;
INSERT INTO `wxch_keywords1` VALUES (14,'关注回复图文','关注回复图文',4,'',2,1,0),(26,'关注回复图文','关注回复图文',4,'',0,1,0),(27,'关注回复文本','关注回复文本',3,'欢迎光临 &nbsp;甜心100.做最专业的技术支持和最完善的售后模式！',31,1,1);