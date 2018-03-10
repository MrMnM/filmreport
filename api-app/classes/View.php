<?
use \Medoo\Medoo;

class View
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
    }

    public function get($request,$response,$args){
    }


    public function download($request,$response,$args){
    }

}
