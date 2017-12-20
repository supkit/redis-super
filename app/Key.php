<?php

namespace App;

use Lib\App;

class Key extends Base
{
    public function item($index)
    {
        $this->redis->select($index);
        $dbSize = $this->redis->dbSize();
        $keys = $this->redis->keys('*');
        $this->item($keys, $index);

        $html = $this->displayKeysHtml .'</ul>';
        $data = [
            'html' => $html,
            'dbSize' => $dbSize
        ];
        return App::success($data);
    }

    private function displayHtml()
    {

    }
}
