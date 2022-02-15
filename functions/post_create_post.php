<?php
  include_once("../config.php");

  function post_create_post($fields){
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("influencer"))){
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }
    
    if (!is_numeric($fields["taskid"])){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
    $values = array(
      "influencerid" => $GLOBALS["account_id"],
      "foto" => $fields["picture"],
      "omschrijving" => $fields["description"],
      "aantallikes" => $fields["totallikes"],
      "aantalcomments" => $fields["totalcomments"],
      "bereik" => $fields["reach"],
      "opdrachtid" => $fields["taskid"]
    ); 

    $result = pg_insert($GLOBALS["conn"], "post", $values);

    if ($result) {
      return array("valid" => true);
    }

    return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");  
    
  }
?>
