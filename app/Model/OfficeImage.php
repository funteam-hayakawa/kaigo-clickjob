<?php
App::uses('AppModel', 'Model');

class OfficeImage extends AppModel {
    var $useTable='uploaded_image';
    var $primaryKey = 'id';
    public $hasOne = array(
        'Office' => array(
            'className'  => 'Office',
            'conditions' => array('Office.deleted' => 0),
            'foreignKey' => 'id',
        ),
    );
}
