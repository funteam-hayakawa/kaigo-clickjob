<?php
App::uses('AppController', 'Controller');

class RegisterController extends AppController { 
    public $components = array('Paginator', 'Search.Prg',);
    public $uses = array(
      'Registration', 
      'Prefecture', 
    );
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function index(){
        $this->set('prefectures',$this->Prefecture->find('list', array(
          'recursive' => -1,
          'fields' => array('name')
        )));
        $this->set('license', Configure::read("application_license"));
        $this->render("form");
    }
    
}
