/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : oa_project

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-06-08 03:31:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for oa_company_notice
-- ----------------------------
DROP TABLE IF EXISTS `oa_company_notice`;
CREATE TABLE `oa_company_notice` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) DEFAULT NULL,
  `Content` longtext,
  `Time` varchar(30) DEFAULT NULL,
  `Publisher` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_company_notice
-- ----------------------------
INSERT INTO `oa_company_notice` VALUES ('1', '关于国庆节放假安排的通知', '<p>\r\n                        公司各部门：</p>\r\n\r\n                    <p>\r\n                        　　按照国务院办公厅和重庆市人民政府办公厅通知精神，结合公司最近工作的实际情况，经董事会研究同意，现将2016年国庆节放假调休有关事宜通知如下。</p>\r\n\r\n                    <p>\r\n                        　　一、国庆节放假具体日期为10月1日至10月7日，共7天。</p>\r\n\r\n                    <p>\r\n                        　　二、放假期间，各单位要妥善安排好值班和安全保卫等工作，遇有重大突发事件发生，要按规定及时报告并妥善处置，确保全体员工祥和平安度过节日假期。</p>\r\n\r\n                    <p>\r\n                        　　特此通知</p>', '2016年9月25日', '张经理');

-- ----------------------------
-- Table structure for oa_department
-- ----------------------------
DROP TABLE IF EXISTS `oa_department`;
CREATE TABLE `oa_department` (
  `DepartmentID` int(255) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) NOT NULL,
  `FileManager` varchar(30) DEFAULT NULL,
  `Manager` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`DepartmentID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_department
-- ----------------------------
INSERT INTO `oa_department` VALUES ('1', '销售部', null, null);
INSERT INTO `oa_department` VALUES ('2', '产品部', null, null);
INSERT INTO `oa_department` VALUES ('3', '客户部', null, null);
INSERT INTO `oa_department` VALUES ('4', '财务部', null, null);
INSERT INTO `oa_department` VALUES ('5', '行政部', null, null);
INSERT INTO `oa_department` VALUES ('6', '市场部', null, null);
INSERT INTO `oa_department` VALUES ('7', '技术部', '黄小明', '黄小明');
INSERT INTO `oa_department` VALUES ('8', '售后部', null, null);

-- ----------------------------
-- Table structure for oa_department_notice
-- ----------------------------
DROP TABLE IF EXISTS `oa_department_notice`;
CREATE TABLE `oa_department_notice` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) DEFAULT NULL,
  `Content` longtext,
  `Time` varchar(30) DEFAULT NULL,
  `Publisher` varchar(30) DEFAULT NULL,
  `Department` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_department_notice
-- ----------------------------
INSERT INTO `oa_department_notice` VALUES ('1', '对于技术部的通知', '<p>\r\n                        公司各部门112：</p>\r\n\r\n                    <p>\r\n                        　　按照国务院办公厅和重庆市人民政府办公厅通知精神，结合公司最近工作的实际情况，经董事会研究同意，现将2016年国庆节放假调休有关事宜通知如下。</p>\r\n\r\n                    <p>\r\n                        　　一、国庆节放假具体日期为10月1日至10月7日，共7天。</p>\r\n\r\n                    <p>\r\n                        　　二、放假期间，各单位要妥善安排好值班和安全保卫等工作，遇有重大突发事件发生，要按规定及时报告并妥善处置，确保全体员工祥和平安度过节日假期。</p>\r\n\r\n                    <p>\r\n                        　　特此通知</p>', '2016-Jun-07 15:31:36', '张经理', '技术部');
INSERT INTO `oa_department_notice` VALUES ('2', '测试', '测试部门公告11', '2016-Jun-07 15:32:01', '黄小明', '技术部');

-- ----------------------------
-- Table structure for oa_document
-- ----------------------------
DROP TABLE IF EXISTS `oa_document`;
CREATE TABLE `oa_document` (
  `DocumentID` int(255) NOT NULL AUTO_INCREMENT,
  `Number` int(30) NOT NULL,
  `Title` varchar(30) NOT NULL,
  `Author` varchar(30) NOT NULL,
  `Target1` varchar(30) NOT NULL,
  `Target2` varchar(30) DEFAULT NULL,
  `Keyword` varchar(30) DEFAULT NULL,
  `Introduction` longtext,
  `FilePath` varchar(255) DEFAULT NULL,
  `FileName` varchar(255) DEFAULT NULL,
  `Time` varchar(30) DEFAULT NULL,
  `State` varchar(30) DEFAULT NULL,
  `IsRead` varchar(30) DEFAULT NULL,
  `Checker` varchar(30) DEFAULT NULL,
  `Opinion` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`DocumentID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_document
-- ----------------------------
INSERT INTO `oa_document` VALUES ('1', '10001', '关于推动文化文物的若干意见', '罗经理', '市场部', '黄小明', '文化文物', '如何通过文化创意产品开发让博物馆、美术馆、图书馆优秀资源焕发新的生命力？国家行政学院社会和文化教研部主任祁述裕对意见进行了详解。', './Uploads/document/1463413465.doc', '附件1.doc', '2016-May-16 20:17:49', '已审核', '已读', '托尔斯泰', '可以');
INSERT INTO `oa_document` VALUES ('2', '10002', '关于推动文化文物的若干意见', '雷经理', '技术部', '黄小明', '文化文物', '如何通过文化创意产品开发让博物馆、美术馆、图书馆优秀资源焕发新的生命力？国家行政学院社会和文化教研部主任祁述裕对意见进行了详解。', './Uploads/document/1463413465.doc', '附件222.doc', '2016-May-16 20:17:50', '未审核', '已读', null, null);
INSERT INTO `oa_document` VALUES ('3', '10003', '关于推动文化文物的若干意见', '王经理', '罗椋仁', '', '文化文物', '如何通过文化创意产品开发让博物馆、美术馆、图书馆优秀资源焕发新的生命力？国家行政学院社会和文化教研部主任祁述裕对意见进行了详解。', './Uploads/document/1463413465.doc', '附件.doc', '2016-May-16 20:17:51', '未审核', '已读', null, null);
INSERT INTO `oa_document` VALUES ('4', '10004', '关于推动文化文物的若干意见', '张经理', '技术部', '罗椋仁', '文化文物', '如何通过文化创意产品开发让博物馆、美术馆、图书馆优秀资源焕发新的生命力？国家行政学院社会和文化教研部主任祁述裕对意见进行了详解。', './Uploads/document/1463413465.doc', '附件.doc', '2016-May-16 20:17:52', '已审核', '已读', '托尔斯泰', '可以');
INSERT INTO `oa_document` VALUES ('5', '10005', '关于推动文化文物的若干意见', '苗经理', '产品部', '售后部', '文化文物', '如何通过文化创意产品开发让博物馆、美术馆、图书馆优秀资源焕发新的生命力？国家行政学院社会和文化教研部主任祁述裕对意见进行了详解。', './Uploads/document/1463413465.doc', '附件.doc', '2016-May-16 20:17:53', '未审核', '已读', null, null);
INSERT INTO `oa_document` VALUES ('6', '10006', '关于推动文化文物的若干意见', '苗经理', '客户部', '技术部', '文化文物', '如何通过文化创意产品开发让博物馆、美术馆、图书馆优秀资源焕发新的生命力？国家行政学院社会和文化教研部主任祁述裕对意见进行了详解。', './Uploads/document/1463413465.doc', '附件.doc', '2016-May-16 20:17:54', '未审核', '已读', null, null);
INSERT INTO `oa_document` VALUES ('7', '10088', '测试用公文', '罗椋仁', '市场部', '', '测试', '测试测试', './Uploads/document/1463413465.doc', '文档模板  (1).doc', '2016-May-23 23:29:18', '未审核', '已读', null, null);

-- ----------------------------
-- Table structure for oa_email
-- ----------------------------
DROP TABLE IF EXISTS `oa_email`;
CREATE TABLE `oa_email` (
  `EmailID` int(255) NOT NULL AUTO_INCREMENT,
  `Recipient` varchar(50) NOT NULL,
  `Sender` varchar(50) NOT NULL,
  `Topic` varchar(100) DEFAULT NULL,
  `Type` varchar(10) DEFAULT NULL,
  `Content` longtext,
  `Time` varchar(50) NOT NULL,
  PRIMARY KEY (`EmailID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_email
-- ----------------------------
INSERT INTO `oa_email` VALUES ('1', '罗椋仁', '黄小明', '明天会议', '重要邮件', '明天上午9点的会议要带的东西有：<p><b>1.纸</b></p><p><b>2.笔</b></p>', '2016-May-12 23:57:18');
INSERT INTO `oa_email` VALUES ('2', '黄小明', '罗椋仁', '国庆放假', '普通邮件', '国庆放假准备去哪玩啊？', '2016-May-13 10:27:22');
INSERT INTO `oa_email` VALUES ('3', '罗椋仁', '黄小明', '国庆放假', '普通邮件', '国庆准备去九寨沟耍耍', '2016-May-13 10:29:51');
INSERT INTO `oa_email` VALUES ('4', '黄小明', '罗椋仁', '国庆放假', '普通邮件', '九寨沟不错啊，一起撒。', '2016-May-13 10:31:02');
INSERT INTO `oa_email` VALUES ('5', '罗椋仁', '黄小明', '国庆放假', '普通邮件', '好啊。', '2016-May-13 10:33:11');
INSERT INTO `oa_email` VALUES ('6', '测试用', '黄小明', '测试', '普通邮件', '测试', '2016-May-18 20:00:48');
INSERT INTO `oa_email` VALUES ('7', '测试用', '罗椋仁', '测试', '重要邮件', '测试', '2016-May-18 20:00:49');
INSERT INTO `oa_email` VALUES ('8', '测试用', '黄小明', '测试', '重要邮件', '测试', '2016-May-18 20:00:50');
INSERT INTO `oa_email` VALUES ('9', '测试用', '罗椋仁', '测试', '普通邮件', '测试', '2016-May-18 20:00:51');
INSERT INTO `oa_email` VALUES ('16', '黄小明', '罗椋仁', '儿童节', '普通邮件', '儿童节快乐！', '2016-May-18 20:00:52');
INSERT INTO `oa_email` VALUES ('18', '托尔斯泰', '系统', '请假申请', '重要邮件', '您好，您提交的请假申请已通过审核', '2016-May-20 01:39:23');
INSERT INTO `oa_email` VALUES ('19', '罗椋仁', '系统', '请假申请', '重要邮件', '您好，您提交的请假申请已通过审核', '2016-May-20 01:44:42');

-- ----------------------------
-- Table structure for oa_employees
-- ----------------------------
DROP TABLE IF EXISTS `oa_employees`;
CREATE TABLE `oa_employees` (
  `EmployeeID` int(255) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `RealName` varchar(30) NOT NULL,
  `Telephone` varchar(30) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL,
  `Department` varchar(30) DEFAULT NULL,
  `LoginTime` varchar(30) DEFAULT NULL,
  `Role` varchar(30) NOT NULL,
  PRIMARY KEY (`EmployeeID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_employees
-- ----------------------------
INSERT INTO `oa_employees` VALUES ('1', 'root', 'root', '罗椋仁', '10001', '10001@qq.com', '技术部', '2016-Jun-08 02:39:55', '系统管理员');
INSERT INTO `oa_employees` VALUES ('2', 'test', 'test', '黄小明', '10086', '10086@qq.com', '技术部', '2016-Jun-08 02:39:55', '部门经理');
INSERT INTO `oa_employees` VALUES ('3', 'test1', 'test', '托尔斯泰', null, '1@qq.com', null, '2016-Jun-08 02:39:55', '总经理');
INSERT INTO `oa_employees` VALUES ('4', 'test2', 'test', '苏格拉底', '', '22@qq.com', '技术部', '2016-Jun-08 02:39:55', '总经理秘书');
INSERT INTO `oa_employees` VALUES ('5', 'test3', 'test', '施瓦辛格', '111', '3@qq.com', '售后部', '2016-Jun-08 02:39:55', '普通员工');
INSERT INTO `oa_employees` VALUES ('6', 'test4', 'test', '帕瓦罗蒂', '', '4@qq.com', '财务部', '2016-Jun-08 02:39:55', '普通员工');
INSERT INTO `oa_employees` VALUES ('7', 'test5', 'test', '蒙哥马利', null, '5@qq.com', '技术部', '2016-Jun-08 02:39:55', '普通员工');
INSERT INTO `oa_employees` VALUES ('8', 'test6', 'test', '麦克阿瑟', null, '6@qq.com', null, '2016-Jun-08 02:39:55', '普通员工');
INSERT INTO `oa_employees` VALUES ('9', 'test7', 'test', '巴甫洛夫', '10086', '7@qq.com', '技术部', '2016-Jun-08 02:39:55', '普通员工');
INSERT INTO `oa_employees` VALUES ('10', 'test8', 'test', '希区柯克', '911', '911@qq.com', '产品部', '2016-Jun-08 02:39:55', '部门经理');

-- ----------------------------
-- Table structure for oa_file
-- ----------------------------
DROP TABLE IF EXISTS `oa_file`;
CREATE TABLE `oa_file` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Topic` varchar(255) DEFAULT NULL,
  `FileName` varchar(60) DEFAULT NULL,
  `FilePath` varchar(60) DEFAULT NULL,
  `Department` varchar(60) DEFAULT NULL,
  `Uploader` varchar(60) DEFAULT NULL,
  `Time` varchar(60) DEFAULT NULL,
  `Type` varchar(60) DEFAULT NULL,
  `Introduction` longtext,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_file
-- ----------------------------
INSERT INTO `oa_file` VALUES ('1', '关于推动文化文物的若干意见——附件', '附件.doc', './Uploads/document/1463397855.doc', '技术部', '罗椋仁', '2016-May-16 20:17:51', 'fujian', '如何通过文化创意产品开发让博物馆、美术馆、图书馆优秀资源焕发新的生命力？国家行政学院社会和文化教研部主任祁述裕对意见进行了详解。');
INSERT INTO `oa_file` VALUES ('2', '关于推动文化文物的若干意见——附件', '附件.doc', './Uploads/document/1463397855.doc', '技术部', '罗椋仁', '2016-May-16 20:17:51', 'fujian', '如何通过文化创意产品开发让博物馆、美术馆、图书馆优秀资源焕发新的生命力？国家行政学院社会和文化教研部主任祁述裕对意见进行了详解。');
INSERT INTO `oa_file` VALUES ('3', '关于推动文化文物的若干意见——附件', '附件.doc', './Uploads/document/1463397855.doc', '技术部', '罗椋仁', '2016-May-16 20:17:51', 'fujian', '如何通过文化创意产品开发让博物馆、美术馆、图书馆优秀资源焕发新的生命力？国家行政学院社会和文化教研部主任祁述裕对意见进行了详解。');
INSERT INTO `oa_file` VALUES ('4', '测试用公文——附件', '文档模板  (1).doc', './Uploads/document/1464017358.doc', '技术部', '罗椋仁', '2016-May-23 23:29:18', 'fujian', '测试');
INSERT INTO `oa_file` VALUES ('5', '测试删除', '测试删除.doc', './Uploads/other/1463397887.doc', '技术部', '罗椋仁', '2016-May-23 23:29:18', null, null);
INSERT INTO `oa_file` VALUES ('6', '测试删除', '测试删除.doc', './Uploads/other/1463397887.doc', '技术部', '罗椋仁', '2016-May-23 23:29:18', null, null);
INSERT INTO `oa_file` VALUES ('7', '测试删除', '测试删除.doc', './Uploads/other/1463397887.doc', '技术部', '罗椋仁', '2016-May-23 23:29:18', null, null);
INSERT INTO `oa_file` VALUES ('8', '测试上传功能', '公司员工请假管理制度.doc', './Uploads/other/1465220203.doc', '技术部', '黄小明', '2016-Jun-06 21:36:43', null, '测试上传功能');
INSERT INTO `oa_file` VALUES ('9', '测试上传2222', '毕业设计论文副本.doc', './Uploads/other/1465220472.doc', '技术部', '黄小明', '2016-Jun-06 21:41:12', null, '测试上传2222');

-- ----------------------------
-- Table structure for oa_leave
-- ----------------------------
DROP TABLE IF EXISTS `oa_leave`;
CREATE TABLE `oa_leave` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `RealName` varchar(30) DEFAULT NULL,
  `Department` varchar(30) DEFAULT NULL,
  `Type` varchar(30) DEFAULT NULL,
  `Days` varchar(30) DEFAULT NULL,
  `Start` varchar(30) DEFAULT NULL,
  `End` varchar(30) DEFAULT NULL,
  `Reason` longtext,
  `Time` varchar(30) DEFAULT NULL,
  `Checker1` varchar(30) DEFAULT NULL,
  `Checker2` varchar(30) DEFAULT NULL,
  `State` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_leave
-- ----------------------------
INSERT INTO `oa_leave` VALUES ('1', '巴甫洛夫', '技术部', '出差', '短假', '2016/05/18 08:00', '2016/05/21 08:00', '去杭州出差2天', '2016/May/19 22:29:29', null, null, '0');
INSERT INTO `oa_leave` VALUES ('2', '苏格拉底', '技术部', '出差', '长假', '2016/05/20 08:00', '2016/05/28 08:00', '去北京出差', '2016/May/19 22:29:30', null, null, '0');
INSERT INTO `oa_leave` VALUES ('3', '蒙哥马利', '技术部', '出差', '长假', '2016/05/20 08:00', '2016/06/10 08:00', '去深圳出差', '2016/May/19 22:29:29', '黄小明', null, '1');

-- ----------------------------
-- Table structure for oa_message
-- ----------------------------
DROP TABLE IF EXISTS `oa_message`;
CREATE TABLE `oa_message` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Speaker` varchar(30) DEFAULT NULL,
  `Content` longtext,
  `Time` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_message
-- ----------------------------
INSERT INTO `oa_message` VALUES ('1', '罗椋仁', '留言必读\r\n \r\n请仔细阅读下列规则, 留言和发表评论之前，您必须已经阅读和认可本规则：\r\n\r\n1．请尊重网上道德，遵守《全国人大常委会关于维护互联网安全的决定》及中华人民共和国其他各相关法律法规。\r\n2．严禁发布任何有关危害国家安全、破坏民族团结、破坏国家宗教政策、破坏社会稳定、侮辱、诽谤、教唆、色情、暴力等内容的信息及链接。\r\n3．严禁发表任何无关的信息及链接；严禁发布任何商业广告及链接。违者将在第一时间删除。\r\n4．您应承担一切因您的行为而直接或间接导致的民事、行政直至追究刑事法律责任。\r\n5．管理员有权保留或随时删除用户评论中的任何内容。', '2016-May-20 16:20:40');
INSERT INTO `oa_message` VALUES ('2', '托尔斯泰', '我要留言<img src=\"/OA/Public/images/face/1.gif\" />', '2016-May-20 16:21:30');
INSERT INTO `oa_message` VALUES ('3', '黄小明', '我也要留言<img src=\"/OA/Public/images/face/13.gif\" /><img src=\"/OA/Public/images/face/13.gif\" /><img src=\"/OA/Public/images/face/13.gif\" />', '2016-May-20 16:22:00');
INSERT INTO `oa_message` VALUES ('4', '巴甫洛夫', '我也来凑个热闹<img src=\"/OA/Public/images/face/52.gif\" />', '2016-May-20 16:22:56');
INSERT INTO `oa_message` VALUES ('5', '蒙哥马利', '借楼问下，国庆放几天<img src=\"/OA/Public/images/face/31.gif\" />', '2016-May-20 16:24:02');
INSERT INTO `oa_message` VALUES ('6', '黄小明', '公告出了，快去看吧。', '2016-May-20 16:25:03');
INSERT INTO `oa_message` VALUES ('7', '麦克阿瑟', '前排挤挤<img src=\"/OA/Public/images/face/13.gif\" />', '2016-May-26 20:59:14');

-- ----------------------------
-- Table structure for oa_request_contact
-- ----------------------------
DROP TABLE IF EXISTS `oa_request_contact`;
CREATE TABLE `oa_request_contact` (
  `EmployeeID` int(255) NOT NULL,
  `EmployeeID2` int(255) NOT NULL,
  PRIMARY KEY (`EmployeeID`,`EmployeeID2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_request_contact
-- ----------------------------
INSERT INTO `oa_request_contact` VALUES ('1', '9');
INSERT INTO `oa_request_contact` VALUES ('2', '9');

-- ----------------------------
-- Table structure for oa_role
-- ----------------------------
DROP TABLE IF EXISTS `oa_role`;
CREATE TABLE `oa_role` (
  `RoleID` int(255) NOT NULL AUTO_INCREMENT,
  `RoleName` varchar(30) NOT NULL,
  PRIMARY KEY (`RoleID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_role
-- ----------------------------
INSERT INTO `oa_role` VALUES ('1', '普通员工');
INSERT INTO `oa_role` VALUES ('2', '部门经理');
INSERT INTO `oa_role` VALUES ('3', '总经理秘书');
INSERT INTO `oa_role` VALUES ('4', '总经理');
INSERT INTO `oa_role` VALUES ('5', '系统管理员');

-- ----------------------------
-- Table structure for oa_r_employees_employees
-- ----------------------------
DROP TABLE IF EXISTS `oa_r_employees_employees`;
CREATE TABLE `oa_r_employees_employees` (
  `EmployeeID` int(255) DEFAULT NULL,
  `EmployeeID2` int(255) DEFAULT NULL,
  KEY `EmployeeID` (`EmployeeID`),
  KEY `EmployeeID2` (`EmployeeID2`),
  CONSTRAINT `EmployeeID` FOREIGN KEY (`EmployeeID`) REFERENCES `oa_employees` (`EmployeeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `EmployeeID2` FOREIGN KEY (`EmployeeID2`) REFERENCES `oa_employees` (`EmployeeID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_r_employees_employees
-- ----------------------------
INSERT INTO `oa_r_employees_employees` VALUES ('1', '2');
INSERT INTO `oa_r_employees_employees` VALUES ('1', '3');
INSERT INTO `oa_r_employees_employees` VALUES ('1', '4');
INSERT INTO `oa_r_employees_employees` VALUES ('1', '5');
INSERT INTO `oa_r_employees_employees` VALUES ('1', '6');
INSERT INTO `oa_r_employees_employees` VALUES ('1', '7');
INSERT INTO `oa_r_employees_employees` VALUES ('1', '8');
INSERT INTO `oa_r_employees_employees` VALUES ('2', '1');
INSERT INTO `oa_r_employees_employees` VALUES ('2', '3');
INSERT INTO `oa_r_employees_employees` VALUES ('2', '4');
INSERT INTO `oa_r_employees_employees` VALUES ('2', '5');
INSERT INTO `oa_r_employees_employees` VALUES ('2', '6');
INSERT INTO `oa_r_employees_employees` VALUES ('2', '7');
INSERT INTO `oa_r_employees_employees` VALUES ('2', '8');
