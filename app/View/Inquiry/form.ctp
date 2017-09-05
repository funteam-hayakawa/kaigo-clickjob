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
          $('#InquiryPrefecture').change(function(){
            var p = $('#InquiryPrefecture').val();
            if (!p){
              $('#InquiryCities option').remove();
              $('#InquiryCities').append(new Option('都道府県を選択してください', ''));
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
                 $('#InquiryCities option').remove();
                 var opt = [];
                 opt.push(new Option('選択してください', ''));
                 $.each(result.value, function(i, e){
                   opt.push(new Option(e, i));
                 });
                 $('#InquiryCities').append(opt);
               }
             });//ajax
          });
        });
</script>
<h1>登録</h1>
<?php
echo $this->Form->create('Inquiry', array('type' => 'post', 'url' => array('controller' => 'inquiry', 'action' => 'index')));
//echo $this->Form->input('email', array('type' => '', 'required' => false));
?>
<?php 
echo $this->Form->input('Inquiry.recruit_sheet_id', array('type' => 'text', 'style' => 'display:none', 'label' => false));
?>
<table>
    <tr>
        <td>お問い合わせ内容</td>
        <td>
          <?php 
                echo $this->Form->input('Inquiry.type', array(
                                        'type' => 'select', 
                                        'label' => false,
                                        'required' => false,
                                        'options' => $inquiry_type,
                ))
           ?>
        </td>
    </tr>
    <tr>
        <td>保有資格</td>
        <td>
          <?php echo $this->Form->input('Inquiry.license', array(
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
        <td>郵便番号</td>
        <td>
          <?php echo $this->Form->input('Inquiry.zipcode', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>都道府県</td>
        <td>
          <?php 
                echo $this->Form->input('Inquiry.prefecture', array(
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
              echo $this->Form->input('Inquiry.cities', array(
                                      'type' => 'select', 
                                      'label' => false,
                                      'required' => false,
                                      'options' => $opt,
              ))
           ?>
        </td>
    </tr>
    <tr>
        <td>生年</td>
        <td>
          <?php 
                $birthYearList = array('' => '選択してください') + $birthdayYearOpt;
                echo $this->Form->input('Inquiry.birthday_year', array(
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
          <?php echo $this->Form->input('Inquiry.name', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>電話番号</td>
        <td>
          <?php echo $this->Form->input('Inquiry.tel', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>メールアドレス</td>
        <td>
          <?php echo $this->Form->input('Inquiry.email', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>その他</td>
        <td>
          <?php echo $this->Form->textarea('Inquiry.other_text', array('required' => false)); ?>
        </td>
    </tr>

</table>
<?php
echo $this->Form->end('Save');
?>