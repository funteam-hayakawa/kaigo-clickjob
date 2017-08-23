<?php
App::uses('AppModel', 'Model');

class State extends AppModel {
    var $useTable='state';
    var $primaryKey = 'no';
    public $belongsTo = array(
        'Prefecture' => array(
            'className'  => 'Prefecture',
            'foreignKey' => 'prefecture_no',
        ),
    );
    public $hasMany = array(
        'City' => array(
            'className'  => 'City',
            'foreignKey' => 'state_no',
        ),
    );
}
