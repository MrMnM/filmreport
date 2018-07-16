<?php
use \Medoo\Medoo;

class Chat
{
    public function __construct($container)
    {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
    }

    public function get($request, $response, $args)
    {
        $p_id = $args['p_id'];
        $data = $this->db->select('chats', [
                           "[>]users" => ["from" => "u_id"]
                       ], [
                        'chats.id',
                        'chats.time',
                        'users.name',
                        'chats.project',
                        'chats.seen',
                        'chats.text'
                      ], [
                        "project" => $p_id
                      ], 	["ORDER" => [

		// Order by column with descending sorting
		"chats.time" => "DESC"

	]]);

        $full=[];
        foreach ($data as $cur) {
            $c=['id'=>$cur["id"],
              'date'=>$cur["time"],
              'from'=>$cur["name"],
              'text'=>htmlspecialchars($cur["text"], ENT_QUOTES)
          ];
            array_push($full, $c);
        }
        return $response->withJson($full);
    }


    public function add($request, $response, $args)
    {
        if (!isset($_SESSION["running"]) || ($_SESSION["running"] != 1)) {
            $u_id="guest";
        } else {
            $u_id= $_SESSION['user'];
        }
        $req = $request->getParsedBody();
        $p_id=$args['p_id'];
        $text=htmlspecialchars($req["text"], ENT_QUOTES);

        $to = $this->db->get("projects", "user_id", [
             "project_id" => $p_id
         ]);

        $this->db->insert("chats", [
                         "from" => $u_id,
                         "project" => $p_id,
                         "seen" => 0,
                         "text" => $text,
                         "receiver" => $to,
                        ]);

        return $response->withJson(array('status'=>'SUCCESS','msg'=>'Nachricht hinzugefügt'));
    }


    public function delete($request, $response, $args)
    {
    }
}
