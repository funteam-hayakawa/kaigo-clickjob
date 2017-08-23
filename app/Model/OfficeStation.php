<?php
App::uses('AppModel', 'Model');

class OfficeStation extends AppModel {
    var $useTable='station_office';
    var $primaryKey = 'id';
    public $belongsTo = array(
        'Office' => array(
            'className'  => 'Office',
            'conditions' => array('Office.deleted' => 0),
            'foreignKey' => 'id',
        ),
    );
}
