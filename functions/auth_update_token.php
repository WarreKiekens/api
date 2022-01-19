<?php
  include_once("../config.php");

  function auth_update_token($id, $type){
    
    // Genrate new token
    $token = bin2hex(random_bytes(64));
    
    $result = pg_update($GLOBALS["conn"], $type, array("token" => $token), array("id" => $id));
    //$result = pg_fetch_all($rows);
    
    if ($result) {
      return $token;
    }
    return null;
  }
?>
