<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public $uses = array();

    public function display() {
        $this->set('recruit_flex_type', Configure::read("recruit_flex_type"));
        $this->set('area',$this->Area->find('all'));
        $this->set('recruitSheetCount',$this->getRecruitSheetCount());
        $this->set('ranking',$this->searchRecruitRanking());
        $this->set('highIncome',$this->searchRecruitHighIncome());
        $this->render("display_top");
    }
    public function image() {
        $filepath = Router::url();
        $len = strlen($filepath);
        $filepath = substr($filepath, 6, $len);
        $this->layout = false;
        $this->render(false);
        $imgFile = COMMON_FILE_PATH . $filepath;
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($imgFile);
        header('Content-type: ' . $mimeType . '; charset=UTF-8');
        readfile($imgFile);
    }
}
