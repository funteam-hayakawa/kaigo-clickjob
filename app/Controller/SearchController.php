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
      'SeoKaigoPrefectureText',
    );
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    /*
     * /area
     */
    public function area_idx(){
        $area = $this->Area->find('all');
        $pref = $this->Prefecture->find('all');
        
        $this->set('area', $area);
        $this->set('prefecture', $pref);
        $this->render("area_index");
    }
    /*
     * /area/県/（市区町村 or こだわり条件）/（なし or こだわり条件）
     */
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
        $commitmentCondFlg = false;
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
        /* こだわり条件関係 */
        $urlConf = Configure::read("searchURL");
        if (empty($searchCond['cities']) && ($cond1 !== null)){
            if (isset($urlConf[$cond1])){
                $searchCond[$urlConf[$cond1]['type']] = $urlConf[$cond1]['search_key'];
                $commitmentCondFlg = true;
            } else {
                throw new NotFoundException();
            }
        } 
        if (!empty($searchCond['cities']) && ($cond2 !== null)){
            if (isset($urlConf[$cond2])){
                $searchCond[$urlConf[$cond2]['type']] = $urlConf[$cond2]['search_key'];
                $commitmentCondFlg = true;
            } else {
                throw new NotFoundException();
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
            $seoCond['city_code'] = '0';
            $seoHeaderText = $this->SeoHeaderText->find('first', array('conditions' => $seoCond));
            $seoFooterText = $this->SeoFooterText->find('all', array('conditions' => $seoCond, 'order' => 'sort_order', 'limit' => 4));
        }
        if (empty($seoHeaderText)){
            $seoCond['state_code'] = '0';
            $seoHeaderText = $this->SeoHeaderText->find('first', array('conditions' => $seoCond));
            $seoFooterText = $this->SeoFooterText->find('all', array('conditions' => $seoCond, 'order' => 'sort_order', 'limit' => 4));
        }
        /* ページングもこの関数内でやってる */
        $officeSearchResult = $this->searchOfficeByCond($searchCond);
        
        $prefectureText = $this->SeoKaigoPrefectureText->find('first', array('conditions' => array('prefecture_code' => $prefId, 'del_flg' => 0)));
        
        $this->setCommonConfig();
        $this->set('officeSearchResult',$officeSearchResult);
        $this->set('seoHeaderText',$seoHeaderText);
        $this->set('seoFooterText',$seoFooterText);
        $this->set('prefectureText',$prefectureText);
        if (isset($searchCond['prefecture']) && empty($searchCond['cities']) && !$commitmentCondFlg){
            $this->set('cityArray', $this->cityOptions($searchCond['prefecture']));
        }
        if (!$commitmentCondFlg){
            $this->set('commitmentTextConf', $this->getCommitmentConf());
        }
        if (isset($searchCond['prefecture'])){
            $this->set('lineArray', $this->lineOptions($searchCond['prefecture']));
        }
        $this->set('prefName', $pref);
        if (!empty($searchCond['cities'])){
            $this->set('cityCond', $searchCond['cities']);
        }
        
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
    /*
     * /feature
     */
    public function feature_idx(){
        $this->set('commitmentTextConf', $this->getCommitmentConf());
        $this->render("feature_index");
    }
    /*
     * /feature/こだわり条件
     */
    public function feature($fearure){
        $searchCond = array();

        /* こだわり条件関係 */
        $urlConf = Configure::read("searchURL");
        if (isset($urlConf[$fearure])){
            $searchCond[$urlConf[$fearure]['type']] = $urlConf[$fearure]['search_key'];
            $this->request->data['Search'][$urlConf[$fearure]['type']] = $urlConf[$fearure]['code'];
        } else {
            throw new NotFoundException();
        }
        /*
        TODO 
        // ここで表示するSEOテキストが未定
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
        */
        /* ページングもこの関数内でやってる */
        $officeSearchResult = $this->searchOfficeByCond($searchCond);
        
        $this->setCommonConfig();
        $this->set('officeSearchResult',$officeSearchResult);
        //$this->set('seoHeaderText',$seoHeaderText);
        //$this->set('seoFooterText',$seoFooterText);
        
        if (empty($officeSearchResult)){
            $resemblesOffice = $this->searchResemblesOfficeByCond($searchCond);
            $this->set('prefectures',$this->Prefecture->find('list', array(
              'recursive' => -1,
              'fields' => array('name')
            )));
            $this->set('resemblesOffice',$resemblesOffice);
            $this->render("no_result");
            return;
        }
        $this->render("feature_result");
    }
    /*
     * /detail/求人id
     */
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
        $r['RecruitSheet']['recruit_flex_type_label'] = $this->formatRecruitFlexTypeLabel(explode(',', $r['RecruitSheet']['recruit_flex_type']));
        $this->setCommonConfig();
        
        $cond = $this->getConditionsFromRecruitSheet($r);
        $this->set('resemblesRecruit', $this->searchResemblesRecruitSheetByCond($cond, $id));
        //$this->set('ranking',$this->searchRecruitRanking());
        $this->set('recruitSheet', $r);
        $this->render("detail");
    }
    /*
     * /search
     */
    public function result() {
        if (isset($this->request->data['x'])){
            unset($this->request->data['x']);
        }
        if (isset($this->request->data['y'])){
            unset($this->request->data['y']);
        }
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
            $tmp = $this->stationOptions($searchCond['line']);
            $stationArray = array();
            foreach ($tmp as $k=>$t){
                $stationArray[$k] = $t['line'].'/'.$t['station'];
            }
            $this->set('stationArray', $stationArray);
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
    /*
     * $recruitSheetからその求人を見つけるための検索条件を生成
     * 似た求人を検索するための検索条件の初期値を作る
     */
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
        $this->set('employment_type_search_disp', $this->extractDispArray(Configure::read("employment_type_search_disp")));
        $this->set('institution_type_search_disp', $this->extractDispArray(Configure::read("institution_type_search_disp")));
        $this->set('application_license_search_disp', $this->extractDispArray(Configure::read("application_license_search_disp")));
        $this->set('employment_type', Configure::read("employment_type"));
        $this->set('access_type', Configure::read("access_type"));
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
        $fcond = array();
        if (isset($searchCond['freeword'])){
            $fcond = $this->getConditionFreeword($searchCond['freeword']);
            $officeCond = array_merge($officeCond, $fcond);
        }
        return array('office' => $officeCond, 'recruitSheet' => $recruitSheetCond, 'freeword' => $fcond);
    }

    private function searchOfficeByCond($searchCond, $notIds = array() , $limit = 10){
        $cond = $this->createFindCond($searchCond);
        
        $officeConditions = array_merge($this->commonSearchConditios['office'], $cond['office']);
        if (!empty($notIds)){
            $officeConditions = array_merge($officeConditions, array('NOT' => array('Office.id' => $notIds)));
        }
        $mergedRecruitSheetCond = array_merge($this->commonSearchConditios['recruitSheet'], $cond['recruitSheet']);
        
        if (!empty($cond['freeword'])){
            $ids = $this->RecruitSheet->find('list', array(
                'conditions' => array_merge($cond['freeword'], $this->commonSearchConditios['recruitSheet'] + $this->commonSearchConditios['office']),
                'fields' => array('RecruitSheet.recruit_sheet_id'),
                'recursive' => 1,
                'group' => 'RecruitSheet.recruit_sheet_id',
                'joins' => array(
                    array(
                        'type' => 'LEFT',
                        'table' => 'public_info',
                        'alias' => 'OfficeInfo',
                        'conditions' => array('`OfficeInfo`.`id` = `Office`.`id`', '`OfficeInfo`.`deleted` = 0')
                    ),
                    array(
                        'type' => 'LEFT',
                        'table' => 'prefecture',
                        'alias' => 'Prefecture',
                        'conditions' => array('`Prefecture`.`no` = `Office`.`prefecture`')
                    ),
                    array(
                        'type' => 'LEFT',
                        'table' => 'state',
                        'alias' => 'State',
                        'conditions' => array('`State`.`no` = `Office`.`cities`')
                    ),
                    array(
                        'type' => 'LEFT',
                        'table' => 'city',
                        'alias' => 'City',
                        'conditions' => array('`City`.`no` = `Office`.`cities`')
                    ),
                    array(
                        'type' => 'LEFT',
                        'table' => 'station_office',
                        'alias' => 'OfficeStation',
                        'conditions' => array('`OfficeStation`.`office_id` = `Office`.`id`', '`OfficeStation`.`deleted` = 0')
                    ),
                )
              )
            );
            $mergedRecruitSheetCond = array_merge($mergedRecruitSheetCond, array('RecruitSheet.recruit_sheet_id' => $ids));
        } 
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
                        'conditions' => array('`OfficeStation`.`office_id` = `Office`.`id`', '`OfficeStation`.`deleted` = 0')
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
    private function searchRecruitSheetByCond($searchCond, $notRecruitSheetIds, $limit){
        $cond = $this->createFindCond($searchCond);
        $officeConditions = array_merge($this->commonSearchConditios['office'], $cond['office']);
        $mergedRecruitSheetCond = array_merge($this->commonSearchConditios['recruitSheet'], $cond['recruitSheet']);
        if (!empty($notRecruitSheetIds)){
            $mergedRecruitSheetCond = array_merge($mergedRecruitSheetCond, array('NOT' => array('RecruitSheet.recruit_sheet_id' => $notRecruitSheetIds)));
        }
        
        $recruitSheet = $this->RecruitSheet->find('all', array(
          'recursive' => 2,
          'conditions' => array_merge($officeConditions, $mergedRecruitSheetCond),
          'joins' => array(
              array(
                  'type' => 'LEFT',
                  'table' => 'station_office',
                  'alias' => 'OfficeStation',
                  'conditions' => array('`OfficeStation`.`office_id` = `Office`.`id`', '`OfficeStation`.`deleted` = 0')
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
        $notIds = array();
        $return = array();
        foreach (array_reverse($priority) as $cond){
            if (isset($searchCond[$cond])){
                unset($searchCond[$cond]);
                $tmp = $this->searchOfficeByCond($searchCond, $notIds, $limit);
                if (!empty($tmp)){
                    foreach ($tmp as $t){
                        $return[] = $t;
                        $limit --;
                        if ($limit == 0){
                            goto LoopExit;
                        } 
                        $notIds[] = $t['Office']['id'];
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
        $notIds = array($recruitSheetId);
        $return = array();
        foreach (array_reverse($priority) as $cond){
            if (isset($searchCond[$cond])){
                unset($searchCond[$cond]);
                $tmp = $this->searchRecruitSheetByCond($searchCond, $notIds, $limit);
                if (!empty($tmp)){
                    foreach ($tmp as $t){
                        $return[] = $t;
                        $limit --;
                        if ($limit == 0){
                            goto LoopExit;
                        } 
                        $notIds[] = $t['RecruitSheet']['recruit_sheet_id'];
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
        $conf = Configure::read("institution_type_search_disp");
        foreach ($array as $val) {
            if (isset($conf[$val])) {
                foreach ($conf[$val]['search_key'] as $v){
                    $conditions[] = "FIND_IN_SET('$v', Office.institution_type)";
                }
            }
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    private function getConditionLicense($array) {
        $conditions = array();
        $conf = Configure::read("application_license_search_disp");
        foreach ($array as $val) {
            if (isset($conf[$val])) {
                foreach ($conf[$val]['search_key'] as $v){
                    $conditions[] = "FIND_IN_SET('$v', RecruitSheet.application_license)";
                }
            }
        }
        return !empty($conditions) ? array(array('OR' => $conditions)) : array();
    }
    private function getConditionEmploymentType($array) {
        $conditions = array();
        $conf = Configure::read("employment_type_search_disp");
        foreach ($array as $val) {
            if (isset($conf[$val])) {
                foreach ($conf[$val]['search_key'] as $v){
                    $conditions[] = "FIND_IN_SET('$v', RecruitSheet.employment_type)";
                }
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

        /* DBの検索対象にするカラムリスト */
        $freewordFields = array(
            'Office.name',
            'OfficeInfo.introduce_title',
            'OfficeInfo.introduce_memo',
            //'Prefecture.name',
            //'State.name',
            //'City.name',
            //'Office.address',
            /* 県市区町村住所は画面上で連結表示されているので、「東京都練馬区」のようなワードで引っ掛けるためにconcatで検索、性能問題が発生したら項目毎の検索に修正 */
            'concat(ifnull(`Prefecture`.`name`, ""), ifnull(`State`.`name`, ""), ifnull(`City`.`name`, ""), ifnull(`Office`.`address`, ""))',
            'OfficeStation.station',
            'OfficeStation.line',
            'RecruitSheet.sheet_title',
            'RecruitSheet.recruit_introduce_title',
            'RecruitSheet.salary',
        );
        /* DB上ではコード値で対応文字列がConfigにあるもの */
        $freewordSearchOptionTable = array(
            'RecruitSheet.occupation' => array('type' => 'val' , 'conf' => Configure::read("occupation")),
            'Office.institution_type' => array('type' => 'set' , 'conf' => Configure::read("institution_type")),
            'RecruitSheet.application_license' => array('type' => 'set' , 'conf' => Configure::read("application_license")),
            'RecruitSheet.employment_type' => array('type' => 'val' , 'conf' => Configure::read("employment_type")),
            'RecruitSheet.recruit_flex_type' => array('type' => 'set' , 'conf' => Configure::read("recruit_flex_type_for_freeword")),
            /* str2stationQueryでaccess_typeもクエリ生成するので不要 */
            //'OfficeStation.access_type' => array('type' => 'val' , 'conf' => Configure::read("access_type")),
        );
        $returnCond = array();
        foreach ($words as $w){
            $conditions = array();
            foreach ($freewordFields as $f){
                if ($f == 'OfficeStation.station'){
                    $tmp = $this->str2stationQuery($w);
                    if (!empty($tmp)){
                        $conditions[] = $tmp;
                    }
                    continue;
                }
                if ($f == 'OfficeStation.line'){
                    /* 駅DBは英字が全て全角大文字なので、半角文字や小文字が入力された場合に対応 */
                    $tmp = mb_strtoupper(mb_convert_kana($w, 'R'));
                    $conditions[] = array("$f LIKE" => '%'.$tmp.'%');
                    continue;
                }
                $conditions[] = array("$f LIKE" => '%'.$w.'%');
            }
            foreach ($freewordSearchOptionTable as $key => $opt){
                foreach ($opt['conf'] as $val => $str){
                    if (mb_strpos($str, $w) !== FALSE){
                        if ($opt['type'] == 'val'){
                            $conditions[] = array("$key" => $val);
                        }
                        if ($opt['type'] == 'set'){
                            $conditions[] = "FIND_IN_SET('$val', $key)";
                        }
                    }
                }
            }
            $returnCond[] = array('OR' => $conditions);
        }
        return !empty($returnCond) ? $returnCond : array();
    }
    /* 暇つぶしで作ったロジックなのでバグってたら機能そのものを削除で
     * 要求仕様には無い裏コマンドです。
     */
    /*
      例えば「光が丘駅徒歩7分」というワードに対して
      OfficeStation.station LIKE => "%光が丘%"
      OfficeStation.access_type = 3 -- 徒歩
      OfficeStation.access_interval <= 7 -- 7分以内
      のクエリを返す。
      現在以下のバリエーションに対応
        光が丘
        光が丘駅
        光が丘駅7分
        光が丘駅7
        光が丘7分
        光が丘駅徒歩
        徒歩7分
        7分
    */
    private function str2stationQuery($word){
        $type = Configure::read("access_type");
        /* 入力を全て全角にして、数字のみ半角にする */
        $tmp = mb_convert_kana(mb_convert_kana($word, 'A'),'n');
        $station = '';
        $ekiflg = 0;
        if (($p = mb_strrpos($tmp, '駅')) !== false){
            $station = mb_substr($tmp, 0, $p);
            $tmp = mb_substr($tmp, $p + 1, mb_strlen($tmp));
            $ekiflg = 1;
        } else {
            $f = 0;
            if (!mb_ereg_match("^.*\d+分$", $tmp)){
                $f = 1;
                foreach ($type as $val => $str){
                    if ($str == $tmp){
                        $f = 0;
                    }
                }
            }
            if ($f){
                $station = mb_convert_kana($tmp, 'A');
            }
        }
        $typeStr = '';
        if (($s = mb_ereg_replace("\d+.*", "", $tmp)) !== false){
            $typeStr = $s;
            $tmp = mb_ereg_replace($typeStr, "", $tmp);
        }
        if ($ekiflg){
            if (mb_ereg_match("^\d+分?$", $tmp)){
                $timeStr = mb_ereg_replace("分.*", "", $tmp);
            }
        } else {
            if (mb_ereg_match("^\d+分$", $tmp)){
                $timeStr = mb_ereg_replace("分.*", "", $tmp);
            }
        }
        $ret = array();
        if (!empty($typeStr)){
            $f = 0;
            foreach ($type as $val => $str){
                if (mb_strpos($str, $typeStr) !== FALSE){
                    if (!isset($ret['OR'])){
                        $ret['OR'] = array();
                    }
                    $ret['OR'][] = array("OfficeStation.access_type" => $val);
                    $f = 1;
                }
            }
            if (!$f){
                $station = $typeStr;
            }
        }
        if (!empty($station)){
            $ret[] = array("OfficeStation.station LIKE" => '%'.$station.'%');
        }
        if (!empty($timeStr)){
            $ret[] = array("OfficeStation.access_interval <=" => $timeStr);
        }
        return $ret;
    }
    /* こだわり条件URL情報コンフィグロード、整形 */
    private function getCommitmentConf(){
        /* こだわり条件のURLと検索条件のセット */
        $urlConf = Configure::read("searchURL");
        $commitmentText = Configure::read("commitment_text");
        $conf = array();
        foreach ($urlConf as $url => $cond){
            if (!isset($conf[$cond['type']])){
                $conf[$cond['type']] = array('name' => $commitmentText[$cond['type']], 'list' => array());
            }
            $conf[$cond['type']]['list'][] = array('code' => $cond['code'], 'url' => $url, 'text' => $cond['text']);
        }
        return $conf;
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
            'institution_type' => Configure::read("institution_type_search_disp"),
            'application_license' => Configure::read("application_license_search_disp"),
            'employment_type' => Configure::read("employment_type_search_disp"),
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
        $stations = array();
        $lines = array();
        foreach ($cond['station'] as $s){
            $t = explode(':', $s);
            if (!isset($t[0]) || !isset($t[0])){
                return false;
            }
            $lines[$t[0]] = $t[0];
            $stations[$t[1]] = $t[1];
        }
        foreach ($lines as $l){
            if (!in_array($l, $cond['line'])){
                return false;
            }
        }
        $c = $this->Station->find('count', array(
          'conditions' => array(
            array('Station.line_code' => $cond['line']),
            'Station.line_code' => $lines,
            'Station.station_code' => $stations,
            'Station.del_flg' => 0,
          ),
          'group' => array('Station.station_code'),
        ));
        return $c == count($stations);
    }
}
