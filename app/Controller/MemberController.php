<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class MemberController extends AppController { 
    public $components = array('Cookie', 'Session');
    public $uses = array(
      'Member',
      'MembersMailConfirmTable',
      'MembersRecruitsheetAccessHistory',
      'MembersFavoriteRecruitsheets',
      'Area', 
      'Prefecture', 
      'State', 
      'City', 
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
        $this->Auth->allow('registration', 'sessionFavorite', 'sessionHistory');
    }
    public function index(){
        $this->response->disableCache();
        if ($this->Auth->loggedIn()) {
            $this->redirect('/member/mypage');
        }
        $this->logout();
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
            $histories = $this->findHistory($member);
            $favorite = $this->findFavorite($member);
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
            if (!strlen($this->params['url']['token'])){
                throw new NotFoundException();
            }
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
    /* お気に入りリストページ、メンバー用 */
    public function memberFavorite(){
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
            $favorite = $this->findFavorite($member);
            $this->set('employment_type', Configure::read("employment_type"));
            $this->set(compact('favorite'));
            $this->set('loggedIn', true);
        } else {
            $this->Member->clearLoginToken($this->Auth->user());
            if ($this->Cookie->check('autoLoginToken')){
                $token = $this->Cookie->destroy('autoLoginToken');
            }
            $this->Auth->logout();
        }
        $this->render("favorite");
    }
    /* 閲覧履歴ページ、メンバー用 */
    public function memberHistory(){
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
            $histories = $this->findHistory($member);
            $this->set('employment_type', Configure::read("employment_type"));
            $this->set(compact('histories'));
            $this->set('loggedIn', true);
        } else {
            $this->Member->clearLoginToken($this->Auth->user());
            if ($this->Cookie->check('autoLoginToken')){
                $token = $this->Cookie->destroy('autoLoginToken');
            }
            $this->Auth->logout();
        }
        $this->render("history");
    }
    /* お気に入りリストページ、非メンバー用、セッションで管理 */
    public function sessionFavorite(){
        $this->response->disableCache();
        /* ログインチェック、ログイン済ならメンバー用URLにリダイレクト */
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
                $this->redirect('/member/favorite');
            }
        }
        $favorite = $this->findSessionFavorite();
        $this->set('employment_type', Configure::read("employment_type"));
        $this->set(compact('favorite'));
        $this->render("favorite");
    }
    /* 閲覧履歴ページ、非メンバー用、セッションで管理 */
    public function sessionHistory(){
        $this->response->disableCache();
        /* ログインチェック、ログイン済ならメンバー用URLにリダイレクト */
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
                $this->redirect('/member/history');
            }
        }
        $histories = $this->findSessionHistory();
        $this->set('employment_type', Configure::read("employment_type"));
        $this->set(compact('histories'));
        $this->render("history");
    }
}
