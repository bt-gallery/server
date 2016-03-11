<?php
if(!defined('APP_PATH')) define('APP_PATH',__DIR__.('/..'));

return new \Phalcon\Config(array(

    'database' => array(
        'adapter'    => 'Mysql',
        'host'       => '',
        'username'   => '',
        'password'   => '',
        'dbname'     => 'contest',
    ),

    'application' => array(
        'modelsDir'      => APP_PATH . '/models/',
        'viewsDir'       => APP_PATH . '/views/',
        'uploadDir'       => APP_PATH . '/public/files/',
        'baseUri'        => '/konkurs/',
    )
));
