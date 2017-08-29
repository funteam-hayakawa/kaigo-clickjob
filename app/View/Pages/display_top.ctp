<?php 
//header
echo $this->element('header'); 
?>

トップページ

<hr>

掲載求人数：<?php echo $recruitSheetCount['all'] ?><br/>
ハローワーク求人：<?php echo $recruitSheetCount['hw'] ?><br/>
最終更新日：<?php echo date('Y/m/d H:m', strtotime($recruitSheetCount['modified'])) ?>
<hr>

エリアから探す
<br>
<?php
foreach($area as $a){
    echo $a['Area']['name'];
    echo '<br/>';
    foreach ($a['Prefecture'] as $p){
        echo $this->Html->link($p['name'], array('controller' => 'search', 'action' => 'area', $p['short_name']) );  
        echo '&nbsp';
    }
    echo '<br/>';
} 
?>
<hr>

人気の求人特集

<?php foreach($ranking as $r): ?>
    <table>
        <tr>
            <td>求人ID</td>
            <td><?php echo $r['RecruitSheet']['recruit_sheet_id']; ?>
            </td>
        </tr>
        <tr>
            <td>施設名</td>
            <td><?php echo $r['RecruitSheet']['Office']['name']; ?></td>
        </tr>
        <tr>
            <td>施設画像</td>
            <td>
                <?php if (!empty($r['RecruitSheet']['Office']['OfficeImage']) && !empty($r['RecruitSheet']['Office']['OfficeImage']['name'])): ?>
                <img src="<?php echo '/read/hospital/'.$r['RecruitSheet']['Office']['OfficeImage']['name']; ?>" width="128">
                <?php else: ?>
                <img src="<?php echo '/img/hospital/nophoto'.($r['RecruitSheet']['Office']['id']%50+1).'.png'; ?>" width="128">
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>求人票タイトル</td>
            <td><?php echo $this->Html->link($r['RecruitSheet']['sheet_title'], '/detail/'.$r['RecruitSheet']['recruit_sheet_id']); ?></td>
        </tr>
        <tr>
            <td>求人紹介タイトル</td>
            <td><?php echo $r['RecruitSheet']['recruit_introduce_title']; ?></td>
        </tr>
        <?php $flexTypeList = explode(',', $r['RecruitSheet']['recruit_flex_type']); ?>
        <?php foreach ($flexTypeList as $f): ?>
            <?php if (isset($recruit_flex_type[$f])): ?>
                <tr>
                    <td>融通</td>
                    <td><?php echo $recruit_flex_type[$f]; ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
<?php endforeach; ?>
<hr>
