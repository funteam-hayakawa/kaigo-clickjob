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
                $ret = $this->cityOptions($this->request->data['prefecture_id']);
                if (empty($ret)){
                    throw new NotFoundException();
                }
                $retVal['status'] = true;
                $retVal['value'] = $ret;
                echo (json_encode($retVal));
                exit();
            }
        }
        throw new NotFoundException();
    }
    /* 路線情報 */
    public function line(){
        $retVal = array(
            'status' => false,
            'msg' => '',
            'value' => array(),
        );
        if($this->request->is('ajax')) {
            if (isset($this->request->data['prefecture_id'])){
                $ret = $this->lineOptions($this->request->data['prefecture_id']);
                if (empty($ret)){
                    throw new NotFoundException();
                }
                $retVal['status'] = true;
                $retVal['value'] = $ret;
                echo (json_encode($retVal));
                exit();
            }
        }
        throw new NotFoundException();
    }
    /* 駅情報 */
    public function station(){
        $retVal = array(
            'status' => false,
            'msg' => '',
            'value' => array(),
        );
        if($this->request->is('ajax')) {
            if (isset($this->request->data['line_ids'])){
                $ret = $this->stationOptions($this->request->data['line_ids']);
                if (empty($ret)){
                    throw new NotFoundException();
                }
                $retVal['status'] = true;
                $retVal['value'] = $ret;
                echo (json_encode($retVal));
                exit();
            }
        }
        throw new NotFoundException();
    }
}
