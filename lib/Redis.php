<?php

namespace Lib;

class Redis
{
    /**
     * @var \Redis
     */
    public static $redis;

    /**
     * 连接redis server
     * @param string $server
     */
    public static function connection($server = 'local')
    {
        $config = config('redis')[$server];

        if (empty(self::$redis)) {
            self::$redis = new \Redis();
        }
        self::$redis->connect($config['host'], $config['port']);
    }
}
