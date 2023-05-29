/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : php_db

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2018-10-11 13:23:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_admin`
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `username` varchar(20) NOT NULL default '',
  `password` varchar(32) default NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('a', 'a');

-- ----------------------------
-- Table structure for `t_buyinfo`
-- ----------------------------
DROP TABLE IF EXISTS `t_buyinfo`;
CREATE TABLE `t_buyinfo` (
  `buyId` int(11) NOT NULL auto_increment COMMENT '进货编号',
  `productObj` varchar(20) NOT NULL COMMENT '进货产品',
  `buyDate` varchar(20) default NULL COMMENT '进货日期',
  `price` float NOT NULL COMMENT '进货单价',
  `count` int(11) NOT NULL COMMENT '进货数量',
  `supplyerObj` int(11) NOT NULL COMMENT '供应商',
  `personName` varchar(20) default NULL COMMENT '负责人',
  PRIMARY KEY  (`buyId`),
  KEY `productObj` (`productObj`),
  KEY `supplyerObj` (`supplyerObj`),
  CONSTRAINT `t_buyinfo_ibfk_2` FOREIGN KEY (`supplyerObj`) REFERENCES `t_supplyer` (`supplyerId`),
  CONSTRAINT `t_buyinfo_ibfk_1` FOREIGN KEY (`productObj`) REFERENCES `t_productinfo` (`productNo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_buyinfo
-- ----------------------------
INSERT INTO `t_buyinfo` VALUES ('1', 'CP001', '2018-10-03', '289', '50', '1', '李晓明');
INSERT INTO `t_buyinfo` VALUES ('2', 'CP002', '2018-10-09', '20', '200', '2', '李晓明');

-- ----------------------------
-- Table structure for `t_customerinfo`
-- ----------------------------
DROP TABLE IF EXISTS `t_customerinfo`;
CREATE TABLE `t_customerinfo` (
  `customerId` int(11) NOT NULL auto_increment COMMENT '客户编号',
  `customerName` varchar(20) NOT NULL COMMENT '客户名称',
  `personName` varchar(20) default NULL COMMENT '联系人',
  `telephone` varchar(20) default NULL COMMENT '联系电话',
  `address` varchar(20) default NULL COMMENT '联系地址',
  PRIMARY KEY  (`customerId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_customerinfo
-- ----------------------------
INSERT INTO `t_customerinfo` VALUES ('1', '二仙桥十里配送超市', '李明阳', '13985243943', '成都市成华区二仙桥13号');
INSERT INTO `t_customerinfo` VALUES ('2', '二仙桥理工配送超市', '王大海', '13985083423', '十里店成都理工大学');

-- ----------------------------
-- Table structure for `t_productclass`
-- ----------------------------
DROP TABLE IF EXISTS `t_productclass`;
CREATE TABLE `t_productclass` (
  `productClassId` int(11) NOT NULL auto_increment COMMENT '商品类别编号',
  `productClassName` varchar(20) NOT NULL COMMENT '商品类别名称',
  PRIMARY KEY  (`productClassId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_productclass
-- ----------------------------
INSERT INTO `t_productclass` VALUES ('1', '厨房用品类');
INSERT INTO `t_productclass` VALUES ('2', '电子产品类');

-- ----------------------------
-- Table structure for `t_productinfo`
-- ----------------------------
DROP TABLE IF EXISTS `t_productinfo`;
CREATE TABLE `t_productinfo` (
  `productNo` varchar(20) NOT NULL COMMENT 'productNo',
  `productClass` int(11) NOT NULL COMMENT '产品类别',
  `productName` varchar(20) NOT NULL COMMENT '产品名称',
  `price` float NOT NULL COMMENT '产品单价',
  `leftCount` int(11) NOT NULL COMMENT '产品库存',
  `madeDate` varchar(20) default NULL COMMENT '生产日期',
  `productPhoto` varchar(60) NOT NULL COMMENT '产品图片',
  `productDesc` varchar(8000) NOT NULL COMMENT '产品描述',
  PRIMARY KEY  (`productNo`),
  KEY `productClass` (`productClass`),
  CONSTRAINT `t_productinfo_ibfk_1` FOREIGN KEY (`productClass`) REFERENCES `t_productclass` (`productClassId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_productinfo
-- ----------------------------
INSERT INTO `t_productinfo` VALUES ('CP001', '1', '苏泊尔电饭煲', '389', '100', '2018-10-02', 'upload/93d591a4162ed3c1424ce44893564f12.jpg', '<ul id=\"J_AttrUL\" data-spm-anchor-id=\"a220o.1000855.0.i0.48aa5adehRooLs\" style=\"list-style-type: none;\" class=\" list-paddingleft-2\"><li><p>证书编号：2013010718646628</p></li><li><p>证书状态：有效</p></li><li><p>申请人名称：浙江苏泊尔家电制造有限公司</p></li><li><p>制造商名称：浙江苏泊尔家电制造有限公司</p></li><li><p>产品名称：豪华智能电饭煲</p></li><li><p>3C产品型号：CFXB30FZ16Q-60 3.0L 600W; CFXB40FZ16Q-75, CFXB40FC...</p></li><li><p>3C规格型号：CFXB30FZ16Q-60,CFXB30FC866-60; 3.0L 600W; CFXB40FZ...</p></li><li><p>产品名称：SUPOR/苏泊尔 CFXB40FC81...</p></li><li><p>容量:&nbsp;4L</p></li><li><p>控制方式:&nbsp;微电脑式</p></li><li><p>电饭煲多功能:&nbsp;煲仔饭&nbsp;预约&nbsp;定时&nbsp;煮饭</p></li><li><p>内胆材质:&nbsp;釜胆</p></li><li><p>形状:&nbsp;方形</p></li><li><p>加热方式:&nbsp;三维立体加热</p></li><li><p>适用人数:&nbsp;3人-4人</p></li><li><p>售后服务:&nbsp;全国联保</p></li><li><p>品牌:&nbsp;SUPOR/苏泊尔</p></li><li><p>型号:&nbsp;CFXB40FC8155-75</p></li><li><p>颜色分类:&nbsp;摩卡棕</p></li></ul><p><br/></p>');
INSERT INTO `t_productinfo` VALUES ('CP002', '2', '金士顿U盘32G', '36', '100', '2018-10-10', 'upload/1e4432c716d91211e5a4496bc5c23824.jpg', '<ul class=\"attributes-list list-paddingleft-2\" style=\"list-style-type: none;\"><li><p>品牌:&nbsp;Kingston/金士顿</p></li><li><p>内存容量:&nbsp;32GB</p></li><li><p>颜色分类:&nbsp;尊贵银16【g】+免费刻字 尊贵银32【g】+免费刻字 尊贵银64【g】+免费刻字 尊贵银128【g】+免费刻字 支持7天无理由退换</p></li><li><p>USB类型:&nbsp;USB2.0</p></li></ul><p><br/></p>');

-- ----------------------------
-- Table structure for `t_sellinfo`
-- ----------------------------
DROP TABLE IF EXISTS `t_sellinfo`;
CREATE TABLE `t_sellinfo` (
  `sellId` int(11) NOT NULL auto_increment COMMENT '销售编号',
  `productObj` varchar(20) NOT NULL COMMENT '销售产品',
  `sellDate` varchar(20) default NULL COMMENT '销售日期',
  `price` float NOT NULL COMMENT '销售价格',
  `count` int(11) NOT NULL COMMENT '销售数量',
  `customerObj` int(11) NOT NULL COMMENT '销售客户',
  `personName` varchar(20) NOT NULL COMMENT '销售负责人',
  PRIMARY KEY  (`sellId`),
  KEY `productObj` (`productObj`),
  KEY `customerObj` (`customerObj`),
  CONSTRAINT `t_sellinfo_ibfk_2` FOREIGN KEY (`customerObj`) REFERENCES `t_customerinfo` (`customerId`),
  CONSTRAINT `t_sellinfo_ibfk_1` FOREIGN KEY (`productObj`) REFERENCES `t_productinfo` (`productNo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_sellinfo
-- ----------------------------
INSERT INTO `t_sellinfo` VALUES ('1', 'CP001', '2018-10-09', '300', '10', '1', '王达');
INSERT INTO `t_sellinfo` VALUES ('2', 'CP002', '2018-10-11', '32', '30', '2', '王达');

-- ----------------------------
-- Table structure for `t_supplyer`
-- ----------------------------
DROP TABLE IF EXISTS `t_supplyer`;
CREATE TABLE `t_supplyer` (
  `supplyerId` int(11) NOT NULL auto_increment COMMENT '供应商编号',
  `supplyerName` varchar(20) NOT NULL COMMENT '供应商名称',
  `telephone` varchar(20) default NULL COMMENT '供应商电话',
  `personName` varchar(20) default NULL COMMENT '联系人',
  `address` varchar(20) default NULL COMMENT '供应商地址',
  PRIMARY KEY  (`supplyerId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_supplyer
-- ----------------------------
INSERT INTO `t_supplyer` VALUES ('1', '四川成都厨具厂', '028-89293432', '王大志', '成都市双流区航天路12号');
INSERT INTO `t_supplyer` VALUES ('2', '四川电子厂1', '028-82983422', '李明刚', '成都市邛崃市清白路');
