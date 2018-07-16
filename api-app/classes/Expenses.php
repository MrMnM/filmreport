<?
use \Medoo\Medoo;
use Slim\Http\UploadedFile;

class Expenses
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
        $this->uploadDirectory = '../upload';
    }

    public function get($request,$response,$args){
      $p_id = $args['p_id'];
      $data = $this->db->select('expenses', [
                      'id',
                      'date',
                      'name',
                      'comment',
                      'img',
                      'value'
                    ], [
                      "project" => $p_id
                    ]);

      return $response->withJson($data);
    }


    public function save($request,$response,$args){
      $this->auth->check();
      $req = $request->getParsedBody();
      $p_id=$args['p_id'];
      $this->db->insert('expenses', [
                       "project" => $p_id,
                       "date" => $req['date'],
                       "name" => $req['name'],
                       "comment" => $req['comment'],
                       "img" => $req['img'],
                       "value" => $req['val'],
                      ]);

      return $response->withStatus(204);
    }

    public function delete($request,$response,$args){
      $this->auth->check();
      $directory = $this->uploadDirectory;
      $p_id=$args['p_id'];
      $e_id=$args['e_id'];
      $data = $this->db->select('expenses',[
                            'img'
                          ], [
                            "AND" => [
                              'project' => $p_id,
                              'id' => $e_id
                            ]]);
      $file =  $directory . DIRECTORY_SEPARATOR . $data[0]['img'] ;
      if(file_exists($file)) {
         unlink($file);
       }

      $this->db->delete('expenses', [
	                 "AND" => [
		                    'project' => $p_id,
		                     'id' => $e_id
	                      ]
                      ]);
      return $response->withStatus(204);
      //TODO: check how many rows affected
    }

    public function upload($request,$response,$args){
      $this->auth->check();
      $directory = $this->uploadDirectory;
      $p_id=$args['p_id'];
      $uploadedFiles = $request->getUploadedFiles();
      $uploadedFile = $uploadedFiles['file'];
      if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $filename = $this->moveUploadedFile($directory, $uploadedFile, $p_id);
        return $response->withJson(array('status'=>'SUCCESS','file_id'=>$filename));
      }
    }

    private function moveUploadedFile($directory, UploadedFile $uploadedFile,$p_id){
      $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
      $basename = $p_id .'_'. bin2hex(random_bytes(3));
      $filename = sprintf('%s.%0.8s', $basename, $extension);
      $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
      return $filename;
    }

}
