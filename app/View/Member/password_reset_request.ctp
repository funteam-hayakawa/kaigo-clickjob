<h1>メールアドレスを入力</h1>
<?php
echo $this->Form->create('Member', array('type' => 'post', 'url' => array('controller' => 'member', 'action' => 'password_reset')));
echo $this->Form->input('Member.email', array('type' => '', 'required' => false));

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
));

echo $this->Form->end('Save Post');
?>