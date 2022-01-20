<?php
  include_once("../config.php");

  function auth_update_token($id, $type){
    
    // Genrate new token
    $token = bin2hex(random_bytes(64));
        
    // Get current epoch time on psql server
    $res = pg_query("select TO_CHAR(NOW(), 'DD-MM-YYYY HH:MI:SS') as now");
    $now = fetch_query_data($res);
    
    $res = pg_query("select TO_CHAR(NOW() + interval '1 day', 'DD-MM-YYYY HH:MI:SS') as expire");
    $expire = fetch_query_data($res);
    
    $result = pg_update($GLOBALS["conn"], $type, array("token" => $token, "expiretoken" => $now["now"]), array("id" => $id));
    
    if ($result) {
      return array("token" => $token, "creationtime" => $now["now"], "expiretime" => $expire["expire"]);
    }
    return array("debug" => "PSQL statement couldn't be updated!");
  }
?>
