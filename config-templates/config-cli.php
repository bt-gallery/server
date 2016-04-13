<?php
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__.('/../../current')); 
}

return $config = [
    'viewsDir'   => APP_PATH . '/views/email/',
    'logDir'   => APP_PATH . '/log/',
    'driver'     => 'sendmail',
    'sendmail'   => '/usr/sbin/sendmail -bs',
    'from'       => [
        'email' => 'foto1945@mirtv.ru',
        'name'  => 'Мир24'
    ],
    "database" => [
        'host'       => 'localhost',
        'username'   => 'contestuser',
        'password'   => 'userpass',
        'dbname'     => 'photo1945'
    ]
];
