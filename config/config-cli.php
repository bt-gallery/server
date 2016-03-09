<?php
return $config = [
    'viewsDir'   => __DIR__ . '/../views/email/',
    'logDir'   => __DIR__ . '/../log/',
    'driver'     => 'sendmail',
    'sendmail'   => '/usr/sbin/sendmail -bs',
    'from'       => [
        'email' => 'example@gmail.com',
        'name'  => 'Конкурс рисунка, Мир24'
    ]
];
