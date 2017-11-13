<?php

namespace App;

use Lib\App;

class Login extends Base
{
    /**
     * 无需登录
     * @var bool
     */
    protected $isRequiredLogin = false;

    public function entry()
    {
        return view('view/login.php');
    }

    public function auth()
    {
        $config = config('admin');
        $account = $_POST['account'];
        $password = $_POST['password'];

        if ($config['account'] != $account || $config['password'] != $password) {
            return App::error('account error');
        }

        $_SESSION['admin'] = $account;
        return true;
    }
}
