<?php 
//header
echo $this->element('header'); 
?>
<script src="/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript">
        $(function(){
          function isJSON(arg) {
              arg = (typeof arg === "function") ? arg() : arg;
              if (typeof arg  !== "string") {
                  return false;
              }
              try {
              arg = (!JSON) ? eval("(" + arg + ")") : JSON.parse(arg);
                  return true;
              } catch (e) {
                  return false;
              }
          };
          $('#favorite_btn').click(function(){
            $.ajax({
               type: "POST",
               url: "/members_log/favorite/",
               data: {'recruit_sheet_id' : $('#favorite_btn').attr('recruit_sheet_id'),
                      'favorite_flg' : 1,
                      },
               success: function(msg){
                   if (!isJSON(msg)){
                      alert("予期しないエラーが発生しました");
                      return;
                   }
                   var result = JSON.parse(msg);
                   alert(result.msg);
               }
             });//ajax
          });
          $('#delete_favorite_btn').click(function(){
            $.ajax({
               type: "POST",
               url: "/members_log/favorite/",
               data: {'recruit_sheet_id' : $('#favorite_btn').attr('recruit_sheet_id'),
                      'favorite_flg' : 0,
                      },
               success: function(msg){
                 if (!isJSON(msg)){
                    alert("予期しないエラーが発生しました");
                    return;
                 }
                 var result = JSON.parse(msg);
                 alert(result.msg);
               }
             });//ajax
          });
          /* 閲覧履歴登録post */
          $.ajax({
             type: "POST",
             url: "/members_log/visitlog/",
             data: {'recruit_sheet_id' : $('#favorite_btn').attr('recruit_sheet_id'),
                    },
             success: function(msg){
             }
           });//ajax
        });
</script>

<input type="button" value="お気に入りに追加" id="favorite_btn" style="width:120px;" recruit_sheet_id="<?php echo $recruitSheet['RecruitSheet']['recruit_sheet_id'] ?>">
<input type="button" value="お気に入りから削除" id="delete_favorite_btn" style="width:120px;" recruit_sheet_id="<?php echo $recruitSheet['RecruitSheet']['recruit_sheet_id'] ?>">


<h1>施設情報</h1>
<table>
    <tr>
        <th>要素</th>
        <th>内容</th>
    </tr>
    <tr>
        <td>ID</td>
        <td><?php echo $recruitSheet['Office']['id']; ?></td>
    </tr>
    <tr>
        <td>施設名</td>
        <td><?php echo $recruitSheet['Office']['name']; ?></td>
    </tr>
    <tr>
        <td>施設画像</td>
        <td>
            <?php if (!empty($recruitSheet['Office']['OfficeImage']) && !empty($recruitSheet['Office']['OfficeImage']['name'])): ?>
            <img src="<?php echo '/read/hospital/'.$recruitSheet['Office']['OfficeImage']['name']; ?>" width="128">
            <?php else: ?>
            <img src="<?php echo '/img/hospital/nophoto'.($recruitSheet['Office']['id']%50+1).'.png'; ?>" width="128">
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>施設紹介タイトル</td>
        <td><?php echo $recruitSheet['Office']['OfficeInfo']['introduce_title']; ?></td>
    </tr>
    <tr>
        <td>施設紹介本文</td>
        <td><?php echo $recruitSheet['Office']['OfficeInfo']['introduce_memo']; ?></td>
    </tr>
    <tr>
        <td>勤務地</td>
        <?php 
          $city = '';
          if (!empty($recruitSheet['Office']['City'])){
            $city = $recruitSheet['Office']['City']['name'];
          } else {
            if (!empty($recruitSheet['Office']['State'])){
              $city = $recruitSheet['Office']['State']['name'];
            }
          }
         ?>
        <td><?php echo $recruitSheet['Office']['Prefecture']['name'].$city.$recruitSheet['Office']['address']; ?></td>
    </tr>
    <?php foreach($recruitSheet['Office']['OfficeStation'] as $st): ?>
        <?php if (!empty($st['station']) && !empty($st['access_interval'])): ?>
            <tr>
                <td>アクセス</td>
                <?php 
                  $access = '';
                  if (isset($access_type[$st['access_type']])){
                    $access = $access_type[$st['access_type']];
                  }
                 ?>
                <td><?php echo $st['station'].'駅'.$access.$st['access_interval'].'分' ; ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    <tr>
        <td>サービス種類</td>
        <td>
            <?php $institutionTypeList = explode(',', $recruitSheet['Office']['institution_type']); ?>
            <?php foreach($institutionTypeList as $i){
                if (isset($institution_type[$i])){
                    echo $institution_type[$i];
                }
            } ?>
        </td>
    </tr>
</table>
<table>
    <h2>求人情報</h2>
    <tr>
        <td>求人票ID</td>
        <td><?php echo $recruitSheet['RecruitSheet']['recruit_sheet_id']; ?></td>
    </tr>
    <tr>
        <td>募集職種</td>
        <td><?php echo $recruitSheet['RecruitSheet']['sheet_title']?></td>
    </tr>
    <tr>
        <td>給与</td>
        <td><?php echo $recruitSheet['RecruitSheet']['salary']?></td>
    </tr>
    <tr>
        <td>応募条件</td>
        <td><?php echo $recruitSheet['RecruitSheet']['application_conditions']?></td>
    </tr>
    <tr>
        <td>資格</td>
        <td>
            <?php $applicationLicenseTypeList = explode(',', $recruitSheet['RecruitSheet']['application_license']); ?>
            <?php foreach($applicationLicenseTypeList as $i){
                if (isset($application_license[$i])){
                    echo $application_license[$i];
                }
            } ?>
        </td>
    </tr>
    <tr>
        <td>雇用形態</td>
        <td><?php if (isset($employment_type[$recruitSheet['RecruitSheet']['employment_type']])) {echo $employment_type[$recruitSheet['RecruitSheet']['employment_type']];}; ?></td>
    </tr>
    <tr>
        <td>就業時間</td>
        <td>
          <?php for($i = 1; $i <= 6 ; $i++): ?>
            <?php if (!empty($recruitSheet['RecruitSheet']["working_hours$i".'_from']) && !empty($recruitSheet['RecruitSheet']["working_hours$i".'_to'])){
              echo $recruitSheet['RecruitSheet']["working_hours$i".'_from'].'〜'.$recruitSheet['RecruitSheet']["working_hours$i".'_to'].'<br/>';
            } else {
              break;
            }?>
          <?php endfor; ?>
          <?php
          $text = '';
          // 休憩時間(日勤)
          $tmp = $recruitSheet['RecruitSheet']['recess_day'] == '' ? '' : $recruitSheet['RecruitSheet']['recess_day'].'分';
          if (!empty($tmp)) {
            $text .= "休憩時間(日勤)$tmp<br/>";
          }

          // 休憩時間(夜勤)
          $tmp = $recruitSheet['RecruitSheet']['recess_night'] == '' ? '' : $recruitSheet['RecruitSheet']['recess_night'].'分';
          if (!empty($tmp)) {
            $text .= "休憩時間(夜勤)$tmp<br/>";
          }

          // 時間外
          $tmp = '';
          $tmp = $recruitSheet['RecruitSheet']['overtime_work'] == '' ? '' : '月平均'.$recruitSheet['RecruitSheet']['overtime_work'].'時間';
          if (!empty($tmp)) {
            $text .= "時間外$tmp<br/>";
          } 
          echo $text;
           ?>
        </td>
    </tr>
    <tr>
        <td>仕事内容</td>
        <td><?php echo $recruitSheet['RecruitSheet']['work']?></td>
    </tr>
    <tr>
        <td>賞与</td>
        <td><?php echo $recruitSheet['RecruitSheet']['bonus']?></td>
    </tr>
    <?php if (isset($house_for_single[$recruitSheet['RecruitSheet']['house_for_single']])): ?>
      <tr>
          <td>入居可能住宅 単身用</td>
          <td><?php echo $house_for_single[$recruitSheet['RecruitSheet']['house_for_single']] ?></td>
      </tr>
    <?php endif; ?>
    <?php if (isset($house_for_family[$recruitSheet['RecruitSheet']['house_for_family']])): ?>
      <tr>
          <td>入居可能住宅 家族用</td>
          <td><?php echo $house_for_family[$recruitSheet['RecruitSheet']['house_for_family']] ?></td>
      </tr>
    <?php endif; ?>
    <tr>
        <td>休み</td>
        <td><?php echo $recruitSheet['RecruitSheet']['holiday']?></td>
    </tr>
    <tr>
        <td>通勤</td>
        <td>
          <?php  
            $text = '';
            if (isset($mycar[$recruitSheet['RecruitSheet']['mycar']])){
                $text .= '車通勤'.$mycar[$recruitSheet['RecruitSheet']['mycar']].'<br/>';
            }
            if (isset($commutation[$recruitSheet['RecruitSheet']['commutation']])){
                $tmp = $commutation[$recruitSheet['RecruitSheet']['commutation']];
                $tmp = str_replace('{%1}', number_format($recruitSheet['RecruitSheet']['commutation_limit']), $tmp);
                $text .= '通勤手当'.$tmp.'<br/>';
            }
            echo $text;
          ?>
        </td>
    </tr>
    <tr>
        <td>福利厚生</td>
        <td>該当カラム不明</td>
    </tr>
    <tr>
        <td>加入保険</td>
        <td>
          <?php $socialInsuranceTypeList = explode(',', $recruitSheet['RecruitSheet']['social_insurance']); 
            $tmp = '';
            foreach($socialInsuranceTypeList as $i){
              if (isset($social_insurance[$i])){
                  $tmp .= empty($tmp) ? '' : '、';
                  $tmp .= $social_insurance[$i];
              }
            }
            echo $tmp;
           ?>
        </td>
    </tr>
    <tr>
        <td>退職制度</td>
        <td>
          <?php
          // 退職制度
          $text = '';
          // 定年
          $tmp = '';
          if (isset($retirement[$recruitSheet['RecruitSheet']['retirement']])){
              $tmp = sprintf('%s歳', $recruitSheet['RecruitSheet']['retirement_age']);
              $text .= '定年'.$tmp.'<br/>';
          }
          // 再雇用
          $tmp = '';
          if (isset($reemployment[$recruitSheet['RecruitSheet']['reemployment']])){
              $tmp = sprintf('%s歳まで', $recruitSheet['RecruitSheet']['reemployment_age']);
              $text .= '再雇用'.$tmp.'<br/>';
          }
          // 退職金
          $tmp = '';
          if (isset($retirement_pay[$recruitSheet['RecruitSheet']['retirement_pay']])){
              $tmp = sprintf('あり (勤続%s年以上)', $recruitSheet['RecruitSheet']['retirement_pay_conditions']);
              $text .= '退職金'.$tmp.'<br/>';
          }
          echo $text;
          ?>
        </td>
    </tr>
    <tr>
        <td>備考</td>
        <td><?php echo $recruitSheet['RecruitSheet']['notes']?></td>
    </tr>
</table>
<hr>
同じ事業所の求人
<table>
    <h2>求人情報</h2>
    <?php foreach($recruitSheet['Office']['RecruitSheet'] as $rs): ?>
        <?php if ($rs['recruit_sheet_id'] == $recruitSheet['RecruitSheet']['recruit_sheet_id']){
          continue;
        } ?>
        <tr>
            <td>求人票ID</td>
            <td><?php echo $rs['recruit_sheet_id']; ?></td>
        </tr>
        <tr>
            <td>求人票タイトル</td>
            <td><?php echo $this->Html->link($rs['sheet_title'], '/detail/'.$rs['recruit_sheet_id']); ?></td>
        </tr>
        <tr>
            <td>雇用形態</td>
            <td><?php if (isset($employment_type[$rs['employment_type']])) {echo $employment_type[$rs['employment_type']];}; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
