<?php
require_once('../../vendor/autoload.php');
require_once('../../vendor/Medoo.php');

require_once('../../api-app/lib/Auth.php');
require_once('../../api-app/lib/Encrypt.php');
require_once('../../api-app/lib/Globals.php');

//--------------------------------------------------------
//  Controller Classes
//--------------------------------------------------------
require_once('../../api-app/classes/Chat.php');
require_once('../../api-app/classes/Company.php');
require_once('../../api-app/classes/Jobs.php');
require_once('../../api-app/classes/Project.php');
require_once('../../api-app/classes/Stats.php');
require_once('../../api-app/classes/User.php');
require_once('../../api-app/classes/View.php');
require_once('../../api-app/classes/Expenses.php');
require_once('../../api-app/classes/Timer.php');
require_once('../../api-app/classes/Mail.php');


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
        return $response->withStatus(404);;
    };
};

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response->withJson(array('status'=>'ERROR','msg'=>$exception->getMessage()));
    };
};
//--------------------------------------------------------
//  ROUTING
$app->get('/', function ($request, $response, $args) {
    return $response->withJson(array('status'=>'SUCCESS','msg'=>'Filmstunden API select version'));
});

$app->group('/v01', function() {
  $this->get('/', function ($request, $response, $args) {
      return $response->withJson(array('status'=>'SUCCESS','msg'=>'Filmstunden API v01'));
  });
  // User
  $this->get('/user', 'User:get');
  $this->get('/user/get/{u_id}', 'User:getSpecific'); // This takes an encrypted version of the userid
  $this->post('/user', 'User:update');
  $this->post('/user/new', 'User:new');
  $this->get('/user/validate', 'User:validate'); //?v=xx

  // Company
  $this->get('/company', 'Company:list');
  $this->post('/company/new', 'Company:new');
  $this->get('/company/{c_id}', 'Company:get');
  $this->delete('/company/{c_id}', 'Company:delete');

  // Project
  $this->get('/project', 'Project:list');
  $this->post('/project/new', 'Project:new');
  $this->post('/project/{p_id}', 'Project:save');
  $this->delete('/project/{p_id}', 'Project:delete');
  $this->get('/project/{p_id}', 'Project:load');
  //$this->get('/project/{p_id}/data', 'Project:getData');
  //$this->get('/project/{p_id}/info', 'Project:getInfo');
  $this->post('/project/{p_id}/info', 'Project:saveInfo');
  $this->post('/project/{p_id}/finish', 'Project:finish');
  $this->get('/project/{p_id}/download', 'Project:download');

  // Stats
  $this->get('/stats/chart/donut', 'Stats:donutchart');
  $this->get('/stats/chart/line', 'Stats:linechart'); //?start=xx&end=xx
  $this->get('/stats', 'Stats:misc'); //?start=xx&end=xx

  //Expenses
  $this->get('/expenses/{p_id}', 'Expenses:get');
  $this->post('/expenses/{p_id}', 'Expenses:save');
  $this->post('/expenses/{p_id}/upload', 'Expenses:upload');
  $this->delete('/expenses/{p_id}/{e_id}', 'Expenses:delete');
  // Jobs
  $this->get('/jobs', 'Jobs:get');

  //Chats
  $this->get('/chats/{p_id}', 'Chat:get');
  $this->post('/chats/{p_id}', 'Chat:add'); //?text=xxx

  // View
  $this->get('/view/{p_id}', 'View:show');
  $this->get('/view/download/{p_id}', 'View:Download'); //?format=xx

  // Timer
  $this->get('/timer', 'Timer:list');
  $this->post('/timer', 'Timer:new');
  $this->get('/timer/{t_id}', 'Timer:load');
  $this->post('/timer/{t_id}', 'Timer:update');
  $this->delete('/timer/{t_id}', 'Timer:delete');

  // Mail
  $this->post('/mail', 'Mail:send'); //?text=xxx
});

//--------------------------------------------------------
// RUNNING
//--------------------------------------------------------
$app->run();
