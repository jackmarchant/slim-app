<?php

return [
    'settings' => [
        // ini config
        'displayErrorDetails' => getenv('DISPLAY_ERRORS'),
        
        // renderer
        'renderer' => [
            'template_path' => 'src/app/templates'
        ],

        // database
        'db' => [
            'driver'    => getenv('DB_CONNECTION'),
            'host'      => getenv('DB_HOST'),
            'database'  => getenv('DB_DATABASE'),
            'username'  => getenv('DB_USERNAME'),
            'password'  => getenv('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]
    ]
];