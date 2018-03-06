<?
class Project
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
    }


    public function list($request,$response,$args){
      $mode = $request->getQueryParam('m');
      if ($mode == 0) {
        $fin= [0,1]; // ALLE ANZEIGEN
      }elseif ($mode == 1) {
        $fin= 0;  // ACTIVE
      }else{
        $fin= 1; // BEENDET
      }
      $indata = $this->db->select('projects', [
                        "[>]companies" => ["p_company" => "company_id"]
                        ],[
                          'projects.project_id',
                          'projects.p_start',
                          'projects.p_name',
                          'companies.name',
                          'projects.tot_hours',
                          'projects.tot_money',
                          'projects.p_finished',
                          'projects.view_id'
                        ],[
                          "AND" => [
                          "user_id" => $_SESSION['user'],
                          "p_finished" => $fin
                          ]
                        ]);
        $o=[];
        foreach ($indata as $cur) {
          $c=[$cur["p_start"],
              $cur["p_name"],
              $cur["name"],
              $cur["tot_hours"],
              $cur["tot_money"],
              $cur["project_id"],
              $cur["p_finished"],
              $cur["view_id"]
            ];
          array_push($o,$c);
        }

  $out=['data' => $o];


        $response = $response->withHeader('Access-Control-Allow-Origin', 'https://filmstunden.ch')
                         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                         ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                         ->withHeader('Access-Control-Allow-Credentials', 'true')
                         ->withJson($out);
        return $response;
    }

    public function load($request,$response,$args){
        $data = $args['id'];
        $response = $response->withJson($data);
        return $response;
    }

}
