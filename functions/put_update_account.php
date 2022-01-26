<?php
  include_once("../config.php");

  function put_update_account($fields){
       
    if (!is_numeric($fields["id"])){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
    $type = $fields["type"];
    if (!in_array($type, ["stad", "influencer"])) {
      return array("valid" => false, "code" => 400, "message" => "Type is expected to be of stad or influencer in body!", "error" => "AuthTypeInvalid");
    }
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad", "influencer")) or ($GLOBALS["account_id"] != $id) or ($GLOBALS["account_type"] != $type)) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to update this resource", "error" => "ForbiddenContent");
    }
    
    if ($GLOBALS["account_type"] == "influencer")) {

      // TODO: validate input
      $values = array(
        "gebruikersnaam" => $fields["username"],
        "wachtwoord" => $fields["password"],
        "naam" => $fields["name"],
        "postcode" => $fields["postcode"],
        "emailadres" => $fields["email"],
      ); 
    
    } elseif ($GLOBALS["account_type"] == "stad") {
      
      // TODO: validate input
      $values = array(
        "gebruikersnaam" => $fields["username"],
        "wachtwoord" => $fields["password"],
        "naam" => $fields["name"],
        "postcode" => $fields["postcode"],
        "emailadres" => $fields["email"],
      ); 
    } 
      
    $result = pg_insert($GLOBALS["conn"], $type, $values);
    
    
    if ($data == null) {
      return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => "200", "message" => "Successfully updated account");  
    
  };
?>
