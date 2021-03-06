<?php
App::uses('AppModel', 'Model');

class SeoHeaderText extends AppModel {
    var $useTable='seo_text';
    var $primaryKey = 'id';
    public $belongsTo = array(
        'Prefecture' => array(
            'className'  => 'Prefecture',
            'foreignKey' => 'prefecture_code',
        ),
        'State' => array(
            'className'  => 'State',
            'foreignKey' => 'state_code',
        ),
        'City' => array(
            'className'  => 'City',
            'foreignKey' => 'city_code',
        ),
    );
}
