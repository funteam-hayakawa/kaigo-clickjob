<?php 
//header
echo $this->element('header'); 
?>
<h1>登録情報変更</h1>
<?php
echo $this->Form->create('Member', array('type' => 'post', 'url' => array('controller' => 'member', 'action' => 'edit')));
//echo $this->Form->input('email', array('type' => '', 'required' => false));
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
        <td>メールアドレス</td>
        <td>
          <?php echo $this->Form->input('Member.email', array('type' => '', 'required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>その他</td>
        <td>
          <?php echo $this->Form->textarea('Member.other_text', array('required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>現在のパスワード</td>
        <td>
          <?php echo $this->Form->input('Member.current_password', array('type' => 'password', 'required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>新しいパスワード</td>
        <td>
          <?php echo $this->Form->input('Member.password', array('type' => 'password', 'required' => false)); ?>
        </td>
    </tr>
    <tr>
        <td>パスワード再入力</td>
        <td>
          <?php echo $this->Form->input('Member.password_retype', array('type' => 'password', 'required' => false)); ?>
        </td>
    </tr>
</table>
<?php
echo $this->Form->end('Save');
?>

<?php echo $this->Html->link('マイページ', '/member/mypage'); ?><br/>
<?php echo $this->Html->link('ログアウト', '/member/logout'); ?><br/>