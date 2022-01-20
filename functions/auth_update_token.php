<?php
  include_once("../config.php");

  function auth_update_token($id, $type){
    
    // Genrate new token
    $token = bin2hex(random_bytes(64));
        
    // Get current epoch time on psql server
    $now = get_query_data("select TO_CHAR(NOW(), 'DD-MM-YYYY HH:MI:SS') as now");
    $expire = get_query_data("select TO_CHAR(NOW() + interval '1 day', 'DD-MM-YYYY HH:MI:SS') as expire");

    $result = pg_update($GLOBALS["conn"], $type, array("token" => $token, "expiretoken" => $now["now"]), array("id" => $id));
    
    if ($result) {
      return array("token" => $token, "creationtime" => $now["now"], "expiretime" => $expire["expire"]);
    }
    return array("debug" => "PSQL statement couldn't be updated!");
  }
?>
