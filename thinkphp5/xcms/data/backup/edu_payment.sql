DROP TABLE IF EXISTS <--db-prefix-->payment;
CREATE TABLE `<--db-prefix-->payment` (
  `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `out_trade_no` varchar(100) DEFAULT '' COMMENT '商品订单',
  `pay_trade_no` varchar(100) DEFAULT NULL COMMENT '支付订单号',
  `money` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单金额',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `type` varchar(50) DEFAULT '' COMMENT '支付方式',
  `uid` int(11) DEFAULT NULL COMMENT '付款uid',
  `create_time` int(11) DEFAULT NULL COMMENT '排序',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '订单更新时间',
  `client_ip` varchar(50) DEFAULT '' COMMENT '支付ip',
  `product_name` varchar(200) DEFAULT '' COMMENT '商品名称',
  `product_body` varchar(200) DEFAULT '' COMMENT '商品描述',
  `product_url` varchar(100) DEFAULT '' COMMENT '商品地址',
  `extra_param` varchar(500) DEFAULT '' COMMENT '特殊扩展',
  `payment_cid` varchar(100) NOT NULL DEFAULT '未填写' COMMENT '前台栏目',
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `out_trade_no` (`out_trade_no`),
  UNIQUE KEY `pay_trade_no` (`pay_trade_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;