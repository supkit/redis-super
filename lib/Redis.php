<?php

namespace Lib;

class Redis
{
    /**
     * @var \Redis
     */
    public static $redis;

    public static function connection()
    {
        $config = App::getConfig()['redis'];
        self::$redis = new \Redis();
        self::$redis->connect($config['host'], $config['port']);
    }
}
