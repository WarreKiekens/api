<?php
  include_once("../config.php");

  function put_verify_post($fields){
       
    
    if (!is_numeric($fields["tasks"]) || !is_numeric($fields["posts"])){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
        
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))) {
      // also check if city is owner of task mentioned by post
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to update this resource", "error" => "ForbiddenContent");
    }
    
    $res = pg_query("select id,opdrachtid, (select stadid from opdracht where id = opdrachtid) as stadid from post;");
    $data = fetch_query_data($res);
    
    if (!in_array(array( "id" => $fields["posts"], "opdrachtid" => $fields["tasks"], "stadid" => $GLOBALS["account_id"]), $data)) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to update this resource", "error" => "ForbiddenContent");
    }
    
    $values = array(
        "isgoedgekeurd" => $fields["isapproved"],
        "commentaarstad" => $fields["commentscity"]
    );
    
    $result = pg_update($GLOBALS["conn"], "post", $values, array("id" => $fields["posts"]));
    
    
    if (!$result) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => 200);  
    
  };
?>
