CREATE TABLE members (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL COMMENT '登録e-mail',
    `password` VARCHAR(255) NOT NULL COMMENT 'ログインパスワード',
    `auto_login_token` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '自動ログイントークン',
    `withdraw_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退会フラグ',
    `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
    `created` datetime NOT NULL COMMENT '作成時刻',
    `modified` datetime NOT NULL COMMENT '更新時刻',
    PRIMARY KEY (`id`)
);
