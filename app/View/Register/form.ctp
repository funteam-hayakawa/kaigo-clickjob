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
          $('#RegistrationPrefecture').change(function(){
            var p = $('#RegistrationPrefecture').val();
            if (!p){
              $('#RegistrationCities option').remove();
              $('#RegistrationCities').append(new Option('都道府県を選択してください', ''));
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
                 $('#RegistrationCities option').remove();
                 var opt = [];
                 opt.push(new Option('選択してください', ''));
                 $.each(result.value, function(i, e){
                   opt.push(new Option(e, i));
                 });
                 $('#RegistrationCities').append(opt);
               }
             });//ajax
          });
        });
</script>
<h1>登録</h1>
<?php
echo $this->Form->create('Registration', array('type' => 'post', 'url' => array('controller' => 'register', 'action' => 'index')));
//echo $this->Form->input('email', array('type' => '', 'required' => false));
?>
<?php 
    if (!empty($applicationRecruitSheetIds)){
    foreach ($applicationRecruitSheetIds as $i){
        $applicationRecruitSheetIdsList[$i] = $i;
    }
    echo $this->Form->input('Registration.recruit_sheet_ids', array(
                            'type'=>'select',
                            'multiple' => 'true',
                            'label' => false,
                            'required' => false,
                            'selected' => $applicationRecruitSheetIds,
                            'options' => $applicationRecruitSheetIdsList,
                            'style' => 'display:none'
    ));
}
?>
<table>
    <tr>
        <td>名前</td>
        <td>
          <?php echo $this->Form->input('Registration.name', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>ふりがな</td>
        <td>
          <?php echo $this->Form->input('Registration.name_kana', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>生年</td>
        <td>
          <?php 
                $birthYearList = array('' => '選択してください') + $birthdayYearOpt;
                echo $this->Form->input('Registration.birthday_year', array(
                                        'type' => 'select', 
                                        'label' => false,
                                        'required' => false,
                                        'options' => $birthYearList,
                ))
           ?>
        </td>
    </tr>
    <tr>
        <td>郵便番号</td>
        <td>
          <?php echo $this->Form->input('Registration.postcode', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>都道府県</td>
        <td>
          <?php 
                echo $this->Form->input('Registration.prefecture', array(
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
              echo $this->Form->input('Registration.cities', array(
                                      'type' => 'select', 
                                      'label' => false,
                                      'required' => false,
                                      'options' => $opt,
              ))
           ?>
        </td>
    </tr>
    <tr>
        <td>電話番号</td>
        <td>
          <?php echo $this->Form->input('Registration.tel', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>メールアドレス</td>
        <td>
          <?php echo $this->Form->input('Registration.mail', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>その他</td>
        <td>
          <?php echo $this->Form->textarea('Registration.comment', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>保有資格</td>
        <td>
          <?php echo $this->Form->input('Registration.license', array(
                                        'type' => 'select', 
                                        'multiple'=> 'checkbox',
                                        'label' => false,
                                        'required' => false,
                                        'options' => $license,
                ))
           ?>
        </td>
    </tr>
</table>
<?php
echo $this->Form->end('Save');
?>