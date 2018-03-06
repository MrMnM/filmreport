<?php
require_once('../vendor/autoload.php');
require_once('../vendor/Medoo.php');


require_once('../api-app/lib/Auth.php');
require_once('../api-app/lib/Encrypt.php');
//--------------------------------------------------------
//  Controller Classes
//--------------------------------------------------------
require_once('../api-app/classes/User.php');
require_once('../api-app/classes/Project.php');
require_once('../api-app/classes/Company.php');
require_once('../api-app/classes/Stats.php');

//require('../public_html/includes/inc_sessionhandler_ajax.php');

use Medoo\Medoo;
$app = new \Slim\App();

/*
[
	'database_type' => 'mysql',
	'database_name' => 'filmstun_dev',
	'server' => 'localhost',
	'username' => 'filmstun_dev',
	'password' => 'ZbFqMoHOAFIO'
]
*/

//--------------------------------------------------------
//  CONTAINERS
//--------------------------------------------------------
$container = $app->getContainer();
$container['database'] = function () {
	return new Medoo([
		'database_type' => 'mysql',
		'database_name' => 'filmstun_proj_calc',
		'server' => 'localhost',
		'username' => 'filmstun_proj',
		'password' => '^UCLj]K2+aOv'
	]);
};
$container['auth'] = function(){
	return new Auth();
};
$container['encrypt'] = function(){
	return new Encrypt();
};
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withJson(array('status'=>'404','msg'=>'ERROR: RESSOURCE NOT FOUND'));
    };
};

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
			return $response->withJson(array('status'=>'500','msg'=>'ERROR: '.$exception->getMessage()));
    };
};
//--------------------------------------------------------
//  ROUTING
//--------------------------------------------------------
$app->get('/', function($request, $response, $args) {
	return $response->withJson(array('status'=>'200','msg'=>'Filmstunden API v.01'));
});
// User
$app->get('/user','User:get');
$app->post('/user', 'User:update');

// Project
$app->get('/project','Project:list');
$app->get('/project/{id}','Project:load');

// Stats
$app->get('/stats/chart/donut','Stats:donutchart');
$app->get('/stats/chart/line','Stats:linechart');
$app->get('/stats','Stats:misc');
/*

/*
You can use nested grouping like this to prevent repeating the url:
$app->group('/v1', function () {
    $this->group('/auth', function () {
        $this->map(['GET', 'POST'], '/login', 'App\controllers\AuthController:login');
        $this->map(['GET', 'POST'], '/logout', 'App\controllers\AuthController:logout');
        $this->map(['GET', 'POST'], '/signup', 'App\controllers\AuthController:signup');
    });
    $this->group('/events', function () {
        $this->get('', 'App\controllers\EventController:getEvents');
        $this->post('', 'App\controllers\EventController:createEvent');
        $this->group('/{eventId}', function () {
            $this->get('', 'App\controllers\EventController:getEvent');
            $this->put('', 'App\controllers\EventController:updateEvent');
            $this->delete('', 'App\controllers\EventController:deleteEvent');
        });
    });
});
*/

//--------------------------------------------------------
// RUNNING
//--------------------------------------------------------
$app->run();
