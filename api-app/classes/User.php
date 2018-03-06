<?
class User
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
        $this->enc = $container->get('encrypt');
    }

    public function get($request,$response,$args){
      $this->auth->check();
      $data = $this->db->select('users', [
                            'mail',
                            'name',
                            'tel',
                            'address_1',
                            'address_2',
                            'ahv',
                            'dateob',
                            'konto',
                            'bvg',
                            'type',
                            'affiliation',
                          ],[
                            "u_id" => $_SESSION['user']
                          ]);
      if(sizeof($data)>1){throw new Exception('Multiple Users with same ID');};
      $data = $data['0'];
      $data['ahv'] = $this->enc->encrypt($data['ahv'],'d');
      $data['konto'] = $this->enc->encrypt($data['konto'],'d');
      return $response ->withHeader('Access-Control-Allow-Origin', 'https://filmstunden.ch')
                       ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                       ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                       ->withHeader('Access-Control-Allow-Credentials', 'true')
                       ->withJson($data);
    }

    public function update($request,$response,$args){
      //$this->auth->check();
      var_dump($request->getParsedBody());
      /*
      $data = $this->db->update("users", [
	                                 "mail" => "user",
                                   "name" => "user",
                                   "mail" => "user",
                                 ],[
	                                  "u_id" => $_SESSION['user']
                                  ]);

      $response = $response->withJson($data);
      return $response;
      */


      /*

      function update($conn, $post,$db,$encrypt)
      {
        if (!empty($_POST[$post])) {
          if($encrypt){
            $cur= encrypt($_POST[$post], 'e');
          }else{
            $cur= mysqli_real_escape_string($conn, $_POST[$post]);
          }
            $sql = "UPDATE users SET '$db'='$cur' WHERE u_id = '$u_id'";
            if ($conn->query($sql) !== true) {
                die('{ "message":"ERROR:'. $sql .' ' . $conn->error.'"}');
            }
        }
      }

      function UpdateUser($u_id, $conn)
      {
          upd($conn, "name", "name", FALSE);
          upd($conn, "tel", "tel", FALSE);
          upd($conn, "address1", "address_1", FALSE);
          upd($conn, "address2", "address_2", FALSE);
          upd($conn, "ahv", "ahv", TRUE);
          upd($conn, "dob", "dateob", FALSE);
          upd($conn, "konto", "konto", TRUE);
          upd($conn, "bvg", "bvg", FALSE);
          die('{ "message":"SUCCESS"}');
      }
      */
    }
}
