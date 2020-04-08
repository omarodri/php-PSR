<?php
ini_set('display_errors',1);
ini_set('display_starup_errors',1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

session_start();

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'cursophp',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();


$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,  
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index','/cursophp/',[
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction'
]);
$map->get('addJobs','/cursophp/jobs/add',[
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction',
    'auth' => true
]);
$map->post('saveJobs','/cursophp/jobs/add',[
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction',
    'auth' => true
]);
$map->get('addUser','/cursophp/user/add',[
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getAddUserAction',
    'auth' => true
]);
$map->post('saveUser','/cursophp/user/add',[
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getAddUserAction',
    'auth' => true
]);
$map->get('loginForm','/cursophp/login',[
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogin'
]);
$map->post('auth','/cursophp/auth',[
    'controller' => 'App\Controllers\AuthController',
    'action' => 'postLogin'
]);
$map->get('admin','/cursophp/admin',[
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getIndex',
    'auth' => true
]);
$map->get('logout','/cursophp/logout',[
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogout'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo 'No route defined '; 
} else{
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth = $handlerData['auth'] ?? false;

    $controller = new $controllerName;

    $sessionUserId = $_SESSION['userId'] ?? null;
    if($needsAuth && !$sessionUserId){
        $response = $controller->renderHTML('login.twig',[
            'responseMessage' => 'Access Denied. You have to login first'
            ]);  
    }else{
        $response = $controller->$actionName($request);
    }

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
    http_response_code($response->getStatusCode());

    echo $response->getBody();
}

// function printElement($job)
// {
//     echo '<li class="work-position">';
//     echo '<h5>' . $job->title . '</h5>';
//     echo '<p>' . $job->description . '</p>';
//     echo '<p>' . $job->getDurationAsString() . '</p>'; 
//     echo '<strong>Achievements:</strong>';
//     echo '<ul>';
//     echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
//     echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
//     echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
//     echo '</ul>';
//     echo '</li>';
// }





// $route = $_GET['route'] ?? '/';

// if ($route == '/'){
//     require '../index.php';
// } elseif($route == 'addJob'){
//     require '../addJob.php';
// }