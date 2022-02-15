<?php
  include_once("../config.php");

  function put_update_admin($fields){
       
    if (!is_numeric($fields["id"])){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
    if ($fields["id"] == 1){
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to update original admin", "error" => "ForbiddenContent");
    }
        
    // Authorization
    if (!in_array($GLOBALS["account_issuper"], array(1))) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to update this resource", "error" => "ForbiddenContent");
    }
   
    $values = array(
      "isactief" => $fields["isactive"],
      "issuper" => $fields["issuper"],
    ); 

    // unset all null values
    foreach($values as $key=>$value){
      if(is_null($value) || $value === '')
          unset($values[$key]);
    }
    
    $result = pg_update($GLOBALS["conn"], "admin", $values, array("id" => $fields["id"]));
    
    
    if (!$result) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => 200, "message" => "Successfully updated account");  
    
  };
?>
