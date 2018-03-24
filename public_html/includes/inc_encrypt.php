<?
function encrypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $v = substr($string,0,4);
    $cur = 'v00:';
    if($v=='v00:' || $action=='e'){
      if($v=='v00:'){$string = substr($string,4);}
      $secret_key = 'QEPEg-wg_PKn?PtBx-VpF34fRQ@zYE*B'; //TODO Replace with by Userkey
      $secret_iv = 'JzUSGr?$2tWRn*r$5sTJ7E!LBw6zUdX!';
    }else{
      $secret_key = 'my_simple_secret_key'; //TODO Replace with by Userkey
      $secret_iv = 'my_simple_secret_iv';
    }

    $output = false;
    $encrypt_method = "AES-256-CBC"; //if possible GCM
    $key = hash( 'sha256', $secret_key );
    //$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
    $iv = substr( hash( 'md5', $secret_iv ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );

    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }

    if($v!==$cur && $action=='e'){
      $output = $cur.''.$output;
    }

    return $output;
}
?>
