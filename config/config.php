<?php
if(!defined('APP_PATH')) define('APP_PATH',__DIR__.('/..'));

return new \Phalcon\Config(array(

    'database' => array(
        'adapter'    => 'Mysql',
        'host'       => 'localhost',
        'username'   => 'root',
        'password'   => 'VfrcGkfyr1MS',
        'dbname'     => 'contest',
    ),

    'application' => array(
        'modelsDir'      => APP_PATH . '/models/',
        'viewsDir'       => APP_PATH . '/views/',
        'uploadDir'       => APP_PATH . '/public/files/',
        'baseUri'        => '/konkurs/',
    )
));
