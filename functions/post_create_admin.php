<?php
  include_once("../config.php");

  function post_create_admin($fields){

    // Authorization
    if (!in_array($GLOBALS["account_type"], array("admin"))) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to create this resource", "error" => "ForbiddenContent");
    }
    
    // Authorization: Check if account is super user
    $query = "SELECT isSuper FROM admin WHERE id = $1;";
    $data = fetch_query_params($query, array($GLOBALS["account_id"]))[0];
    if ($data["isSuper"] == "f"){
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to create this resource", "error" => "ForbiddenContent");
    }
    
    // Check if account already exists
    $query = "SELECT count(*) as count FROM admin WHERE gebruikersnaam = $1;";
    $data = fetch_query_params($query, array($fields["username"]))[0];
    
    if ($data["count"] >= 1) {
      return array("valid" => false, "code" => "409", "message" => "Username already exists!", "error" => "AccountExists");
    }
    
      
    // TODO: validate input
    $values = array(
      "gebruikersnaam" => $fields["username"],
      "voornaam" => $fields["firstname"],
      "familienaam" => $fields["lastname"],
      "wachtwoord" => $fields["password"],
      "emailadres" => $fields["email"],
    ); 

    $result = pg_insert($GLOBALS["conn"], "admin", $values);
      
    if ($result) {
      return array("valid" => true);
    }

    return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");  
    
  }
?>
