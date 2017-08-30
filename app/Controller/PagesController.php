<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }
/**
 * This controller does not use a model
 *
 * @var array
 */
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
