<?php

if (getenv('DEBUG') === 'true') {
    ini_set('display_errors', 1);
    ini_set('display_starup_errors', 1);
    error_reporting(E_ALL);
}

require_once '../vendor/autoload.php';

use App\Middlewares\AuthenticationMiddleware;
use DI\Container;
use Franzl\Middleware\Whoops\WhoopsMiddleware;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
use Zend\Diactoros\Response;
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

session_start();

$dotenv = Dotenv\Dotenv::create('..');
$dotenv->load();

$log = new Logger('app');
$log->pushHandler(new StreamHandler('../logs/app.log', Logger::WARNING));

$container = new Container();

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => getenv('DB_DRIVER'),
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => getenv('DB_CHARSET'),
    'collation' => getenv('DB_COLLATION'),
    'prefix'    => getenv('DB_PREFIX'),
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index', '/', [
    'App\Controllers\IndexController',
    'indexAction'
]);
$map->get('addJob', '/jobs/add', [
    'App\Controllers\JobsController',
    'getAddJobAction'
]);
$map->post('saveJob', '/jobs/add', [
    'App\Controllers\JobsController',
    'getAddJobAction'
]);
$map->get('indexJobs', '/jobs', [
    'App\Controllers\JobsController',
    'indexAction'
]);
$map->get('deleteJobs', '/jobs/delete', [
    'App\Controllers\JobsController',
    'deleteAction'
]);
$map->get('addUser', '/users/add', [
    'App\Controllers\UserController',
    'getAddUserAction',
    'auth' => false
]);
$map->post('saveUser', '/users/add', [
    'App\Controllers\UserController',
    'getAddUserAction',
    'auth' => false
]);
$map->get('getLogin', '/login', [
    'App\Controllers\AuthController',
    'getLogin',
    false
]);
$map->get('getLogout', '/logout', [
    'App\Controllers\AuthController',
    'getLogout'
]);
$map->post('auth', '/auth', [
    'App\Controllers\AuthController',
    'postLogin',
    'auth' => false
]);
$map->get('admin', '/admin', [
    'App\Controllers\AdminController',
    'getIndex'
]);
$map->get('contactForm', '/contact', [
    'App\Controllers\ContactController',
    'index'
]);
$map->post('contactSend', '/contact/send', [
    'App\Controllers\ContactController',
    'send'
]);
$map->get('changePasswordForm', '/password', [
    'App\Controllers\ChangePasswordController',
    'index'
]);
$map->post('changePassword', '/password/change', [
    'App\Controllers\ChangePasswordController',
    'change'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);
if (!$route) {
    echo 'No route';
} else {
    try {
        $harmony = new Harmony($request, new Response());
        $harmony
            ->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()))
            ->addMiddleware(new AuthenticationMiddleware());
            if (getenv('DEBUG') === 'true') {
                $harmony->addMiddleware(new WhoopsMiddleware());
            }
        $harmony
            ->addMiddleware(new Middlewares\AuraRouter($routerContainer))
            ->addMiddleware(new DispatcherMiddleware($container,'request-handler'))
            ->run();
    } catch (Exception $e) {
        $log->warning($e->getMessage());
        $emmiter = new SapiEmitter();
        $emmiter->emit(new Response\EmptyResponse(400));
    } catch (Error $e) {
        $emmiter = new SapiEmitter();
        $emmiter->emit(new Response\EmptyResponse(500));
    }
}