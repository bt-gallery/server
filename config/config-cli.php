<?php
return $config = [
    'viewsDir'   => __DIR__ . '/../views/email/',
    'logDir'   => __DIR__ . '/../log/',
    'driver'     => 'sendmail',
    'sendmail'   => '/usr/sbin/sendmail -bs',
    'from'       => [
        'email' => 'risunok@mirtv.ru',
        'name'  => 'Мир24'
    ],
    "database" => [
	    'host'       => '',
	    'username'   => '',
	    'password'   => '',
	    'dbname'     => 'contest'
    ]
];
