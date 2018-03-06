<?
class Company
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
    }

    public function test($request,$response,$args){
        $data = ['test'=>'works'];
        $response = $response->withJson($data);
        return $response;
    }

}
