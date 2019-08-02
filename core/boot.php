<?php

namespace core;

define('PATH_ROOT', dirname(__DIR__));

require_once 'utils.php';

spl_autoload_register('autoload_nerv');

if ($autoload = realpath(PATH_ROOT . '/vendor/autoload.php')) {
    require_once $autoload;
}

class Boot
{
    private $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    public function run(Input $Input = null): Output
    {
        $Input = $Input ?: input(array_merge($GLOBALS, $_SERVER));
        // 解析路由
        $route = router($this->domain)->resolve($Input->URI);
        // 生成控制器
        $controller = new $route['ctrl']($Input);
        // 尝试执行前置方法
        if (method_exists($controller, 'runBefore')) {
            $result = $controller->runBefore();
            // 若存在返回值，则终止并输出
            if ($result) {
                return output($result);
            }
        }
        if (method_exists($controller, 'run')) {
            $result = $controller->run(...$route['args']);
        }
        // 尝试执行后置方法
        if (method_exists($controller, 'runBehind')) {
            $controller->runBehind();
        }
        return output($result);
    }
}
