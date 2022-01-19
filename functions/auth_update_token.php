<?php
  include_once("../config.php");

  function auth_update_token($id, $type){
    
    // Genrate new token
    $token = bin2hex(random_bytes(64));
    
    $result = pg_update($GLOBALS["conn"], $type, array("token" => $token), array("id" => $id));
    
    // Get current epoch time on psql server
    $epoch = get_query_data("select extract(epoch from now()) as epoch;");
    
    //$result2 = pg_update($GLOBALS["conn"], $type, array("token" => $token, "tokenExpire" => $epoch), array("id" => $id));
    
    if ($result) {
      return $token;
    }
    return null;
  }
?>
