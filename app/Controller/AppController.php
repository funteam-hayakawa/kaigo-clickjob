<?php

App::uses('Controller', 'Controller');

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
    /*
     * RecruitSheetとOfficeを検索するときの共通条件
     */
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
            array("FIND_IN_SET('23', Office.institution_type)"),
            array("FIND_IN_SET('24', Office.institution_type)"),
            array("FIND_IN_SET('25', Office.institution_type)"),
            array("FIND_IN_SET('26', Office.institution_type)"),
          ),
        ),
      ),
    ); 
    /* 高収入求人特集 
     * 東京 神奈川 千葉 埼玉 大阪 兵庫の求人のうち、recruit_flex_type = 7（給与多め）の求人を受理日時降順で取得
     */
    public function searchRecruitHighIncome(){
        $ranking = $this->RecruitSheet->find('all', array(
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
        /* こだわり条件をカテゴリ別に並べ直す */
        foreach ($ranking as $i => $r){
            $ranking[$i]['RecruitSheet']['recruit_flex_type_label'] = $this->formatRecruitFlexTypeLabel(explode(',', $r['RecruitSheet']['recruit_flex_type']));
        }
        return $ranking;
    }
    /* 人気求人ランキング 
     * adminで手動メンテしている人気求人、最大3件取得
     */
    public function searchRecruitRanking(){
        $ranking = $this->RecruitSheetAttention->find('all', array(
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
        /* こだわり条件をカテゴリ別に並べ直す */
        foreach ($ranking as $i => $r){
            $ranking[$i]['RecruitSheet']['recruit_flex_type_label'] = $this->formatRecruitFlexTypeLabel(explode(',', $r['RecruitSheet']['recruit_flex_type']));
        }
        return $ranking;
    }
    /* サイドバーの求人数取得 
     * allに全求人数
     * hwにハロワ求人の数、ハロワ求人とみなすロジックが先方から提示されていないので暫定 TODO
     */
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
    /*
     * DBのお気に入り求人の取得、メンバー登録会員に対してはDBでの永続的な機能を提供
     */
    public function findFavorite($member){
        $favorite = $this->MembersFavoriteRecruitsheets->find('all', array(
          'conditions' => array(
            'MembersFavoriteRecruitsheets.favorite_flg' => 1,
            'MembersFavoriteRecruitsheets.del_flg' => 0,
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
    /*
     * セッション変数のお気に入り求人の取得、非メンバー登録会員に対してはセッションでの一時的な機能を提供
     */
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
    /*
     * DB格納された閲覧履歴の取得、メンバー登録会員に対してはDBでの永続的な機能を提供
     */
    public function findHistory($member){
        $histories = $this->MembersRecruitsheetAccessHistory->find('all', array(
          'conditions' => array('MembersRecruitsheetAccessHistory.member_id' => $member['Member']['id'],
                                'MembersRecruitsheetAccessHistory.del_flg' => 0) + 
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
    /*
     * セッション変数の求人閲覧履歴の取得、非メンバー登録会員に対してはセッションでの一時的な機能を提供
     */
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
    /*
     * $recruitSheetIdが閲覧可能状態にあるかを判定
     */
    public function isValidRecruitSheet($recruitSheetId){
        $r = $this->RecruitSheet->find('count', array(
          'recursive' => 0,
          'conditions' => array('RecruitSheet.recruit_sheet_id' => $recruitSheetId,) + 
                                 $this->commonSearchConditios['recruitSheet'] + 
                                 $this->commonSearchConditios['office'],
        ));
        return $r;
    }
    /*
     * 誕生年セレクタのオプション配列を生成
     */
    public function birthdayYearOptions(){
        $b = Configure::read("birthday_year_selector");
        $wareki = Configure::read("wareki");
        $ret = array();
        foreach (range($b['from'], $b['to']) as $y){
            $wStr = '';
            foreach ($wareki as $key => $w){
                if ($w['st'] <= $y && $w['ed'] >= $y){
                    $wStr .= '/'.$key.($y - $w['st'] + 1).'年';
                }
            }
            $ret[$y] = $y.'年'.$wStr;
        }
        return $ret;
    }
    /*
     * 駅のオプション配列を生成、路線コードを配列、または文字列にて与える
     */
    public function stationOptions($lineIds){
        $station = $this->Station->find('all', array(
          'conditions' => array(
            'Station.line_code' => $lineIds,
            'Station.del_flg' => 0,
          ),
          'group' => array('Station.line_code, Station.station_code'),
          'order' => array('Station.line_code, Station.station_code'),
        ));
        $ret = array();
        foreach ($station as $s){
            $ret[$s['Station']['line_code'].':'.$s['Station']['station_code']] = array('line' => $s['Station']['line_name'], 'station' => $s['Station']['station_name']);
        }
        return $ret;
    }
    /*
     * 路線のオプション配列を生成、県コードを文字列で与える
     */
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
    /*
     * 市区町村のオプション配列を生成、県コードを文字列で与える
     */
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
    /*
     * stateまたはcityコードを引数に、対応する条件を生成
     * cityを与えた場合は単一条件、stateを与えた場合はstate配下の全てのcity条件を生成
     */
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
    /*
     * 路線コードから条件を生成
     * 路線での条件と、路線配下のstationでの条件を生成する
     */
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
    /*
      駅コードから条件を作成
      以下の2パターンの引数を取りうる。ここで分解するのは綺麗じゃないかもしれない。
      引数が正しいフォーマットでコール前提のコードなので、コール前に引数のフォーマットチェックを。
      路線コード：駅コード
      駅コード
    */
    public function getConditionStation($array){
        if (empty($array) || !is_array($array)){
            return array();
        }
        $stations = array();
        /* :で区切れるかどうかでフォーマットを判別 */
        foreach ($array as $s){
            $t = explode(':', $s);
            /* 駅コード重複が有りうるので添字は必要 */
            if (!isset($t[1])){
                $stations[$t[0]] = $t[0];
            } else {
                $stations[$t[1]] = $t[1];
            }
        }
        $conditions = array();
        $station = $this->Station->find('all', array(
          'conditions' => array(
            'Station.station_code' => $stations,
            'Station.del_flg' => 0,
          ),
          'group' => array('Station.station_code'),
        ));
        foreach ($station as $s){
            $conditions[] = array('OfficeStation.station' => $s['Station']['station_name']);
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    /*
     * $configで定義された_search_disp系の配列を表示に都合の良い形にフォーマットする
     */
    public function extractDispArray($array){
        $r = array();
        foreach ($array as $k => $d){
            $r[$k] = $d['text'];
        }
        return $r;
    }
    /*
     * こだわり条件の配列を、こだわり条件種別ごとの配列に組み替える 
     */
    public function formatRecruitFlexTypeLabel($flexTypeArray){
        $conf = Configure::read("recruit_flex_type_label");
        $idxArray = array();
        $ret = array();
        foreach ($flexTypeArray as $f){
            $idxArray[$f] = 1;
        }
        foreach ($conf as $i=>$c){
            if (!isset($ret[$c['type']])){
                $ret[$c['type']] = array();
            }
            if (isset($idxArray[$i])){
                $ret[$c['type']][] = $c['text'];
            }
        }
        return $ret;
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
