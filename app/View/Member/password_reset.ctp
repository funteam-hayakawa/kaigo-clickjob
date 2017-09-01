<h1>パスワードリセット</h1>
<?php
echo $this->Form->create('Member', array('type' => 'post', 'url' => array('controller' => 'member', 'action' => 'password_reset')));
//echo $this->Form->input('email', array('type' => '', 'required' => false));
?>
<?php 
    if (isset($token)){
      echo $this->Form->input('Member.pw_reset_token', array(
                              'label' => false,
                              'required' => false,
                              'value' => $token,
                              'style' => 'display:none'
      )); 
    } else {
      echo $this->Form->input('Member.pw_reset_token', array(
                              'label' => false,
                              'required' => false,
                              'style' => 'display:none'
      )); 
    }
 ?>
<table>
    <tr>
        <td>新しいパスワード</td>
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
</table>
<?php
echo $this->Form->end('Save');
?>