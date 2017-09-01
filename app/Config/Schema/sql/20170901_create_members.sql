CREATE TABLE members (
    `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL COMMENT '登録e-mail',
    `birthday_year` VARCHAR(4) NOT NULL COMMENT '生年',
    `name` VARCHAR(255) NOT NULL COMMENT '名前',
    `prefecture` VARCHAR(4) NOT NULL COMMENT '都道府県code',
    `cities` VARCHAR(8) NOT NULL COMMENT '市区町村code',
    `password` VARCHAR(255) NOT NULL COMMENT 'ログインパスワード',
    `other_text` TEXT COMMENT 'その他テキスト',
    `auto_login_token` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '自動ログイントークン',
    `pw_reset_token` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'パスワードリセットトークン',
    `withdraw_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '退会フラグ',
    `del_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
    `created` DATETIME NOT NULL COMMENT '作成時刻',
    `modified` DATETIME NOT NULL COMMENT '更新時刻',
    PRIMARY KEY (`id`)
);

CREATE TABLE member_licenses (
    `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `member_id` INT(11) unsigned NOT NULL COMMENT 'メンバーID', 
    `license` VARCHAR(4) NOT NULL COMMENT '資格コード',
    `del_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
    `created` DATETIME NOT NULL COMMENT '作成時刻',
    `modified` DATETIME NOT NULL COMMENT '更新時刻',
    PRIMARY KEY (`id`)
);