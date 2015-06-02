<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    */

    'default' => env('CACHE_DRIVER', 'memcached'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    */

    'stores' => [

        'array' => [
            'driver' => 'array'
        ],

        'database' => [
            'driver' => 'database',
            'table'  => env('CACHE_DATABASE_TABLE', 'cache'),
            'connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path'   => storage_path('framework/cache'),
        ],

        'memcached' => [
            'driver'  => 'memcached',
            'persistent_id' => 'laravel',
            'sasl'       => [
                env('MEMCACHEDCLOUD_USERNAME'),
                env('MEMCACHEDCLOUD_PASSWORD')
            ],
            'options'    => [
                'OPT_NO_BLOCK'         => true,
                'OPT_AUTO_EJECT_HOSTS' => true,
                'OPT_CONNECT_TIMEOUT'  => 2000,
                'OPT_POLL_TIMEOUT'     => 2000,
                'OPT_RETRY_TIMEOUT'    => 2,
            ],
            'servers' => [
                [
                    'host' => '127.0.0.1', 'port' => 11211, 'weight' => 100
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'prefix' => 'laravel',

];
