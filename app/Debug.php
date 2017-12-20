<?php

namespace App;

class Debug extends Base
{
    public function databases($index = 0)
    {
        $this->redis->select($index);
        $databases = (int)$this->redis->config('GET', 'databases')['databases'];
        return view('view/page/databases.php', [
                'databases' => $databases,
                'index' => $index
            ]
        );
    }
}