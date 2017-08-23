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
  <!--
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
  -->   

    <tr>
        <td>メールアドレス</td>
        <td>
          <?php echo $this->Form->input('Member.email', array('type' => '', 'required' => false)); ?>
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
</table>
<?php
echo $this->Form->end('Save');
?>

<?php echo $this->Html->link('マイページ', '/member/mypage'); ?><br/>
<?php echo $this->Html->link('ログアウト', '/member/logout'); ?><br/>