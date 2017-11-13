<?php

namespace App;

use Lib\Redis;

abstract class Base
{
    /**
     * Redis实例
     * @var \Redis
     */
    protected $redis;

    /**
     * 是否必须登录
     * @var bool
     */
    protected $isRequiredLogin = true;

    public function __construct()
    {
        session_start();
        Redis::connection();
        $this->redis = Redis::$redis;
        $this->auth();
    }

    private function auth()
    {
        if (!$this->isRequiredLogin) {
            return true;
        }
        if (!config('admin')['login']) {
            return true;
        }
        if (empty($_SESSION['admin'])) {
            redirect(route('login.entry'));
            return false;
        }

        return true;
    }
}
