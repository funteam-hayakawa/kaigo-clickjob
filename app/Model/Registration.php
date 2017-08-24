<?php
App::uses('AppModel', 'Model');

class Registration extends AppModel {
    var $useTable='registration';
    var $primaryKey = 'registration_id';
    
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
        ),
        'name_kana' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
        ),
        'birthday_year' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
        ),
        'postcode' => array(
            'is_numeric' => array(
                'allowEmpty' => true,
                'rule' => '/^\d{7}$/',
                'message' => '7桁の数字で入力してください（ハイフンなし）'
            ),
        ),
        'prefecture' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
            /*
            'isValid' => array(
                'rule' => array('isValidPrefectureCode'),
                'message' => '不正な都道府県コードがpostされました'
            )
            */
        ),
        /*
        'city' => array(
          
        ),
        */
        'tel' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => '必須項目が入力されていません'
            ),
            'is_numeric' => array(
                'allowEmpty' => true,
                'rule' => '/^\d*$/',
                'message' => '数字で入力してください（ハイフンなし）'
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
        ),
    );
    
    public function saveRegistration($data){
        pr($data);
        $this->set($data);
        if ($this->validates()){
            $date = new DateTime();
            App::import('Model','Prefecture');
            $Prefecture = new Prefecture;
            $data['Registration']['name'] = mb_ereg_replace("(\s|　)", "", $data['Registration']['name']);
            $data['Registration']['name_kana'] = mb_ereg_replace("(\s|　)", "", $data['Registration']['name_kana']);
            $data['Registration']['tel2'] = '';
            $data['Registration']['license'] = implode(',', $data['Registration']['license']);
            $data['Registration']['years_old'] = $date->format('Y') - $data['Registration']['birthday_year'];
            $pref = $Prefecture->find('first', array('conditions' => array('Prefecture.no' => $data['Registration']['prefecture']), 'recursive' => -1));
            $data['Registration']['area'] = $pref['Prefecture']['area_no'];
            $data['Registration']['address'] = '';
            $data['Registration']['first_person'] = 0;
            $data['Registration']['request_lead1'] = '自動登録';
            $data['Registration']['request_lead2'] = 'Rフォーム';
            $data['Registration']['order_number'] = 'K-' . date_create()->format('YmdHis') .  chr(65 + rand(0, 25));
            $data['Registration']['reliability'] = 1;
            $data['Registration']['contact'] = 1;
            $data['Registration']['register'] = 1;
            $data['Registration']['interview'] = 1;
            $data['Registration']['order_type'] = 1;
            $data['Registration']['register_method'] = 8;
            $data['Registration']['other_text'] = '【フォーム登録時備考】' . $data['Registration']['comment'] ."\n【現在】\n【予定】\n【動機】\n【希望】\n【経歴】\n【スキル】\n【人柄】\n【家族】\n【その他】";
            $data['Registration']['request_date'] = DboSource::expression('NOW()');
            $data['Registration']['deleted'] = 0;
            return $this->save($data, false);
        }
    }
    
    
}
