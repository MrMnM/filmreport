<?php
require_once('../vendor/autoload.php');
require_once('../vendor/Medoo.php');

//--------------------------------------------------------
//  Controller Classes
//--------------------------------------------------------
require_once('../api-app/classes/User.php');

use Medoo\Medoo;
$app = new \Slim\App();

//--------------------------------------------------------
//  DATABASE CONNECTIVITY
//--------------------------------------------------------
$container = $app->getContainer();
$container['database'] = function () {
	return new Medoo([
		'database_type' => 'mysql',
		'database_name' => 'filmstun_dev',
		'server' => 'localhost',
		'username' => 'filmstun_dev',
		'password' => 'ZbFqMoHOAFIO'
	]);
};
//--------------------------------------------------------
//  ROUTING
//--------------------------------------------------------
$app->get('/', function($request, $response, $args) {
	$data = $this->database->select('test', ['id', 'name']);
	return $response->write(json_encode($data));
});
$app->get('/test/{id}', function($request, $response, $args) {
 $usr = new User();
 $usr->find($this->database, $args);
});
$app->get('/user/', function($request, $response, $args) {
 echo 'user';
});

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
