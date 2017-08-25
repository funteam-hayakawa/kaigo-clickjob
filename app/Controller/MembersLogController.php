<?php
App::uses('AppController', 'Controller');

class MembersLogController extends AppController { 
    public $components = array('Session');
    public $uses = array(
      'Member',
      'MembersRecruitsheetAccessHistory',
      'MembersFavoriteRecruitsheets',
      'RecruitSheet', 
    );
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }
    public function favorite(){
        $retval = array(
            'status' => false,
            'msg' => '',
        );
        if($this->request->is('ajax')) {
            /* 無効な求人idがpostされたら弾く */
            if (isset($this->request->data['recruit_sheet_id'])){
                if (!$this->isValidRecruitSheet($this->request->data['recruit_sheet_id'])){
                    unset($this->request->data['recruit_sheet_id']);
                }
            }
            /* メンバーログイン済みの場合はDB格納 */
            if ($this->Auth->loggedIn()){
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
                          ) + $this->commonSearchConditios['recruitSheet'] +
                          $this->commonSearchConditios['office'],
                          'joins' => array(
                             array(
                                 'type' => 'INNER',
                                 'table' => 'office',
                                 'alias' => 'Office',
                                 'conditions' =>  array('`RecruitSheet`.`id` = `Office`.`id`')
                             ),
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
                    $retval['status'] = false;
                    $retval['msg'] = '不正な情報がpostされました';
                    echo (json_encode($retval));
                    $this->Member->clearLoginToken($this->Auth->user());
                    $this->Auth->logout();
                    exit();
                }
            } else {
              /* メンバーログインしてない場合はセッション保存 */
                if (isset($this->request->data['recruit_sheet_id'])){
                    $id = $this->request->data['recruit_sheet_id'];
                    $flg = $this->request->data['favorite_flg'];
                    $saved = array();
                    if ($this->Session->check('favorite')){
                        $saved = $this->Session->read('favorite');
                    }
                    foreach ($saved as $i => $time){
                        if (!$this->isValidRecruitSheet($i)){
                            unset($saved[$i]);
                            $this->Session->delete('favorite.'.$i);
                        }
                    }
                    if ($flg){
                        if (isset($saved[$id])){
                            $retval['status'] = true;
                            $retval['msg'] = '既にお気に入りに登録済です';
                            echo (json_encode($retval));
                        } else {
                            if (count($saved) >= 20){
                                $retval['status'] = true;
                                $retval['msg'] = 'お気に入り登録は最大20件までです。お気に入り求人を削除してください。';
                                echo (json_encode($retval));
                                exit();
                            } else {
                                $this->Session->write('favorite.'.$id, date('Y-m-d H:i:s'));
                                $retval['status'] = true;
                                $retval['msg'] = 'お気に入りに追加しました';
                                echo (json_encode($retval));
                            }
                        }
                    } else {
                        if (isset($saved[$id])){
                            $retval['status'] = true;
                            $retval['msg'] = 'お気に入りから削除しました';
                            $this->Session->delete('favorite.'.$id);
                            echo (json_encode($retval));
                        } else {
                            $retval['status'] = true;
                            $retval['msg'] = 'お気に入り登録されていません';
                            echo (json_encode($retval));
                        }
                    }
                    exit();
                }
                $retval['status'] = false;
                $retval['msg'] = '不正な情報がpostされました';
                echo (json_encode($retval));
                exit();
            }
        }
        if ($this->Auth->loggedIn()){
            $this->Member->clearLoginToken($this->Auth->user());
            $this->Auth->logout();
        }
        exit();
    }
    public function visitlog(){
        $retval = array(
            'status' => false,
            'msg' => '',
        );
        if($this->request->is('ajax')) {
            /* 無効な求人idがpostされたら弾く */
            if (isset($this->request->data['recruit_sheet_id'])){
                if (!$this->isValidRecruitSheet($this->request->data['recruit_sheet_id'])){
                    unset($this->request->data['recruit_sheet_id']);
                }
            }
            /* メンバーログイン済みの場合はDB格納 */
            if ($this->Auth->loggedIn()){
                $member = $this->Auth->user();
                if (!empty($member)){
                    $member = $this->Member->find('first', array('conditions' => array(
                      'Member.id' => $member['id'],
                      'Member.withdraw_flg' => 0,
                      'Member.del_flg' => 0
                    )));
                }
                if (!empty($member) && isset($this->request->data['recruit_sheet_id'])){
                    $saveData = $this->MembersRecruitsheetAccessHistory->find('first', array('conditions' => array(
                      'MembersRecruitsheetAccessHistory.member_id' => $member['Member']['id'],
                      'MembersRecruitsheetAccessHistory.recruit_sheet_id' => $this->request->data['recruit_sheet_id'],
                    )));
                    if (empty($saveData)){
                        $saveData = array('MembersRecruitsheetAccessHistory' => array(
                            'member_id' => $member['Member']['id'],
                            'recruit_sheet_id' => $this->request->data['recruit_sheet_id'],
                        ));
                    }
                    unset($saveData['MembersRecruitsheetAccessHistory']['modified']);
                    if ($this->MembersRecruitsheetAccessHistory->save($saveData)){
                        //$retval['status'] = true;
                        //$retval['msg'] = '閲覧履歴を更新しました';
                        //echo (json_encode($retval));
                    } else {
                        //$retval['status'] = false;
                        //$retval['msg'] = 'DBの更新に失敗しました';
                        //echo (json_encode($retval));
                    }
                    exit();
                } else {
                    //$retval['status'] = false;
                    //$retval['msg'] = '不正な情報がpostされました';
                    //echo (json_encode($retval));
                    $this->Member->clearLoginToken($this->Auth->user());
                    $this->Auth->logout();
                    exit();
                }
            } else {
              /* メンバーログインしてない場合はセッション保存 */
                if (isset($this->request->data['recruit_sheet_id'])){
                    $id = $this->request->data['recruit_sheet_id'];
                    $saved = array();
                    if ($this->Session->check('history')){
                        $saved = $this->Session->read('history');
                    }
                    foreach ($saved as $i => $time){
                        if (!$this->isValidRecruitSheet($i)){
                            unset($saved[$i]);
                            $this->Session->delete('history.'.$i);
                        }
                    }
                    $this->Session->write('history.'.$id, date('Y-m-d H:i:s'));
                    $saved = $this->Session->read('history');
                    /* 古い順にソート */
                    uasort($saved, function ($a, $b){
                        return strtotime($a) - strtotime($b);
                    });
                    $ct = count($saved);
                    $del = array();
                    /* 20件を超えた履歴はセッションから削除 */
                    if ($ct > 20){
                        $del = array_slice($saved, 0, $ct - 20, true);
                    }
                    foreach ($del as $i => $t){
                        $this->Session->delete('history.'.$i);
                    }
                    //$retval['status'] = true;
                    //$retval['msg'] = '閲覧履歴を更新しました';
                    //echo (json_encode($retval));
                    exit();
                }
                //$retval['status'] = false;
                //$retval['msg'] = '不正な情報がpostされました';
                //echo (json_encode($retval));
                exit();
            }
        }
        if ($this->Auth->loggedIn()){
            $this->Member->clearLoginToken($this->Auth->user());
            $this->Auth->logout();
        }
        exit();
    }
}
