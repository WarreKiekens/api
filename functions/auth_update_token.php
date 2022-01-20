<?php
  include_once("../config.php");

  function auth_update_token($id, $type){
    
    // Genrate new token
    $token = bin2hex(random_bytes(64));
        
    // Get current epoch time on psql server
    //$now = get_query_data("select now()::timestamp;");
    $now = get_query_data("select TO_CHAR(NOW(), 'DD-MM-YYYY HH:mm:ss') as now");

    $result = pg_update($GLOBALS["conn"], $type, array("token" => $token, "expiretoken" => $now["now"]), array("id" => $id));
    
    if ($result) {
      return array("token" => $token, "creationTime" => $now["now"]);
    }
    return null;
  }
?>
