<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class RegisterController extends AppController { 
    public $components = array('Paginator', 'Search.Prg',);
    public $uses = array(
      'Registration', 
      'Prefecture', 
      'Session',
      'Member',
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
        $this->set('birthday_year', Configure::read("birthday_year_selector"));
        if ($this->request->is('post')){
            /* 応募求人情報post有りでの登録画面リンク時 */
            if (isset($this->request->data['Application'])){
                if ($this->isValidPost($this->request->data['Application']['recruit_sheet_ids'])){
                    $applicationRecruitSheetIds = $this->request->data['Application']['recruit_sheet_ids'];
                    $this->set(compact('applicationRecruitSheetIds'));
                }
            } else {
                /* 登録フォーム入力時 */
                $loggedIn = false;
                if ($this->Auth->loggedIn()){
                    $member = $this->Auth->user();
                    if (!empty($member)){
                        $member = $this->Member->find('first', array('conditions' => array(
                          'Member.id' => $member['id'],
                          'Member.withdraw_flg' => 0,
                          'Member.del_flg' => 0
                        )));
                    }
                    if (!empty($member)){
                        $loggedIn = true;
                    }
                }
                if ($loggedIn){
                    $favorite = $this->findFavorite($member);
                    $histories = $this->findHistory($member);
                } else {
                    $favorite = $this->findSessionFavorite();
                    $histories = $this->findSessionHistory();
                }
                $this->request->data['favorite'] = $favorite;
                $this->request->data['histories'] = $histories;
                if ($data = $this->Registration->saveRegistration($this->request->data)){
                    $this->Flash->success(__('has been saved.'));
                    $application = empty($data['Registration']['recruit_sheet_ids'])? '【案件応募K】' : '';
                    $licence = Configure::read("application_license");
                    $email = new CakeEmail('clickjob');
                    $email->to(EMAIL_ALL);
                    $email->subject($application.'登録がありました。');
                    $email->emailFormat('text');
                    $email->template('registration');
                    $email->viewVars(compact('data', 'licence'));
                    $email->send();
                    return $this->redirect(array('action' => 'thanks'));
                } else {
                    $this->set('cityArray', $this->cityOptions($this->request->data['Registration']['prefecture']));
                    $this->Flash->error(__('has not been saved.'));
                }
            }
        }
        $this->render("form");
    }
    public function thanks(){
        $this->render("thanks");
    }
    private function isValidPost(&$data){
        if (empty($data)){
            return false;
        }
        if (!is_array($data)){
            return false;
        }
        foreach ($data as $i => $id){
            if ($id == 0){
                unset($data[$i]);
                continue;
            }
            if (!$this->isValidRecruitSheet($id)){
                return false;
            }
        }
        return true;
    }
    
}
