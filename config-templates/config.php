<?php
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__.('/../../current'));
}

return new \Phalcon\Config(
    array(

    'database' => array(
        'adapter'    => 'Mysql',
        'host'       => 'localhost',
        'username'   => 'contest_user',
        'password'   => 'userpass',
        'dbname'     => 'contest'
    ),

    'application' => array(
        'modelsDir'      => APP_PATH . '/models/',
        'viewsDir'       => APP_PATH . '/views/',
        'uploadDir'       => APP_PATH . '/public/files/',
        'logDir'       => APP_PATH . '/log/',
        'baseUri'        => '/konkurs/',
    )
    )
);
