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
        
        if ($this->request->is('post')){
            if ($this->Registration->saveRegistration($this->request->data)){
                $this->Flash->success(__('has been saved.'));
                return $this->redirect(array('action' => 'thanks'));
            } else {
                $this->Flash->error(__('has not been saved.'));
            }
        }
        $this->render("form");
    }
    public function thanks(){
        $this->render("thanks");
    }
    
}
