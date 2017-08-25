<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $uses = array(
      'Search', 
      'Area', 
      'Prefecture', 
      'State', 
      'City', 
      'Office', 
      'RecruitSheet', 
      'SeoHeaderText', 
      'SeoFooterText', 
      'RecruitSheetAttention',
    );
    public $components = array(
        'Flash',
        'Auth' => array(
            'loginAction'    => "/member/login/",
            'loginRedirect'  => array('controller' => 'member', 'action' => 'mypage'),
            'logoutRedirect' => "/member/login/",
            'authenticate'   => array(
                'Form' => array(
                    'fields'    => array('username' => 'email', 'password' => 'password'),
                    'userModel' => 'Member',
                    'scope'     => array('Member.withdraw_flg' => 0, 'Member.del_flg' => 0),
                    'passwordHasher' => 'Blowfish'
                )
            )
        ),
        'Security',
    );
    public $commonSearchConditios = array(
      'recruitSheet' => array(
        'RecruitSheet.recruit_public' => 2,
        'RecruitSheet.deleted' => 0,
        'NOT' => array(
          'RecruitSheet.occupation' => array(24, 25, 26, 27, 33, 34, 35, 36, 37, 44, 48, 49, 50, 51, 52, 53, 54, 55, 56, 63)
        ),
      ),
      'office' => array(
        'Office.deleted' => 0,
        'Office.reply_kg_type >=' => 1,
        'Office.reply_kg_type <=' => 2,
        'Office.public' => 1,
        array('OR' => array(
            array("FIND_IN_SET('1', Office.institution_type)"),
            array("FIND_IN_SET('2', Office.institution_type)"),
            array("FIND_IN_SET('3', Office.institution_type)"),
            array("FIND_IN_SET('4', Office.institution_type)"),
            array("FIND_IN_SET('5', Office.institution_type)"),
            array("FIND_IN_SET('6', Office.institution_type)"),
            array("FIND_IN_SET('7', Office.institution_type)"),
            array("FIND_IN_SET('8', Office.institution_type)"),
            array("FIND_IN_SET('10', Office.institution_type)"),
            array("FIND_IN_SET('11', Office.institution_type)"),
            array("FIND_IN_SET('12', Office.institution_type)"),
            array("FIND_IN_SET('14', Office.institution_type)"),
            array("FIND_IN_SET('15', Office.institution_type)"),
            array("FIND_IN_SET('16', Office.institution_type)"),
            array("FIND_IN_SET('19', Office.institution_type)"),
            array("FIND_IN_SET('20', Office.institution_type)"),
            array("FIND_IN_SET('21', Office.institution_type)"),
            array("FIND_IN_SET('22', Office.institution_type)"),
            array("FIND_IN_SET('24', Office.institution_type)"),
            array("FIND_IN_SET('25', Office.institution_type)"),
            array("FIND_IN_SET('26', Office.institution_type)"),
          ),
        ),
      ),
    ); 
    
    /* 人気求人ランキング */
    public function searchRecruitRanking(){
        return $this->RecruitSheetAttention->find('all', array(
          'joins' => array(
              array(
                  'type' => 'INNER',
                  'table' => 'office',
                  'alias' => 'Office',
                  'conditions' => array('`RecruitSheet`.`id` = `Office`.`id`')
              ),
            ),
          'recursive' => 3,
          'conditions' => $this->commonSearchConditios['recruitSheet'] + $this->commonSearchConditios['office'],
          'order' => 'RecruitSheetAttention.number',
        ));
    }
    /* サイドバーの求人数取得 */
    public function getRecruitSheetCount(){
        $rc = array();
        $cond = $this->commonSearchConditios['recruitSheet'];
        $rc['all'] = $this->RecruitSheet->find('count',array('conditions' => $cond));
        $rc['modified'] = $this->RecruitSheet->find('first',array(
          'fields' => "MAX(RecruitSheet.updated) as modified",
          'conditions' => $cond));
        $rc['modified'] = $rc['modified'][0]['modified'];
        $cond['NOT']['OR'] = array(array('RecruitSheet.hw_no' => null), array('RecruitSheet.hw_no' => '')); /* ハロワ */
        $rc['hw'] = $this->RecruitSheet->find('count',array('conditions' => $cond));
        return $rc;
    }
 
    public function beforeFilter() {
        $this->Security->validatePost = false;
        $this->Security->csrfCheck = false;
        if (FORCE_SSL){
            $this->Security->blackHoleCallback = 'forceSSL';
            $this->Security->requireSecure();
        }
    }
    public function forceSSL() {
        return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
    }
}
