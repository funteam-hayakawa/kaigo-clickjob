CREATE TABLE members_recruitsheet_access_histories (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `member_id` int(11) unsigned NOT NULL  COMMENT 'メンバーID',
    `recruit_sheet_id` int(11) unsigned NOT NULL  COMMENT '求人票ID',
    `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
    `created` datetime NOT NULL COMMENT '作成時刻',
    `modified` datetime NOT NULL COMMENT '更新時刻（最終訪問時刻）',
    PRIMARY KEY (`id`)
);
