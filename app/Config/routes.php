<?php

Router::connect('/pages', array('controller' => '', 'action' => ''));
Router::connect('/pages/*', array('controller' => '', 'action' => ''));
Router::connect('/app', array('controller' => '', 'action' => ''));
Router::connect('/app/*', array('controller' => '', 'action' => ''));

Router::connect('/read/*',array('controller' => 'pages', 'action' => 'image'));
Router::connect('/', array('controller' => 'pages', 'action' => 'display'));

Router::connect('/area', array('controller' => 'search', 'action' => 'area_idx'));
Router::connect('/area/*', array('controller' => 'search', 'action' => 'area'));

Router::connect('/feature', array('controller' => 'search', 'action' => 'feature_idx'));
Router::connect('/feature/*', array('controller' => 'search', 'action' => 'feature'));


Router::connect('/search/*', array('controller' => 'search', 'action' => 'result'));
Router::connect('/detail/:id', 
  array('controller' => 'search', 'action' => 'detail'),
  array('pass' => array('id'), 'id' => '[0-9]+')
);
Router::connect('/inquiry/:id', 
  array('controller' => 'inquiry', 'action' => 'index'),
  array('pass' => array('id'), 'id' => '[0-9]+')
);

Router::connect('/member/favorite', array('controller' => 'member', 'action' => 'memberFavorite'));
Router::connect('/member/history', array('controller' => 'member', 'action' => 'memberHistory'));
Router::connect('/favorite', array('controller' => 'member', 'action' => 'sessionFavorite'));
Router::connect('/history', array('controller' => 'member', 'action' => 'sessionHistory'));

Router::connect('/column/:category',
  array('controller' => 'column', 'action' => 'index'),
  array('pass' => array('category'), 'category' => 'news|column|knowledge')
);
Router::connect('/column/detail/:id', 
  array('controller' => 'column', 'action' => 'detail'),
  array('pass' => array('id'), 'id' => '[0-9]+')
);
Router::connect('/column/*', array('controller' => 'column', 'action' => 'index'));

CakePlugin::routes();

require CAKE . 'Config' . DS . 'routes.php';
