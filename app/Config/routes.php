<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
 

Router::connect('/read/*',array('controller' => 'pages', 'action' => 'image'));
 
Router::connect('/', array('controller' => 'pages', 'action' => 'display'));
/**
* ...and connect the rest of 'Pages' controller's URLs.
*/
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

Router::connect('/area', array('controller' => 'search', 'action' => 'area_idx'));
Router::connect('/area/*', array('controller' => 'search', 'action' => 'area'));

Router::connect('/search/*', array('controller' => 'search', 'action' => 'result'));
Router::connect('/detail/:id', 
  array('controller' => 'search', 'action' => 'detail'),
  array('pass' => array('id'), 'id' => '[0-9]+')
);

Router::connect('/column/:category',
  array('controller' => 'column', 'action' => 'index'),
  array('pass' => array('category'), 'category' => 'news|column|knowledge')
);
Router::connect('/column/detail/:id', 
  array('controller' => 'column', 'action' => 'detail'),
  array('pass' => array('id'), 'id' => '[0-9]+')
);
Router::connect('/column/*', array('controller' => 'column', 'action' => 'index'));
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
  CakePlugin::routes();

  require CAKE . 'Config' . DS . 'routes.php';
