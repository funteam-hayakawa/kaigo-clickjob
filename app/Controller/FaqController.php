<?php

App::uses('AppController', 'Controller');

class FaqController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function index() {
        $this->layout = '';
        $this->render("faq");
    }
}
