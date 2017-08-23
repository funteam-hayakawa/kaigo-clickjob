<?php
App::uses('AppController', 'Controller');

class ColumnController extends AppController { 
    public $components = array('Paginator', 'Search.Prg');
    public $uses = array('Column', 'ColumnCoordRanking', 'ColumnRecommendRanking', 'ColumnTopPickup');
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }
    public function index($category = ''){
        $this->Prg->commonProcess();
        $searchCond = $this->Prg->parsedParams();
        $catArray = Configure::read("column_category");
        $catTab = array();
        foreach ($catArray as $i => $c){
            $catTab[$c['url']] = $i;
        }
        $categoryId = false;
        if (strlen($category)){
            if (!isset($catTab[$category])){
                throw new NotFoundException();
            }
            $categoryId = $catTab[$category];
        }
        $conditions = array(
            'Column.del_flg' => 0,
        );
        if ($categoryId !== false){
            $conditions['ColumnCategory.category_id'] = $categoryId;
        }
        if (isset($searchCond['word'])){
            $conditions = array_merge($conditions, $this->getConditionFreeword($searchCond['word']));
        }
        $paginate = array(
            'Column' => array(
                'limit' => (SITE_TYPE == 'p') ? 9 : 4,
                'conditions' => $conditions,
                'order' => 'Column.modified DESC',
                'group' => 'Column.id',
                'recursive' => 2,
                'joins' => array(
                    array(
                        'type' => 'LEFT',
                        'table' => 'seo_kaigo_column_contents',
                        'alias' => 'ColumnContent',
                        'conditions' => array('`Column`.`id` = `ColumnContent`.`parent_id`'),
                    ),
                )
            )
        );
        $this->Paginator->settings = $paginate;
        $column = $this->Paginator->paginate('Column');
        $this->Paginator->settings = array();
        
        $ranking = $this->getRanking();
        $this->set('category',$catArray);
        $this->set('ranking',$ranking);
        $this->set('column',$column);
        $this->layout = '';
        if (SITE_TYPE == 'p') {
            $this->render("index");
        } else {
            $this->render("sp-index");
        }
    }
    public function detail($id){
        $column = $this->Column->find('first', array('conditions' => array('Column.id' => $id, 'Column.del_flg' => 0),'recursive' => 2));
        if (empty($column)){
            throw new NotFoundException();
        }
        $this->setRelationRanking($column);
        $ranking = $this->getRanking();
        $this->set('category',Configure::read("column_category"));
        $this->set('ranking',$ranking);
        $this->set('column',$column);
        $this->layout = '';
        if (SITE_TYPE == 'p') {
            $this->render("detail");
        } else {
            $this->render("sp-detail");
        }
    }
    /* 各種ランキング情報の取得 : 設計時に変な仕様にしてしまったためCakeのアソシエーションで持ってこれない */
    private function getRanking(){
        $retRanking = array();
        $ColumnCoordRankingOrg = $this->ColumnCoordRanking->find('first', array('conditions' => array('del_flg' => 0)));
        $ColumnRecommendRankingOrg = $this->ColumnRecommendRanking->find('first', array('conditions' => array('del_flg' => 0)));
        $ColumnTopPickupOrg = $this->ColumnTopPickup->find('first', array('conditions' => array('del_flg' => 0)));
        $ranking = array();
        for ($i = 1 ; $i <= 3 ; $i++){
            $ranking[$i - 1] = strlen($ColumnCoordRankingOrg['ColumnCoordRanking']["column_$i"]) ?
                            $this->Column->find('first', array(
                                'conditions' => array(
                                    'Column.view_id' => $ColumnCoordRankingOrg['ColumnCoordRanking']["column_$i"],
                                    'Column.del_flg' => 0,
                                ),
                                'recursive' => 2,
                            ))
                            :array()
            ;
        }
        $retRanking['CoordRanking'] = $ranking;
        $ranking = array();
        for ($i = 1 ; $i <= 3 ; $i++){
            $ranking[$i - 1] = strlen($ColumnRecommendRankingOrg['ColumnRecommendRanking']["column_$i"]) ?
                            $this->Column->find('first', array(
                                'conditions' => array(
                                    'Column.view_id' => $ColumnRecommendRankingOrg['ColumnRecommendRanking']["column_$i"],
                                    'Column.del_flg' => 0,
                                ),
                                'recursive' => 2,
                            ))
                            :array()
            ;
        }
        $retRanking['RecommendRanking'] = $ranking;
        $ranking = array();
        for ($i = 1 ; $i <= 4 ; $i++){
            $ranking[$i - 1] = strlen($ColumnTopPickupOrg['ColumnTopPickup']["column_$i"]) ?
                            $this->Column->find('first', array(
                                'conditions' => array(
                                    'Column.view_id' => $ColumnTopPickupOrg['ColumnTopPickup']["column_$i"],
                                    'Column.del_flg' => 0,
                                ),
                                'recursive' => 2,
                            ))
                            :array()
            ;
        }
        $retRanking['TopPickup'] = $ranking;
        return $retRanking;
    }
    /* 関連ランキング情報の取得 : 設計時に変な仕様にしてしまったためCakeのアソシエーションで持ってこれない */
    private function setRelationRanking(& $c){
        $ranking = array();
        for ($i = 1 ; $i <= 3 ; $i++){
            $ranking[$i - 1] = strlen($c['ColumnHeaderContent']["ranking_$i"]) ?
                            $this->Column->find('first', array(
                                'conditions' => array(
                                    'Column.view_id' => $c['ColumnHeaderContent']["ranking_$i"],
                                    'Column.del_flg' => 0,
                                ),
                                'recursive' => 2,
                            ))
                            :array()
            ;
        }
        $c['ColumnHeaderContent']['RelationRanking'] = $ranking;
    }
    private function getConditionFreeword($freeword){
        if (!is_string($freeword)){
            return array();
        }
        $freeword = mb_ereg_replace("(\s|　)", ' ', $freeword);
        $words = explode(' ',$freeword);
        $words = array_filter($words, "strlen");
        $freewordFields = array(
            'ColumnHeaderContent.title',
            'ColumnHeaderContent.introduction',
            'ColumnContent.text',
        );
        $returnCond = array();
        foreach ($words as $w){
            $conditions = array();
            foreach ($freewordFields as $f){
                $conditions[] = array("$f LIKE" => '%'.$w.'%');
            }
            $returnCond[] = array('OR' => $conditions);
        }
        return !empty($returnCond) ? $returnCond : array();
    }
}
