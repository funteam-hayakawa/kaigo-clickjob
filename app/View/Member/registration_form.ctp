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
        <td>パスワード</td>
        <td>
          <?php echo $this->Form->input('Member.password'); ?>
        </td>
    </tr>
  

</table>
<?php
echo $this->Form->end('Save');
?>