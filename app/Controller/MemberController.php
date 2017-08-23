<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class MemberController extends AppController { 
    public $components = array('Paginator', 'Search.Prg', 'Cookie', );
    public $uses = array(
      'Member',
      'MembersMailConfirmTable',
      'MembersRecruitsheetAccessHistory',
      'MembersFavoriteRecruitsheets',
      'Search', 
      'Area', 
      'Prefecture', 
      'State', 
      'City', 
      'Office', 
      'RecruitSheet', 
    );
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Cookie->name = 'auto_login';
        $this->Cookie->time = '360 Days';
        $this->Cookie->path = '/member/';
        $this->Cookie->domain = $_SERVER["HTTP_HOST"];
        $this->Cookie->secure = COOKIE_SSL_FLG;
        $this->Cookie->key = COOLIE_ENCRYPT_SALT;
        $this->Cookie->httpOnly = true;
        $this->Auth->allow('registration');
    }
    public function login() {
        $this->response->disableCache();
        if ($this->Auth->loggedIn()) {
            $this->redirect('/member/mypage');
        }
        if ($this->request->is('post')) {            
            if ($this->Auth->login()) {
                if ($this->request->data['Member']['auto_login_flg']){
                    $autoLoginToken = $this->Member->saveLoginToken($this->Auth->user());
                    if (strlen($autoLoginToken)){
                        $this->Cookie->write('autoLoginToken', $autoLoginToken);
                    }
                } else {
                    $this->Member->clearLoginToken($this->Auth->user());
                    if ($this->Cookie->check('autoLoginToken')){
                        $token = $this->Cookie->destroy('autoLoginToken');
                    }
                }
                $this->redirect('/member/mypage');
            } else {
                $this->Flash->error(__('The member could not be login'));
            }
        }
        if ($this->Cookie->check('autoLoginToken')){
            $token = $this->Cookie->read('autoLoginToken');
            $member = array();
            if (strlen($token)){
                $member = $this->Member->find('first', array('conditions' => array(
                  'Member.auto_login_token' => $token,
                  'Member.withdraw_flg' => 0,
                  'Member.del_flg' => 0,
                )));
            }
            if (!empty($member)){
                unset($member['Member']['password']);
                $autoLoginToken = $this->Member->saveLoginToken($member['Member']);
                if (strlen($autoLoginToken)){
                    $this->Cookie->write('autoLoginToken', $autoLoginToken);
                    $this->Auth->login($member['Member']);
                    $this->redirect('/member/mypage');
                }
            }
        }
    }
    public function logout(){
        $this->response->disableCache();
        $this->Member->clearLoginToken($this->Auth->user());
        if ($this->Cookie->check('autoLoginToken')){
            $token = $this->Cookie->destroy('autoLoginToken');
        }
        $this->redirect($this->Auth->logout());
    }
    public function favorite(){
        $retval = array(
            'status' => false,
            'msg' => '',
        );
        if($this->request->is('ajax')) {
            $member = $this->Auth->user();
            if (!empty($member)){
                $member = $this->Member->find('first', array('conditions' => array(
                  'Member.id' => $member['id'],
                  'Member.withdraw_flg' => 0,
                  'Member.del_flg' => 0
                )));
            }
            if (!empty($member) && isset($this->request->data['recruit_sheet_id']) && isset($this->request->data['favorite_flg'])){
                $saveData = $this->MembersFavoriteRecruitsheets->find('first', array('conditions' => array(
                  'MembersFavoriteRecruitsheets.member_id' => $member['Member']['id'],
                  'MembersFavoriteRecruitsheets.recruit_sheet_id' => $this->request->data['recruit_sheet_id'],
                )));
                if (empty($saveData)){
                    $saveData = array('MembersFavoriteRecruitsheets' => array(
                      'member_id' => $member['Member']['id'],
                      'recruit_sheet_id' => $this->request->data['recruit_sheet_id'],
                    ));
                } else {
                    if ($saveData['MembersFavoriteRecruitsheets']['favorite_flg'] == $this->request->data['favorite_flg']){
                        if ($this->request->data['favorite_flg']){
                            $retval['status'] = true;
                            $retval['msg'] = '既にお気に入りに登録済です';
                            echo (json_encode($retval));
                        } else {
                            $retval['status'] = true;
                            $retval['msg'] = 'お気に入り登録されていません';
                            echo (json_encode($retval));
                        }
                        exit();
                    }
                }
                if ($this->request->data['favorite_flg']){
                    $favoriteCnt = $this->MembersFavoriteRecruitsheets->find('count', array(
                      'conditions' => array(
                        'MembersFavoriteRecruitsheets.favorite_flg' => 1,
                        'MembersFavoriteRecruitsheets.member_id' => $member['Member']['id'],
                      ),
                    ));
                    if ($favoriteCnt >= 20){
                        $retval['status'] = true;
                        $retval['msg'] = 'お気に入り登録は最大20件までです。お気に入り求人を削除してください。';
                        echo (json_encode($retval));
                        exit();
                    }
                }
                $saveData['MembersFavoriteRecruitsheets']['favorite_flg'] = $this->request->data['favorite_flg'];
                unset($saveData['MembersFavoriteRecruitsheets']['modified']);
                if ($this->MembersFavoriteRecruitsheets->save($saveData)){
                    $retval['status'] = true;
                    if ($this->request->data['favorite_flg']){
                      $retval['msg'] = 'お気に入りに追加しました';
                    } else {
                      $retval['msg'] = 'お気に入りから削除しました';
                    }
                    echo (json_encode($retval));
                } else {
                    $retval['status'] = false;
                    $retval['msg'] = 'DBの更新に失敗しました';
                    echo (json_encode($retval));
                }
                exit();
            } else {
                $this->logout();
            }
        }
        $this->logout();
    }
    public function mypage(){
        $this->response->disableCache();
        $member = $this->Auth->user();
        if (!empty($member)){
            $member = $this->Member->find('first', array('conditions' => array(
              'Member.id' => $member['id'],
              'Member.withdraw_flg' => 0,
              'Member.del_flg' => 0
            )));
        }
        if (!empty($member)){
            $histories = $this->MembersRecruitsheetAccessHistory->find('all', array(
              'conditions' => array(
                'MembersRecruitsheetAccessHistory.member_id' => $member['Member']['id'],
              ),
              'order' => 'MembersRecruitsheetAccessHistory.modified DESC',
              'limit' => 20,
              'recursive' => 3,
            ));
            $favorite = $this->MembersFavoriteRecruitsheets->find('all', array(
              'conditions' => array(
                'MembersFavoriteRecruitsheets.favorite_flg' => 1,
                'MembersFavoriteRecruitsheets.member_id' => $member['Member']['id'],
              ),
              'order' => 'MembersFavoriteRecruitsheets.modified DESC',
              'limit' => 20,
              'recursive' => 3,
            ));
            $this->set('employment_type', Configure::read("employment_type"));
            $this->set(compact('histories', 'favorite'));
        } else {
            $this->logout();
        }
    }
    public function edit(){
        $this->response->disableCache();
        $member = $this->Auth->user();
        if (!empty($member)){
            $member = $this->Member->find('first', array('conditions' => array(
              'Member.id' => $member['id'],
              'Member.withdraw_flg' => 0,
              'Member.del_flg' => 0
            )));
        }
        if ($this->request->is('post')) {
            $saveData = array();
            $saveData['Member'] = $this->request->data['Member'];
            $saveData['Member']['id'] = $member['Member']['id'];
            if ($member['Member']['email'] === $this->request->data['Member']['email']){
                unset($saveData['Member']['email']);
            }
            if ($this->Member->updateMemberInfo($saveData)){
                $this->Flash->success(__('Your account has been saved.'));
                return $this->redirect(array('action' => 'edit'));
            } else {
                $this->Flash->error(__('Your account has not been saved.'));
            }
        } else {
            if (!empty($member)){
                $this->request->data['Member'] = array(
                  'email' => $member['Member']['email'],
                );
            } else {
                $this->logout();
            }
        }
    }
    public function registration(){
        /* 登録用フォーム出力 */
        if (isset($this->params['url']['token'])){
            $email = $this->MembersMailConfirmTable->find('first', array('conditions' => array(
                'token' => $this->params['url']['token'],
                'del_flg' => false,
            )));
            if (empty($email)){
                throw new NotFoundException();
            }
            $this->set('license', Configure::read("application_license"));
            $this->set('token', $email['MembersMailConfirmTable']['token']);
            $this->render("registration_form");
            return;
        }
        /* 登録処理 */
        if ($this->request->is('post') && !empty($this->request->data['Member']['token'])) {
            //pr($this->request->data);
            
            $email = $this->MembersMailConfirmTable->find('first', array('conditions' => array(
                'token' => $this->request->data['Member']['token'],
                'del_flg' => false,
            )));
            if (empty($email)){
                $this->Flash->error(__('Your account has not been saved.'));
            } else {
                $data = array(
                    'Member' => array(
                      'email' => $email['MembersMailConfirmTable']['email'],
                      'password' => $this->request->data['Member']['password'],
                    )
                );
                if ($this->Member->checkAndSave($data)){
                    $this->Flash->success(__('Your account has been saved.'));
                } else {
                    $this->Flash->error(__('Your account has not been saved.'));
                }
            }
            $this->set('license', Configure::read("application_license"));
            $this->render("registration_form");
            return;
        }
        /* 登録用URLメール送信処理 */
        if ($this->request->is('post')) {
            $this->MembersMailConfirmTable->create();
            $token = '';
            $this->request->data['MembersMailConfirmTable']['token'] = $token;
            if (($token = $this->MembersMailConfirmTable->checkAndSave($this->request->data)) !== false) {
                $this->Flash->success(__('Your email has been saved.'));
                $registrationURL = Router::url('', true).'?token='.$token;
                $email = new CakeEmail('clickjob');
                $email->to($this->request->data['MembersMailConfirmTable']['email']);
                $email->subject('クリックジョブメンバー登録URL');
                $email->emailFormat('text');
                $email->template('members_registration_url');
                $email->viewVars(compact('registrationURL'));
                $email->send();
            } else {
                $this->Flash->error(__('Unable to add your email.'));
            }
        }
        /* メールアドレス入力フォーム */
        $this->render("mail_confirmation");
    }
}
