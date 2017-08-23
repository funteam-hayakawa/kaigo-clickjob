<?php
App::uses('AppModel', 'Model');

class ColumnContent extends AppModel {
    var $useTable='seo_kaigo_column_contents';
    var $primaryKey = 'id';
    public $belongsTo = array(
        'ColumnImage' => array(
            'className'  => 'ColumnImage',
            'conditions' => array('ColumnImage.del_flg' => 0),
            'foreignKey' => 'image_id'
        ),
    );
}
