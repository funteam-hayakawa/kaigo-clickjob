<?php
App::uses('AppModel', 'Model');

class Column extends AppModel {
    var $useTable='seo_kaigo_column';
    var $primaryKey = 'id';
    
    public $actsAs = array('Search.Searchable');
    public $filterArgs = array(
        'word' => array(
            'type' => 'query',
            'empty' => true,
            'method' => NULL
        ),
    );
    
    public $hasMany = array(
        'ColumnContent' => array(
            'className'  => 'ColumnContent',
            'conditions' => array('ColumnContent.del_flg' => 0,
                                  'ColumnContent.level' => 4,
            ),
            'foreignKey' => 'parent_id',
        ),
    );
    public $belongsTo = array(
        'ColumnCategory' => array(
            'className'  => 'ColumnCategory',
            'conditions' => array('ColumnCategory.del_flg' => 0),
            'foreignKey' => 'level1_id',
            'type' => 'INNER',
        ),
        'ColumnHeaderContent' => array(
            'className'  => 'ColumnHeaderContent',
            'conditions' => array('ColumnHeaderContent.del_flg' => 0,
                                  'ColumnHeaderContent.progress' => 2, /* 投稿済 */ 
            ),
            'foreignKey' => 'level4_id',
            'type' => 'INNER',
        ),
    );
}
