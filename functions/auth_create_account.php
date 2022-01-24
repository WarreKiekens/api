<?php
  include_once("../config.php");

  function auth_create_account($type, $fields){
    
    // Check if account already exists
    $query = "SELECT count(*) as count FROM $type WHERE gebruikersnaam = $1;";
    $data = fetch_query_params($query, array($username));
    
    if ($data["count"] >= 1) {
      return array("valid" => false, "code" => "409", "message" => "Account already exists!", "error" => "AccountExists");
    }
    
    
    if ($type == "influencer") {
      $result = pg_insert($GLOBALS["conn"], "influencer", $fields, PG_DML_ESCAPE);
      
    
    } elseif ($type == "stad") {
      
        
      $values = array(
        "gebruikersnaam" => $fields["username"],
        "wachtwoord" => $fields["password"],
        "naam" => $fields["name"],
        "postcode" => $fields["postcode"],
        "emailadres" => $fields["email"],
      ); 
      
      $result = pg_insert($GLOBALS["conn"], 'stad', $values, PG_DML_ESCAPE);
      
      
    
    } else {
      return array("valid" => false, "code" => "500", "message" => "Internal referral type not allowed!", "error" => "InternalError");
    
    }
    
    if ($result) {
      return array("valid" => true, "data" => $values);
    }

    return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");  
    
  }
?>