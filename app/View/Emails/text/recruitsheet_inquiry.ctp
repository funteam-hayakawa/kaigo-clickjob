-----------------------------------------------------------------
本メールはお客様からのお問い合わせがメールサーバに
到達した時点で送信される、自動配信メールです。
-----------------------------------------------------------------

お問い合わせがありました。

お名前：<?php echo $inquiry['Inquiry']['name']."\n" ?>
生年： <?php echo $inquiry['Inquiry']['birthday_year']."年\n" ?>
郵便番号：<?php echo $inquiry['Inquiry']['zipcode']."\n" ?>
都道府県：<?php echo $inquiry['Prefecture']['name']."\n" ?>
市区町村：<?php echo $inquiry['State']['name'].$inquiry['City']['name']."\n" ?>
電話：<?php echo $inquiry['Inquiry']['tel']."\n" ?>
保有資格：<?php 
foreach ($inquiry['InquiryLicense'] as $l){
    echo $license[$l['license']].',' ;
}
echo "\n";
 ?>
メールアドレス：<?php echo $inquiry['Inquiry']['email']."\n" ?>

【お問い合わせ案件】
事業所id：<?php echo $recruitSheet['Office']['id'] ?>   求人名：<?php echo $recruitSheet['Office']['name']."\n" ?>
求人票id：<?php echo $recruitSheet['RecruitSheet']['recruit_sheet_id'] ?>   求人名：<?php echo $recruitSheet['RecruitSheet']['sheet_title']."\n" ?>

【お問い合わせ種別】
<?php echo $inquiryType[$inquiry['Inquiry']['type']]."\n" ?>

【お問い合わせテキスト】
<?php echo $inquiry['Inquiry']['other_text']."\n" ?>

--
このメールは、クリックジョブ介護の会員登録フォーム (https://kaigo.clickjob.jp/inquiry/) から送信されました