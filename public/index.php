<?php

ini_set('display_errors', 1);
ini_set('display_starup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

//$dotenv = Dotenv\Dotenv::create('..');
//$dotenv->load();

session_start();

use Zend\Diactoros\Response\RedirectResponse;
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'pgsql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => getenv('DB_CHARSET'),
    'collation' => getenv('DB_COLLATION'),
    'prefix'    => getenv('DB_PREFIX'),
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
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
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction'
]);
$map->get('addJob', '/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);
$map->post('saveJob', '/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);
$map->get('addUser', '/users/add', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'getAddUserAction',
    'auth' => false
]);
$map->post('saveUser', '/users/add', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'getAddUserAction',
    'auth' => false
]);
$map->get('getLogin', '/login', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogin',
    'auth' => false
]);
$map->get('getLogout', '/logout', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogout'
]);
$map->post('auth', '/auth', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'postLogin',
    'auth' => false
]);
$map->get('admin', '/admin', [
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getIndex'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);
if (!$route) {
    echo 'No route';
} else {
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth = $handlerData['auth'] ?? true;

    $controller = new $controllerName;

    $sessionUserId = $_SESSION['userId'] ?? null;
    if ($needsAuth && !$sessionUserId){
        $response = new RedirectResponse('/login');
    } else {
        $response = $controller->$actionName($request);
    }

    foreach($response->getHeaders() as $name => $values) {
        foreach($values as $value) {
            header(sprintf('%s: %s',$name,$value),false);
        }
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();
}

function printElement($element)
{
    echo '<li class="work-position">';
    echo '<h5>' . $element->title . '</h5>';
    echo '<p>' . $element->description . '</p>';
    echo '<p>' . $element->getDurationAsString() . '</p>';
    echo '<strong>Achievements:</strong>';
    echo '<ul>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing el';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing el';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing el';
    echo '</ul>';
    echo '</li>';
}