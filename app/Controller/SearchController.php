<?php
App::uses('AppController', 'Controller');

class SearchController extends AppController { 
    public $components = array('Paginator', 'Search.Prg',);
    public $uses = array(
      'Search',
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
      'Station',
    );
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function area_idx(){
        $area = $this->Area->find('all');
        $pref = $this->Prefecture->find('all');
        $this->set('area',$area);
        $this->set('prefecture',$pref);
        $this->render("area_index");
    }

    public function area($pref=null, $cond1=null, $cond2=null){
        $p = $this->Prefecture->find('first', array('conditions' => array('short_name' => $pref),
                                                    'fields' => array('Prefecture.no')
        ));
        if (empty($p)){
            throw new NotFoundException();
        }
        $prefId = $p['Prefecture']['no'];
        $c = $this->City->find('first', array('conditions' => array('City.prefecture_no' => $prefId,
                                                                    'City.no' => $cond1),
                                                                    'fields' => array('City.no')
        ));
        $s = $this->State->find('first', array('conditions' => array('State.prefecture_no' => $prefId,
                                                                     'State.no' => $cond1),
                                                                     'fields' => array('State.no')
        ));
        $searchCond = array();
        $cityCode = '0';
        $stateCode = '0';
        $searchCond['prefecture'] = $prefId;
        if (!empty($c)){
            $cityCode = $c['City']['no'];
            $searchCond['cities'] = $cityCode;
        } else {
            if (!empty($s)){
                $stateCode = $s['State']['no'];
                $searchCond['cities'] = $stateCode;
            }
        }
        
        $seoCond = array(
            'prefecture_code' => $prefId, 
            'state_code' => $stateCode, 
            'city_code' => $cityCode, 
            'occupation_id' => '0',
            'institution_id' => '0',
            'license_id' => '0',
            'employment_id' => '0',
            'del_flg' => 0, 
        );
        $seoHeaderText = $this->SeoHeaderText->find('first', array('conditions' => $seoCond));
        $seoFooterText = $this->SeoFooterText->find('all', array('conditions' => $seoCond, 'order' => 'sort_order', 'limit' => 4));
        if (empty($seoHeaderText)){
            $seoCond['SeoHeaderText.city_code'] = '0';
            $seoHeaderText = $this->SeoHeaderText->find('first', array('conditions' => $seoCond));
            $seoFooterText = $this->SeoFooterText->find('all', array('conditions' => $seoCond, 'order' => 'sort_order', 'limit' => 4));
        }
        if (empty($seoHeaderText)){
            $seoCond['SeoHeaderText.state_code'] = '0';
            $seoHeaderText = $this->SeoHeaderText->find('first', array('conditions' => $seoCond));
            $seoFooterText = $this->SeoFooterText->find('all', array('conditions' => $seoCond, 'order' => 'sort_order', 'limit' => 4));
        }
        /* ページングもこの関数内でやってる */
        $officeSearchResult = $this->searchOfficeByCond($searchCond);
        $this->setCommonConfig();
        $this->set('officeSearchResult',$officeSearchResult);
        $this->set('seoHeaderText',$seoHeaderText);
        $this->set('seoFooterText',$seoFooterText);
        if (isset($searchCond['prefecture']) && empty($searchCond['cities'])){
            $this->set('cityArray', $this->cityOptions($searchCond['prefecture']));
        }
        if (isset($searchCond['prefecture'])){
            $this->set('lineArray', $this->lineOptions($searchCond['prefecture']));
        }
        $this->set('prefName', $pref);
        
        if (empty($officeSearchResult)){
            $resemblesOffice = $this->searchResemblesOfficeByCond($searchCond);
            $this->request->data['Search']['prefecture'] = $prefId;
            $this->set('prefectures',$this->Prefecture->find('list', array(
              'recursive' => -1,
              'fields' => array('name')
            )));
            if (!empty($searchCond['cities'])){
                $this->request->data['Search']['cities'] = $searchCond['cities'];
            }
            if (isset($searchCond['prefecture'])){
                $this->set('cityArray', $this->cityOptions($searchCond['prefecture']));
                $this->set('lineArray', $this->lineOptions($searchCond['prefecture']));
            }
            $this->set('resemblesOffice',$resemblesOffice);
            $this->render("no_result");
            return;
        }
        $this->render("area_result");
    }
    public function detail($id){
        $this->RecruitSheet->Office->hasMany['RecruitSheet']['conditions'] = $this->commonSearchConditios['recruitSheet'];
        $r = $this->RecruitSheet->find('first', array(
          'recursive' => 2,
          'conditions' => array('RecruitSheet.recruit_sheet_id' => $id,) + 
                                 $this->commonSearchConditios['recruitSheet'] + 
                                 $this->commonSearchConditios['office'],
        ));
        $this->RecruitSheet->Office->hasMany['RecruitSheet']['conditions'] = array();
        if (empty($r)){
            throw new NotFoundException();
        }        
        $this->setCommonConfig();
        
        $cond = $this->getConditionsFromRecruitSheet($r);
        $this->set('resemblesRecruit', $this->searchResemblesRecruitSheetByCond($cond, $id));
        //$this->set('ranking',$this->searchRecruitRanking());
        $this->set('recruitSheet', $r);
        $this->render("detail");
    }
    public function result() {
        $this->Prg->commonProcess();
        $searchCond = $this->Prg->parsedParams();
        
        /* URLの検索条件にゴミを入れられたら全て404に飛ばす */
        $this->searchCondValidate($searchCond);
        /* ページングもこの関数内でやってる */
        $officeSearchResult = $this->searchOfficeByCond($searchCond);
        
        $this->setCommonConfig();
        //$this->set('ranking',$this->searchRecruitRanking());
        $this->set('area',$this->Area->find('all'));
        $this->set('prefectures',$this->Prefecture->find('list', array(
          'recursive' => -1,
          'fields' => array('name')
        )));
        
        if (isset($searchCond['prefecture'])){
            $this->set('cityArray', $this->cityOptions($searchCond['prefecture']));
            $this->set('lineArray', $this->lineOptions($searchCond['prefecture']));
        }
        if (isset($searchCond['line'])){
            $this->set('stationArray', $this->stationOptions($searchCond['line']));
        }
        
        $this->set('officeSearchResult',$officeSearchResult);
        
        if (empty($officeSearchResult)){
            $resemblesOffice = $this->searchResemblesOfficeByCond($searchCond);
            $this->set('resemblesOffice', $resemblesOffice);
            $this->render("no_result");
            return;
        }
        $this->render("search_result");
    }
    private function getConditionsFromRecruitSheet($recruitSheet){
        $cond = array();
        $cond['prefecture'] = $recruitSheet['Office']['prefecture'];
        $cond['cities'] = $recruitSheet['Office']['cities'];
        $lineName = array();
        $stationName = array();
        foreach ($recruitSheet['Office']['OfficeStation'] as $s){
            $l = explode(',', $s['line']);
            $lineName = array_merge($lineName, $l);
            $stationName[] = $s['station'];
        }
        $lineCode = $this->Station->find('list', array(
          'conditions' => array(
            'OR' => array(
              'Station.line_name' => $lineName,
              'Station.station_name' => $stationName,
            ),
          ),
          'group' => 'Station.line_code',
          'fields' => 'Station.line_code',
        ));
        $cond['line'] = array();
        foreach ($lineCode as $c){
            $cond['line'][] = $c;
        }
        $stationCode = $this->Station->find('list', array(
          'conditions' => array(
            'OR' => array(
              'Station.station_name' => $stationName,
            ),
          ),
          'group' => 'Station.station_code',
          'fields' => 'Station.station_code',
        ));
        $cond['station'] = array();
        foreach ($stationCode as $c){
            $cond['station'][] = $c;
        }
        $cond['occupation'] = explode(',', $recruitSheet['RecruitSheet']['occupation']);
        $cond['institution_type'] = explode(',', $recruitSheet['Office']['institution_type']);
        $cond['application_license'] = explode(',', $recruitSheet['RecruitSheet']['application_license']);
        $cond['employment_type'] = explode(',', $recruitSheet['RecruitSheet']['employment_type']);
        $cond['recruit_flex_type'] = explode(',', $recruitSheet['RecruitSheet']['recruit_flex_type']);
        $cond['particular_ttl_hour'] = array();
        /* 残業月10時間以下 */
        if (empty($r['RecruitSheet']['overtime_work']) || $recruitSheet['RecruitSheet']['overtime_work'] <= 10){
            $cond['particular_ttl_hour'][] = '1';
        }
        /* 日勤のみ */
        if (array_search($cond['employment_type'], array('4','6','7')) !== FALSE){
            $cond['particular_ttl_hour'][] = '2';
        }
        /* 夜勤のみ */
        if (array_search($cond['employment_type'], array('5')) !== FALSE){
            $cond['particular_ttl_hour'][] = '3';
        }
        return $cond;
    }
    private function setCommonConfig(){
        $this->set('occupation', Configure::read("occupation"));
        $this->set('institution_type', Configure::read("institution_type"));
        $this->set('application_license', Configure::read("application_license"));
        $this->set('access_type', Configure::read("access_type"));
        $this->set('employment_type', Configure::read("employment_type"));
        $this->set('recruit_flex_type', Configure::read("recruit_flex_type"));
        $this->set('particular_ttl_hour', Configure::read("particular_ttl_hour"));
        $this->set('house_for_single', Configure::read("house_for_single"));
        $this->set('house_for_family', Configure::read("house_for_family"));
        $this->set('mycar', Configure::read("mycar"));
        $this->set('commutation', Configure::read("commutation"));
        $this->set('social_insurance', Configure::read("social_insurance"));
        $this->set('retirement', Configure::read("retirement"));
        $this->set('reemployment', Configure::read("reemployment"));
        $this->set('retirement_pay', Configure::read("retirement_pay"));
    }
    private function createFindCond($searchCond){
        $officeCond = array(); /* officeの条件をこっちに詰める */
        $recruitSheetCond = array(); /* recruit_sheetの条件をこっちに詰める */
        if (isset($searchCond['prefecture'])){
            $officeCond = array_merge($officeCond, array('Office.prefecture' => $searchCond['prefecture']));
        }
        if (isset($searchCond['cities'])){
            $officeCond = array_merge($officeCond, $this->getConditionCities($searchCond['cities']));
        }
        if (isset($searchCond['line']) && !isset($searchCond['station'])){
            $officeCond = array_merge($officeCond, $this->getConditionLine($searchCond['line']));
        }
        if (isset($searchCond['station'])){
            $officeCond = array_merge($officeCond, $this->getConditionStation($searchCond['station']));
        }
        if (!empty($searchCond['occupation'])){
            $recruitSheetCond = array_merge($recruitSheetCond, $this->getConditionOccupation($searchCond['occupation']));
        }
        if (!empty($searchCond['institution_type'])){
            $officeCond = array_merge($officeCond, $this->getConditionInstitutionType($searchCond['institution_type']));
        }
        if (!empty($searchCond['application_license'])){
            $recruitSheetCond = array_merge($recruitSheetCond, $this->getConditionLicense($searchCond['application_license']));
        }
        if (!empty($searchCond['employment_type'])){
            $recruitSheetCond = array_merge($recruitSheetCond, $this->getConditionEmploymentType($searchCond['employment_type']));
        }
        if (!empty($searchCond['recruit_flex_type'])){
            $recruitSheetCond = array_merge($recruitSheetCond, $this->getConditionRecruitFlexType($searchCond['recruit_flex_type']));
        }
        if (!empty($searchCond['particular_ttl_hour'])){
            $recruitSheetCond = array_merge($recruitSheetCond, $this->getConditionPaticularType($searchCond['particular_ttl_hour']));
        }
        if (isset($searchCond['freeword'])){
            $officeCond = array_merge($officeCond, $this->getConditionFreeword($searchCond['freeword']));
        }
        return array('office' => $officeCond, 'recruitSheet' => $recruitSheetCond);
    }

    private function searchOfficeByCond($searchCond, $limit = 10){
        $cond = $this->createFindCond($searchCond);
        
        $officeConditions = array_merge($this->commonSearchConditios['office'], $cond['office']);
        $mergedRecruitSheetCond = array_merge($this->commonSearchConditios['recruitSheet'], $cond['recruitSheet']);
        $this->Office->hasMany['RecruitSheet']['conditions'] = $mergedRecruitSheetCond;
        $this->Office->hasMany['RecruitSheet']['order'] = 'RecruitSheet.receipted DESC';
        $this->Office->virtualFields += array('R_updated' => 'MAX(RecruitSheet.updated)');
        $paginate = array(
            'Office' => array(
                'limit' => $limit,
                'conditions' => $officeConditions,
                //'order' => 'Office.updated DESC',
                'order' => 'R_updated DESC',
                'group' => 'Office.id',
                'recursive' => 1,
                'joins' => array(
                    array(
                        'type' => 'INNER',
                        'table' => 'recruit_sheet',
                        'alias' => 'RecruitSheet',
                        'conditions' => array_merge($mergedRecruitSheetCond,
                                              array('`RecruitSheet`.`id` = `Office`.`id`')
                                        )
                    ),
                    array(
                        'type' => 'LEFT',
                        'table' => 'station_office',
                        'alias' => 'OfficeStation',
                        'conditions' => array('`OfficeStation`.`office_id` = `Office`.`id`')
                    ),
                )
            )
        );
        $this->Paginator->settings = $paginate;
        $office = $this->Paginator->paginate('Office');
        $this->Paginator->settings = array();
        $this->Office->hasMany['RecruitSheet']['conditions'] = array();
        $this->Office->hasMany['RecruitSheet']['order'] = array();
        return $office;
    }
    private function searchRecruitSheetByCond($searchCond, $notRecruitSheetId, $limit){
        $cond = $this->createFindCond($searchCond);
        $officeConditions = array_merge($this->commonSearchConditios['office'], $cond['office']);
        $mergedRecruitSheetCond = array_merge($this->commonSearchConditios['recruitSheet'], $cond['recruitSheet']);
        $mergedRecruitSheetCond = array_merge($mergedRecruitSheetCond, array('NOT' => array('RecruitSheet.recruit_sheet_id' => $notRecruitSheetId)));
        
        $recruitSheet = $this->RecruitSheet->find('all', array(
          'recursive' => 2,
          'conditions' => array_merge($officeConditions, $mergedRecruitSheetCond),
          'joins' => array(
              array(
                  'type' => 'LEFT',
                  'table' => 'station_office',
                  'alias' => 'OfficeStation',
                  'conditions' => array('`OfficeStation`.`office_id` = `Office`.`id`')
              ),
          ),
          'group' => 'RecruitSheet.recruit_sheet_id',
          'order' => 'RecruitSheet.receipted DESC',
          'limit' => $limit
        ));
        return $recruitSheet;
    }
    private function searchResemblesOfficeByCond($searchCond){
        $limit = 10;
        $priority = array(
          'prefecture', /* エリア */
          'cities', /* エリア */
          'occupation', /* 職種 */
          'employment_type', /* 雇用形態 */
          'institution_type', /* 施設タイプ */
          'recruit_flex_type',
          'particular_ttl_hour',
          'line',
          'station',
          'application_license',
          'freeword',
        );
        $ids = array();
        $return = array();
        foreach (array_reverse($priority) as $cond){
            if (isset($searchCond[$cond])){
                unset($searchCond[$cond]);
                $tmp = $this->searchOfficeByCond($searchCond, $limit);
                if (!empty($tmp)){
                    foreach ($tmp as $t){
                        if (!isset($ids[$t['Office']['id']])){
                            $return[] = $t;
                            $limit --;
                            if ($limit == 0){
                                goto LoopExit;
                            } 
                            $ids[$t['Office']['id']] = 1;
                        }
                    }
                }
            }
        }
        LoopExit:;
        return $return;
    }
    private function searchResemblesRecruitSheetByCond($searchCond, $recruitSheetId){
        $limit = 8;
        $priority = array(
          'prefecture', /* エリア */
          'cities', /* エリア */
          'occupation', /* 職種 */
          'employment_type', /* 雇用形態 */
          'institution_type', /* 施設タイプ */
          'recruit_flex_type',
          'particular_ttl_hour',
          'line',
          'station',
          'application_license',
          'freeword',
        );
        $ids = array();
        $return = array();
        foreach (array_reverse($priority) as $cond){
            if (isset($searchCond[$cond])){
                unset($searchCond[$cond]);
                $tmp = $this->searchRecruitSheetByCond($searchCond, $recruitSheetId, $limit);
                if (!empty($tmp)){
                    foreach ($tmp as $t){
                        if (!isset($ids[$t['RecruitSheet']['recruit_sheet_id']])){
                            $return[] = $t;
                            $limit --;
                            if ($limit == 0){
                                goto LoopExit;
                            } 
                            $ids[$t['RecruitSheet']['recruit_sheet_id']] = 1;
                        }
                    }
                }
            }
        }
        LoopExit:;
        return $return;
    }
    private function getConditionOccupation($array) {
        $conditions = array();
        foreach ($array as $val) {
            if (!empty($val)) {
                if($val == 1) {
                    $conditions[] = array('RecruitSheet.occupation' => array(1, 21, 41, 42));
                } elseif($val == 2) {
                    $conditions[] = array('RecruitSheet.occupation' => 2);
                } elseif($val == 3) {
                    $conditions[] = array('RecruitSheet.occupation' => 3);
                } elseif($val == 4) {
                    $conditions[] = array('RecruitSheet.occupation' => array(4, 40, 45));
                } elseif($val == 5) {
                    $conditions[] = array('RecruitSheet.occupation' => 5);
                } elseif($val == 6) {
                    $conditions[] = array('RecruitSheet.occupation' => 6);
                } elseif($val == 7) {
                    $conditions[] = array('RecruitSheet.occupation' => 7);
                } elseif($val == 8) {
                    $conditions[] = array('RecruitSheet.occupation' => 8);
                } elseif($val == 9) {
                    $conditions[] = array('RecruitSheet.occupation' => 9);
                } elseif($val == 10) {
                    $conditions[] = array('RecruitSheet.occupation' => array(10, 11, 12, 13, 14, 23, 24, 25));
                } elseif($val == 63) {
                    $conditions[] = array(
                      array('RecruitSheet.occupation >=' => 26),
                      array('RecruitSheet.occupation !=' => 40),
                      array('RecruitSheet.occupation !=' => 41),
                      array('RecruitSheet.occupation !=' => 42),
                      array('RecruitSheet.occupation !=' => 45),
                    );
                }
            }
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    private function getConditionInstitutionType($array) {
        $conditions = array();
        foreach ($array as $val) {
            if (!empty($val)) {
                $conditions[] = "FIND_IN_SET('$val', Office.institution_type)";
            }
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    private function getConditionLicense($array) {
        $conditions = array();
        foreach ($array as $val) {
            if (!empty($val)) {
                $conditions[] = "FIND_IN_SET('$val', RecruitSheet.application_license)";
            }
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    private function getConditionEmploymentType($array) {
        $conditions = array();
        foreach ($array as $val) {
            if (!empty($val)) {
                $conditions[] = "FIND_IN_SET('$val', RecruitSheet.employment_type)";
            }
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    private function getConditionRecruitFlexType($array) {
        $conditions = array();
        foreach ($array as $val) {
            if (!empty($val)) {
                $conditions[] = "FIND_IN_SET('$val', RecruitSheet.recruit_flex_type)";
            }
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    private function getConditionOvertime($overtime) {
        if (empty($overtime)) return array();
        return array(array('OR' => array("IFNULL(RecruitSheet.overtime_work, 0) <= $overtime")));
    }
    private function getConditionPaticularType($array) {
        $conditions = array();
        foreach ($array as $val) {
            switch ($val){
                case '1':
                    $c = $this->getConditionOvertime(10);
                break;
                case '2':
                    $c = $this->getConditionEmploymentType(array('4','6','7'));
                break;
                case '3':
                    $c = $this->getConditionEmploymentType(array('5'));
                break;
            }
            $conditions = array_merge($conditions, $c[0]['OR']);
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    private function getConditionFreeword($freeword){
        if (!is_string($freeword)){
            return array();
        }
        $freeword = mb_ereg_replace("(\s|　)", ' ', $freeword);
        $words = explode(' ',$freeword);
        $words = array_filter($words, "strlen");

        $freewordFields = array(
            'Office.name',
            'Office.address',
            'OfficeInfo.introduce_title',
            'OfficeInfo.introduce_memo',
            'Prefecture.name',
            'State.name',
            'City.name',
            'OfficeStation.station',
            'OfficeStation.line',
            'RecruitSheet.sheet_title',
            'RecruitSheet.recruit_introduce_title',
            'RecruitSheet.salary',
        );
        $freewordSearchOptionTable = array(
            'RecruitSheet.occupation' => Configure::read("occupation"),
            'Office.institution_type' => Configure::read("institution_type"),
            'RecruitSheet.application_license' => Configure::read("application_license"),
            'RecruitSheet.employment_type' => Configure::read("employment_type"),
            'RecruitSheet.recruit_flex_type' => Configure::read("recruit_flex_type"),
            'OfficeStation.access_type' => Configure::read("access_type"),
        );
        $returnCond = array();
        foreach ($words as $w){
            $conditions = array();
            foreach ($freewordFields as $f){
                $conditions[] = array("$f LIKE" => '%'.$w.'%');
            }
            foreach ($freewordSearchOptionTable as $key => $opt){
                foreach ($opt as $val => $str){
                    if (mb_strpos($str, $w) !== FALSE){
                        $conditions[] = array("$key" => $val);
                    }
                }
            }
            $returnCond[] = array('OR' => $conditions);
        }
        return !empty($returnCond) ? $returnCond : array();
    }
    
    /* URLの検索条件にゴミを入れられたら全て404に飛ばす */
    private function searchCondValidate($cond){
        if (empty($cond)){
            return;
        }
        if (!is_array($cond)){
            throw new NotFoundException();
        }
        $validateTable = array(
            'occupation' => Configure::read("occupation"),
            'institution_type' => Configure::read("institution_type"),
            'application_license' => Configure::read("application_license"),
            'employment_type' => Configure::read("employment_type"),
            'recruit_flex_type' => Configure::read("recruit_flex_type"),
            'particular_ttl_hour' => Configure::read("particular_ttl_hour"),
            'freeword' => 'text',
            'prefecture' => array('function' => 'isValidPrefecture'),
            'cities' => array('function' => 'isValidCityCode'),
            'line' => array('function' => 'isValidLineCode'),
            'station' => array('function' => 'isValidStationCode'),
        );
        foreach ($cond as $key => $condList){
            if (!isset($validateTable[$key])){
                throw new NotFoundException();
            }
            if (!is_array($validateTable[$key])){
                if ($validateTable[$key] === 'text'){
                    continue;
                }
            } else {
                if (key($validateTable[$key]) == 'function'){
                    if (!$this->$validateTable[$key]['function']($cond)){
                        throw new NotFoundException();
                    }
                    continue;
                }
            }
            foreach($condList as $val){
                if (!isset($validateTable[$key][$val])){
                    throw new NotFoundException();
                }
            }
        }
    }
    private function isValidPrefecture($cond){
        return $this->Prefecture->find('count', array('conditions' => array('Prefecture.no' => $cond['prefecture']), 'recursive' => -1));
    }
    private function isValidCityCode($cond){
        if (empty($cond['prefecture'])){
            return false;
        }
        if (!$this->isValidPrefecture($cond)){
            return false;
        }
        return $this->State->find('count', array(
          'conditions' => array(
            'State.no' => $cond['cities'], 
            'State.prefecture_no' => $cond['prefecture']
          ), 
          'recursive' => -1)
          ) + $this->City->find('count', array(
          'conditions' => array(
            'City.no' => $cond['cities'], 
            'City.prefecture_no' => $cond['prefecture']
          ), 
          'recursive' => -1)
        );
    }
    private function isValidLineCode($cond){
        $pref = $this->Prefecture->find('first', array('conditions' => array('Prefecture.no' => $cond['prefecture']), 'recursive' => -1));
        if (empty($pref)){
            return false;
        }
        $c = $this->Station->find('count', array(
          'conditions' => array(
            'Station.prefecture_name' => $pref['Prefecture']['name'],
            'Station.line_code' => $cond['line'],
            'Station.del_flg' => 0,
          ),
          'group' => array('Station.line_code'),
        ));
        return $c == count($cond['line']);
    }
    private function isValidStationCode($cond){
        $c = $this->Station->find('count', array(
          'conditions' => array(
            'Station.line_code' => $cond['line'],
            'Station.station_code' => $cond['station'],
            'Station.del_flg' => 0,
          ),
          'group' => array('Station.station_code'),
        ));
        return $c == count($cond['station']);
    }
}
