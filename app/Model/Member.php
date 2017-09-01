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
        'license' => array(
            'required' => array(
                'rule' => array('multiple', array('min' => 1)),
                'message' => '1つ以上選択してください'
            ),
            'isValid' => array(
                'rule' => array('isValidLicenceCode'),
                'message' => '不正な資格コードがpostされました'
            )
        ),
        'birthday_year' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
            'is_valid' => array(
                'rule' => 'isValidBirthdayYear',
                'message' => '不正な値がpostされました',
            ),
        ),
        'name' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
            'maxlength' => array(
                'rule' => array('maxLength', 40),
                'message' => '最大%d文字で入力してください'
            ),
        ),
        'prefecture' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
            'isValid' => array(
                'rule' => array('isValidPrefectureCode'),
                'message' => '不正な都道府県コードがpostされました'
            )
        ),
        'cities' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
            'isValid' => array(
                'rule' => array('isValidCityCode'),
                'message' => '不正な市区町村コードがpostされました'
            )
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
        'password_retype' => array(
            'retype_passwd' => array(
                'rule' => array('isSamePasswd'),
                'message' => '再入力されたパスワードが違います'
            )
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
    public $hasMany = array(
        'MemberLicense' => array(
            'className'  => 'MemberLicense',
            'conditions' => array('MemberLicense.del_flg' => 0),
            'foreignKey' => 'member_id',
        ),
    );
    
    public function checkAndSave($data){
        $this->set($data);
        if ($this->validates()){
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
                        $s['MembersMailConfirmTable']['token'] = '';
                        unset($s['MembersMailConfirmTable']['modified']);
                        if (!$MembersMailConfirmTable->save($s['MembersMailConfirmTable'], false)){
                            throw new Exception('更新失敗');
                        }
                    }
                } else {
                    throw new Exception('メールアドレス無効');
                }
                $this->create();
                $license = $data['Member']['license'];
                unset($data['Member']['token']);
                unset($data['Member']['license']);
                unset($data['Member']['password_retype']);
                if (!$this->save($data, false)){
                    throw new Exception('データ追加失敗');
                }
                $saved_id = $this->getLastInsertID();
                App::import('Model','MemberLicense');
                $MemberLicense = new MemberLicense;
                foreach ($license as $l){
                    $MemberLicense->create();
                    $d = array(
                      'member_id' => $saved_id,
                      'license' => $l,
                    );
                    if (!$MemberLicense->save($d, false)){
                        throw new Exception('データ追加失敗');
                    }
                }
                $datasource->commit();
                return true;
            } catch (Exception $e) {
                $datasource->rollback();
                return false;
            }
        }
    }
    public function updateMemberInfo($data){
        if (!isset($data['Member']['id'])){
            return false;
        }
        if (!strlen($data['Member']['password'])){
            unset($data['Member']['password']);
            unset($data['Member']['password_retype']);
        }
        $this->set($data);
        if ($this->validates()){
            $datasource = $this->getDataSource();
            $datasource->begin();
            $successFlg = true;
            try {
                $license = array();
                $licenseUpdateFlg = false;
                if (isset($data['Member']['license'])){
                    $license = $data['Member']['license'];
                    $licenseUpdateFlg = true;
                }
                unset($data['Member']['token']);
                unset($data['Member']['license']);
                unset($data['Member']['password_retype']);
                
                App::import('Model','MemberLicense');
                $MemberLicense = new MemberLicense;
                $data['Member']['pw_reset_token'] = '';
                if (!$this->save($data, false)){
                    throw new Exception('データ登録失敗');
                }
                if ($licenseUpdateFlg){
                    $delLicense = array(
                        'MemberLicense.del_flg' => 1,
                        'MemberLicense.modified' => "'" . date("Y-m-d H:i:s") . "'",
                    );
                    $conditions = array(
                        'NOT' => array('license' => $license),
                        'MemberLicense.member_id' => $data['Member']['id'],
                        'MemberLicense.del_flg' => 0
                    );
                    $MemberLicense->updateAll($delLicense, $conditions);
                    
                    $saved = $MemberLicense->find('all', array('conditions' => array('MemberLicense.member_id' => $data['Member']['id'], 'MemberLicense.del_flg' => 0)));
                    $savedArray = array();
                    foreach ($saved as $s){
                        $savedArray[$s['MemberLicense']['license']] = 1;
                    }
                    foreach ($license as $l){
                        if (isset($savedArray[$l])){
                            continue;
                        }
                        $MemberLicense->create();
                        $d = array(
                          'member_id' => $data['Member']['id'],
                          'license' => $l,
                        );
                        if (!$MemberLicense->save($d, false)){
                            throw new Exception('データ追加失敗');
                        }
                    }
                }
                $datasource->commit();
                return true;
            } catch (Exception $e) {
                $datasource->rollback();
                return false;
            }
        }
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
                $token = $this->genToken(128);
                $t = $this->find('first', array('conditions' => array(
                    'auto_login_token' => $token,
                    'del_flg' => false,
                )));
                if (empty($t)){
                    break;
                }
            } while(1);
            $data['auto_login_token'] = $token;
            unset($data['modified']); /* トークン発行でタイムスタンプ更新されるようにする */
            
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
    public function clearPWResetToken($data){
        $saveData = array(
          'id' => $data['id'],
          'pw_reset_token' => '',
        );
        return $this->save($saveData);
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
    public function genPWResetToken($member){
        $datasource = $this->getDataSource();
        $datasource->begin();
        $successFlg = true;
        $token = '';
        try {
            do {
                /* トークン衝突がなければループを抜ける */
                $token = $this->genToken(32);
                $t = $this->find('first', array('conditions' => array(
                    'pw_reset_token' => $token,
                    'del_flg' => false,
                )));
                if (empty($t)){
                    break;
                }
            } while(1);
            $data['Member']['id'] = $member['Member']['id'];
            $data['Member']['pw_reset_token'] = $token;
            $data['Member']['auto_login_token'] = '';
            if (!$this->save($data)){
                throw new Exception('データ更新失敗');
            }
            $datasource->commit();
            return $token;
        } catch (Exception $e) {
            $datasource->rollback();
            return false;
        }
    }
}
