<?php
  include_once("../config.php");

  function put_verify_post($fields){
       
        var_dump($fields);
    
    die();
    
    if (!is_numeric($fields["id"])){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
        
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))) {
      // also check if city is owner of task mentioned by post
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to update this resource", "error" => "ForbiddenContent");
    }
    

      
    $values = array(
      "gebruikersnaam" => $fields["username"],
      "wachtwoord" => $fields["password"],
      "naam" => $fields["name"],
      "postcode" => $fields["postcode"],
      "emailadres" => $fields["email"],
    );

    // unset all null values
    foreach($values as $key=>$value){
      if(is_null($value) || $value == '')
          unset($values[$key]);
    }
    
    $result = pg_update($GLOBALS["conn"], $GLOBALS["account_type"], $values, array("id" => $GLOBALS["account_id"]));
    
    
    if (!$result) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => 200, "message" => "Successfully updated account");  
    
  };
?>
