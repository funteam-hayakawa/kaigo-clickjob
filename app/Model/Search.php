<?php
App::uses('AppModel', 'Model');

/* Searchプラグイン用ダミーモデル */

class Search extends AppModel {
    var $useTable=false;
    public $actsAs = array('Search.Searchable');
        
    public $filterArgs = array(
        'prefecture' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
        /* CityとState両方アリ */
        'cities' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
        'occupation' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
        'institution_type' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
        'application_license' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
        'employment_type' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
        'recruit_flex_type' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
        'particular_ttl_hour' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
        'freeword' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
    );
}
