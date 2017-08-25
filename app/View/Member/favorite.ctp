<?php 
//header
echo $this->element('header'); 
?>

<h1>お気に入り求人</h1>

<?php if(isset($loggedIn) && $loggedIn): ?>
  <?php echo $this->Html->link('登録情報編集', '/member/edit'); ?><br/>
  <?php echo $this->Html->link('ログアウト', '/member/logout'); ?><br/>
<?php endif; ?>

<table>
    <?php foreach($favorite as $h): ?>
        <?php $rs = $h['RecruitSheet'] ?>
        <tr>
            <td>お気に入り追加日時</td>
            <td><?php echo $h['MembersFavoriteRecruitsheets']['modified']; ?></td>
        </tr>
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
        <tr>
            <td>勤務地</td>
            <?php 
              $city = '';
              if (!empty($rs['Office']['City'])){
                $city = $rs['Office']['City']['name'];
              } else {
                if (!empty($rs['Office']['State'])){
                  $city = $rs['Office']['State']['name'];
                }
              }
             ?>
            <td><?php echo $rs['Office']['Prefecture']['name'].$city.$rs['Office']['address']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>