<?php
App::uses('AppModel', 'Model');

class Inquiry extends AppModel {    
    public $validate = array(
        'type' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
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
        'postcode' => array(
            'is_numeric' => array(
                'allowEmpty' => true,
                'rule' => '/^\d{7}|\d{3}-\d{4}$/',
                'message' => '郵便番号のフォーマットが違います'
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
            'isValid' => array(
                'allowEmpty' => true,
                'rule' => array('isValidCityCode'),
                'message' => '不正な市区町村コードがpostされました'
            )
        ),
        'tel' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
            'is_numeric' => array(
                'allowEmpty' => true,
                'rule' => '/^(\d|-)*$/',
                'message' => '電話番号のフォーマットが違います'
            ),
        ),
        'mail' => array(
            'isHalfLetter' => array(
                'rule' => '/^[\x21-\x7E]*$/',
                'message' => '半角文字で入力してください'
            ),
            'maxlength' => array(
                'rule' => array('maxLength', 40),
                'message' => '最大%d文字で入力してください'
            ),
            'emailformat' => array(
                'allowEmpty' => true,
                'rule' => array('email'),
                'message' => '不正なメールアドレス形式です'
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
    );
    public $hasMany = array(
        'InquiryLicense' => array(
            'className'  => 'InquiryLicense',
            'conditions' => array('InquiryLicense.del_flg' => 0),
            'foreignKey' => 'inquiry_id',
        ),
    );
    public $belongsTo = array(
        'Prefecture' => array(
            'className'  => 'Prefecture',
            'foreignKey' => 'prefecture',
        ),
        'State' => array(
            'className'  => 'State',
            'foreignKey' => 'cities',
        ),
        'City' => array(
            'className'  => 'City',
            'foreignKey' => 'cities',
        ),
    );
    public function saveInquiry($data){
        $this->set($data);
        if ($this->validates()){
            $datasource = $this->getDataSource();
            $datasource->begin();
            try {
                $this->create();
                if (!$this->save($data, false)){
                    throw new Exception('データ追加失敗');
                }
                $saved_id = $this->getLastInsertID();
                App::import('Model','InquiryLicense');
                $inquiryLicense = new InquiryLicense;
                foreach ($data['Inquiry']['license'] as $l){
                    $inquiryLicense->create();
                    $d = array(
                      'inquiry_id' => $saved_id,
                      'license' => $l,
                    );
                    if (!$inquiryLicense->save($d, false)){
                        throw new Exception('データ追加失敗');
                    }
                }
                $datasource->commit();
                return $saved_id;
            } catch (Exception $e) {
                $datasource->rollback();
                return false;
            }
        } 
        return false;
    }


}
