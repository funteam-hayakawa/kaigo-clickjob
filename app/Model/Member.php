<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Member extends AppModel {
    
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
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A password is required'
            ),
            'maxlength' => array(
                'rule' => array('maxLength', 20),
                'message' => '最大%d文字で入力してください'
            ),
            'minlength' => array(
                'rule' => array('minLength', 6),
                'message' => '最低%d文字で入力してください'
            ),
        ),
        'current_password' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '現在のパスワードを入力してください'
            ),
            'currentPassword' => array(
                'rule' => array('isCurrentPassword'),
                'message' => '現在のパスワードが違います'
            ),
        ),
    );
    
    public function checkAndSave($data){
        $datasource = $this->getDataSource();
        $datasource->begin();
        $successFlg = true;
        try {
            if (empty($data['Member']['email']) || empty($data['Member']['password'])){
                throw new Exception('post内容不備');
            }
            $saved = $this->find('all', array(
                'conditions' => array(
                  'email' => $data['Member']['email'],
                  'del_flg' => false,
                  'withdraw_flg' => false
                )
              )
            );
            if (!empty($saved)){
                throw new Exception('メールアドレス重複');
            }
            App::import('Model','MembersMailConfirmTable');
            $MembersMailConfirmTable = new MembersMailConfirmTable;
            
            $saved = $MembersMailConfirmTable->find('all', array(
                'conditions' => array(
                  'email' => $data['Member']['email'],
                  'del_flg' => false,
                )
              )
            );
            if (!empty($saved)){
                foreach ($saved as $s){
                    $s['MembersMailConfirmTable']['del_flg'] = 1;
                    unset($s['MembersMailConfirmTable']['modified']);
                    if (!$MembersMailConfirmTable->save($s['MembersMailConfirmTable'])){
                        throw new Exception('更新失敗');
                    }
                }
            } else {
                throw new Exception('メールアドレス無効');
            }
            $this->create();
            if (!$this->save($data)){
                throw new Exception('データ追加失敗');
            }
            $datasource->commit();
            return true;
        } catch (Exception $e) {
            $datasource->rollback();
            return false;
        }
    }
    public function updateMemberInfo($data){
        if (!isset($data['Member']['id'])){
            return false;
        }
        if (!strlen($data['Member']['password'])){
            unset($data['Member']['password']);
        }
        return $this->save($data);
    }
    /* 自動ログイン用トークンを発行しDB格納、発行したトークンが戻り値 */
    public function saveLoginToken($data){
        $datasource = $this->getDataSource();
        $datasource->begin();
        $successFlg = true;
        $token = '';
        try {
            do {
                /* トークン衝突がなければループを抜ける */
                $token = $this->genToken();
                $t = $this->find('first', array('conditions' => array(
                    'auto_login_token' => $token,
                    'del_flg' => false,
                )));
                if (empty($t)){
                    break;
                }
            } while(1);
            $data['auto_login_token'] = $token;
            if (!$this->save($data)){
                throw new Exception('更新失敗');
            }
            $datasource->commit();
            return $token;
        } catch (Exception $e) {
            $datasource->rollback();
            return false;
        }
    }
    public function clearLoginToken($data){
        $saveData = array(
          'id' => $data['id'],
          'auto_login_token' => '',
        );
        return $this->save($saveData);
    }
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }
    public function isCurrentPassword($check = null){
        $hashedCurrentPasswd = $this->find('first', array(
          'conditions' => array(
            'Member.id' => $this->data['Member']['id'],
            'Member.withdraw_flg' => 0,
            'Member.del_flg' => 0,
          ),
          'fields' => array('password'),
        ));
        if (empty($hashedCurrentPasswd)){
            return false;
        }
        $passwordHasher = new BlowfishPasswordHasher();
        if ($passwordHasher->check($check['current_password'], $hashedCurrentPasswd['Member']['password'])){
            return true;
        }
        return false;
    }
    private function genToken(){
        // 自動ログイン用トークン生成
        $charSetForToken = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $tokenLength = 128;
        $token = '';
        for ($i = 0; $i < $tokenLength; $i++) {
            $token .= $charSetForToken[rand(0, strlen($charSetForToken) - 1)];
        }
        return $token;
    }
}
