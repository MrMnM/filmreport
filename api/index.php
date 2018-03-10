<?php
require_once('../vendor/autoload.php');
require_once('../vendor/Medoo.php');

require_once('../api-app/lib/Auth.php');
require_once('../api-app/lib/Encrypt.php');
require_once('../api-app/lib/Globals.php');

//--------------------------------------------------------
//  Controller Classes
//--------------------------------------------------------
require_once('../api-app/classes/Chat.php');
require_once('../api-app/classes/Company.php');
require_once('../api-app/classes/Jobs.php');
require_once('../api-app/classes/Project.php');
require_once('../api-app/classes/Stats.php');
require_once('../api-app/classes/User.php');
require_once('../api-app/classes/View.php');

use Medoo\Medoo;

$app = new \Slim\App();

//--------------------------------------------------------
//  CONTAINERS
//--------------------------------------------------------
$container = $app->getContainer();
$container['database'] = function () {
    return new Medoo($GLOBALS['db']);
};
$container['auth'] = function () {
    return new Auth();
};
$container['encrypt'] = function () {
    return new Encrypt();
};
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withJson(array('status'=>'ERROR','msg'=>'Ressource not found'));
    };
};

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response->withJson(array('status'=>'ERROR','msg'=>$exception->getMessage()));
    };
};
//--------------------------------------------------------
//  ROUTING
//--------------------------------------------------------
$app->get('/', function ($request, $response, $args) {
    return $response->withJson(array('status'=>'SUCCESS','msg'=>'Filmstunden API v.01'));
});
// User
$app->get('/user', 'User:get');
$app->post('/user', 'User:update');
$app->post('/user/new', 'User:new');
$app->get('/user/validate', 'User:validate'); //?v=xx

// Company
$app->get('/company', 'Company:list');
$app->get('/company/{id}', 'Company:get');
$app->post('/company/new', 'Company:new');
$app->delete('/company/{id}', 'Company:delete');

// Project
$app->get('/project', 'Project:list');
$app->get('/project/{id}', 'Project:load');
$app->post('/project/{id}', 'Project:save');
$app->delete('/project/{id}', 'Project:delete');

// Stats
$app->get('/stats/chart/donut', 'Stats:donutchart');
$app->get('/stats/chart/line', 'Stats:linechart'); //?start=xx&end=xx
$app->get('/stats', 'Stats:misc'); //?start=xx&end=xx

// Jobs
$app->get('/jobs', 'Jobs:get');

//Chats
$app->get('/chats', 'Chat:get'); //?p=xx
$app->post('/chats', 'Chat:add'); //?p=xx


// View
$app->get('/view', 'View:get');
$app->get('/view/download', 'View:Download'); //?format=xx


//--------------------------------------------------------
// RUNNING
//--------------------------------------------------------
$app->run();
