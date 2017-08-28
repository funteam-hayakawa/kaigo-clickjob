-----------------------------------------------------------------
本メールはお客様からのお問い合わせがメールサーバに
到達した時点で送信される、自動配信メールです。
-----------------------------------------------------------------

応募がありました。

お名前：<?php echo $data['Registration']['name']."\n" ?>
ふりがな：<?php echo $data['Registration']['name_kana']."\n" ?>
生年： <?php echo $data['Registration']['birthday_year']."年\n" ?>
郵便番号：<?php echo $data['Registration']['postcode']."\n" ?>
都道府県：<?php echo $data['Registration']['pref_name']."\n" ?>
市区町村：<?php echo $data['Registration']['city_name']."\n" ?>
電話：<?php echo $data['Registration']['tel']."\n" ?>
保有資格：<?php 
foreach (explode(',', $data['Registration']['license']) as $l){
    echo $licence[$l].',' ;
}
echo "\n";
 ?>
メールアドレス：<?php echo $data['Registration']['mail']."\n" ?>
その他：<?php echo $data['Registration']['other_text']."\n" ?>
<?php echo $data['Registration']['recent_history']."\n" ?>
注文番号：<?php echo $data['Registration']['order_number']."\n" ?>

--
このメールは、クリックジョブ介護の会員登録フォーム (https://kaigo.clickjob.jp/register/) から送信されました
