<?php
App::uses('AppModel', 'Model');

class Prefecture extends AppModel {
    var $useTable='prefecture';
    var $primaryKey = 'no';
    public $hasMany = array(
        'City' => array(
            'className'  => 'City',
            'foreignKey' => 'prefecture_no',
        ),
        'State' => array(
            'className'  => 'State',
            'foreignKey' => 'prefecture_no',
        ),
    );

}
