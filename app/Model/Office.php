<?php
App::uses('AppModel', 'Model');

class Office extends AppModel {
    var $useTable='office';
    var $primaryKey = 'id';
    public $hasMany = array(
        'RecruitSheet' => array(
            'className'  => 'RecruitSheet',
            'conditions' => array('RecruitSheet.deleted' => 0),
            'foreignKey' => 'id',
        ),
        'OfficeStation' => array(
            'className'  => 'OfficeStation',
            'conditions' => array('OfficeStation.deleted' => 0),
            'foreignKey' => 'office_id',
        ),
    );
    public $hasOne = array(
        'OfficeImage' => array(
            'className'  => 'OfficeImage',
            'conditions' => array('OfficeImage.deleted' => 0),
            'foreignKey' => 'id',
        ),
        'OfficeInfo' => array(
            'className'  => 'OfficeInfo',
            'conditions' => array('OfficeInfo.deleted' => 0),
            'foreignKey' => 'id',
        ),
    );
    public $belongsTo = array(
        'Prefecture' => array(
            'className'  => 'Prefecture',
            'foreignKey' => 'prefecture',
        ),
        'State' => array(
            'className'  => 'State',
            'foreignKey' => 'cities',
        ),
        'City' => array(
            'className'  => 'City',
            'foreignKey' => 'cities',
        ),
    );
}
