<?php 
//header
echo $this->element('header'); 
?>

SEOテキスト
<?php if (!empty($seoHeaderText)): ?>
<div class="seoBox">
    <h2 class="seoTitle">
    <span style="font-size:52px;color:#ff397a;">
    <?php 
        $areaName = '';
        if (empty($seoHeaderText['City']['name'])){
            $areaName = $seoHeaderText['Prefecture']['name'].$seoHeaderText['State']['name'];
        } else {
            $areaName = $seoHeaderText['Prefecture']['name'].$seoHeaderText['City']['name'];
        }
    ?>
    <?php echo $areaName; ?> 
    </span>
    <span style="font-size:34px;">
        <?php echo $seoHeaderText['SeoHeaderText']['text1'] ?>
    </span>
    </h2>
    <br/>
    <div class="seoText">
       <?php echo $seoHeaderText['SeoHeaderText']['text2']; ?>
    </div>
</div>
<?php endif; ?>
<hr>

検索エリア

<?php echo $this->Form->create(false, array('type' => 'post',
                                               'url' => array('controller' => 'search', 'action' => 'result')
)); ?>
<table>
  <tr>
    <th>検索項目</th>
    <th>検索条件</th>
  </tr>
  <tr>
    <td>職種</td>
    <td>
      <?php echo $this->Form->input('Search.occupation', array(
                                    'type' => 'select', 
                                    'multiple'=> 'checkbox',
                                    'label' => false,
                                    'required' => false,
                                    'options' => $occupation,
            ))
       ?>
    </td>
  </tr>
  <tr>
    <td>施設</td>
    <td>
      <?php echo $this->Form->input('Search.institution_type', array(
                                    'type' => 'select', 
                                    'multiple'=> 'checkbox',
                                    'label' => false,
                                    'required' => false,
                                    'options' => $institution_type_search_disp,
            ))
       ?>
    </td>
  </tr>
  <tr>
    <td>資格</td>
    <td>
      <?php echo $this->Form->input('Search.application_license', array(
                                    'type' => 'select', 
                                    'multiple'=> 'checkbox',
                                    'label' => false,
                                    'required' => false,
                                    'options' => $application_license_search_disp,
            ))
       ?>
    </td>
  </tr>
  <tr>
    <td>雇用形態</td>
    <td>
      <?php echo $this->Form->input('Search.employment_type', array(
                                    'type' => 'select', 
                                    'multiple'=> 'checkbox',
                                    'label' => false,
                                    'required' => false,
                                    'options' => $employment_type_search_disp,
            ))
       ?>
    </td>
  </tr>
  <tr>
    <td>働きやすさ</td>
    <td>
      <?php echo $this->Form->input('Search.recruit_flex_type', array(
                                    'type' => 'select', 
                                    'multiple'=> 'checkbox',
                                    'label' => false,
                                    'required' => false,
                                    'options' => $recruit_flex_type,
            ))
       ?>
    </td>
  </tr>
  <tr>
    <td>働く時間</td>
    <td>
      <?php echo $this->Form->input('Search.particular_ttl_hour', array(
                                    'type' => 'select', 
                                    'multiple'=> 'checkbox',
                                    'label' => false,
                                    'required' => false,
                                    'options' => $particular_ttl_hour,
            ))
       ?>
    </td>
  </tr>
  <tr>
    <td>フリーワード</td>
    <td>
      <?php echo $this->Form->input('Search.freeword', array(
                                    'label' => false,
                                    'required' => false
            ))
       ?>
    </td>
  </tr>
</table>
<?php echo $this->Form->end(array('label' => __('Search'),
    'class' => 'btn btn-default btn-search-margin',
)) ?>
<hr>
<p>
    <small>
        <?php echo $this->Paginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
    </small>
</p>
<ul class="pagination pagination-sm">
    <?php
        echo $this->Paginator->prev(__('&larr; Previous'), array('class' => 'prev','tag' => 'li','escape' => false), '<a onclick="return false;">&larr; Previous</a>', array('class' => 'prev disabled','tag' => 'li','escape' => false));
        echo $this->Paginator->numbers(array('separator' => '','tag' => 'li','currentClass' => 'active','currentTag' => 'a'));
        echo $this->Paginator->next(__('Next &rarr;'), array('class' => 'next','tag' => 'li','escape' => false), '<a onclick="return false;">Next &rarr;</a>', array('class' => 'next disabled','tag' => 'li','escape' => false));
    ?>
</ul>
<?php foreach ($officeSearchResult as $o): ?>
<h1>施設情報</h1>
<table>
    <tr>
        <th>要素</th>
        <th>内容</th>
    </tr>
    <tr>
        <td>ID</td>
        <td><?php echo $o['Office']['id']; ?></td>
    </tr>
    <tr>
        <td>施設名</td>
        <td><?php echo $o['Office']['name']; ?></td>
    </tr>
    <tr>
        <td>施設画像</td>
        <td>
            <?php if (!empty($o['OfficeImage']) && !empty($o['OfficeImage']['name'])): ?>
            <img src="<?php echo '/read/hospital/'.$o['OfficeImage']['name']; ?>" width="128">
            <?php else: ?>
            <img src="<?php echo '/img/hospital/nophoto'.($o['Office']['id']%50+1).'.png'; ?>" width="128">
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>施設紹介タイトル</td>
        <td><?php echo $o['OfficeInfo']['introduce_title']; ?></td>
    </tr>
    <tr>
        <td>施設紹介本文</td>
        <td><?php echo $o['OfficeInfo']['introduce_memo']; ?></td>
    </tr>
    <tr>
        <td>勤務地</td>
        <?php 
          $city = '';
          if (!empty($o['City'])){
            $city = $o['City']['name'];
          } else {
            if (!empty($o['State'])){
              $city = $o['State']['name'];
            }
          }
         ?>
        <td><?php echo $o['Prefecture']['name'].$city.$o['Office']['address']; ?></td>
    </tr>
    <?php foreach($o['OfficeStation'] as $st): ?>
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
        <td>施設形態</td>
        <td>
            <?php $institutionTypeList = explode(',', $o['Office']['institution_type']); ?>
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
    <?php foreach($o['RecruitSheet'] as $rs): ?>
        <tr>
            <td>求人票ID</td>
            <td><?php echo $rs['recruit_sheet_id']; ?></td>
        </tr>
        <tr>
            <td>求人票タイトル</td>
            <td><?php echo $this->Html->link($rs['sheet_title'], '/detail/'.$rs['recruit_sheet_id']); ?></td>        </tr>
        <tr>
            <td>雇用形態</td>
            <td><?php if (isset($employment_type[$rs['employment_type']])) {echo $employment_type[$rs['employment_type']];}; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endforeach; ?>
<hr>
SEOフッターテキスト
<?php if (!empty($seoFootertext)): ?>
<div class="seoFooterBox">
    <?php     
        $areaName = '';
        if (!empty($seoFootertext)){
            if (empty($seoFootertext[0]['City']['name'])){
                $areaName = $seoFooterText[0]['Prefecture']['name'].$seoFooterText[0]['State']['name'];
            } else {
                $areaName = $seoFooterText[0]['Prefecture']['name'].$seoFooterText[0]['City']['name'];
            }
        }
    ?>
    <?php foreach($seoFooterText AS $seoFooter): ?>
    <h3 class="seoFooterTitle">
    <?php echo $areaName; ?><?php echo $seoFooter['SeoFooterText']['text1'] ?>
    </h3>
    <div class="seoFooterText">
       <?php echo $seoFooter['SeoFooterText']['text2']; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>