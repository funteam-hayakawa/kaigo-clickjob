<?php
App::uses('AppModel', 'Model');

class MembersRecruitsheetAccessHistory extends AppModel {
    public $belongsTo = array(
        'RecruitSheet' => array(
            'className'  => 'RecruitSheet',
            'conditions' => array(
              'RecruitSheet.deleted' => 0,
              'RecruitSheet.recruit_public' => 2,
              'NOT' => array(
                'RecruitSheet.occupation' => array(24, 25, 26, 27, 33, 34, 35, 36, 37, 44, 48, 49, 50, 51, 52, 53, 54, 55, 56, 63)
              ),
            ),
            'foreignKey' => 'recruit_sheet_id',
            'type' => 'INNER',
        ),
    );
}
