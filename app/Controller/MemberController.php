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
        $this->Auth->allow('registration', 'sessionFavorite', 'sessionHistory', 'password_reset');
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
                $m = $this->Auth->user();
                if ($m['pw_reset_token']){
                    $this->Member->clearPWResetToken($m);
                }
                if ($this->request->data['Member']['auto_login_flg']){
                    $autoLoginToken = $this->Member->saveLoginToken($m);
                    if (strlen($autoLoginToken)){
                        $this->Cookie->write('autoLoginToken', $autoLoginToken);
                    }
                } else {
                    $this->Member->clearLoginToken($m);
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
    public function password_reset(){
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
                $this->redirect('/member/mypage');
            }
        }
        /* パスワードリセットフォーム出力 */
        if (isset($this->params['url']['token'])){
            if (!strlen($this->params['url']['token'])){
                throw new NotFoundException();
            }
            $member = $this->Member->find('first', array('conditions' => array(
                'pw_reset_token' => $this->params['url']['token'],
                'del_flg' => false,
            )));
            if (empty($member)){
                throw new NotFoundException();
            }
            $this->set('token', $member['Member']['pw_reset_token']);
            $this->render("password_reset");
            return;
        }
        /* パスワードリセット処理 */
        if ($this->request->is('post') && !empty($this->request->data['Member']['pw_reset_token'])) {
            $member = $this->Member->find('first', array('conditions' => array(
                'pw_reset_token' => $this->request->data['Member']['pw_reset_token'],
                'del_flg' => false,
            )));
            if (empty($member)){
                throw new NotFoundException();
            }
            if (empty($member)){
                $this->Flash->error(__('トークンが無効です。再度トークン発行してください。'));
                $this->redirect('/member/password_reset');
            } else {
                $data = array();
                $data['Member'] = $member['Member'];
                $data['Member']['password'] = $this->request->data['Member']['password'];
                $data['Member']['password_retype'] = $this->request->data['Member']['password_retype'];
                if ($this->Member->updateMemberInfo($data)){
                    $this->Flash->success(__('Your account has been saved.'));
                    $this->redirect('/member/login');
                } else {
                    $this->Flash->error(__('Your account has not been saved.'));
                }
            }
            $this->render("password_reset");
            return;
        }
        /* パスワードリセットURLメール送信処理 */
        if ($this->request->is('post')) {
            $member = $this->Member->find('first', array('conditions' => array(
              'Member.email' => $this->request->data['Member']['email'],
              'Member.birthday_year' => $this->request->data['Member']['birthday_year'],
              'Member.withdraw_flg' => 0,
              'Member.del_flg' => 0
            )));
            if (empty($member)){
                $this->Flash->error(__('メールアドレスが登録されていないか、登録された生年が異なります'));
            } else {
                if (($token = $this->Member->genPWResetToken($member)) !== false) {
                    $this->Flash->success(__('パスワードリセット用URLを送信しました。'));
                    $PWResetURL = Router::url('', true).'?token='.$token;
                    $email = new CakeEmail('clickjob');
                    $email->to($member['Member']['email']);
                    $email->subject('クリックジョブパスワードリセットURL');
                    $email->emailFormat('text');
                    $email->template('members_passwdreset_url');
                    $email->viewVars(compact('PWResetURL'));
                    $email->send();
                    $this->redirect('/member/login');
                } else {
                    $this->Flash->error(__('サーバ内エラーが発生しました。リトライしてください。'));
                }
            }
        }
        /* メールアドレス入力フォーム */
        $this->set('birthday_year', Configure::read("birthday_year_selector"));
        $this->render("password_reset_request");
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
                $this->set('prefectures',$this->Prefecture->find('list', array(
                  'recursive' => -1,
                  'fields' => array('name')
                )));
                if (isset($this->request->data['Member']['prefecture'])){
                    $this->set('cityArray', $this->cityOptions($this->request->data['Member']['prefecture']));
                }
                $this->set('birthday_year', Configure::read("birthday_year_selector"));
                $this->set('license', Configure::read("application_license"));
            }
        } else {
            if (!empty($member)){
                $this->request->data['Member'] = $member['Member'];
                $this->request->data['Member']['license'] = Hash::extract($member['MemberLicense'], '{n}.license');
                unset($this->request->data['Member']['id']);
                unset($this->request->data['Member']['password']);
                unset($this->request->data['Member']['auto_login_token']);
                $this->set('prefectures',$this->Prefecture->find('list', array(
                  'recursive' => -1,
                  'fields' => array('name')
                )));
                if (isset($this->request->data['Member']['prefecture'])){
                    $this->set('cityArray', $this->cityOptions($this->request->data['Member']['prefecture']));
                }
                $this->set('birthday_year', Configure::read("birthday_year_selector"));
                $this->set('license', Configure::read("application_license"));
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
            $this->set('prefectures',$this->Prefecture->find('list', array(
              'recursive' => -1,
              'fields' => array('name')
            )));
            $this->set('birthday_year', Configure::read("birthday_year_selector"));
            $this->set('license', Configure::read("application_license"));
            $this->set('token', $email['MembersMailConfirmTable']['token']);
            $this->render("registration_form");
            return;
        }
        /* 登録処理 */
        if ($this->request->is('post') && !empty($this->request->data['Member']['token'])) {
            $email = $this->MembersMailConfirmTable->find('first', array('conditions' => array(
                'token' => $this->request->data['Member']['token'],
                'del_flg' => false,
            )));
            if (empty($email)){
                $this->Flash->error(__('メンバー登録トークンが無効です。再度メールアドレス登録してください。'));
                $this->redirect('/member/registration');
            } else {
                $data = array();
                $data['Member'] = $this->request->data['Member'];
                $data['Member']['email'] = $email['MembersMailConfirmTable']['email'];
                if ($this->Member->checkAndSave($data)){
                    $this->Flash->success(__('Your account has been saved.'));
                    $this->redirect('/member/login');
                } else {
                    $this->Flash->error(__('Your account has not been saved.'));
                }
            }
            $this->set('prefectures',$this->Prefecture->find('list', array(
              'recursive' => -1,
              'fields' => array('name')
            )));
            if (isset($this->request->data['Member']['prefecture'])){
                $this->set('cityArray', $this->cityOptions($this->request->data['Member']['prefecture']));
            }
            $this->set('birthday_year', Configure::read("birthday_year_selector"));
            $this->set('license', Configure::read("application_license"));
            $this->render("registration_form");
            return;
        }
        /* 登録用URLメール送信処理 */
        if ($this->request->is('post')) {
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
