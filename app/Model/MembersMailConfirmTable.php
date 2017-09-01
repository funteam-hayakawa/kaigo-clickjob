<?php
App::uses('AppModel', 'Model');

class MembersMailConfirmTable extends AppModel {
    public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
            'isHalfLetter' => array(
                'rule' => '/^[\x21-\x7E]*$/',
                'message' => '半角文字で入力してください'
            ),
            'maxlength' => array(
                'rule' => array('maxLength', 40),
                'message' => '最大%d文字で入力してください'
            ),
            'emailformat' => array(
                'rule' => array('email'),
                'message' => '不正なメールアドレス形式です'
            ),
            'unique' => array(
                'rule' => array('isUniqueEmail'),
                'message' => 'このメールアドレスは既に登録されています'
            ),
        ),
    );
    public function checkAndSave($data){
        $datasource = $this->getDataSource();
        $datasource->begin();
        $successFlg = true;
        $token = '';
        try {
            if (empty($data['MembersMailConfirmTable']['email'])){
                throw new Exception('post内容不備');
            }
            $saved = $this->find('all', array(
                'conditions' => array(
                  'email' => $data['MembersMailConfirmTable']['email'],
                  'del_flg' => false,
                )
              )
            );
            if (!empty($saved)){
                foreach ($saved as $s){
                    $s['MembersMailConfirmTable']['del_flg'] = 1;
                    $s['MembersMailConfirmTable']['token'] = '';
                    unset($s['MembersMailConfirmTable']['modified']);
                    if (!$this->save($s['MembersMailConfirmTable'])){
                        throw new Exception('更新失敗');
                    }
                }
            }
            do {
                /* トークン衝突がなければループを抜ける */
                $token = $this->genToken(32);
                $t = $this->find('first', array('conditions' => array(
                    'token' => $token,
                    'del_flg' => false,
                )));
                if (empty($t)){
                    break;
                }
            } while(1);
            $data['MembersMailConfirmTable']['token'] = $token;
            $this->create();
            if (!$this->save($data)){
                throw new Exception('データ追加失敗');
            }
            $datasource->commit();
            return $token;
        } catch (Exception $e) {
            $datasource->rollback();
            return false;
        }
    }
}
