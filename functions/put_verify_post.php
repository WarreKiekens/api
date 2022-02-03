<?php
  include_once("../config.php");

  function put_verify_post($fields){
       
    
    if (!is_numeric($fields["id"])){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
        
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))) {
      // also check if city is owner of task mentioned by post
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to update this resource", "error" => "ForbiddenContent");
    }
    
    $res = pg_query("select id,opdrachtid, (select stadid from opdracht where id = opdrachtid) as stadid from post;");
    $data = fetch_query_data($res);
    
    // check validation
    echo json_encode($data); 
    die();
    $result = pg_update($GLOBALS["conn"], $GLOBALS["account_type"], $values, array("id" => $GLOBALS["account_id"]));
    
    
    if (!$result) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => 200, "message" => "Successfully updated account");  
    
  };
?>
