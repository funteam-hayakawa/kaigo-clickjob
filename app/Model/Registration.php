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
        $this->set($data);
        if ($this->validates()){
            $date = new DateTime();
            App::import('Model','Prefecture');
            $Prefecture = new Prefecture;
            App::import('Model','State');
            $State = new State;
            App::import('Model','City');
            $City = new City;
            $data['Registration']['name'] = mb_ereg_replace("(\s|　)", "", $data['Registration']['name']);
            $data['Registration']['name_kana'] = mb_ereg_replace("(\s|　)", "", $data['Registration']['name_kana']);
            $data['Registration']['tel2'] = '';
            $data['Registration']['license'] = implode(',', $data['Registration']['license']);
            $data['Registration']['years_old'] = $date->format('Y') - $data['Registration']['birthday_year'];
            $pref = $Prefecture->find('first', array('conditions' => array('Prefecture.no' => $data['Registration']['prefecture']), 'recursive' => -1));
            $data['Registration']['pref_name'] = $pref['Prefecture']['name'];
            
            $st = $State->find('first', array('conditions' => array('State.no' => $data['Registration']['cities']), 'recursive' => -1));
            $ct = $City->find('first', array('conditions' => array('City.no' => $data['Registration']['cities']), 'recursive' => -1));
            $data['Registration']['city_name'] = '';
            if (!empty($st)){
                $data['Registration']['city_name'] = $st['State']['name'];
            }
            if (!empty($ct)){
                $data['Registration']['city_name'] = $ct['City']['name'];
            }
            
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
            $data['Registration']['other_text'] = "【フォーム登録時備考】\n" . $data['Registration']['comment'];
            if (!empty($data['Registration']['recruit_sheet_ids'])){
                $data['Registration']['other_text'] .= "\n【応募求人一覧】\n".$this->recruitStr($data['Registration']['recruit_sheet_ids'])."\n";
            }
            if (!empty($data['favorite'])){
                $data['Registration']['other_text'] .= "\n【お気に入り一覧】\n".$this->favStr($data['favorite'])."\n";
            }
            $data['Registration']['recent_history'] = '';
            if (!empty($data['histories'])){
                $data['Registration']['recent_history'] = "【最近チェックした求人】\n".$this->favStr($data['histories'])."\n";
            }
            $data['Registration']['other_text'] .= "【現在】\n【予定】\n【動機】\n【希望】\n【経歴】\n【スキル】\n【人柄】\n【家族】\n【その他】";
            $data['Registration']['request_date'] = DboSource::expression('NOW()');
            $data['Registration']['deleted'] = 0;
            if ($this->save($data, false)){
                return $data;
            }
            return false;
        }
    }
    private function recruitStr($recruitSheetIds){
        if (empty($recruitSheetIds)){
           return '';
        }
        if (!is_array($recruitSheetIds)){
           return '';
        }
        App::import('Model','RecruitSheet');
        $recruitSheet = new RecruitSheet;
        $rSheets = $recruitSheet->find('all', array('conditions' => array('RecruitSheet.recruit_sheet_id' => $recruitSheetIds), 'recursive' => 2));
        $ret = '';
        foreach ($rSheets as $r){
            $ret .= '・事業所ID:' . $r['Office']['id'] . '  求人票ID:' . $r['RecruitSheet']['recruit_sheet_id'] . '  事業所名:' . $r['Office']['name'] . '  求人名:' . $r['RecruitSheet']['sheet_title'] . "\n";
        }
        return $ret;
    }
    private function favStr($data){
        if (empty($data)){
           return '';
        }
        if (!is_array($data)){
           return '';
        }
        $ret = '';
        foreach ($data as $r){
            $ret .= '・事業所ID:' . $r['RecruitSheet']['Office']['id'] . '  求人票ID:' . $r['RecruitSheet']['recruit_sheet_id'] . '  事業所名:' . $r['RecruitSheet']['Office']['name'] . '  求人名:' . $r['RecruitSheet']['sheet_title'] . "\n";
        }
        return $ret;
    }
}
