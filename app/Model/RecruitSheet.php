<?php
App::uses('AppModel', 'Model');

class RecruitSheet extends AppModel {
    var $useTable='recruit_sheet';
    var $primaryKey = 'recruit_sheet_id';
    
    public $belongsTo = array(
        'Office' => array(
            'className'  => 'Office',
            'conditions' => array('Office.deleted' => 0),
            'foreignKey' => 'id',
            'type' => 'INNER',
        ),
    );
}
