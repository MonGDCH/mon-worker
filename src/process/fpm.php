<?php

/*
|--------------------------------------------------------------------------
| 加载composer
|--------------------------------------------------------------------------
| 加载composer, 处理类文件自动加载
|
*/
require_once __DIR__ . '/../vendor/autoload.php';



/*
|--------------------------------------------------------------------------
| 获取配置服务
|--------------------------------------------------------------------------
| 这里获取配置服务, 定义配置环境
|
*/
$config = \mon\env\Config::instance();



/*
|--------------------------------------------------------------------------
| 加载配置
|--------------------------------------------------------------------------
| 加载配置
|
*/
$config->loadDir(CONFIG_PATH);



/*
|--------------------------------------------------------------------------
| 定义应用时区
|--------------------------------------------------------------------------
| 这里设置时区
|
*/
date_default_timezone_set($config->get('app.time_zone', 'PRC'));



/*
|--------------------------------------------------------------------------
| 初始化日志服务
|--------------------------------------------------------------------------
| 这里初始化日志服务
|
*/
\mon\log\Logger::instance()->registerChannel($config->get('log', []));



/*
|--------------------------------------------------------------------------
| 定义日志服务通道
|--------------------------------------------------------------------------
| 这里定义日志服务通道
|
*/
\mon\log\Logger::instance()->setDefaultChanneel('default');



/*
|--------------------------------------------------------------------------
| 加载全局中间件
|--------------------------------------------------------------------------
| 这里加载全局中间件
|
*/
\mon\http\Middleware::instance()->load($config->get('http.middleware', []));



/*
|--------------------------------------------------------------------------
| 定义应用运行模式
|--------------------------------------------------------------------------
| 这里定义应用运行模式, 默认生产模式
|
*/
$debug = $config->get('app.debug', false);



/*
|--------------------------------------------------------------------------
| 创建fpm应用
|--------------------------------------------------------------------------
| 这里创建fpm应用
|
*/
$app = new \mon\http\Fpm($debug);



/*
|--------------------------------------------------------------------------
| 自定义异常处理实支持
|--------------------------------------------------------------------------
| 这里自定义异常处理实支持
|
*/
$app->supportError($config->get('http.app.exception', \support\http\HttpErrorHandler::class));



/*
|--------------------------------------------------------------------------
| 注册session支持
|--------------------------------------------------------------------------
| 这里注册session支持
|
*/
$app->supportSession($config->get('http.session', []));



/*
|--------------------------------------------------------------------------
| 启动时
|--------------------------------------------------------------------------
| 这里注册应用启动时业务
|
*/
support\http\Bootstrap::start($app);



/*
|--------------------------------------------------------------------------
| 定义路由
|--------------------------------------------------------------------------
| 注册应用请求路由
|
*/
support\http\Bootstrap::registerRoute($app, $app->route());



/*
|--------------------------------------------------------------------------
| 运行程序
|--------------------------------------------------------------------------
| 运行程序
|
*/
$app->run();
