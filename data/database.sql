# Host: 127.0.0.1  (Version 5.5.53)
# Date: 2017-11-13 14:58:31
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "agency"
#

DROP TABLE IF EXISTS `agency`;
CREATE TABLE `agency` (
  `agency_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '办事处ID',
  `agency_name` varchar(32) NOT NULL DEFAULT '' COMMENT '办事处名称',
  `date_added` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '新增时间',
  `date_modified` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '变更时间',
  PRIMARY KEY (`agency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "agency"
#


#
# Structure for table "bank_description"
#

DROP TABLE IF EXISTS `bank_description`;
CREATE TABLE `bank_description` (
  `bank_id` int(11) NOT NULL COMMENT '银行ID',
  `language_id` int(11) NOT NULL COMMENT '语言ID',
  `bank_name` varchar(255) NOT NULL COMMENT '银行名称',
  `is_top` int(11) unsigned NOT NULL DEFAULT '0',
  `sort_order` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bank_id`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "bank_description"
#

INSERT INTO `bank_description` VALUES (1,1,'安徽省农村信用社',0,0),(2,1,'鞍山银行',0,0),(3,1,'北京农商行',0,0),(4,1,'渤海银行',0,0),(5,1,'包商银行',0,0),(6,1,'北京银行',0,0),(7,1,'长安银行',0,0),(8,1,'长沙银行',0,0),(9,1,'沧州银行',0,0),(10,1,'常熟农商银行',0,0),(11,1,'重庆农商行',0,0),(12,1,'重庆银行',0,0),(13,1,'承德银行',0,0),(14,1,'德阳银行',0,0),(15,1,'大连银行',0,0),(16,1,'东莞农村商业银行',0,0),(17,1,'东莞银行',0,0),(18,1,'德州银行',0,0),(19,1,'东营银行',0,0),(20,1,'鄂尔多斯银行',0,0),(21,1,'福建省农村信用社联合社',0,0),(22,1,'阜新银行',0,0),(23,1,'富滇银行',0,0),(24,1,'福建海峡银行',0,0),(25,1,'广发银行',0,0),(26,1,'贵阳银行',0,0),(27,1,'桂林银行',0,0),(28,1,'广西北部湾银行',0,0),(29,1,'广东华兴银行',0,0),(30,1,'广州农村商业银行',0,0),(31,1,'赣州银行',0,0),(32,1,'广西壮族自治区农村信用社联合社',0,0),(33,1,'广东南粤银行',0,0),(34,1,'广州银行',0,0),(35,1,'恒丰银行',0,0),(36,1,'湖北省农村信用合作联社',0,0),(37,1,'韩亚银行',0,0),(38,1,'韩国企业银行',0,0),(39,1,'徽商银行',0,0),(40,1,'哈尔滨银行',0,0),(41,1,'葫芦岛银行',0,0),(42,1,'杭州银行',0,0),(43,1,'河北银行',0,0),(44,1,'邯郸银行',0,0),(45,1,'海南省农村信用社',0,0),(46,1,'汉口银行',0,0),(47,1,'华夏银行',0,0),(48,1,'湖州银行',0,0),(49,1,'交通银行',0,0),(50,1,'济宁银行',0,0),(51,1,'吉林银行',0,0),(52,1,'吉林省农村信用社联合社',0,0),(53,1,'晋商银行',0,0),(54,1,'晋城银行',0,0),(55,1,'江苏省农村信用社联合社',0,0),(56,1,'江苏银行',0,0),(57,1,'九江银行',0,0),(58,1,'嘉兴银行',0,0),(59,1,'锦州银行',0,0),(60,1,'昆仑银行',0,0),(61,1,'昆山农村商业银行',0,0),(62,1,'开封市商业银行',0,0),(63,1,'莱商银行',0,0),(64,1,'龙江银行',0,0),(65,1,'漯河银行',0,0),(66,1,'柳州银行',0,0),(67,1,'兰州银行',0,0),(68,1,'临商银行',0,0),(69,1,'乐山市商业银行',0,0),(70,1,'廊坊银行',0,0),(71,1,'洛阳银行',0,0),(72,1,'绵阳市商业银行',0,0),(73,1,'宁波银行',0,0),(74,1,'南京银行',0,0),(75,1,'宁夏银行',0,0),(76,1,'宁夏黄河农村商业银行',0,0),(77,1,'宁波东海银行',0,0),(78,1,'南阳市商业银行',0,0),(79,1,'内蒙古银行',0,0),(80,1,'南昌银行',0,0),(81,1,'浦发银行',0,0),(82,1,'攀枝花市商业银行',0,0),(83,1,'齐商银行',0,0),(84,1,'青岛银行',0,0),(85,1,'泉州银行',0,0),(86,1,'青海银行',0,0),(87,1,'日照银行',0,0),(88,1,'深圳平安银行',0,0),(89,1,'绍兴银行',0,0),(90,1,'深圳农村商业银行',0,0),(91,1,'顺德农商银行',0,0),(92,1,'商丘市商业银行',0,0),(93,1,'上饶银行',0,0),(94,1,'三门峡银行',0,0),(95,1,'上海农商银行',0,0),(96,1,'上海银行',0,0),(97,1,'苏州银行',0,0),(98,1,'山东省农村信用社联合社',0,0),(99,1,'天津银行',0,0),(100,1,'泰安市商业银行',0,0),(101,1,'铁岭银行',0,0),(102,1,'天津农商银行',0,0),(103,1,'台州银行',0,0),(104,1,'潍坊银行',0,0),(105,1,'乌鲁木齐市商业银行',0,0),(106,1,'威海市商业银行',0,0),(107,1,'外换银行',0,0),(108,1,'温州银行',0,0),(109,1,'吴江农村商业银行',0,0),(110,1,'兴业银行',0,0),(111,1,'邢台银行',0,0),(112,1,'厦门银行',0,0),(113,1,'西安银行',0,0),(114,1,'新韩银行',0,0),(115,1,'鄞州银行',0,0),(116,1,'烟台银行',0,0),(117,1,'友利银行',0,0),(118,1,'榆次融信村镇银行',0,0),(119,1,'云南省农村信用社',0,0),(120,1,'营口银行',0,0),(121,1,'中国工商银行',0,0),(122,1,'中国农业银行',0,0),(123,1,'中国建设银行',0,0),(124,1,'中国邮政储蓄银行',0,0),(125,1,'中国银行',0,0),(126,1,'招商银行',0,0),(127,1,'中国光大银行',0,0),(128,1,'中信银行',0,0),(129,1,'浙江民泰商业银行',0,0),(130,1,'浙商银行',0,0),(131,1,'中银富登村镇银行',0,0),(132,1,'珠海华润银行',0,0),(133,1,'郑州银行',0,0),(134,1,'张家港农村商业银行',0,0),(135,1,'张家口市商业银行',0,0),(136,1,'浙江稠州商业银行',0,0),(137,1,'浙江泰隆商业银行',0,0),(138,1,'浙江省农村信用社联合社',0,0),(139,1,'自贡市商业银行',0,0),(140,1,'中国民生银行',0,0);

#
# Structure for table "member"
#

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员ID',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '会员姓名',
  `mobile` varchar(16) NOT NULL COMMENT '手机号码',
  `yun_id` varchar(64) NOT NULL DEFAULT '' COMMENT '云联惠ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(1-有效/0-无效)',
  `date_added` int(11) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `date_modified` int(11) NOT NULL DEFAULT '0' COMMENT '变更时间',
  `bank_name` varchar(64) NOT NULL DEFAULT '' COMMENT '银行账户',
  `bank_branch_name` varchar(64) NOT NULL DEFAULT '' COMMENT '银行分行名称',
  `bank_account_name` varchar(64) NOT NULL DEFAULT '' COMMENT '银行账号名称',
  `bank_account_number` varchar(64) NOT NULL DEFAULT '' COMMENT '银行账号',
  `bind_mobile` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员';

#
# Data for table "member"
#

INSERT INTO `member` VALUES (1,'111','18702990845','1111',1,1510194084,1510194084,'包商银行','1','1111111111','1111111111111','18702990845');

#
# Structure for table "member_cashback_transaction"
#

DROP TABLE IF EXISTS `member_cashback_transaction`;
CREATE TABLE `member_cashback_transaction` (
  `member_cashback_transaction_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员返现交易ID',
  `member_recharge_transaction_id` int(11) NOT NULL COMMENT '会员充值交易ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `bank_name` varchar(64) NOT NULL DEFAULT '' COMMENT '银行名称',
  `bank_account_name` varchar(64) NOT NULL DEFAULT '' COMMENT '银行账号名称',
  `bank_account_number` varchar(64) NOT NULL DEFAULT '' COMMENT '银行账号',
  `cashback_amount` decimal(15,4) NOT NULL COMMENT '会员返现金额',
  `cashback_datetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员返现时间',
  `comment` text NOT NULL COMMENT '备注',
  `is_cashback` tinyint(1) NOT NULL DEFAULT '0' COMMENT '返现状态（0-未转账/1-已转账）',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（0-不可用1-可用）',
  `date_added` int(11) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `date_modified` int(11) NOT NULL DEFAULT '0' COMMENT '变更时间',
  PRIMARY KEY (`member_cashback_transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='现金返现记录';

#
# Data for table "member_cashback_transaction"
#

INSERT INTO `member_cashback_transaction` VALUES (1,1,1,'','','',600.0000,1509465600,'',0,1,0,0),(2,1,1,'','','',600.0000,1512057600,'',0,1,0,0),(3,1,1,'','','',600.0000,1514736000,'',0,1,0,0),(4,1,1,'','','',600.0000,1517414400,'',0,1,0,0),(5,1,1,'','','',600.0000,1519833600,'',0,1,0,0),(6,1,1,'','','',600.0000,1522512000,'',0,1,0,0),(7,1,1,'','','',600.0000,1525104000,'',0,1,0,0),(8,1,1,'','','',600.0000,1527782400,'',0,1,0,0),(9,1,1,'','','',600.0000,1530374400,'',0,1,0,0),(10,1,1,'','','',600.0000,1533052800,'',0,1,0,0),(11,1,1,'','','',600.0000,1535731200,'',0,1,0,0),(12,1,1,'','','',600.0000,1538323200,'',0,1,0,0),(13,3,1,'','','',600.0000,1512748800,'',0,1,0,0),(14,3,1,'','','',600.0000,1515427200,'',0,1,0,0),(15,3,1,'','','',600.0000,1518105600,'',0,1,0,0),(16,3,1,'','','',600.0000,1520524800,'',0,1,0,0),(17,3,1,'','','',600.0000,1523203200,'',0,1,0,0),(18,3,1,'','','',600.0000,1525795200,'',0,1,0,0),(19,3,1,'','','',600.0000,1528473600,'',0,1,0,0),(20,3,1,'','','',600.0000,1531065600,'',0,1,0,0),(21,3,1,'','','',600.0000,1533744000,'',0,1,0,0),(22,3,1,'','','',600.0000,1536422400,'',0,1,0,0),(23,3,1,'','','',600.0000,1539014400,'',0,1,0,0),(24,3,1,'','','',600.0000,1541692800,'',0,1,0,0);

#
# Structure for table "member_recharge_transaction"
#

DROP TABLE IF EXISTS `member_recharge_transaction`;
CREATE TABLE `member_recharge_transaction` (
  `member_recharge_transaction_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员充值交易ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `recharge_rule_id` int(11) NOT NULL COMMENT '充值规则ID',
  `agency_id` int(11) NOT NULL COMMENT '办事处ID',
  `share_member_id` int(11) NOT NULL COMMENT '分享人ID',
  `amount` decimal(15,4) NOT NULL COMMENT '会员充值金额',
  `recharge_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员充值时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（0-不可用1-可用）',
  `date_added` int(11) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `date_modified` int(11) NOT NULL DEFAULT '0' COMMENT '变更时间',
  PRIMARY KEY (`member_recharge_transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='充值记录';

#
# Data for table "member_recharge_transaction"
#

INSERT INTO `member_recharge_transaction` VALUES (1,1,2,0,0,9600.0000,1506787200,1,1510195489,1510195489),(2,1,3,0,0,4800.0000,1506787200,1,1510195987,1510195987),(3,1,2,0,0,9600.0000,1510156800,1,1510198558,1510198558);

#
# Structure for table "member_score_distribute_transaction"
#

DROP TABLE IF EXISTS `member_score_distribute_transaction`;
CREATE TABLE `member_score_distribute_transaction` (
  `member_score_distribute_transaction_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员积分分发交易ID',
  `member_recharge_transaction_id` int(11) DEFAULT NULL COMMENT '会员充值交易ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `yun_id` varchar(64) NOT NULL DEFAULT '' COMMENT '云联惠ID',
  `score_distribute_amount` decimal(15,4) NOT NULL COMMENT '会员分发积分数',
  `score_distribute_datetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员分发积分时间',
  `comment` text NOT NULL COMMENT '备注',
  `is_score_distribute` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员分发积分状态（0-未分发/1-已分发）',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（0-不可用1-可用）',
  `date_added` int(11) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `date_modified` int(11) NOT NULL DEFAULT '0' COMMENT '变更时间',
  PRIMARY KEY (`member_score_distribute_transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='积分返现记录';

#
# Data for table "member_score_distribute_transaction"
#

INSERT INTO `member_score_distribute_transaction` VALUES (1,1,1,'1111',9600.0000,1506787200,'',0,1,0,0),(2,2,1,'1111',14400.0000,1506787200,'',0,1,0,0),(3,3,1,'1111',9600.0000,1510156800,'',0,1,0,0);

#
# Structure for table "member_shoppingback_transaction"
#

DROP TABLE IF EXISTS `member_shoppingback_transaction`;
CREATE TABLE `member_shoppingback_transaction` (
  `member_shoppingback_transaction_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员购物返现交易ID',
  `member_recharge_transaction_id` int(11) NOT NULL COMMENT '会员充值交易ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `shopping_kind` varchar(20) NOT NULL DEFAULT '0' COMMENT '购物返现种类',
  `shoppingback_amount` decimal(15,4) NOT NULL COMMENT '会员购物返现金额',
  `shoppingback_datetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员购物返现时间',
  `comment` text NOT NULL COMMENT '备注',
  `is_shoppingback` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员购物返现状态（0-未返/1-已返）',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（0-不可用1-可用）',
  `date_added` int(11) NOT NULL DEFAULT '0' COMMENT '新增时间',
  `date_modified` int(11) NOT NULL DEFAULT '0' COMMENT '变更时间',
  PRIMARY KEY (`member_shoppingback_transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='商品返现记录';

#
# Data for table "member_shoppingback_transaction"
#

INSERT INTO `member_shoppingback_transaction` VALUES (1,1,1,'油卡',800.0000,1506787200,'',0,1,0,0),(2,1,1,'油卡',800.0000,1509465600,'',0,1,0,0),(3,1,1,'油卡',800.0000,1512057600,'',0,1,0,0),(4,1,1,'油卡',800.0000,1514736000,'',0,1,0,0),(5,1,1,'油卡',800.0000,1517414400,'',0,1,0,0),(6,1,1,'油卡',800.0000,1519833600,'',0,1,0,0),(7,1,1,'油卡',800.0000,1522512000,'',0,1,0,0),(8,1,1,'油卡',800.0000,1525104000,'',0,1,0,0),(9,1,1,'油卡',800.0000,1527782400,'',0,1,0,0),(10,1,1,'油卡',800.0000,1530374400,'',0,1,0,0),(11,1,1,'油卡',800.0000,1533052800,'',0,1,0,0),(12,1,1,'油卡',800.0000,1535731200,'',0,1,0,0),(13,2,1,'油卡',400.0000,1506787200,'',0,1,0,0),(14,2,1,'油卡',400.0000,1509465600,'',0,1,0,0),(15,2,1,'油卡',400.0000,1512057600,'',0,1,0,0),(16,2,1,'油卡',400.0000,1514736000,'',0,1,0,0),(17,2,1,'油卡',400.0000,1517414400,'',0,1,0,0),(18,2,1,'油卡',400.0000,1519833600,'',0,1,0,0),(19,2,1,'油卡',400.0000,1522512000,'',0,1,0,0),(20,2,1,'油卡',400.0000,1525104000,'',0,1,0,0),(21,2,1,'油卡',400.0000,1527782400,'',0,1,0,0),(22,2,1,'油卡',400.0000,1530374400,'',0,1,0,0),(23,2,1,'油卡',400.0000,1533052800,'',0,1,0,0),(24,2,1,'油卡',400.0000,1535731200,'',0,1,0,0),(25,3,1,'电话费充值',800.0000,1510156800,'',0,1,0,0),(26,3,1,'电话费充值',800.0000,1512748800,'',0,1,0,0),(27,3,1,'电话费充值',800.0000,1515427200,'',0,1,0,0),(28,3,1,'电话费充值',800.0000,1518105600,'',0,1,0,0),(29,3,1,'电话费充值',800.0000,1520524800,'',0,1,0,0),(30,3,1,'电话费充值',800.0000,1523203200,'',0,1,0,0),(31,3,1,'电话费充值',800.0000,1525795200,'',0,1,0,0),(32,3,1,'电话费充值',800.0000,1528473600,'',0,1,0,0),(33,3,1,'电话费充值',800.0000,1531065600,'',0,1,0,0),(34,3,1,'电话费充值',800.0000,1533744000,'',0,1,0,0),(35,3,1,'电话费充值',800.0000,1536422400,'',0,1,0,0),(36,3,1,'电话费充值',800.0000,1539014400,'',0,1,0,0);
