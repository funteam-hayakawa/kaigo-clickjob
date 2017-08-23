<?php
App::uses('AppModel', 'Model');

class OfficeInfo extends AppModel {
    var $useTable='public_info';
    var $primaryKey = 'id';
    public $hasOne = array(
        'Office' => array(
            'className'  => 'Office',
            'conditions' => array('Office.deleted' => 0),
            'foreignKey' => 'id',
        ),
    );
}
