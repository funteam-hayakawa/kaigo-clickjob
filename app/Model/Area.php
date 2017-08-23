<?php
App::uses('AppModel', 'Model');

class Area extends AppModel {
    var $useTable='area';
    var $primaryKey = 'no';
    public $hasMany = array(
        'Prefecture' => array(
            'className'  => 'Prefecture',
            'foreignKey' => 'area_no',
        ),
    );

}
