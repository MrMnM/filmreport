<?
use \Medoo\Medoo;

class Company
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
    }

    public function list($request,$response,$args){
      $data = $this->db->select('companies', [
                            'company_id',
                            'name'
                          ],[
                            "ORDER" => "name"
                          ]);
                          return $response ->withHeader('Access-Control-Allow-Origin', 'https://filmstunden.ch')
                                           ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                                           ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                                           ->withHeader('Access-Control-Allow-Credentials', 'true')
                                           ->withJson($data);
    }

    public function get($request,$response,$args){
        $c_id = $args['c_id'];
        $data = $this->db->select('companies', [
                              'name',
                              'address_1',
                              'address_2',
                              'telephone',
                              'mail'
                            ],[
                              'company_id' => $c_id
                            ]);
        if (sizeof($data)>1) {throw new Exception('Multiple Companies with same ID');};
        $data = $data['0'];
        array_walk_recursive($data, function(&$item) {
            $item = htmlspecialchars($item, ENT_QUOTES);
        });
        return $response ->withHeader('Access-Control-Allow-Origin', 'https://filmstunden.ch')
                       ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                       ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                       ->withHeader('Access-Control-Allow-Credentials', 'true')
                       ->withJson($data);
    }



    public function new($request,$response,$args){
      $req = $request->getParsedBody();
      $name=$req["name"];
      $address1=$req["address1"];
      $address2=$req["address2"];
      $phone= $req["phone"];
      $companyid = substr(md5($name.$address1.$phone), 0, 5);
        $this->db->insert("companies", [
                           "name" => $name,
	                         "company_id" => $companyid,
	                         "address_1" => $address1,
	                         "address_2" => $address2,
                           "telephone" => $phone,
                           "mail" => $req['mail']
                          ]);
        return $response ->withHeader('Access-Control-Allow-Origin', 'https://filmstunden.ch')
                         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                         ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                         ->withHeader('Access-Control-Allow-Credentials', 'true')
                         ->withJson(array('status'=>'SUCCESS','msg'=>'Produktionsfirma Erstellt','c_id'=>$companyid));
    }


    public function delete($request,$response,$args){
        $data = ['test'=>'works'];
        $response = $response->withJson($data);
        return $response;
    }
}
