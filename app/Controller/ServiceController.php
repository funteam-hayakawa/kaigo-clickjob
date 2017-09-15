<?php

App::uses('AppController', 'Controller');

class ServiceController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function index(){
        $this->redirect('/service/about');
    }

    public function about(){
        $this->render("about");
    }

    public function flow() {
        $this->layout = '';
        $this->render("flow");
    }
}
