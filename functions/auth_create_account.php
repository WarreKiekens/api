<?php
  include_once("../config.php");

  function auth_create_account($fields){
    
    $type = $fields["type"];
    
    if (!in_array($type, ["stad", "influencer"])) {
      return array("valid" => false, "code" => 400, "message" => "Type is expected to be of stad or influencer in body!", "error" => "AuthTypeInvalid");
    }
    
    // Check if account already exists
    $query = "SELECT count(*) as count FROM $type WHERE gebruikersnaam = $1;";
    $data = fetch_query_params($query, array($fields["username"]))[0];
    
    if ($data["count"] >= 1) {
      return array("valid" => false, "code" => "409", "message" => "Username already exists!", "error" => "AccountExists");
    }
    
    
    if ($type == "influencer") {
      
      $values = array(
        "gebruikersnaam" => $fields["username"],
        "wachtwoord" => $fields["password"],
        "emailadres" => $fields["email"],
      );
      
      $result = pg_insert($GLOBALS["conn"], $type, $values);
      
      sendResponse(200, "Influencer not yet supported!");
      //$result = pg_insert($GLOBALS["conn"], "influencer", $fields);
      
    
    } elseif ($type == "stad") {
      
        
      $values = array(
        "gebruikersnaam" => $fields["username"],
        "wachtwoord" => $fields["password"],
        "emailadres" => $fields["email"],
      ); 
      
      $result = pg_insert($GLOBALS["conn"], $type, $values);
      
      
    
    } else {
      return array("valid" => false, "code" => "500", "message" => "Internal referral type not allowed!", "error" => "InternalError");
    
    }
    
    if ($result) {
      return array("valid" => true);
    }

    return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");  
    
  }
?>
