CREATE TABLE inquiries (
    `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `recruit_sheet_id` INT(11) unsigned DEFAULT NULL COMMENT '求人票id',
    `type` TINYINT(4) NOT NULL COMMENT '問い合わせタイプ',
    `email` VARCHAR(255) NOT NULL COMMENT 'e-mail',
    `birthday_year` VARCHAR(4) NOT NULL COMMENT '生年',
    `name` VARCHAR(255) NOT NULL COMMENT '名前',
    `zipcode` VARCHAR(8) NOT NULL COMMENT '郵便番号',
    `tel` VARCHAR(20) NOT NULL COMMENT '電話番号',
    `prefecture` VARCHAR(4) NOT NULL COMMENT '都道府県code',
    `cities` VARCHAR(8) NOT NULL COMMENT '市区町村code',
    `other_text` TEXT COMMENT 'その他テキスト',
    `del_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
    `created` DATETIME NOT NULL COMMENT '作成時刻',
    `modified` DATETIME NOT NULL COMMENT '更新時刻',
    PRIMARY KEY (`id`)
);

CREATE TABLE inquiry_licenses (
    `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `inquiry_id` INT(11) unsigned NOT NULL COMMENT '問い合わせID', 
    `license` VARCHAR(4) NOT NULL COMMENT '資格コード',
    `del_flg` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
    `created` DATETIME NOT NULL COMMENT '作成時刻',
    `modified` DATETIME NOT NULL COMMENT '更新時刻',
    PRIMARY KEY (`id`)
);