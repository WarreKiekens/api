<?php
  include_once("../config.php");

  function put_isactive($type, $id, $bool){
    
    if (!in_array($type, ["stad", "influencer"])) {
      return array("valid" => false, "code" => 400, "message" => "Type is expected to be of stad or influencer in body!", "error" => "AuthTypeInvalid");
    }
    
    if (!is_numeric($id) or !is_bool($bool)){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("admin"))) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to update this resource", "error" => "ForbiddenContent");
    }
    
    // Get range of possible ids
    $query = "SELECT id FROM $type where id = $1";
    $data = fetch_query_params($query, array($id));
    
    if (!in_array($id, $data)){
      return array("valid" => false, "code" => 422, "message" => "Account doesn't exist!", "error" => "UnprocessableEntity");  
    }
    
            
    $result = pg_update($GLOBALS["conn"], $type, array("isactief" => $bool), array("id" => $id));
    
    if ($data == null) {
      return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    if ($bool) {
      return array("valid" => true, "code" => "200", "message" => "Successfully activated account");  
    } else {
      return array("valid" => true, "code" => "200", "message" => "Successfully deactivated account");  
    }
    
  };
?>
