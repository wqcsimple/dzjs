-- 页面
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (1, '关于我们', 'about');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (2, '阳光牧场', 'case-1');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (3, '优质果园', 'case-2');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (4, '运输方式', 'case-3');
INSERT INTO `{{$prefix}}pages` (id, title, alias) VALUES (5, '联系我们', 'contact');

-- 基础参数
INSERT INTO `{{$prefix}}options` VALUES ('1', 'copyright', 'Copyright © 2012-2013 fayfox.com', '', '{{$time}}', '0', '1');
INSERT INTO `{{$prefix}}options` VALUES ('2', 'beian', '浙ICP备12036784号-1', '', '{{$time}}', '0', '1');
INSERT INTO `{{$prefix}}options` VALUES ('3', 'sitename', 'Fayfox', '', '{{$time}}', '0', '1');

-- 基础分类
INSERT INTO `{{$prefix}}categories` (id, parent, sort, alias, title, description) VALUES ('271', '1', '100', 'product', '产品', '');

-- 小工具
INSERT INTO `{{$prefix}}widgets` VALUES ('1', 'index-slides-camera', '{\\\"height\\\":617,\\\"transPeriod\\\":800,\\\"time\\\":5000,\\\"fx\\\":\\\"simpleFade\\\",\\\"files\\\":[{\\\"file_id\\\":\\\"646\\\",\\\"link\\\":\\\"\\\",\\\"title\\\":\\\"slide-1.jpg\\\"},{\\\"file_id\\\":\\\"647\\\",\\\"link\\\":\\\"\\\",\\\"title\\\":\\\"slide-2.jpg\\\"},{\\\"file_id\\\":\\\"648\\\",\\\"link\\\":\\\"\\\",\\\"title\\\":\\\"slide-3.jpg\\\"}]}', 'common/jq_camera', '使用Jquery Camera插件', '1');
INSERT INTO `{{$prefix}}widgets` VALUES ('2', 'contacts', '{\\\"data\\\":[{\\\"key\\\":\\\"\\\\u516c\\\\u53f8\\\\u540d\\\\u79f0\\\",\\\"value\\\":\\\"Fayfox\\\\u5de5\\\\u4f5c\\\\u5ba4\\\"},{\\\"key\\\":\\\"\\\\u90ae\\\\u7f16\\\",\\\"value\\\":\\\"310000\\\"},{\\\"key\\\":\\\"\\\\u90ae\\\\u7bb1\\\",\\\"value\\\":\\\"admin@fayfox.com\\\"},{\\\"key\\\":\\\"\\\\u5730\\\\u5740\\\",\\\"value\\\":\\\"\\\\u6d59\\\\u6c5f\\\\u676d\\\\u5dde\\\\u6ee8\\\\u6c5f\\\\u533a\\\\u6d77\\\\u5a01\\\\u56fd\\\\u9645\\\"}],\\\"template\\\":\\\"<p><label>{$key}\\\\uff1a<\\\\/label>{$value}<\\\\/p> \\\"}', 'common/options', '联系方式', '1');

-- 顶部导航
INSERT INTO `{{$prefix}}menus` VALUES ('67', '0', '100', '22', '31', '_fruit_top', '水果顶部导航', '', '', '');
INSERT INTO `{{$prefix}}menus` VALUES ('68', '67', '100', '23', '24', 'home', '首页', '', '{$base_url}', '');
INSERT INTO `{{$prefix}}menus` VALUES ('69', '67', '100', '25', '26', 'product', '产品中心', '', '{$base_url}product/', '');
INSERT INTO `{{$prefix}}menus` VALUES ('70', '67', '100', '27', '28', 'contact', '联系我们', '', '{$base_url}contact.html', '');
INSERT INTO `{{$prefix}}menus` VALUES ('71', '67', '100', '29', '30', 'taobao', '淘宝店铺', '', 'http://shop68779173.taobao.com/', '_blank');