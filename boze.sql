-- phpMyAdmin SQL Dump
-- version 2.11.9.2
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2016 �?12 �?12 �?00:59
-- 服务器版本: 5.6.11
-- PHP 版本: 5.5.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `boze`
--

-- --------------------------------------------------------

--
-- 表的结构 `t_admin`
--

CREATE TABLE IF NOT EXISTS `t_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(26) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `salt` varchar(12) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `email` varchar(125) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台系统管理员' AUTO_INCREMENT=5 ;

--
-- 导出表中的数据 `t_admin`
--

INSERT INTO `t_admin` (`id`, `uname`, `pwd`, `salt`, `status`, `last_login_time`, `update_time`, `email`) VALUES
(1, 'admin', 'bdf4cb89353876fab51af05b3af811c2', 'adflernsk=', 1, 1481495774, 1466667847, '');

-- --------------------------------------------------------

--
-- 表的结构 `t_batch`
--

CREATE TABLE IF NOT EXISTS `t_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `client_id` int(12) NOT NULL DEFAULT '0',
  `type` smallint(2) NOT NULL DEFAULT '1',
  `car_no` varchar(12) NOT NULL DEFAULT '',
  `remark` varchar(12) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='批次编号' AUTO_INCREMENT=10002 ;

--
-- 导出表中的数据 `t_batch`
--

INSERT INTO `t_batch` (`id`, `create_time`, `client_id`, `type`, `car_no`, `remark`) VALUES
(10000, 1481431685, 1, 2, '20362', '测试'),
(10001, 1481496788, 1, 1, 'bg65343', 'sfsd');

-- --------------------------------------------------------

--
-- 表的结构 `t_client`
--

CREATE TABLE IF NOT EXISTS `t_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '' COMMENT '客户名称',
  `remark` varchar(120) NOT NULL DEFAULT '' COMMENT '备注',
  `status` smallint(2) NOT NULL DEFAULT '1' COMMENT '1,正常，0锁定',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='客户' AUTO_INCREMENT=5 ;

--
-- 导出表中的数据 `t_client`
--

INSERT INTO `t_client` (`id`, `name`, `remark`, `status`, `create_time`) VALUES
(1, '1452\\4226', '1452\\4226', 1, 1481355729),
(2, '1452\\4225', '1452\\4225', 1, 1481355729),
(3, '5289', '大客户34', 1, 1481422157),
(4, '5685', '大客户', 1, 1481422418);

-- --------------------------------------------------------

--
-- 表的结构 `t_product`
--

CREATE TABLE IF NOT EXISTS `t_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(60) NOT NULL DEFAULT '' COMMENT '对应属性名',
  `name` varchar(80) NOT NULL DEFAULT '' COMMENT '产品名称',
  `remark` varchar(120) NOT NULL DEFAULT '' COMMENT '备注',
  `status` smallint(2) NOT NULL DEFAULT '1' COMMENT '1正常使用，2出问题，99报废',
  `is_where` smallint(2) NOT NULL DEFAULT '1' COMMENT '1库房,2客户',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='产品' AUTO_INCREMENT=27 ;

--
-- 导出表中的数据 `t_product`
--

INSERT INTO `t_product` (`id`, `cate_name`, `name`, `remark`, `status`, `is_where`, `create_time`) VALUES
(2, 'P00367-000', '5265', '', 1, 1, 1481419770),
(3, 'P00367-000', '5266', '', 1, 1, 1481419770),
(4, 'P00367-000', '5267', '', 1, 1, 1481419770),
(5, 'P00367-000', '5268', '', 1, 1, 1481419770),
(6, 'P00367-000', '5269', '', 1, 1, 1481419770),
(7, 'P00367-000', '5210', '', 1, 1, 1481419770),
(8, 'P00367-000', '4226', '备注', 1, 1, 1481355729),
(9, 'P00367-000', '5265', '', 1, 1, 1481419770),
(10, 'P00367-000', '5266', '', 1, 1, 1481419770),
(11, 'P00367-000', '5267', '', 1, 1, 1481419770),
(12, 'P00367-000', '5268', '', 1, 1, 1481419770),
(13, 'P00367-000', '5269', '', 1, 1, 1481419770),
(14, 'P00367-000', '5210', '', 1, 1, 1481419770),
(15, 'P00367-000', '4226', '备注', 1, 1, 1481355729),
(16, 'P00367-000', '5265', '', 1, 1, 1481419770),
(17, 'P00367-000', '5266', '', 1, 1, 1481419770),
(18, 'P00367-000', '5267', '', 1, 1, 1481419770),
(19, 'P00367-000', '5268', '', 1, 1, 1481419770),
(20, 'P00367-000', '5269', '', 1, 1, 1481419770),
(21, 'P00367-000', '5210', '', 1, 1, 1481419770),
(23, 'P00367-000', '5265', '', 1, 1, 1481419770),
(24, 'P00367-000', '5266', '', 1, 1, 1481419770),
(25, 'P00367-000', '5267', '', 1, 1, 1481419770),
(26, 'P00367-000', '5268', '', 1, 1, 1481419770);

-- --------------------------------------------------------

--
-- 表的结构 `t_product_cate`
--

CREATE TABLE IF NOT EXISTS `t_product_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '' COMMENT '分类名称',
  `remark` varchar(120) NOT NULL DEFAULT '' COMMENT '分类名称',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='铁箱属性管理' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `t_product_cate`
--


-- --------------------------------------------------------

--
-- 表的结构 `t_product_record`
--

CREATE TABLE IF NOT EXISTS `t_product_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(2) NOT NULL DEFAULT '1' COMMENT '1入库，2出库',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品编号',
  `product_name` varchar(60) NOT NULL DEFAULT '',
  `cate_name` varchar(120) NOT NULL DEFAULT '' COMMENT '属性编号',
  `client_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品编号',
  `client_name` varchar(60) NOT NULL DEFAULT '',
  `car_no` varchar(26) NOT NULL DEFAULT '' COMMENT '车牌号',
  `batch_no` varchar(12) NOT NULL DEFAULT '' COMMENT '批次编号',
  `remark` varchar(120) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='发货记录' AUTO_INCREMENT=6 ;

--
-- 导出表中的数据 `t_product_record`
--

INSERT INTO `t_product_record` (`id`, `type`, `product_id`, `product_name`, `cate_name`, `client_id`, `client_name`, `car_no`, `batch_no`, `remark`, `create_time`) VALUES
(1, 2, 3, '5266', 'P00367-000', 1, '1452\\4226', '20362', '10000', '测试', 1481431685),
(2, 2, 4, '5267', 'P00367-000', 1, '1452\\4226', '20362', '10000', '测试', 1481431685),
(3, 2, 5, '5268', 'P00367-000', 1, '1452\\4226', '20362', '10000', '测试', 1481431685),
(4, 1, 3, '5266', 'P00367-000', 1, '1452\\4226', 'bg65343', '10001', 'sfsd', 1481496788),
(5, 1, 4, '5267', 'P00367-000', 1, '1452\\4226', 'bg65343', '10001', 'sfsd', 1481496788);
