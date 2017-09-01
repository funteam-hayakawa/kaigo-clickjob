<h1>メールアドレスを入力</h1>
<?php
echo $this->Form->create('Member', array('type' => 'post', 'url' => array('controller' => 'member', 'action' => 'password_reset')));
echo $this->Form->input('Member.email', array('type' => '', 'required' => false));

$birthYearList = array('' => '選択してください') + $birthdayYearOpt;
echo $this->Form->input('Member.birthday_year', array(
                        'type' => 'select', 
                        'label' => false,
                        'required' => false,
                        'options' => $birthYearList,
));

echo $this->Form->end('Save Post');
?>