CREATE TABLE members_favorite_recruitsheets (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `member_id` int(11) unsigned NOT NULL  COMMENT 'メンバーID',
    `recruit_sheet_id` int(11) unsigned NOT NULL  COMMENT '求人票ID',
    `favorite_flg` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'お気に入りフラグ',
    `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
    `created` datetime NOT NULL COMMENT '作成時刻',
    `modified` datetime NOT NULL COMMENT '更新時刻',
    PRIMARY KEY (`id`)
);
