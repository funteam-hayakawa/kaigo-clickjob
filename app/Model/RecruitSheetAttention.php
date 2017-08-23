<?php
App::uses('AppModel', 'Model');

class RecruitSheetAttention extends AppModel {
    var $useTable='recruit_sheet_attention';
    var $primaryKey = 'number';

    public $belongsTo = array(
        'RecruitSheet' => array(
            'className'  => 'RecruitSheet',
            'conditions' => array('RecruitSheet.deleted' => 0),
            'foreignKey' => 'id',
            'type' => 'INNER',
        ),
    );
}
