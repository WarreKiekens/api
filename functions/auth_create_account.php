<?php
  include_once("../config.php");

  function auth_create_account($type, $fields){
    
    // Check if account already exists
    $query = "SELECT count(*) as count FROM $stad WHERE gebruikersnaam = $1;";
    $data = fetch_query_params($query, array($username));
    
    if ($data != null) {
      return array("valid" => false, "code" => "409", "message" => "Account already exists!", "error" => "AccountExists");
    }
    
    
    if ($type == "influencer") {
      $result = pg_insert($GLOBALS["conn"], "influencer", $fields, PG_DML_ESCAPE);
      
    
    } elseif ($type == "stad") {
      $result = pg_insert($GLOBALS["conn"], 'admin', $fields, PG_DML_ESCAPE);
    
    } else {
      return array("valid" => false, "code" => "500", "message" => "Internal referral type not allowed!", "error" => "InternalError");
    
    }
    
    if ($result) {
      return array("valid" => true, "data" => array("token" => $token, "creationtime" => $now["now"], "expiretime" => $expire["expire"]));
    }

    return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");  
    
  }
?>
