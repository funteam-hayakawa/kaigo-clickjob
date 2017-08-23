<h1>メールアドレスを入力</h1>
<?php
echo $this->Form->create('MembersMailConfirmTable', array('type' => 'post', 'url' => array('controller' => 'member', 'action' => 'registration')));
echo $this->Form->input('email', array('type' => '', 'required' => false));
echo $this->Form->end('Save Post');
?>