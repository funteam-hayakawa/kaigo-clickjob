<?php
App::uses('AppModel', 'Model');

class City extends AppModel {
    var $useTable='city';
    var $primaryKey = 'no';
    public $belongsTo = array(
        'Prefecture' => array(
            'className'  => 'Prefecture',
            'foreignKey' => 'prefecture_no',
        ),
        'State' => array(
            'className'  => 'State',
            'foreignKey' => 'state_no',
        ),
    );
}
