<?
class Auth
{
    public function __construct($container) {
      session_name('SESSID');
      session_set_cookie_params(0, '/', '.filmstunden.ch');
      session_start();
    }

    public function check($request,$response,$args){
      if (!isset($_SESSION["running"]) || ($_SESSION["running"] != 1)) {
          session_destroy();
          header('Content-Type: application/json');
          die(json_encode(array('status'=>'403','msg'=>'ERROR: UNAUTHORIZED')));
      } else {
          return TRUE;
      }
    }
}
