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
          $('#SearchPrefecture').change(function(){
            var p = $('#SearchPrefecture').val();
            if (!p){
              $('#SearchCities option').remove();
              $('#SearchCities').append(new Option('都道府県を選択してください', ''));
              $('.Search-line').remove();
              return;
            }
            $.ajax({
               type: "POST",
               url: "/area_option/city/",
               data: {'prefecture_id' : p},
               success: function(msg){
                 if (!isJSON(msg)){
                    alert("予期しないエラーが発生しました");
                    return;
                 }
                 var result = JSON.parse(msg);
                 $('#SearchCities option').remove();
                 var opt = [];
                 opt.push(new Option('選択してください', ''));
                 $.each(result.value, function(i, e){
                   opt.push(new Option(e, i));
                 });
                 $('#SearchCities').append(opt);
               }
             });//ajax
             $.ajax({
                type: "POST",
                url: "/area_option/line/",
                data: {'prefecture_id' : p},
                success: function(msg){
                  if (!isJSON(msg)){
                     alert("予期しないエラーが発生しました");
                     return;
                  }
                  var result = JSON.parse(msg);
                  $('.Search-line').remove();
                  var htm = '';
                  $.each(result.value, function(i, e){
                      htm += '<div class="Search-line"><input type="checkbox" name="data[Search][line][]" value="' + i + 
                      '" id="SearchLine' + i + '" /><label for="SearchLine' + i + '">'+ e +'</label></div>' + "\n";
                  });
                  $('#Search-line-div').append(htm);
                }
              });//ajax
          });
          $(document).on('change', '.Search-line input',function(){
            checked = $('[name="data[Search][line][]"]:checked').map(function(){
              return $(this).val();
            }).get();
            if (!checked.length){
              $('.Search-station').remove();
              return;
            }
            $.ajax({
               type: "POST",
               url: "/area_option/station/",
               data: {'line_ids' : checked},
               success: function(msg){
                 if (!isJSON(msg)){
                    alert("予期しないエラーが発生しました");
                    return;
                 }
                 var result = JSON.parse(msg);
                 $('.Search-station').remove();
                 var htm = '';
                 $.each(result.value, function(i, e){
                     htm += '<div class="Search-station"><input type="checkbox" name="data[Search][station][]" value="' + i + 
                     '" id="SearchStation' + i + '" /><label for="SearchStation' + i + '">'+ e +'</label></div>' + "\n";
                 });
                 $('#Search-station-div').append(htm);
               }
             });//ajax
          });
          
        });
</script>
<?php 
//header
echo $this->element('header'); 
?>
<h2>申し訳ありません。ご指定の求人はホームページに掲載されておりません。</h2>

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
      <td>都道府県</td>
      <td>
        <?php 
              echo $this->Form->input('Search.prefecture', array(
                                      'type' => 'select', 
                                      'label' => false,
                                      'required' => false,
                                      'options' => array('' => '選択してください') + $prefectures,
              ))
         ?>
      </td>
  </tr>
  <tr>
      <td>市区町村</td>
      <td>
        <?php
            if (empty($cityArray)){
                $opt = array('' => '都道府県を選択してください');
            } else {
                $opt = array('' => '選択してください') + $cityArray;
            }
            echo $this->Form->input('Search.cities', array(
                                    'type' => 'select', 
                                    'label' => false,
                                    'required' => false,
                                    'options' => $opt,
            ))
         ?>
      </td>
  </tr>
  <tr>
      <td>路線</td>
      <td>
          <div id='Search-line-div'>
          <?php
              if (!empty($lineArray)){
                  echo $this->Form->input('Search.line', array(
                                          'class' => 'Search-line',
                                          'div' => false,
                                          'hiddenField' => false,
                                          'multiple' => 'checkbox',
                                          'label' => false,
                                          'required' => false,
                                          'options' => $lineArray,
                  ));
              }
           ?>
          </div>
      </td>
  </tr>
  <tr>
      <td>駅</td>
      <td>
          <div id='Search-station-div'>
          <?php
              if (!empty($stationArray)){
                  echo $this->Form->input('Search.station', array(
                                          'class' => 'Search-station',
                                          'div' => false,
                                          'hiddenField' => false,
                                          'multiple' => 'checkbox',
                                          'label' => false,
                                          'required' => false,
                                          'options' => $stationArray,
                  ));
              }
           ?>
          </div>
      </td>
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
                                    'options' => $institution_type,
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
                                    'options' => $application_license,
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
                                    'options' => $employment_type,
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
<h2>指定した条件に似ている求人</h2>
<?php foreach ($resemblesOffice as $o): ?>
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
            <td><?php echo $this->Html->link($rs['sheet_title'], '/detail/'.$rs['recruit_sheet_id']); ?></td>
        </tr>
        <tr>
            <td>雇用形態</td>
            <td><?php if (isset($employment_type[$rs['employment_type']])) {echo $employment_type[$rs['employment_type']];}; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endforeach; ?>
