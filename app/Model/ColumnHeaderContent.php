<?php
App::uses('AppModel', 'Model');

class ColumnHeaderContent extends AppModel {
    var $useTable='seo_kaigo_column_level4';
    var $primaryKey = 'id';
    
    public $belongsTo = array(
        'ColumnImage' => array(
            'className'  => 'ColumnImage',
            'conditions' => array('ColumnImage.del_flg' => 0),
            'foreignKey' => 'image_id'
        ),
    );
}
