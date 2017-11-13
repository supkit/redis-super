<?php

function view ($path, $data) {
    extract($data);
    return include $path;
}

function route($route, $params = []) {
    $query = !empty($params) ? '?' . http_build_query($params) : '';
    return Lib\App::uri() . '/' .str_replace('.', '/', $route) . $query;
}

function assets() {
    return str_replace('index.php', '', Lib\App::uri()).'/assets';
}