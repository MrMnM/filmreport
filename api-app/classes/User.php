<?
class User {
    public function find($db, $args) {
        $data = $db->select('test', ['id', 'name']);
        echo 'ID:'.json_encode($args).'<br>';
        echo json_encode($data);
    }
}