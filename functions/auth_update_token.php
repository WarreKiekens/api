<?php
  include_once("../config.php");

  function auth_update_token($id, $type){
    
    // Genrate new token
    $token = bin2hex(random_bytes(64));
        
    // Get current epoch time on psql server
    $now = get_query_data("select now()::timestamp;");
    
    $result = pg_update($GLOBALS["conn"], $type, array("token" => $token, "expiretoken" => $now["now"]), array("id" => $id));
    
    if ($result) {
      return $token;
    }
    return null;
  }
?>
