<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class InquiryController extends AppController { 
    public $components = array();
    public $uses = array(
      'Inquiry',
      'Prefecture',
      'RecruitSheet'
    );
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function index($id = false){
        if ($this->request->is('post')){
            $id = $this->request->data['Inquiry']['recruit_sheet_id'];
            if (!$this->isValidRecruitSheet($id)){
                throw new NotFoundException();
            }
            if ($inquiryId = $this->Inquiry->saveInquiry($this->request->data)){
                $this->Flash->success(__('has been saved.'));
                $recruitSheet = $this->RecruitSheet->find('first', array(
                  'recursive' => 2,
                  'conditions' => array('RecruitSheet.recruit_sheet_id' => $id),
                ));
                $inquiry = $this->Inquiry->find('first', array('conditions' => array('Inquiry.id' => $inquiryId)));
                $inquiryType = Configure::read("inquiry_type");
                $license = Configure::read("application_license");
                $email = new CakeEmail('clickjob');
                $email->to(EMAIL_ALL);
                $email->subject('お問い合わせがありました。');
                $email->emailFormat('text');
                $email->template('recruitsheet_inquiry');
                $email->viewVars(compact('recruitSheet', 'inquiry', 'license', 'inquiryType'));
                $email->send();
            }
        }
        if (!$this->isValidRecruitSheet($id)){
            throw new NotFoundException();
        }
        $this->set('prefectures',$this->Prefecture->find('list', array(
          'recursive' => -1,
          'fields' => array('name')
        )));
        if (!empty($this->request->data['Inquiry']['prefecture'])){
            $this->set('cityArray', $this->cityOptions($this->request->data['Inquiry']['prefecture']));
        }
        $this->request->data['Inquiry']['recruit_sheet_id'] = $id;
        $this->set('inquiry_type', Configure::read("inquiry_type"));
        $this->set('license', Configure::read("application_license"));
        $this->set('birthdayYearOpt', $this->birthdayYearOptions());
        $this->render("form");
    }
}
