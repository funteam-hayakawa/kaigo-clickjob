<?php 
//header
echo $this->element('header'); 
?>
<h1>メンバー登録</h1>
<?php
echo $this->Form->create('Member', array('type' => 'post', 'url' => array('controller' => 'member', 'action' => 'login')));
//echo $this->Form->input('email', array('type' => '', 'required' => false));
?>
<table>
    <tr>
        <td>メールアドレス</td>
        <td>
          <?php echo $this->Form->input('Member.email'); ?>
        </td>
    </tr>
    <tr>
        <td>パスワード</td>
        <td>
          <?php echo $this->Form->input('Member.password'); ?>
        </td>
    </tr>
    <tr>
        <td>次回から自動でログインする</td>
        <td>
          <?php echo $this->Form->input('Member.auto_login_flg', array( 'type' => 'checkbox')); ?>
        </td>
    </tr>
</table>
<?php
echo $this->Form->end('login');
?>
<?php echo $this->Html->link('パスワード忘れ', '/member/password_reset'); ?>