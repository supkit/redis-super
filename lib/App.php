<?php

namespace Lib;

use Exception;
use ErrorException;

class App
{
    /**
     * PHP报错
     * @var int
     */
    const PHP_ERROR_CODE = 500;

    /**
     * 应用程序报错
     * @var int
     */
    const APP_ERROR_CODE = 700;

    /**
     * App constructor.
     * @param string $namespace
     * @param array $route
     */
    public function __construct($namespace, $route)
    {
        // 将PHP内置错误以异常方式抛出
        set_error_handler(function($level, $message, $file, $line) {
            throw new ErrorException($message, self::PHP_ERROR_CODE, $level, $file, $line);
        });

        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        self::router($namespace, $route);
    }

    /**
     * 简单路由
     * @throws Exception
     */
    private static function router($namespace, $route)
    {
        if (!preg_match('#([a-z/\-0-9A-Z]+)#', $_SERVER['REQUEST_URI'], $match)) {
            throw new Exception('Route error', self::APP_ERROR_CODE);
        }

        $requestUri = $_SERVER['REQUEST_URI'];

        if (isset($_SERVER['QUERY_STRING']) || isset($_GET)) {
            $requestUri = str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
        }

        $scriptName = $_SERVER['SCRIPT_NAME'];

        if (mb_strlen($requestUri) > mb_strlen($scriptName)) {
            $requestPath = ltrim(str_replace($scriptName, '', $requestUri), '/');
        }
        $requestPath = empty($requestPath) ? $route : explode('/', $requestPath, 2);

        list($class, $method) = $requestPath;
        $class = $namespace .'\\' . ucfirst($class);

        if (!class_exists($class)) {
            throw new Exception("Class {$class} not found", self::APP_ERROR_CODE);
        }

        if (!method_exists($class, $method)) {
            throw new Exception("Method {$class}::{$method}() not found", self::APP_ERROR_CODE);
        }

        $params = isset($_GET) ? $_GET : [];
        $response = call_user_func_array([new $class, $method], $params);

        if (is_string($response)) {
            echo $response;
        }

        return true;
    }

    public static function uri()
    {
        $scheme = empty($_SERVER['REQUEST_SCHEME']) ? 'http' : $_SERVER['REQUEST_SCHEME'];
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        return $scheme . '://' . $host . $scriptName;
    }

    /**
     * 输出错误结果
     * @param $message
     * @param array $data
     * @return string
     */
    public static function error($message, $data = [])
    {
        $response = [
            'code' => self::APP_ERROR_CODE,
            'message' => $message,
            'data' => $data
        ];
        header('Content-type: application/json');
        return json_encode($response);
    }

    /**
     * 输出正确结果
     * @param $data
     * @return bool
     */
    public static function success($data)
    {
        $response = [
            'code' => Code::SUCCESS_CODE,
            'message' => Code::SUCCESS_MESSAGE,
            'data' => $data
        ];
        header('Content-type: application/json');
        return json_encode($response);
    }
}
