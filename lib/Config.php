<?php

namespace Lib;

use Exception;

class Config
{
    /**
     * @var array
     */
    private static $config = [];

    /**
     * 加载配置文件
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public static function value($key)
    {
        if (!file_exists(__DIR__ . '/../config.php')) {
            throw new Exception('Config file not exits');
        }

        if (empty(self::$config)) {
            self::$config = include __DIR__ . '/../config.php';
        }

        return self::$config[$key];
    }
}