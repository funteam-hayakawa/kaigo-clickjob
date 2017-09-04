<?php
App::uses('AppModel', 'Model');

class SeoKaigoPrefectureText extends AppModel {
    public $belongsTo = array(
      'Prefecture' => array(
          'className'  => 'Prefecture',
          'foreignKey' => 'prefecture_code',
      ),
    );
}
