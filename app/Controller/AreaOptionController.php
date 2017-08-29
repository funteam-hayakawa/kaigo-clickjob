<?php
App::uses('AppController', 'Controller');

class AreaOptionController extends AppController { 
    public $components = array('Paginator', 'Search.Prg',);
    public $uses = array(
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
                $cityArray = $this->cityOptions($this->request->data['prefecture_id']);
                if (empty($cityArray)){
                    throw new NotFoundException();
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
