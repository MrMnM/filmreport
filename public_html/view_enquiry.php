<?php
if ( !isset( $_GET['id'] ) && empty( $_GET['id'] ) ){
    die('Keine g&uuml;ltige ID gew&auml;hlt');
}


$scripts = '<script type="module" src="./js/view_enquiry.js"></script>';

include('./api/template_anfrage.php');


?>