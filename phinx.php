<?php
return [
  'paths' => [
    'migrations' => 'migrations'
  ],
  'migration_base_class' => '\App\Migration\Migration',
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => getenv('DB_CONNECTION'),
      'host' => getenv('DB_HOST'),
      'name' => getenv('DB_DATABASE'),
      'user' => getenv('DB_USERNAME'),
      'pass' => getenv('DB_PASSWORD')
    ]
  ]
];