<?php
if(!defined('APP_PATH')) define('APP_PATH',__DIR__.('/..'));

return new \Phalcon\Config(array(

    'database' => array(
        'adapter'    => 'Mysql',
        'host'       => '192.168.250.2',
        'username'   => 'contestuser',
        'password'   => 'wK3ErJ9dcMCUyxDm',
        'dbname'     => 'contest',
    ),

    'application' => array(
        'modelsDir'      => APP_PATH . '/models/',
        'viewsDir'       => APP_PATH . '/views/',
        'uploadDir'       => APP_PATH . '/public/files/',
        'baseUri'        => '/konkurs/',
    )
));
