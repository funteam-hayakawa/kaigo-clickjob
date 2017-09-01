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
          $('#MemberPrefecture').change(function(){
            var p = $('#MemberPrefecture').val();
            if (!p){
              $('#MemberCities option').remove();
              $('#MemberCities').append(new Option('都道府県を選択してください', ''));
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
                 $('#MemberCities option').remove();
                 var opt = [];
                 opt.push(new Option('選択してください', ''));
                 $.each(result.value, function(i, e){
                   opt.push(new Option(e, i));
                 });
                 $('#MemberCities').append(opt);
               }
             });//ajax
          });
        });
</script>
<h1>メンバー登録</h1>
<?php
echo $this->Form->create('Member', array('type' => 'post', 'url' => array('controller' => 'member', 'action' => 'registration')));
//echo $this->Form->input('email', array('type' => '', 'required' => false));
?>
<?php 
    if (isset($token)){
      echo $this->Form->input('Member.token', array(
                              'label' => false,
                              'required' => false,
                              'value' => $token,
                              'style' => 'display:none'
      )); 
    } else {
      echo $this->Form->input('Member.token', array(
                              'label' => false,
                              'required' => false,
                              'style' => 'display:none'
      )); 
    }
 ?>
<table>
    <tr>
        <td>保有資格</td>
        <td>
          <?php echo $this->Form->input('Member.license', array(
                                        'type' => 'select', 
                                        'multiple'=> 'checkbox',
                                        'label' => false,
                                        'required' => false,
                                        'options' => $license,
                ))
           ?>
        </td>
    </tr>
    <tr>
        <td>生年</td>
        <td>
          <?php 
                $birthYearList = array();
                $birthYearList[''] = '選択してください';
                foreach (range($birthday_year['from'], $birthday_year['to']) as $y){
                    $birthYearList[$y] = $y;
                }
                echo $this->Form->input('Member.birthday_year', array(
                                        'type' => 'select', 
                                        'label' => false,
                                        'required' => false,
                                        'options' => $birthYearList,
                ))
           ?>
        </td>
    </tr>
    <tr>
        <td>名前</td>
        <td>
          <?php echo $this->Form->input('Member.name', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>都道府県</td>
        <td>
          <?php 
                echo $this->Form->input('Member.prefecture', array(
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
              echo $this->Form->input('Member.cities', array(
                                      'type' => 'select', 
                                      'label' => false,
                                      'required' => false,
                                      'options' => $opt,
              ))
           ?>
        </td>
    </tr>
    <tr>
        <td>パスワード</td>
        <td>
          <?php echo $this->Form->input('Member.password'); ?>
        </td>
    </tr>
    <tr>
        <td>パスワード再入力</td>
        <td>
          <?php echo $this->Form->input('Member.password_retype', array('type' => 'password')); ?>
        </td>
    </tr>
    <tr>
        <td>その他</td>
        <td>
          <?php echo $this->Form->textarea('Member.other_text', array('required' => false)); ?>
        </td>
    </tr>
</table>
<?php
echo $this->Form->end('Save');
?>