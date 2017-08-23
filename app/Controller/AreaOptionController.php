<?php
App::uses('AppController', 'Controller');

class AreaOptionController extends AppController { 
    public $components = array('Paginator', 'Search.Prg',);
    public $uses = array(
      'Prefecture', 
      'State', 
      'City', 
    );
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function city(){
        $retVal = array(
            'status' => false,
            'msg' => '',
            'value' => array(),
        );
        if($this->request->is('ajax')) {
            if (isset($this->request->data['prefecture_id'])){
                $pref_id = $this->request->data['prefecture_id'];
                if (!$this->Prefecture->find('first', array('conditions' => array('Prefecture.no' => $pref_id), 'recursive' => -1))){
                    throw new NotFoundException();
                }
                $state = $this->State->find('all', array(
                  'conditions' => array(
                    'State.prefecture_no' => $pref_id
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
                    'City.prefecture_no' => $pref_id,
                    'City.state_no' => $stateIds,
                    'NOT' => array('City.population' => NULL,),
                  ),
                  'order' => array('State.population DESC', 'City.population DESC'),
                  'recursive' => -1,
                ));
                $cityOther = $this->City->find('all', array(
                  'conditions' => array(
                    'City.prefecture_no' => $pref_id,
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
                $retVal['status'] = true;
                $retVal['value'] = $cityArray;
                echo (json_encode($retVal));
                exit();
            }
        }
        throw new NotFoundException();
    }
}
