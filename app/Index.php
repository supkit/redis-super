<?php

namespace App;

use Lib\App;
use Lib\Redis;

class Index extends Base
{
    /**
     * @var string
     */
    private $displayKeysHtml = '';

    /**
     * @param int $index
     * @return mixed
     */
    public function entry($index = 0)
    {
        $this->redis->select($index);
        $dbSize = $this->redis->dbSize();
        $keys = $this->redis->keys('*');

        $this->item($keys, $index);

        $databases = (int)$this->redis->config('GET', 'databases')['databases'];
        $redisDir = $this->redis->config('GET', 'dir');
        $port = $this->redis->config('GET', 'port');
        $info = $this->redis->info();
        $sidebarWidth = isset($_COOKIE['sidebar']) ? intval($_COOKIE['sidebar']) : 300;

        $data = [
            'keys' => $keys,
            'dbSize' => $dbSize,
            'databases' => $databases,
            'index' => $index,
            'sidebarWidth' => $sidebarWidth,
            'keysHtml' => $this->displayKeysHtml .'</ul>',
            'info' => $info,
            'redisDir' => $redisDir,
            'port' => $port,
        ];

        return view('view/index.php', $data);
    }

    public function keyList($index)
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

    public function search($index, $key)
    {
        sleep(0.5);
        $this->redis->select($index);
        if (!preg_match('/\*/', $key)) {
            $key = $key . '*';
        }
        $keys = $this->redis->keys($key.'*');
        $this->item($keys, $index);
        $html = $this->displayKeysHtml .'</ul>';
        $data = [
            'html' => $html,
        ];
        return App::success($data);
    }

    public function delete($index, $key, $isFullKey = true)
    {
        $this->redis->select($index);
        if ($isFullKey) {
            $this->redis->delete($key);
            return true;
        }
        $keys = $this->redis->keys($key.'*');
        $keys = array_values($keys);
        return call_user_func_array([$this->redis, 'delete'], $keys);
    }

    public function value($index, $key)
    {

        $this->redis->select($index);
        $type = $this->redis->type($key);
        $value = $this->redis->get($key);
        $serialize = false;
        $viewType= 'string';

        if ($type == 1) {
            if (preg_match('/[a-z]+\:[0-9]+/', $value)) {
                $value = unserialize($value);
                $serialize = true;
            }

            $viewType = 'string';
        }

        if ($type == 2) {
            $value = $this->redis->sRandMember($key, 10);
            $viewType = 'set';
        }

        if ($type == 3) {
            $value = $this->redis->lRange($key, 0, 100);
            $viewType = 'list';
        }

        if ($type == 5) {
            $value = $this->redis->hGetall($key);
            $viewType = 'hash';
        }

        $data = [
            'key' => $key,
            'value' => $value,
            'type' => $type,
            'viewType' => $viewType,
            'serialize' => $serialize
        ];

        return view('view/value-'.$viewType.'.php', $data);
    }

    private function item($keys, $index)
    {
        sort($keys);
        $namespaces = [];
        foreach ($keys as $key) {
            $key = explode(':', $key);
            $temp = &$namespaces;
            for ($i = 0; $i < (count($key) -1); $i++) {
                if (!isset($temp[$key[$i]])) {
                    $temp[$key[$i]] = [];
                }
                $temp = &$temp[$key[$i]];
            }

            $temp[$key[count($key) - 1]] = ['PHP_WEB_REDIS_VALUE' => true];
            unset($temp);
        }

        $this->displayKeys($namespaces, '', '', empty($namespaces));
    }

    public function displayKeys($item, $name, $fullKey, $isLast)
    {

        if (isset($item['PHP_WEB_REDIS_VALUE'])) {
            unset($item['PHP_WEB_REDIS_VALUE']);
            if ($isLast) {
                $this->displayKeysHtml .= '<li class="last" data-key="'.$fullKey.'"><div><i class="md-description"></i><span>'.$name.'</span><i data-full="1" class="md-close"></i></div></li>';
            } else {
                $this->displayKeysHtml .= '<li data-key="'.$fullKey.'"><div><i class="md-description"></i><span>'.$name.'</span><i data-full="1" class="md-close"></i></div></li>';
            }
        }

        if (count($item) == 0) {
            return true;
        }

        $subLength = count($item);

        if (empty($fullKey)) {
            $this->displayKeysHtml .= '<ul class="item">';
        } else {
            $this->displayKeysHtml .= '<li data-key="'.$fullKey.'"><div class="item-folder"><i class="icon md-folder"></i><span>'.$name.'</span><i data-full="0" class="md-close"></i></div><ul class="item">';
        }
        foreach ($item as $childName => $childItem) {
            $childFullKey = empty($fullKey) ? $childName : $fullKey .':'. $childName;
            $this->displayKeys($childItem, $childName, $childFullKey, (--$subLength == 0));
        }

        $this->displayKeysHtml .= '</ul></li>';
        return true;
    }
}
