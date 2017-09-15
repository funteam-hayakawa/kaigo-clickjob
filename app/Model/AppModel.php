<?php
App::uses('Model', 'Model');

class AppModel extends Model {
    public function isUniqueEmail($check = null) {
        App::import('Model','Member');
        $Member = new Member;
        $cond = array(
          'Member.del_flg' => 0, 
          'Member.withdraw_flg' => 0, 
          'Member.email' => $check['email'],
        );
        if (isset($this->data['Member']['id'])){
          $cond['NOT'] = array('Member.id' => $this->data['Member']['id']);
        }
        return 0 == $Member->find('count', array('conditions' => $cond));
    }
    public function isValidBirthdayYear($check = null){
        $b = Configure::read("birthday_year_selector");
        if (!is_numeric($check['birthday_year'])){
            return false;
        }
        return ($check['birthday_year'] >= $b['from']) && ($check['birthday_year'] <= $b['to']);
    }
    public function isValidLicenceCode($check = null){
        $l = Configure::read("application_license");
        foreach ($check['license'] as $c){
            if (!isset($l[$c])){
                return false;
            }
        }
        return true;
    }
    public function isValidPrefectureCode($check = null){
        App::import('Model','Prefecture');
        $Prefecture = new Prefecture;
        return $Prefecture->find('count', array('conditions' => array('Prefecture.no' => $check['prefecture']), 'recursive' => -1));
    }
    public function isValidCityCode($check = null){
        App::import('Model','Prefecture');
        $Prefecture = new Prefecture;
        if (empty($this->data[$this->Behaviors->modelName]['prefecture'])){
            return false;
        }
        if (!$Prefecture->find('count', array('conditions' => array('Prefecture.no' => $this->data[$this->Behaviors->modelName]['prefecture']), 'recursive' => -1))){
            return false;
        }
        App::import('Model','State');
        $State = new State;
        App::import('Model','City');
        $City = new City;
        return $State->find('count', array(
          'conditions' => array(
            'State.no' => $check['cities'], 
            'State.prefecture_no' => $this->data[$this->Behaviors->modelName]['prefecture']
          ), 
          'recursive' => -1)
          ) + $City->find('count', array(
          'conditions' => array(
            'City.no' => $check['cities'], 
            'City.prefecture_no' => $this->data[$this->Behaviors->modelName]['prefecture']
          ), 
          'recursive' => -1)
        );
    }
    public function isSamePasswd($check = null){
        return $this->data[$this->Behaviors->modelName]['password'] === $check['password_retype'];
    }
    public function genToken($tokenLength){
        // 自動ログイン用トークン生成
        $charSetForToken = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $token = '';
        for ($i = 0; $i < $tokenLength; $i++) {
            $token .= $charSetForToken[rand(0, strlen($charSetForToken) - 1)];
        }
        return $token;
    }
}
