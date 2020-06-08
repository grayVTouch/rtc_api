-- drop table if exists `rtc_friend_circle_option`;
-- create table if not exists `rtc_friend_circle_option` (
--   id int unsigned not null auto_increment ,
--   user_id int unsigned default 0 comment 'rtc_user.id' ,
--   type varchar(255) default '' comment '可见范围：private-私密（仅自己可看） public-公开' ,
--   create_time datetime default current_timestamp ,
--   primary key `id` (`id`)
-- ) engine = innodb character set = utf8mb4 collate = utf8mb4_bin comment '朋友圈选项';

drop table if exists `rtc_friend_circle_visible`;
create table if not exists `rtc_friend_circle_visible` (
  id int unsigned not null auto_increment ,
  user_id int unsigned default 0 comment 'rtc_user.id' ,
  relation_user_id int unsigned default 0 comment 'rtc_user.id，相关联的用户' ,
  type tinyint default 0 comment '0-不看谁 1-不让他看' ,
  create_time datetime default current_timestamp ,
  primary key `id` (`id`)
) engine = innodb character set = utf8mb4 collate = utf8mb4_bin comment '朋友圈查看范围（不看谁|不让他看）';

drop table if exists `rtc_friend_circle`;
create table if not exists `rtc_friend_circle` (
  id int unsigned not null auto_increment ,
  user_id int unsigned default 0 comment 'rtc_user.id' ,
  type varchar(255) default '' comment '可见范围：private-私密（仅自己可看） public-公开' ,
  `content` text comment '文本' ,
  create_time datetime default current_timestamp ,
  primary key `id` (`id`)
) engine = innodb character set = utf8mb4 collate = utf8mb4_bin comment '朋友圈';

drop table if exists `rtc_friend_circle_media`;
create table if not exists `rtc_friend_circle_media` (
  id int unsigned not null auto_increment ,
  friend_circle_id int unsigned default 0 comment 'rtc_friend_circle.id' ,
  friend_circle_sender int unsigned default 0 comment 'rtc_user.id，朋友圈发布者，缓存字段' ,
  mime varchar(100) default '' comment '媒体类型' ,
  src varchar(1000) default '' comment '资源地址' ,
  create_time datetime default current_timestamp ,
  primary key `id` (`id`)
) engine = innodb character set = utf8mb4 collate = utf8mb4_bin comment '朋友圈-媒体';

drop table if exists `rtc_friend_circle_comment`;
create table if not exists `rtc_friend_circle_comment` (
  id int unsigned not null auto_increment ,
  friend_circle_id int unsigned default 0 comment 'rtc_friend_circle.id' ,
  friend_circle_sender int unsigned default 0 comment 'rtc_user.id，朋友圈发布者，缓存字段' ,
  user_id int unsigned default 0 comment 'rtc_user.id,评论的用户' ,
  p_id int unsigned default 0 comment 'rtc_friend_circle_comment.id，上级评论id' ,
  content text comment '评论内容' ,
  create_time datetime default current_timestamp ,
  primary key `id` (`id`)
) engine = innodb character set = utf8mb4 collate = utf8mb4_bin comment '朋友圈的评论';

drop table if exists `rtc_friend_circle_commendation`;
create table if not exists `rtc_friend_circle_commendation` (
  id int unsigned not null auto_increment ,
  friend_circle_id int unsigned default 0 comment 'rtc_friend_circle.id' ,
  friend_circle_sender int unsigned default 0 comment 'rtc_user.id，朋友圈发布者，缓存字段' ,
  user_id int unsigned default 0 comment 'rtc_user.id,评论的用户' ,
  create_time datetime default current_timestamp ,
  primary key `id` (`id`)
) engine = innodb character set = utf8mb4 collate = utf8mb4_bin comment '朋友圈点赞记录';


drop table if exists `rtc_friend_circle_unread`;
create table if not exists `rtc_friend_circle_unread` (
  id int unsigned not null auto_increment ,
  user_id int unsigned default 0 comment 'rtc_user.id,朋友圈未读消息数量' ,
  total_unread_count int unsigned default 0 comment '总：未读消息数量' ,
  comment_unread_count int unsigned default 0 comment '评论：未读评论数量' ,
  commendation_unread_count int unsigned default 0 comment '评论：未读点赞的数量' ,
  common_unread_count int unsigned default 0 comment '评论：未读评论数量 + 未读点赞数量' ,
  friend_circle_unread_count int unsigned default 0 comment '朋友圈：未读消息数量' ,
  create_time datetime default current_timestamp ,
  primary key `id` (`id`)
) engine = innodb character set = utf8mb4 collate = utf8mb4_bin comment '朋友圈未读消息数量';

CREATE TABLE `rtc_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(255) COLLATE utf8mb4_bin DEFAULT '' COMMENT '名称',
  `thumb` varchar(500) COLLATE utf8mb4_bin DEFAULT '' COMMENT '封面',
  `ios_link` varchar(500) COLLATE utf8mb4_bin DEFAULT '' COMMENT 'ios 下载链接',
  `android_link` varchar(500) COLLATE utf8mb4_bin DEFAULT '' COMMENT 'android 下载链接',
  `android_wakeup_link` varchar(500) COLLATE utf8mb4_bin DEFAULT '' COMMENT 'android 唤醒链接',
  `ios_wakeup_link` varchar(500) COLLATE utf8mb4_bin DEFAULT '' COMMENT 'ios 唤醒链接',
  `is_app` tinyint(4) DEFAULT '0' COMMENT '是否 app： 0-否 1-是',
  `link` varchar(500) COLLATE utf8mb4_bin DEFAULT '' COMMENT '外部链接',
  `weight` smallint(6) DEFAULT '0' COMMENT '权重',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC COMMENT='应用表';

drop table if exists `rtc_image`;
create table if not exists `rtc_image` (
  id int unsigned not null auto_increment ,
  `position` varchar(100) default '' comment '位置' ,
  image varchar(1000) default '' comment '图片地址' ,
  mime varchar(100) default '' comment '媒体类型' ,
  create_time datetime default current_timestamp ,
  primary key `id` (`id`)
) engine = innodb character set = utf8mb4 collate = utf8mb4_bin comment '图片表';