CREATE TABLE members_mail_confirm_tables (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL COMMENT '登録e-mail',
    `token` varchar(255) NOT NULL COMMENT '登録urlトークン',
    `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
    `created` datetime NOT NULL COMMENT '作成時刻',
    `modified` datetime NOT NULL COMMENT '更新時刻',
    PRIMARY KEY (`id`)
);
