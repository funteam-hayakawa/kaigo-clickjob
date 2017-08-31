<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $uses = array(
      'Search', 
      'Station',
      'Area', 
      'Prefecture', 
      'State', 
      'City', 
      'Office', 
      'RecruitSheet', 
      'SeoHeaderText', 
      'SeoFooterText', 
      'RecruitSheetAttention',
      'MembersRecruitsheetAccessHistory',
      'MembersFavoriteRecruitsheets',
    );
    public $components = array(
        'Flash',
        'Auth' => array(
            'loginAction'    => "/member/login/",
            'loginRedirect'  => array('controller' => 'member', 'action' => 'mypage'),
            'logoutRedirect' => "/member/login/",
            'authenticate'   => array(
                'Form' => array(
                    'fields'    => array('username' => 'email', 'password' => 'password'),
                    'userModel' => 'Member',
                    'scope'     => array('Member.withdraw_flg' => 0, 'Member.del_flg' => 0),
                    'passwordHasher' => 'Blowfish'
                )
            )
        ),
        'Security',
        'Session',
    );
    public $commonSearchConditios = array(
      'recruitSheet' => array(
        'RecruitSheet.recruit_public' => 2,
        'RecruitSheet.deleted' => 0,
        'NOT' => array(
          'RecruitSheet.occupation' => array(24, 25, 26, 27, 33, 34, 35, 36, 37, 44, 48, 49, 50, 51, 52, 53, 54, 55, 56, 63)
        ),
      ),
      'office' => array(
        'Office.deleted' => 0,
        'Office.reply_kg_type >=' => 1,
        'Office.reply_kg_type <=' => 2,
        'Office.public' => 1,
        array('OR' => array(
            array("FIND_IN_SET('1', Office.institution_type)"),
            array("FIND_IN_SET('2', Office.institution_type)"),
            array("FIND_IN_SET('3', Office.institution_type)"),
            array("FIND_IN_SET('4', Office.institution_type)"),
            array("FIND_IN_SET('5', Office.institution_type)"),
            array("FIND_IN_SET('6', Office.institution_type)"),
            array("FIND_IN_SET('7', Office.institution_type)"),
            array("FIND_IN_SET('8', Office.institution_type)"),
            array("FIND_IN_SET('10', Office.institution_type)"),
            array("FIND_IN_SET('11', Office.institution_type)"),
            array("FIND_IN_SET('12', Office.institution_type)"),
            array("FIND_IN_SET('14', Office.institution_type)"),
            array("FIND_IN_SET('15', Office.institution_type)"),
            array("FIND_IN_SET('16', Office.institution_type)"),
            array("FIND_IN_SET('19', Office.institution_type)"),
            array("FIND_IN_SET('20', Office.institution_type)"),
            array("FIND_IN_SET('21', Office.institution_type)"),
            array("FIND_IN_SET('22', Office.institution_type)"),
            array("FIND_IN_SET('24', Office.institution_type)"),
            array("FIND_IN_SET('25', Office.institution_type)"),
            array("FIND_IN_SET('26', Office.institution_type)"),
          ),
        ),
      ),
    ); 
    /* 高収入求人特集 */
    public function searchRecruitHighIncome(){
        return $this->RecruitSheet->find('all', array(
          'recursive' => 2,
          'conditions' => array_merge($this->commonSearchConditios['recruitSheet'] + $this->commonSearchConditios['office'],
          array(
            /* 東京 神奈川 千葉 埼玉 大阪 兵庫 */
            'Office.prefecture' => array('13','14','12','11','27','28'),
            /* 給与高め */
            "FIND_IN_SET('7', RecruitSheet.recruit_flex_type)"
          )),
          'order' => array('RecruitSheet.receipted DESC', 'RecruitSheet.updated DESC'),
          'limit' => 3
        ));
    }
    /* 人気求人ランキング */
    public function searchRecruitRanking(){
        return $this->RecruitSheetAttention->find('all', array(
          'joins' => array(
              array(
                  'type' => 'INNER',
                  'table' => 'office',
                  'alias' => 'Office',
                  'conditions' => array('`RecruitSheet`.`id` = `Office`.`id`')
              ),
            ),
          'recursive' => 3,
          'conditions' => $this->commonSearchConditios['recruitSheet'] + $this->commonSearchConditios['office'],
          'order' => 'RecruitSheetAttention.number',
        ));
    }
    /* サイドバーの求人数取得 */
    public function getRecruitSheetCount(){
        $rc = array();
        $cond = $this->commonSearchConditios['recruitSheet'] + $this->commonSearchConditios['office'];
        $rc['all'] = $this->RecruitSheet->find('count',array('conditions' => $cond));
        $rc['modified'] = $this->RecruitSheet->find('first',array(
          'fields' => "MAX(RecruitSheet.updated) as modified",
          'conditions' => $cond));
        $rc['modified'] = $rc['modified'][0]['modified'];
        $cond['NOT']['OR'] = array(array('RecruitSheet.hw_no' => null), array('RecruitSheet.hw_no' => '')); /* ハロワ */
        $rc['hw'] = $this->RecruitSheet->find('count',array('conditions' => $cond));
        return $rc;
    }
    public function findFavorite($member){
        $favorite = $this->MembersFavoriteRecruitsheets->find('all', array(
          'conditions' => array(
            'MembersFavoriteRecruitsheets.favorite_flg' => 1,
            'MembersFavoriteRecruitsheets.member_id' => $member['Member']['id'],
          ) + $this->commonSearchConditios['recruitSheet'] +
          $this->commonSearchConditios['office'],
          'joins' => array(
             array(
                 'type' => 'INNER',
                 'table' => 'office',
                 'alias' => 'Office',
                 'conditions' =>  array('`RecruitSheet`.`id` = `Office`.`id`')
             ),
          ),
          'order' => 'MembersFavoriteRecruitsheets.modified DESC',
          'limit' => 20,
          'recursive' => 3,
        ));
        return $favorite;
    }
    public function findSessionFavorite(){
        $result = array();
        if ($this->Session->check('favorite')){
            $favoriteRecruitSheetIds = $this->Session->read('favorite');
            foreach ($favoriteRecruitSheetIds as $i => $time){
                if (!$this->isValidRecruitSheet($i)){
                    unset($favoriteRecruitSheetIds[$i]);
                }
            }
            uasort($favoriteRecruitSheetIds, function ($a, $b){
                return strtotime($b) - strtotime($a);
            });
            $keys = array_keys($favoriteRecruitSheetIds);
            $result = $this->RecruitSheet->find('all', array(
              'conditions' => array(
                'RecruitSheet.recruit_sheet_id' => $keys,
              ),
              'recursive' => 2,
              'order' => 'FIELD(recruit_sheet_id, '.implode(',', $keys).')',
            ));
            /* MembersFavoriteRecruitsheetsでfindした場合と配列構造を合わせる */
            foreach ($result as $i=>$f){
                $result[$i]['MembersFavoriteRecruitsheets']['modified'] = $favoriteRecruitSheetIds[$result[$i]['RecruitSheet']['recruit_sheet_id']];
                $result[$i]['RecruitSheet']['Office'] = $f['Office'];
                unset($result[$i]['Office']);
            }
        }
        return $result;
    }
    public function findHistory($member){
        $histories = $this->MembersRecruitsheetAccessHistory->find('all', array(
          'conditions' => array('MembersRecruitsheetAccessHistory.member_id' => $member['Member']['id'],) + 
                                 $this->commonSearchConditios['recruitSheet'] +
                                 $this->commonSearchConditios['office'],
          'joins' => array(
             array(
                 'type' => 'INNER',
                 'table' => 'office',
                 'alias' => 'Office',
                 'conditions' =>  array('`RecruitSheet`.`id` = `Office`.`id`')
             ),
          ),
          'order' => 'MembersRecruitsheetAccessHistory.modified DESC',
          'limit' => 20,
          'recursive' => 3,
        ));
        return $histories;
    }
    public function findSessionHistory(){
        $result = array();
        if ($this->Session->check('history')){
            $historyRecruitSheetIds = $this->Session->read('history');
            foreach ($historyRecruitSheetIds as $i => $time){
                if (!$this->isValidRecruitSheet($i)){
                    unset($historyRecruitSheetIds[$i]);
                }
            }
            uasort($historyRecruitSheetIds, function ($a, $b){
                return strtotime($b) - strtotime($a);
            });
            $keys = array_keys($historyRecruitSheetIds);
            $result = $this->RecruitSheet->find('all', array(
              'conditions' => array(
                'RecruitSheet.recruit_sheet_id' => $keys,
              ),
              'recursive' => 2,
              'order' => 'FIELD(recruit_sheet_id, '.implode(',', $keys).')',
            ));
            /* MembersRecruitsheetAccessHistoryでfindした場合と配列構造を合わせる */
            foreach ($result as $i=>$f){
                $result[$i]['MembersRecruitsheetAccessHistory']['modified'] = $historyRecruitSheetIds[$result[$i]['RecruitSheet']['recruit_sheet_id']];
                $result[$i]['RecruitSheet']['Office'] = $f['Office'];
                unset($result[$i]['Office']);
            }
        }
        return $result;
    }
    public function isValidRecruitSheet($recruitSheetId){
        $r = $this->RecruitSheet->find('count', array(
          'recursive' => 0,
          'conditions' => array('RecruitSheet.recruit_sheet_id' => $recruitSheetId,) + 
                                 $this->commonSearchConditios['recruitSheet'] + 
                                 $this->commonSearchConditios['office'],
        ));
        return $r;
    }
    
    public function stationOptions($lineIds){
        $station = $this->Station->find('all', array(
          'conditions' => array(
            'Station.line_code' => $lineIds,
            'Station.del_flg' => 0,
          ),
          'group' => array('Station.station_code'),
          'order' => array('Station.station_code'),
        ));
        $ret = array();
        foreach ($station as $s){
            $ret[$s['Station']['station_code']] = $s['Station']['station_name'];
        }
        return $ret;
    }
    public function lineOptions($prefId){
        if (!($pref = $this->Prefecture->find('first', array('conditions' => array('Prefecture.no' => $prefId), 'recursive' => -1)))){
            return array();
        }
        $line = $this->Station->find('all', array(
          'conditions' => array(
            'Station.prefecture_name' => $pref['Prefecture']['name'],
            'Station.del_flg' => 0,
            'NOT' => array('Station.line_name LIKE' => '%バス%'),
          ),
          'group' => array('Station.line_code'),
        ));
        $ret = array();
        foreach ($line as $l){
            $ret[$l['Station']['line_code']] = $l['Station']['line_name'];
        }
        return $ret;
    }
    public function cityOptions($prefId){
        if (!$this->Prefecture->find('first', array('conditions' => array('Prefecture.no' => $prefId), 'recursive' => -1))){
            return array();
        }
        $state = $this->State->find('all', array(
          'conditions' => array(
            'State.prefecture_no' => $prefId
          ),
          'order' => 'State.population DESC',
          'recursive' => -1,
        ));
        $stateIds = Hash::extract($state, '{n}.State.no');
        $cityInState = $this->City->find('all', array(
          'joins' => array(
              array(
                  'type' => 'INNER',
                  'table' => 'state',
                  'alias' => 'State',
                  'conditions' => array('`City`.`state_no` = `State`.`no`')
              ),
          ),
          'conditions' => array(
            'City.prefecture_no' => $prefId,
            'City.state_no' => $stateIds,
            'NOT' => array('City.population' => NULL,),
          ),
          'order' => array('State.population DESC', 'City.population DESC'),
          'recursive' => -1,
        ));
        $cityOther = $this->City->find('all', array(
          'conditions' => array(
            'City.prefecture_no' => $prefId,
            'City.state_no' => NULL,
            'NOT' => array('City.population' => NULL),
          ),
          'order' => array('City.population DESC'),
          'recursive' => -1,
        ));
        /* findしてきた市区町村を良い感じにマージ */
        $cityArray = array();
        $s = 0; $cs = 0; $c = 0;
        while (isset($state[$s]) || isset($cityOther[$c])){
            if (!isset($state[$s]['State']['population'])){
                $cityArray[$cityOther[$c]['City']['no']] = $cityOther[$c]['City']['name'];
                $c++;
                continue;
            }
            if (!isset($cityOther[$c]['City']['no'])){
                $cityArray[$state[$s]['State']['no']] = $state[$s]['State']['name'];
                while (isset($cityInState[$cs]['City']['state_no']) && $state[$s]['State']['no'] == $cityInState[$cs]['City']['state_no']){
                    $cityArray[$cityInState[$cs]['City']['no']] = $cityInState[$cs]['City']['name'];
                    $cs++;
                }
                $s++;
                continue;
            }
            if ($state[$s]['State']['population'] > $cityOther[$c]['City']['population']){
                $cityArray[$state[$s]['State']['no']] = $state[$s]['State']['name'];
                while (isset($cityInState[$cs]['City']['state_no']) && $state[$s]['State']['no'] == $cityInState[$cs]['City']['state_no']){
                    $cityArray[$cityInState[$cs]['City']['no']] = $cityInState[$cs]['City']['name'];
                    $cs++;
                }
                $s++;
            } else {
                $cityArray[$cityOther[$c]['City']['no']] = $cityOther[$c]['City']['name'];
                $c++;
            }
        }
        return $cityArray;
    }
    public function getConditionCities($val){
        if (empty($val)){
            return array();
        }
        $conditions = array();
        $conditions[] = array('Office.cities' => $val);
        $cityInState = $this->City->find('all', array(
          'joins' => array(
              array(
                  'type' => 'INNER',
                  'table' => 'state',
                  'alias' => 'State',
                  'conditions' => array('`City`.`state_no` = `State`.`no`')
              ),
          ),
          'conditions' => array(
            'City.state_no' => $val,
            'NOT' => array('City.population' => NULL,),
          ),
          'recursive' => -1,
        ));
        foreach ($cityInState as $c){
            $conditions[] = array('Office.cities' => $c['City']['no']);
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    public function getConditionLine($array){
        if (empty($array) || !is_array($array)){
            return array();
        }
        $conditions = array();
        
        $line = $this->Station->find('all', array(
          'conditions' => array(
            'Station.line_code' => $array,
            'Station.del_flg' => 0,
          ),
          'group' => array('Station.line_code'),
        ));
        foreach ($line as $l){
            $conditions[] = array('OfficeStation.line LIKE' => '%'.$l['Station']['line_name'].'%');
        }
        $station = $this->Station->find('all', array(
          'conditions' => array(
            'Station.line_code' => $array,
            'Station.del_flg' => 0,
          ),
          'group' => array('Station.station_code'),
        ));
        foreach ($station as $s){
            $conditions[] = array('OfficeStation.station' => $s['Station']['station_name']);
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    public function getConditionStation($array){
        if (empty($array) || !is_array($array)){
            return array();
        }
        $conditions = array();
        $station = $this->Station->find('all', array(
          'conditions' => array(
            'Station.station_code' => $array,
            'Station.del_flg' => 0,
          ),
          'group' => array('Station.station_code'),
        ));
        foreach ($station as $s){
            $conditions[] = array('OfficeStation.station' => $s['Station']['station_name']);
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    public function beforeFilter() {
        $this->Security->validatePost = false;
        $this->Security->csrfCheck = false;
        if (FORCE_SSL){
            $this->Security->blackHoleCallback = 'forceSSL';
            $this->Security->requireSecure();
        }
    }
    public function forceSSL() {
        return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
    }
}
