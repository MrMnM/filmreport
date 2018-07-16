<?
class Auth
{
    public function __construct() {
      session_name('SESSID');
      session_set_cookie_params(0, '/', '.filmstunden.ch',TRUE,TRUE);
      session_start();
    }

    public function check(){
      if (!isset($_SESSION["running"]) || ($_SESSION["running"] != 1)) {
          session_destroy();
          header('HTTP/1.1 304 Not Authorized');
          die();
      } else {
          return TRUE;
      }
    }
}
