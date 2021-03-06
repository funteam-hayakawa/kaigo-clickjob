<?php
App::uses('AppModel', 'Model');

class MembersRecruitsheetAccessHistory extends AppModel {
    public $belongsTo = array(
        'RecruitSheet' => array(
            'className'  => 'RecruitSheet',
            'conditions' => array(
              'RecruitSheet.deleted' => 0,
            ),
            'foreignKey' => 'recruit_sheet_id',
            'type' => 'INNER',
        ),
    );
}
